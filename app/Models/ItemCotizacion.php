<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemCotizacion extends Model
{
    protected $table = 'items_cotizacion';
    protected $fillable = [
        'cotizacion_id', 'cantidad', 'descripcion',
        'precio_unitario', 'precio_total'
    ];

    public function cotizacion()
    {
        return $this->belongsTo(Cotizacion::class);
    }
}
