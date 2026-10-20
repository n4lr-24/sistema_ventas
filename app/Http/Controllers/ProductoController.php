<?php

namespace App\Http\Controllers;
use App\Models\Producto;
use Illuminate\Http\Request;


class ProductoController extends Controller
{
    public function getProductos(){
        $productos = Producto::select(
            'productos.producto',
            'productos.precio',
            'productos.stock',
            'productos.categoria_id',
            'categoria.categoria'
        )->join('categoria', 'productos.categoria_id', '=', 'categoria.id')
        ->get();

        return view('productos', compact('productos'));
    }
    public function createProducto(Request $request){
        $request->validate([
            "inputProducto"=> "required",
            "inputPrecio"=> "required",
            "inputStock"=> "required",
            "inputCategoria_id"=> "required"
        ]);

        Producto::create([
            "producto"=>$request->inputProducto,
            "precio"=>$request->inputPrecio,
            "stock"=>$request->inputStock,
            "categoria_id"=>$request->inputCategoria_id
        ]);

        return redirect("/productos")->with([
            "mensaje"=>"producto registrado"
        ]);
    }

    public function updateProducto(int $id, Request $request){
        $producto = Producto::where("id", "=", $id)->first();
        $producto->update([
            "producto"=>$request->inputProducto,
            "precio"=>$request->inputPrecio,
            "stock"=>$request->inputStock,
            "categoria_id"=>$request->inputCategoria_id
        ]);

        $producto->save();

        return redirect("/productos")->with([
            "mensaje"=>"producto actualizado"
        ]);

    }

    public function deleteProducto(int $id){
        $producto = Producto::find($id);
        $producto->delete($id);
        $producto->save();

        return redirect("/productos")->with([
            "mensaje"=>"producto eliminado"
        ]);
    }
}
