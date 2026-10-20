<?php

use App\Http\Controllers\CategoriaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('categorias', [CategoriaController::class, 'getCategorias'])
->name('getcategorias');

Route::post('create/categoria',[CategoriaController::class, 'createCategoria'])
->name('categoria.create');

Route::put("update/categoria/{id}",[CategoriaController::class,'updateCategoria'])
->name('categoria.update');

Route::delete("delete/categoria/{id}", [CategoriaController::class, 'deleteCategoria'])
->name("categoria.delete");

Route::get('productos', [ProductoController::class, 'getProductos'])
->name("productos.get");

Route::post("producto/create", [ProductoController::class, 'createProducto'])
->name("producto.create");

Route::put("producto/update/{id}", [ProductoController::class, 'updateProducto'])
->name("producto.update");

Route::delete("producto/delete/{id}", [ProductoController::class, 'deleteProducto'])
->name("productos.delete");

Route::get('clientes', [\App\Http\Controllers\ClienteController::class, 'getClientes'])
->name("clientes.get");

Route::post("cliente/create", [\App\Http\Controllers\ClienteController::class, 'createCliente'])
->name("cliente.create");

Route::put("cliente/update/{id}", [\App\Http\Controllers\ClienteController::class, 'updateCliente'])
->name("cliente.update");

Route::delete("cliente/delete/{id}", [\App\Http\Controllers\ClienteController::class, 'deleteCliente'])
->name("cliente.delete");

Route::get('ventas', [\App\Http\Controllers\VentaController::class, 'getVentas'])
->name("ventas.get");

Route::post("venta/create", [\App\Http\Controllers\VentaController::class, 'createVenta'])
->name("venta.create");

Route::put("venta/update/{id}", [\App\Http\Controllers\VentaController::class, 'updateVenta'])
->name("venta.update");

Route::delete("venta/delete/{id}", [\App\Http\Controllers\VentaController::class, 'deleteVenta'])
->name("venta.delete");