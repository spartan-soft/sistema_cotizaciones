@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-between mb-4">
            <div class="col-md-6">
                <h2>Cotizaciones</h2>
            </div>
            <div class="col-md-6 text-end">
                <a href="{{ route('cotizaciones.create') }}" class="btn btn-primary">Nueva Cotización</a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>N° Control</th>
                        <th>Fecha</th>
                        <th>Cliente</th>
                        <th>Total</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($cotizaciones as $cotizacion)
                        <tr>
                            <td>{{ $cotizacion->numero_control }}</td>
                            <td>{{ \Carbon\Carbon::parse($cotizacion->fecha)->format('d/m/Y') }}</td>
                            <td>{{ $cotizacion->cliente }}</td>
                            <td>{{ $cotizacion->total }} Bs</td>
                            <td>
                                <a href="{{ route('cotizaciones.show', $cotizacion->id) }}" class="btn btn-sm btn-info">Ver</a>
                                <a href="{{ route('cotizaciones.edit', $cotizacion->id) }}" class="btn btn-sm btn-warning">Editar</a>
                                <a href="{{ route('cotizaciones.pdf', $cotizacion->id) }}" class="btn btn-sm btn-secondary">PDF</a>
                                <form action="{{ route('cotizaciones.destroy', $cotizacion->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Está seguro de eliminar esta cotización?')">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                {{ $cotizaciones->links() }}
            </div>
        </div>
    </div>
@endsection
