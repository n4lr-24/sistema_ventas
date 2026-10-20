<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
Nueva Categoria
</button>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Registrar Categoria</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('categoria.create') }}" method="POST">
            @csrf
            <label for="categoria" class="form-label">Nombre</label>
            <input id="categoria" name="categoria" value="{{ old('categoria') }}" class="form-control" type="text" placeholder="Ingresa el nombre de la categoría">
            @error('categoria')
            <div class="col">
                <small class="text-danger">{{$message}}</small>
            </div>
            @enderror
            <div class="col">
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