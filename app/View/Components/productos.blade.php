<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Productos</title>
</head>
<body class="bg-light">
    <x-navbar />
    <main class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h2 mb-1">Productos</h1>
                <p class="text-secondary mb-0">Administra el inventario de productos.</p>
            </div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createProductoModal">
                Nuevo Producto
            </button>
        </div>

        @if (session('mensaje'))
            <div class="alert alert-success">{{ session('mensaje') }}</div>
        @endif

        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-4">ID</th>
                            <th>Producto</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th>Categoría</th>
                            <th class="text-end pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($productos as $p)
                            <tr>
                                <td class="ps-4">{{ $p->id }}</td>
                                <td>{{ $p->producto }}</td>
                                <td>${{ number_format($p->precio, 2) }}</td>
                                <td>{{ $p->stock }}</td>
                                <td>{{ $p->categoria }}</td>
                                <td class="text-end pe-4">
                                    <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#updateProductoModal{{ $p->id }}">
                                        Actualizar
                                    </button>
                                    <form action="{{ route('productos.delete', $p->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Eliminar</button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Modal Actualizar -->
                            <div class="modal fade" id="updateProductoModal{{ $p->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Actualizar Producto</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('producto.update', $p->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="mb-3">
                                                    <label class="form-label">Producto</label>
                                                    <input type="text" name="inputProducto" value="{{ $p->producto }}" class="form-control" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Precio</label>
                                                    <input type="number" step="0.01" name="inputPrecio" value="{{ $p->precio }}" class="form-control" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Stock</label>
                                                    <input type="number" name="inputStock" value="{{ $p->stock }}" class="form-control" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Categoría</label>
                                                    <select name="inputCategoria_id" class="form-select" required>
                                                        @foreach ($categorias as $cat)
                                                            <option value="{{ $cat->id }}" {{ $p->categoria_id == $cat->id ? 'selected' : '' }}>
                                                                {{ $cat->categoria }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <button type="submit" class="btn btn-success">Guardar</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-secondary">No hay productos registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal Registrar -->
        <div class="modal fade" id="createProductoModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Registrar Producto</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('producto.create') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Producto</label>
                                <input type="text" name="inputProducto" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Precio</label>
                                <input type="number" step="0.01" name="inputPrecio" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Stock</label>
                                <input type="number" name="inputStock" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Categoría</label>
                                <select name="inputCategoria_id" class="form-select" required>
                                    <option value="">Seleccione una categoría</option>
                                    @foreach ($categorias as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->categoria }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-success">Guardar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>