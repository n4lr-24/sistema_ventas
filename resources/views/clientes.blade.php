<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Clientes</title>
</head>
<body class="bg-light">
    <x-navbar />
    <main class="container py-4">
        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mb-4">
            <div>
                <h1 class="h2 mb-1">Clientes</h1>
                <p class="text-secondary mb-0">Administra la cartera de clientes.</p>
            </div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createClienteModal">
                Nuevo Cliente
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
                                <th scope="col">Nombre</th>
                                <th scope="col">Email</th>
                                <th scope="col">Dirección</th>
                                <th scope="col">Teléfono</th>
                                <th scope="col" class="text-end pe-4">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($clientes as $c)
                                <tr>
                                    <td class="ps-4 text-secondary">{{ $c->id }}</td>
                                    <td class="fw-medium">{{ $c->nombre }}</td>
                                    <td>{{ $c->email }}</td>
                                    <td>{{ $c->direccion }}</td>
                                    <td>{{ $c->telefono }}</td>
                                    <td class="text-end pe-4">
                                        <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#updateClienteModal{{ $c->id }}">
                                            Actualizar
                                        </button>

                                        <form action="{{ route('cliente.delete', $c->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                Eliminar
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- Modal Actualizar Cliente -->
                                <div class="modal fade" id="updateClienteModal{{ $c->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5">Actualizar Cliente</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('cliente.update', $c->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="mb-3">
                                                        <label class="form-label">Nombre</label>
                                                        <input name="nombre" value="{{ $c->nombre }}" class="form-control" type="text" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Email</label>
                                                        <input name="email" value="{{ $c->email }}" class="form-control" type="email" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Dirección</label>
                                                        <input name="direccion" value="{{ $c->direccion }}" class="form-control" type="text" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Teléfono</label>
                                                        <input name="telefono" value="{{ $c->telefono }}" class="form-control" type="text" required>
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
                                    <td colspan="6" class="text-center text-secondary py-5">No hay clientes registrados.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal Registrar Cliente -->
        <div class="modal fade" id="createClienteModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5">Registrar Cliente</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('cliente.create') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Nombre</label>
                                <input name="nombre" value="{{ old('nombre') }}" class="form-control" type="text" placeholder="Ingresa el nombre completo" required>
                                @error('nombre')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input name="email" value="{{ old('email') }}" class="form-control" type="email" placeholder="correo@ejemplo.com" required>
                                @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Dirección</label>
                                <input name="direccion" value="{{ old('direccion') }}" class="form-control" type="text" placeholder="Ingresa la dirección" required>
                                @error('direccion')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Teléfono</label>
                                <input name="telefono" value="{{ old('telefono') }}" class="form-control" type="text" placeholder="Ingresa el teléfono" required>
                                @error('telefono')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
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
</body>
</html>

