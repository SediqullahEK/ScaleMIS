<!DOCTYPE html>
<html lang="en" class="m-0 p-0">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>HighScale</title>

    @livewireStyles

    @vite(['resources/css/app.css', 'resources/js/app.js'])


    <link rel="stylesheet"
        href="{{ asset('assets/simple-notify-master/simple-notify-master/dist/simple-notify.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/Latest_Persian_Datepicker/persian-datepicker.css') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
        integrity="sha512-...." crossorigin="anonymous" />

    <style>
        input {
            padding: 0.75rem !important;
        }

        .loader {
            color: rgb(2, 110, 165);
        }
    </style>

    @stack('othercss')
    
    <link rel="stylesheet" href="https://unpkg.com/cropperjs/dist/cropper.css">

</head>

<body class="p-0 m-0 bg-[#FBFBFB] kohinoor">



    <x-highscale.topnav />


    <main class=" pl-5 pr-5 pb-5 pt-5  mb-12   bg-[#FBFBFB]  lg:w-[82%]  " style="margin-top: 80px">

        {{ $slot }}
    </main>


    <x-highscale.sidebar />


    <script src="{{ asset('assets/jquery/dist/jquery.min.js') }}"></script>

    @livewireScripts
    <script src="{{ asset('assets/simple-notify-master/simple-notify-master/dist/simple-notify.min.js') }}" defer></script>

    <script src="{{ asset('assets/Latest_Persian_Datepicker/persian-date.min.js') }}"></script>
    <script src="{{ asset('assets/Latest_Persian_Datepicker/persain-datepicker.js') }}"></script>
    <script src="https://unpkg.com/cropperjs"></script>


    <script defer>
        $(".receipt_date").pDatepicker({
            observer: true,

            format: 'YYYY-MM-DD',
            altField: '.observer-example-alt',
            calendar: {
                persian: {
                    locale: 'en'
                }
            },

        });
    </script>




    @stack('otherjs')




    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.6.2/dist/chart.min.js"></script>




</body>

</html>
