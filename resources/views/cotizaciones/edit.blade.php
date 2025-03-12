@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Editar Cotización #{{ $cotizacion->numero_control }}</h2>

        <form action="{{ route('cotizaciones.update', $cotizacion->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="card mb-4">
                <div class="card-header">
                    <h5>Información General</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="numero_control" class="form-label">N° de Control</label>
                            <input type="text" class="form-control" id="numero_control" name="numero_control" value="{{ $cotizacion->numero_control }}" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="fecha" class="form-label">Fecha</label>
                            <input type="date" class="form-control" id="fecha" name="fecha" value="{{ $cotizacion->fecha }}" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="validez_dias" class="form-label">Válido por (días)</label>
                            <input type="number" class="form-control" id="validez_dias" name="validez_dias" value="{{ $cotizacion->validez_dias }}" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="text" class="form-control" id="telefono" name="telefono" value="{{ $cotizacion->telefono }}" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="correo" class="form-label">Correo</label>
                            <input type="email" class="form-control" id="correo" name="correo" value="{{ $cotizacion->correo }}" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="responsable_ventas" class="form-label">Responsable de Ventas</label>
                            <input type="text" class="form-control" id="responsable_ventas" name="responsable_ventas" value="{{ $cotizacion->responsable_ventas }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="cliente" class="form-label">Cliente</label>
                        <input type="text" class="form-control" id="cliente" name="cliente" value="{{ $cotizacion->cliente }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="descripcion_servicios" class="form-label">Descripción de los Servicios</label>
                        <textarea class="form-control" id="descripcion_servicios" name="descripcion_servicios" rows="5">{{ $cotizacion->descripcion_servicios }}</textarea>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Productos/Servicios</h5>
                    <button type="button" class="btn btn-sm btn-success" id="agregar-item">+ Agregar Item</button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="items-table">
                            <thead>
                            <tr>
                                <th>Cantidad</th>
                                <th>Descripción</th>
                                <th>Precio Unitario</th>
                                <th>Precio Total</th>
                                <th>Acciones</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($cotizacion->items as $index => $item)
                                <tr>
                                    <td>
                                        <input type="number" class="form-control cantidad" name="items[{{ $index }}][cantidad]" value="{{ $item->cantidad }}" min="1" required>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="items[{{ $index }}][descripcion]" value="{{ $item->descripcion }}" required>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control precio-unitario" name="items[{{ $index }}][precio_unitario]" value="{{ $item->precio_unitario }}" min="0" step="0.01" required>
                                    </td>
                                    <td class="precio-total">{{ $item->precio_total }} Bs</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-danger eliminar-item">Eliminar</button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="3" class="text-end"><strong>TOTAL</strong></td>
                                <td id="total-general">{{ $cotizacion->total }} Bs</td>
                                <td></td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5>Términos y Condiciones</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <textarea class="form-control" id="terminos_condiciones" name="terminos_condiciones" rows="6">{{ $cotizacion->terminos_condiciones }}</textarea>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end mb-4">
                <a href="{{ route('cotizaciones.index') }}" class="btn btn-secondary me-2">Cancelar</a>
                <button type="submit" class="btn btn-primary">Actualizar Cotización</button>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Delegación de eventos para eliminar items y calcular totales
                document.getElementById('items-table').addEventListener('click', function(e) {
                    if (e.target.classList.contains('eliminar-item')) {
                        eliminarItem(e.target);
                    }
                });

                document.getElementById('items-table').addEventListener('input', function(e) {
                    if (e.target.classList.contains('cantidad') || e.target.classList.contains('precio-unitario')) {
                        calcularTotales();
                    }
                });

                // Botón para agregar items
                document.getElementById('agregar-item').addEventListener('click', function() {
                    agregarItem();
                });

                calcularTotales(); // Calcular totales al cargar la página
            });

            function eliminarItem(button) {
                const tbody = document.querySelector('#items-table tbody');
                if (tbody.children.length > 1) {
                    button.closest('tr').remove();
                    reindexarItems();
                    calcularTotales();
                } else {
                    alert('Debe haber al menos un item en la cotización');
                }
            }

            function reindexarItems() {
                const filas = document.querySelectorAll('#items-table tbody tr');
                filas.forEach((fila, index) => {
                    fila.querySelectorAll('input').forEach(input => {
                        const name = input.name;
                        const newName = name.replace(/items\[\d+\]/, `items[${index}]`);
                        input.name = newName;
                    });
                });
            }

            function calcularTotales() {
                let totalGeneral = 0;
                const filas = document.querySelectorAll('#items-table tbody tr');

                filas.forEach(fila => {
                    const cantidad = parseFloat(fila.querySelector('.cantidad').value) || 0;
                    const precioUnitario = parseFloat(fila.querySelector('.precio-unitario').value) || 0;
                    const precioTotal = cantidad * precioUnitario;

                    fila.querySelector('.precio-total').textContent = precioTotal.toFixed(2) + ' Bs';
                    totalGeneral += precioTotal;
                });

                document.getElementById('total-general').textContent = totalGeneral.toFixed(2) + ' Bs';
            }

            function agregarItem() {
                const tbody = document.querySelector('#items-table tbody');
                const index = tbody.children.length;

                const tr = document.createElement('tr');
                tr.innerHTML = `
            <td>
                <input type="number" class="form-control cantidad" name="items[${index}][cantidad]" value="1" min="1" required>
            </td>
            <td>
                <input type="text" class="form-control" name="items[${index}][descripcion]" required>
            </td>
            <td>
                <input type="number" class="form-control precio-unitario" name="items[${index}][precio_unitario]" value="0" min="0" step="0.01" required>
            </td>
            <td class="precio-total">0.00 Bs</td>
            <td>
                <button type="button" class="btn btn-sm btn-danger eliminar-item">Eliminar</button>
            </td>
        `;

                tbody.appendChild(tr);
            }
        </script>
    @endpush
@endsection
