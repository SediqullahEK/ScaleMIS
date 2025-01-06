<x-highscale.layout>

    @push('othercss')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    @endpush


    <livewire:highscale.high_scale_report />




    @push('otherjs')
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    @endpush

</x-highscale.layout>
