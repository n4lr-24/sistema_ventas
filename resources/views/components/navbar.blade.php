<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
  <div class="container-fluid">
    <a class="navbar-brand fw-semibold" href="{{ url('/') }}">Mi proyecto</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('getcategorias') ? 'active' : '' }}" aria-current="page" href="{{ route('getcategorias') }}">Categorías</a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('productos.get') ? 'active' : '' }}" aria-current="page" href="{{ route('productos.get') }}">Productos</a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('clientes.get') ? 'active' : '' }}" aria-current="page" href="{{ route('clientes.get') }}">Clientes</a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('ventas.get') ? 'active' : '' }}" aria-current="page" href="{{ route('ventas.get') }}">Ventas</a>
        </li>
      </ul>
    </div>
  </div>
</nav>