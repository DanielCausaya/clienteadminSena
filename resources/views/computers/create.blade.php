@extends('layouts.app')

@section('content')
    <h1>Crear Computadores</h1>

    <form action="{{ route('computer.store') }}" method="POST" enctype="multipart/form-data">

        @csrf

        <label>
            Nombre:
            <br>
            <input type="text" name="name">
        </label>
        <br><br>
        <div class="mb-3">
            <label for="post_id" class="form-label">Seleccionar publicación</label>
            <br><br>
            <select name="post_id" id="post_id" class="form-select">
                <option value="">Seleccione una publicación</option>

                @foreach ($posts as $post)
                    <option value="{{ $post['id'] }}">
                        {{ $post['name'] }}
                    </option>
                @endforeach
            </select>
        </div>
        <br><br>
        <button type="submit" class="btn btn-outline-success btn-sm">Enviar Formulario:</button>
        <br><br>
    </form>
@endsection()