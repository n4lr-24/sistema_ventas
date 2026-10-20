<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\Cliente;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

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
            'productos' => 'required|array|min:1',
            'productos.*.producto_id' => 'required|distinct|exists:productos,id',
            'productos.*.cantidad' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($request) {
            $totalVenta = 0;
            $detalles = [];

            foreach ($request->productos as $item) {
                $producto = Producto::whereKey($item['producto_id'])
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($request->estado !== 'cancelada' && $producto->stock < $item['cantidad']) {
                    throw ValidationException::withMessages([
                        'stock' => "No se puede generar la venta: el producto '{$producto->producto}' no tiene stock suficiente (Stock disponible: {$producto->stock}).",
                    ]);
                }

                $total = $producto->precio * $item['cantidad'];
                $totalVenta += $total;
                $detalles[] = [
                    'producto_id' => $producto->id,
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $producto->precio,
                    'total' => $total,
                ];
            }

            $venta = Venta::create([
                'cliente_id' => $request->cliente_id,
                'fecha_venta' => $request->fecha_venta,
                'estado' => $request->estado,
                'total' => $totalVenta,
            ]);

            foreach ($detalles as $detalle) {
                DetalleVenta::create([
                    'venta_id' => $venta->id,
                    ...$detalle,
                ]);

                if ($request->estado !== 'cancelada') {
                    Producto::whereKey($detalle['producto_id'])
                        ->decrement('stock', $detalle['cantidad']);
                }
            }
        });

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
