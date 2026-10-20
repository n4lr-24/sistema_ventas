<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function getCategorias(){
        $categoria = Categoria::all();
        return view('categoria', compact('categoria'));
    }

    public function createCategoria(Request $request){
        $request->validate([
            "categoria"=> "required"
        ]);

        $categoria = Categoria::create([
            "categoria"=> $request->categoria
        ]);

        return redirect("/categorias")->with([
            "mensaje"=>"categoria registrada"
        ]);
    
    }

    public function updateCategoria($id, Request $request){
        $categoria= Categoria::find($id);
        $categoria->update([
            "categoria"=> $request->categoria
        ]);
        $categoria->save();

        return redirect ("/categorias")->with(["mensaje"=>"categoria actualizada"]);
    }

    public function deleteCategoria($id){
        $categoria = Categoria::find($id);
        $categoria->delete();
        return redirect("/categorias")->with(["mensaje"=>"categoria eliminada"]);
    }
}
