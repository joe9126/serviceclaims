<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Tech Support - @yield('title')</title>

         <!--datatable styles styles-->
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
       <link href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap.min.css" rel="stylesheet">
      
       <!--bootstrap  styles-->
       <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

       <!--bootstrap datepicker styles styles-->

       <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
          <!-- Include Bootstrap DateTimePicker CDN -->
    <link href="" rel="stylesheet">
      
       <!-- Fonts -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

       
        <!-- Scripts -->
        @vite(['resources/css/app.css','resources/css/parsley.css', 'resources/js/app.js','resources/js/script.js',
        'resources/js/jquery.number.js','resources/js/parsley.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                @yield('content')
            </main>
        </div>
      <!--<div class="modal" id="loader"></div>-->
    </body>
    <footer >

    </footer>
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <script src=" https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"></script>
    <script src="https://momentjs.com/downloads/moment.js"></script>

   
   <!-- canvas.js for graphs and charts -->
   <script type="text/javascript" src="https://canvasjs.com/assets/script/jquery.canvasjs.min.js"></script>

    <!-- datatable scripts -->
    <script type="text/javascript" charset="utf8"src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
   <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
   <script type="text/javascript" src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

  <!--LOAD PRINTJS LIBRARY-->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/print-js/1.6.0/print.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/print-js/1.6.0/print.css" />

  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"> </script>
   
</html>
