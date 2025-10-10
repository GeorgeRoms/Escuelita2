@extends('layouts.app')

@section('title', 'Error | Escuelita')

@section('content')
<div class="container text-center mt-5">
    <h1 class="mb-3" style="color:#07427a;">Algo ha fallado</h1>

    @if (session('mensaje'))
        <p class="mb-4">{{ session('mensaje') }}</p>
    @else
        <p class="mb-4">Ocurrió un error inesperado. Por favor, vuelva a intentarlo más tarde o comuníquese con el administrador del sistema.</p>
    @endif

    <a href="{{ url()->previous() }}" class="btn btn-primary mt-3">Regresar</a>
</div>
@endsection
