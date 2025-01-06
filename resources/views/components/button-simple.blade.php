
@props([
    'type' => 'button',
    'title' => '',
    'bg' => 'bg-[#0499af]',
    'hover' => 'bg-[#37cce2]',
    'name'=>"",

])

<button type="{{ $type }}" name="{{ $name }}" 
    data-te-ripple-init 
    data-te-ripple-color="light"
    {{ $attributes->merge([
        'class' => 'inline-block rounded ' . $bg . ' font-medium leading-normal text-white p-2 rounded-md hover:text-black transition duration-150 ease-in-out hover:bg-[#37cce2]' .
        ($attributes->has('disabled') ? ' opacity-50 cursor-not-allowed' : '')
    ]) }}
    {{ $attributes->has('disabled') ? 'disabled' : '' }}>

    &nbsp;&nbsp;

    {{ $title }}

</button>