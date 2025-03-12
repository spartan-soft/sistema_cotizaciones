<!-- resources/views/cotizaciones/pdf.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cotización #{{ $cotizacion->numero_control }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            margin-bottom: 20px;
        }
        .header-info {
            width: 100%;
            border-collapse: collapse;
        }
        .header-info td {
            padding: 3px 0;
        }
        h1 {
            text-align: center;
            font-size: 22px;
            margin: 20px 0;
        }
        .cliente {
            margin-bottom: 20px;
        }
        .descripcion {
            margin-bottom: 20px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .items-table th, .items-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .items-table th {
            background-color: #f2f2f2;
        }
        .total-row {
            font-weight: bold;
        }
        .terms {
            margin-top: 30px;
            font-size: 11px;
        }
        .terms h3 {
            text-align: center;
            margin-bottom: 10px;
        }
        .signatures {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }
        .signature-box {
            width: 45%;
            text-align: center;
            border-top: 1px solid #000;
            padding-top: 10px;
        }
    </style>
</head>
<body>
<div class="header">
    <table class="header-info">
        <tr>
            <td><strong>N° DE CONTROL:</strong> {{ $cotizacion->numero_control }}</td>
            <td><strong>FECHA:</strong> {{ \Carbon\Carbon::parse($cotizacion->fecha)->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <td><strong>VÁLIDO POR:</strong> {{ $cotizacion->validez_dias }} días</td>
            <td><strong>TELÉFONOS:</strong> {{ $cotizacion->telefono }}</td>
        </tr>
        <tr>
            <td><strong>CORREO:</strong> {{ $cotizacion->correo }}</td>
            <td><strong>RESP. VENTAS:</strong> {{ $cotizacion->responsable_ventas }}</td>
        </tr>
    </table>
</div>

<h1>COTIZACIÓN</h1>

<div class="cliente">
    <strong>Datos del Cliente</strong><br>
    <strong>CLIENTE:</strong> {{ $cotizacion->cliente }}
</div>

@if($cotizacion->descripcion_servicios)
    <div class="descripcion">
        <strong>Descripción de los Servicios:</strong><br>
        {!! nl2br($cotizacion->descripcion_servicios) !!}
    </div>
@endif

<table class="items-table">
    <thead>
    <tr>
        <th>CANTIDAD</th>
        <th>DESCRIPCIÓN</th>
        <th>P/U</th>
        <th>P/TOTAL</th>
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
    <tr class="total-row">
        <td colspan="3" style="text-align: right;"><strong>TOTAL</strong></td>
        <td><strong>{{ $cotizacion->total }} Bs</strong></td>
    </tr>
    </tbody>
</table>

<div class="terms">
    <h3>TÉRMINOS Y CONDICIONES</h3>
    {!! nl2br($cotizacion->terminos_condiciones) !!}
</div>

<div class="signatures">
    <div class="signature-box">
        Sello empresa
    </div>
    <div class="signature-box">
        Firma Cliente
    </div>
</div>
</body>
</html>
