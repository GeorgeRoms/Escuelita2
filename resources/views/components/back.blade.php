@props([
  'to' => 'home',               // nombre de ruta
  'label' => 'Atrás',            // texto del botón
  'class' => 'btn btn-primary'
])

<a href="{{ route($to) }}" {{ $attributes->merge(['class' => $class]) }}> {{ __($label) }}
</a>
