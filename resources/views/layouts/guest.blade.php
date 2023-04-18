<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Tech Support') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body >
        <div class="container-fluid pl-0">
            <div class="row">
                <div class="col-sm-7 d-none d-sm-block" >
                    <div class="box">
                        <div class="centervertical">

                        </div>
                    </div>
                </div>

                <div class=" col-xs-12 col-sm-5 bg-light">
                    <div class="d-flex justify-content-center mt-6">
                        <a href="/">
                            <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                        </a>
                    </div>
                    <div class="d-flex justify-content-center mt-6">
                        <div class="w-full sm:max-w-md px-6 py-4 ml-6 mt-8 bg-white shadow-md overflow-hidden sm:rounded-lg">
                            {{ $slot }}
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </body>
</html>
