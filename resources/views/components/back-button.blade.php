@props(['to' => 'home', 'label' => 'Back'])

<a href="{{ route($to) }}"
   {{ $attributes->merge(['class' => 'btn btn-primary btn-sm float-right' ]) }}>
    {{ __($label) }}
</a>
