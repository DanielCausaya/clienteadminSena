 @extends('layouts.app')

@section('content')

<h1>Editar Categoria</h1>

<form action="{{route('computer.update',$computer['id'] )}}" method="POST" enctype="multipart/form-data">

@csrf
@method('PUT')

<label>
    Nombre:
    <br>
    <input type="text" name="name" value="{{ old('name', $computer['name'] ?? '') }}" >
</label>
<br>

<br><br>
<button type="submit" class="btn btn-outline-success btn-sm">Enviar Formulario:</button>
<br><br>
</form>

@endsection()