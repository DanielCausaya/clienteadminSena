<div class="container mt-4">
    <h1 class="mb-4">Lista de Computers</h1>

    <table class="table table-bordered table-striped">
        <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>Number</th>
                <th>Brand</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>
            <div>
                    <a href="{{ route('computer.create') }}" class="btn btn-outline-success btn-sm">Nueva categoria</a>
                    <br><br>
                </div>
            @foreach ($computers as $computer)
                <tr>
                    <td>{{ $computer['id'] }}</td>
                    <td>{{ $computer['number'] }}</td>
                    <td>{{ $computer['brand'] }}</td>
                    <td>
                        <a href="{{ route('computer.show', $computer['id']) }}">
                            Ver más
                        </a>
                    </td>
                    <td>
                 <a href="{{ route('computer.edit', $computer['id']) }}" class="btn btn-warning btn-sm">Editar</a>
                        </td>
                        <td>
                            <form action="{{ route('computer.destroy', $computer['id']) }}" method="POST"
                                style="display: inline-block;"
                                onsubmit="return confirm('¿Estás seguro de eliminar esta categoría?')">

                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection