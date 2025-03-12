<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cotizacion extends Model
{
    protected $table = 'cotizaciones';
    protected $fillable = [
        'numero_control', 'fecha', 'validez_dias', 'telefono',
        'correo', 'responsable_ventas', 'cliente',
        'descripcion_servicios', 'terminos_condiciones', 'total'
    ];

    public function items()
    {
        return $this->hasMany(ItemCotizacion::class);
    }
}
