<!DOCTYPE html>
<html lang="en" class="m-0 p-0">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">





    <title>Document</title>



    @livewireStyles

    @vite(['resources/css/app.css', 'resources/js/app.js'])


    <link rel="stylesheet"
        href="{{ asset('assets/simple-notify-master/simple-notify-master/dist/simple-notify.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/Latest_Persian_Datepicker/persian-datepicker.css') }}">


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


    <style>
        input {
            padding: 0.75rem !important;
        }
    </style>

    @stack('othercss')

</head>

<body class="p-0 m-0 bg-[#FBFBFB] kohinoor">

  

    <x-top-nav />




    <main class="float-left pl-5 pr-5 pb-5 pt-3  mb-12   bg-[#FBFBFB]  lg:w-[85%]  ">

        {{ $slot }}
    </main>


    <x-sidebar />




    @livewireScripts

    <script src="{{ asset('assets/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/simple-notify-master/simple-notify-master/dist/simple-notify.min.js') }}" defer></script>

    <script src="{{ asset('assets/Latest_Persian_Datepicker/persian-date.min.js') }}"></script>
    <script src="{{ asset('assets/Latest_Persian_Datepicker/persain-datepicker.js') }}"></script>
    

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

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


    @stack('otherjs')





</body>

</html>
