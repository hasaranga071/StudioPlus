<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Home</title>
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
    <!-- <link href="libraries/css/tiny-slider.css" rel="stylesheet">
		<link href="libraries/css/style.css" rel="stylesheet"> -->

    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
	<!-- <link rel="stylesheet" href="{{ asset('css/tiny-slider.css') }}"> -->
	<link rel="stylesheet" href="{{ asset('css/style.css') }}">

     <!-- Fonts -->
     <link rel="preconnect" href="https://fonts.bunny.net">
     <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

     <!-- Scripts -->
     <!-- @vite(['resources/css/app.css', 'resources/js/app.js']) -->



</head>
<body>
    {{-- <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white dark:bg-gray-800 shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main>

        </main>
    </div> --}}
	@include('components.nav')
    @yield('content')

</body>
</html>
