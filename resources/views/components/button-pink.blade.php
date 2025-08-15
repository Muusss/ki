// resources/views/components/button-pink.blade.php
@props(['type' => 'button', 'variant' => 'primary'])

<button 
    type="{{ $type }}"
    {{ $attributes->merge(['class' => 'btn-pink-'.$variant]) }}
>   
<x-button-pink variant="primary">Submit</x-button-pink>
<x-button-pink variant="outline">Cancel</x-button-pink>
    {{ $slot }}
</button>