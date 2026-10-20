<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\Cliente;
use App\Models\Producto;
use Illuminate\Http\Request;

class VentaController extends Controller
{
    public function getVentas()
    {
        $ventas = Venta::with(['cliente', 'detalles.producto'])->get();
        $clientes = Cliente::all();
        $productos = Producto::all();

        return view('ventas', compact('ventas', 'clientes', 'productos'));
    }

    public function createVenta(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'fecha_venta' => 'required|date',
            'estado' => 'required|in:pendiente,confirmada,cancelada',
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1',
        ]);

        $producto = Producto::findOrFail($request->producto_id);

        // Validar que si el producto no tiene stock no se pueda generar una venta
        if ($producto->stock <= 0 || $producto->stock < $request->cantidad) {
            return redirect('/ventas')->withErrors([
                'stock' => "No se puede generar la venta: el producto '{$producto->producto}' no tiene stock suficiente (Stock disponible: {$producto->stock})."
            ]);
        }

        $precioUnitario = $producto->precio;
        $totalVenta = $precioUnitario * $request->cantidad;

        $venta = Venta::create([
            'cliente_id' => $request->cliente_id,
            'fecha_venta' => $request->fecha_venta,
            'estado' => $request->estado,
            'total' => $totalVenta,
        ]);

        DetalleVenta::create([
            'venta_id' => $venta->id,
            'producto_id' => $producto->id,
            'cantidad' => $request->cantidad,
            'precio_unitario' => $precioUnitario,
            'total' => $totalVenta,
        ]);

        // Descontar stock si la venta no está cancelada
        if ($request->estado !== 'cancelada') {
            $producto->decrement('stock', $request->cantidad);
        }

        return redirect('/ventas')->with([
            'mensaje' => 'venta registrada'
        ]);
    }

    public function updateVenta(int $id, Request $request)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'fecha_venta' => 'required|date',
            'estado' => 'required|in:pendiente,confirmada,cancelada',
        ]);

        $venta = Venta::findOrFail($id);
        $venta->update([
            'cliente_id' => $request->cliente_id,
            'fecha_venta' => $request->fecha_venta,
            'estado' => $request->estado,
        ]);

        return redirect('/ventas')->with([
            'mensaje' => 'venta actualizada'
        ]);
    }

    public function deleteVenta(int $id)
    {
        $venta = Venta::findOrFail($id);

        // Solo se pueden eliminar las ventas que tengan un status de cancelada o pendiente
        if (!in_array($venta->estado, ['pendiente', 'cancelada'])) {
            return redirect('/ventas')->withErrors([
                'eliminar' => 'No se puede eliminar esta venta porque su estado es "' . $venta->estado . '". Solo se pueden eliminar ventas con estado pendiente o cancelada.'
            ]);
        }

        $venta->delete();

        return redirect('/ventas')->with([
            'mensaje' => 'venta eliminada'
        ]);
    }
}

