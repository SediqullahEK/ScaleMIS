
<a {{ $attributes->merge(['class' => 'block p-3 bg-[#1b25ab] border-r border-white text-gray-200 flex justify-between rounded shadow shadow-inner mx-1 hover:bg-[#161e89] hover:text-white']) }} >
    <span class="text-[14px]">{{ $slot }}</span>
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
    </svg>

</a>