@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Cotización #{{ $cotizacion->numero_control }}</h2>
            <div>
                <a href="{{ route('cotizaciones.edit', $cotizacion->id) }}" class="btn btn-warning">Editar</a>
                <a href="{{ route('cotizaciones.pdf', $cotizacion->id) }}" class="btn btn-secondary">Exportar PDF</a>
                <a href="{{ route('cotizaciones.index') }}" class="btn btn-primary">Volver</a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="card mb-4">
            <div class="card-header">
                <h5>Información General</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Cliente:</strong> {{ $cotizacion->cliente }}</p>
                        <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($cotizacion->fecha)->format('d/m/Y') }}</p>
                        <p><strong>Válido por:</strong> {{ $cotizacion->validez_dias }} días</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Teléfono:</strong> {{ $cotizacion->telefono }}</p>
                        <p><strong>Correo:</strong> {{ $cotizacion->correo }}</p>
                        <p><strong>Responsable de Ventas:</strong> {{ $cotizacion->responsable_ventas }}</p>
                    </div>
                </div>

                <div class="mt-3">
                    <h6>Descripción de los Servicios:</h6>
                    <div class="border p-3 rounded bg-light">
                        {!! nl2br($cotizacion->descripcion_servicios) !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h5>Productos/Servicios</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Cantidad</th>
                            <th>Descripción</th>
                            <th>Precio Unitario</th>
                            <th>Precio Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($cotizacion->items as $item)
                            <tr>
                                <td>{{ $item->cantidad }}</td>
                                <td>{{ $item->descripcion }}</td>
                                <td>{{ $item->precio_unitario }} Bs</td>
                                <td>{{ $item->precio_total }} Bs</td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="3" class="text-end"><strong>TOTAL</strong></td>
                            <td><strong>{{ $cotizacion->total }} Bs</strong></td>
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
                <div class="border p-3 rounded bg-light">
                    {!! nl2br($cotizacion->terminos_condiciones) !!}
                </div>
            </div>
        </div>
    </div>
@endsection
