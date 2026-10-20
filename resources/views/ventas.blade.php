<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Ventas</title>
</head>
<body class="bg-light">
    <x-navbar />
    <main class="container py-4">
        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mb-4">
            <div>
                <h1 class="h2 mb-1">Ventas</h1>
                <p class="text-secondary mb-0">Administra las ventas y sus detalles.</p>
            </div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createVentaModal">
                Nueva Venta
            </button>
        </div>

        @if (session('mensaje'))
            <div class="alert alert-success" role="alert">{{ session('mensaje') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col" class="ps-4">ID</th>
                                <th scope="col">Cliente</th>
                                <th scope="col">Fecha Venta</th>
                                <th scope="col">Detalle (Producto x Cant.)</th>
                                <th scope="col">Estado</th>
                                <th scope="col">Total</th>
                                <th scope="col" class="text-end pe-4">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($ventas as $v)
                                <tr>
                                    <td class="ps-4 text-secondary">{{ $v->id }}</td>
                                    <td class="fw-medium">{{ $v->cliente->nombre ?? 'N/A' }}</td>
                                    <td>{{ $v->fecha_venta }}</td>
                                    <td>
                                        @foreach ($v->detalles as $detalle)
                                            <div>
                                                {{ $detalle->producto->producto ?? 'Producto' }}
                                                ({{ $detalle->cantidad }} x ${{ number_format($detalle->precio_unitario, 2) }})
                                            </div>
                                        @endforeach
                                    </td>
                                    <td>
                                        @if ($v->estado === 'confirmada')
                                            <span class="badge bg-success">Confirmada</span>
                                        @elseif ($v->estado === 'pendiente')
                                            <span class="badge bg-warning text-dark">Pendiente</span>
                                        @else
                                            <span class="badge bg-danger">Cancelada</span>
                                        @endif
                                    </td>
                                    <td class="fw-bold">${{ number_format($v->total, 2) }}</td>
                                    <td class="text-end pe-4">
                                        <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#updateVentaModal{{ $v->id }}">
                                            Actualizar
                                        </button>

                                        @if (in_array($v->estado, ['pendiente', 'cancelada']))
                                            <form action="{{ route('venta.delete', $v->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    Eliminar
                                                </button>
                                            </form>
                                        @else
                                            <button type="button" class="btn btn-sm btn-outline-secondary" disabled title="Solo se pueden eliminar ventas pendientes o canceladas">
                                                Eliminar
                                            </button>
                                        @endif
                                    </td>
                                </tr>

                                <!-- Modal Actualizar Venta -->
                                <div class="modal fade" id="updateVentaModal{{ $v->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5">Actualizar Venta #{{ $v->id }}</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('venta.update', $v->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="mb-3">
                                                        <label class="form-label">Cliente</label>
                                                        <select name="cliente_id" class="form-select" required>
                                                            @foreach ($clientes as $cliente)
                                                                <option value="{{ $cliente->id }}" {{ $v->cliente_id == $cliente->id ? 'selected' : '' }}>
                                                                    {{ $cliente->nombre }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Fecha de Venta</label>
                                                        <input name="fecha_venta" value="{{ $v->fecha_venta }}" class="form-control" type="date" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Estado</label>
                                                        <select name="estado" class="form-select" required>
                                                            <option value="pendiente" {{ $v->estado === 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                                            <option value="confirmada" {{ $v->estado === 'confirmada' ? 'selected' : '' }}>Confirmada</option>
                                                            <option value="cancelada" {{ $v->estado === 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                                                        </select>
                                                    </div>
                                                    <div class="mt-3">
                                                        <button type="submit" class="btn btn-success">Guardar</button>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-secondary py-5">No hay ventas registradas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal Registrar Venta -->
        <div class="modal fade" id="createVentaModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5">Registrar Venta</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('venta.create') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Cliente</label>
                                <select name="cliente_id" class="form-select" required>
                                    <option value="">Selecciona un cliente</option>
                                    @foreach ($clientes as $cliente)
                                        <option value="{{ $cliente->id }}" {{ old('cliente_id') == $cliente->id ? 'selected' : '' }}>
                                            {{ $cliente->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Fecha de Venta</label>
                                <input name="fecha_venta" value="{{ old('fecha_venta', date('Y-m-d')) }}" class="form-control" type="date" required>
                            </div>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label class="form-label mb-0">Productos</label>
                                    <button type="button" class="btn btn-sm btn-outline-primary" id="addProductRow">
                                        Agregar producto
                                    </button>
                                </div>
                                <div id="productRows">
                                    <div class="row g-2 align-items-end product-row mb-2">
                                        <div class="col-md-7">
                                            <select name="productos[0][producto_id]" class="form-select product-select" required>
                                                <option value="">Selecciona un producto</option>
                                                @foreach ($productos as $prod)
                                                    <option value="{{ $prod->id }}" data-price="{{ $prod->precio }}">
                                                        {{ $prod->producto }} - ${{ number_format($prod->precio, 2) }} (Stock: {{ $prod->stock }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <input name="productos[0][cantidad]" value="1" class="form-control product-quantity" type="number" min="1" required>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-outline-danger w-100 remove-product" disabled>
                                                Eliminar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-end fw-bold">
                                    Total: $<span id="saleTotal">0.00</span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Estado</label>
                                <select name="estado" class="form-select" required>
                                    <option value="pendiente" {{ old('estado') === 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                    <option value="confirmada" {{ old('estado') === 'confirmada' ? 'selected' : '' }}>Confirmada</option>
                                    <option value="cancelada" {{ old('estado') === 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                                </select>
                            </div>
                            <div class="mt-3">
                                <button type="submit" class="btn btn-success">Guardar</button>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const rows = document.getElementById('productRows');
            const addButton = document.getElementById('addProductRow');
            const totalElement = document.getElementById('saleTotal');
            let rowIndex = 1;

            const updateRows = () => {
                const productRows = [...rows.querySelectorAll('.product-row')];
                productRows.forEach((row) => {
                    row.querySelector('.remove-product').disabled = productRows.length === 1;
                });

                const selectedProducts = productRows
                    .map((row) => row.querySelector('.product-select').value)
                    .filter(Boolean);

                productRows.forEach((row) => {
                    const select = row.querySelector('.product-select');
                    [...select.options].forEach((option) => {
                        option.disabled = option.value !== '' &&
                            selectedProducts.includes(option.value) &&
                            option.value !== select.value;
                    });
                });

                const total = productRows.reduce((sum, row) => {
                    const select = row.querySelector('.product-select');
                    const quantity = Number(row.querySelector('.product-quantity').value) || 0;
                    const price = Number(select.selectedOptions[0]?.dataset.price) || 0;
                    return sum + (price * quantity);
                }, 0);

                totalElement.textContent = total.toFixed(2);
            };

            addButton.addEventListener('click', () => {
                const firstRow = rows.querySelector('.product-row');
                const newRow = firstRow.cloneNode(true);
                newRow.querySelector('.product-select').name = `productos[${rowIndex}][producto_id]`;
                newRow.querySelector('.product-select').value = '';
                newRow.querySelector('.product-quantity').name = `productos[${rowIndex}][cantidad]`;
                newRow.querySelector('.product-quantity').value = 1;
                rows.appendChild(newRow);
                rowIndex++;
                updateRows();
            });

            rows.addEventListener('click', (event) => {
                if (!event.target.closest('.remove-product')) {
                    return;
                }

                event.target.closest('.product-row').remove();
                updateRows();
            });

            rows.addEventListener('input', updateRows);
            rows.addEventListener('change', updateRows);
            updateRows();
        });
    </script>
</body>
</html>
