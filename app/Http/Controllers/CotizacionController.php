<?php

namespace App\Http\Controllers;

use App\Models\Cotizacion;
use App\Models\ItemCotizacion;
use Illuminate\Http\Request;
use PDF;

class CotizacionController extends Controller
{
    public function index()
    {
        $cotizaciones = Cotizacion::orderBy('created_at', 'desc')->paginate(10);
        return view('cotizaciones.index', compact('cotizaciones'));
    }

    public function create()
    {
        // Generar número de control automático
        $ultimaCotizacion = Cotizacion::latest()->first();
        $numeroControl = $ultimaCotizacion ?
            str_pad((intval(substr($ultimaCotizacion->numero_control, 0, 4)) + 1), 4, '0', STR_PAD_LEFT) :
            '0001';

        return view('cotizaciones.create', compact('numeroControl'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'numero_control' => 'required|string',
            'fecha' => 'required|date',
            'validez_dias' => 'required|integer',
            'telefono' => 'required|string',
            'correo' => 'required|email',
            'responsable_ventas' => 'required|string',
            'cliente' => 'required|string',
            'descripcion_servicios' => 'nullable|string',
            'terminos_condiciones' => 'nullable|string',
            'items' => 'required|array',
            'items.*.cantidad' => 'required|integer',
            'items.*.descripcion' => 'required|string',
            'items.*.precio_unitario' => 'required|numeric',
        ]);

        // Calcular totales
        $total = 0;
        foreach ($request->items as $item) {
            $precioTotal = $item['cantidad'] * $item['precio_unitario'];
            $total += $precioTotal;
        }

        // Crear cotización
        $cotizacion = Cotizacion::create([
            'numero_control' => $validated['numero_control'],
            'fecha' => $validated['fecha'],
            'validez_dias' => $validated['validez_dias'],
            'telefono' => $validated['telefono'],
            'correo' => $validated['correo'],
            'responsable_ventas' => $validated['responsable_ventas'],
            'cliente' => $validated['cliente'],
            'descripcion_servicios' => $validated['descripcion_servicios'],
            'terminos_condiciones' => $validated['terminos_condiciones'],
            'total' => $total,
        ]);

        // Crear items
        foreach ($request->items as $item) {
            $precioTotal = $item['cantidad'] * $item['precio_unitario'];

            ItemCotizacion::create([
                'cotizacion_id' => $cotizacion->id,
                'cantidad' => $item['cantidad'],
                'descripcion' => $item['descripcion'],
                'precio_unitario' => $item['precio_unitario'],
                'precio_total' => $precioTotal,
            ]);
        }

        return redirect()->route('cotizaciones.show', $cotizacion->id)
            ->with('success', 'Cotización creada exitosamente');
    }

    public function show($id)
    {
        $cotizacion = Cotizacion::with('items')->findOrFail($id);
        return view('cotizaciones.show', compact('cotizacion'));
    }

    public function edit($id)
    {
        $cotizacion = Cotizacion::with('items')->findOrFail($id);
        return view('cotizaciones.edit', compact('cotizacion'));
    }

    public function update(Request $request, $id)
    {
        $cotizacion = Cotizacion::findOrFail($id);

        $validated = $request->validate([
            'numero_control' => 'required|string',
            'fecha' => 'required|date',
            'validez_dias' => 'required|integer',
            'telefono' => 'required|string',
            'correo' => 'required|email',
            'responsable_ventas' => 'required|string',
            'cliente' => 'required|string',
            'descripcion_servicios' => 'nullable|string',
            'terminos_condiciones' => 'nullable|string',
            'items' => 'required|array',
            'items.*.id' => 'nullable|exists:items_cotizacion,id',
            'items.*.cantidad' => 'required|integer',
            'items.*.descripcion' => 'required|string',
            'items.*.precio_unitario' => 'required|numeric',
        ]);

        // Calcular totales
        $total = 0;
        foreach ($request->items as $item) {
            $precioTotal = $item['cantidad'] * $item['precio_unitario'];
            $total += $precioTotal;
        }

        // Actualizar cotización
        $cotizacion->update([
            'numero_control' => $validated['numero_control'],
            'fecha' => $validated['fecha'],
            'validez_dias' => $validated['validez_dias'],
            'telefono' => $validated['telefono'],
            'correo' => $validated['correo'],
            'responsable_ventas' => $validated['responsable_ventas'],
            'cliente' => $validated['cliente'],
            'descripcion_servicios' => $validated['descripcion_servicios'],
            'terminos_condiciones' => $validated['terminos_condiciones'],
            'total' => $total,
        ]);

        // Eliminar items antiguos
        $cotizacion->items()->delete();

        // Crear nuevos items
        foreach ($request->items as $item) {
            $precioTotal = $item['cantidad'] * $item['precio_unitario'];

            ItemCotizacion::create([
                'cotizacion_id' => $cotizacion->id,
                'cantidad' => $item['cantidad'],
                'descripcion' => $item['descripcion'],
                'precio_unitario' => $item['precio_unitario'],
                'precio_total' => $precioTotal,
            ]);
        }

        return redirect()->route('cotizaciones.show', $cotizacion->id)
            ->with('success', 'Cotización actualizada exitosamente');
    }

    public function destroy($id)
    {
        $cotizacion = Cotizacion::findOrFail($id);
        $cotizacion->delete();

        return redirect()->route('cotizaciones.index')
            ->with('success', 'Cotización eliminada exitosamente');
    }

    public function exportarPdf($id)
    {
        $cotizacion = Cotizacion::with('items')->findOrFail($id);

        $pdf = PDF::loadView('cotizaciones.pdf', compact('cotizacion'));
        $pdf->setPaper('letter', 'portrait');

        return $pdf->download('cotizacion-'.$cotizacion->numero_control.'.pdf');
    }
}
