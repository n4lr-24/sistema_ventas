<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Categorías</title>
</head>
<body class="bg-light">
   <x-navbar />
    <main class="container py-4">
        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mb-4">
            <div>
                <h1 class="h2 mb-1">Categorías</h1>
                <p class="text-secondary mb-0">Administra las categorías de tu catálogo.</p>
            </div>
            <x-createCategoria />
        </div>

        @if (session('mensaje'))
            <div class="alert alert-success" role="alert">{{ session('mensaje') }}</div>
        @endif

        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col" class="ps-4">ID</th>
                                <th scope="col">Categoría</th>
                                <th scope="col" class="text-end pe-4">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($categoria as $i)
    <tr>
        <td class="ps-4 text-secondary">{{ $i->id }}</td>
        <td class="fw-medium">{{ $i->categoria }}</td>
        <td class="text-end pe-4">
            
            <!-- 1. MODIFICADO: Botón Actualizar ahora abre el modal #updateModal{{$i->id}} -->
            <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#updateModal{{ $i->id }}">
                Actualizar
            </button>

            <!-- 2. MODIFICADO: Botón Eliminar ahora está dentro de un form conectado a categoria.delete -->
            <form action="{{ route('categoria.delete', $i->id) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger">
                    Eliminar
                </button>
            </form>

        </td>
    </tr>

    <!-- 3. AGREGADO: Modal que se abre al darle clic a Actualizar y envía los datos a categoria.update -->
    <div class="modal fade" id="updateModal{{ $i->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Actualizar Categoría</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('categoria.update', $i->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <label for="categoria{{ $i->id }}" class="form-label">Nombre</label>
                        <input id="categoria{{ $i->id }}" name="categoria" value="{{ $i->categoria }}" class="form-control" type="text" required>
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
        <td colspan="3" class="text-center text-secondary py-5">No hay categorías registradas.</td>
    </tr>
@endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</body>
</html>