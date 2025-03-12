<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CotizacionController;

Route::get('/', function () {
    return redirect()->route('cotizaciones.index');
});

Route::resource('cotizaciones', CotizacionController::class);
Route::get('cotizaciones/{id}/pdf', [CotizacionController::class, 'exportarPdf'])->name('cotizaciones.pdf');
