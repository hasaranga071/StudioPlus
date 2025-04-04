<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Home</title>
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    {{-- <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script> --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

     <style>
        .edit-order, .remove-order {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
            padding: 5px;
            font-size: 14px;
            border-radius: 50%;
        }

        .edit-order {
            background-color: #28a745;
            border: none;
            color: white;
        }

        .remove-order {
            background-color: #dc3545;
            border: none;
            color: white;
        }

        td {
            text-align: center;
        }

        .edit-order i, .remove-order i {
            font-size: 12px;
        }
        .action-buttons {
            display: flex;
            align-items: center;
            gap: 5px; /* Space between buttons */
        }

        .action-buttons button {
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-size: 14px;
            padding: 5px;
        }
        .highlighted-row {
            background-color: #b7baba !important; /* Light blue background */
            transition: background-color 0.3s ease-in-out;
        }
        /* Improved table styling */
            .order-summary-table {
                width: 100%;
                border-collapse: collapse;
                border-radius: 10px;
                overflow: hidden;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            }

            /* Styling for table headers */
            .order-summary-table thead {
                background-color: #757576;
                color: rgb(22, 21, 21);
                text-align: center;
                font-weight: bold;
            }

            /* Styling for table rows */
            .order-summary-table tbody tr {
                transition: background 0.3s ease-in-out;
            }

            /* Alternate row colors
            .order-summary-table tbody tr:nth-child(even) {
                background-color: #f8f9fa;
            } */

            /* Row hover effect */
            .order-summary-table tbody tr:hover {
                background-color: #7d7b7b;
            }

            /* Action buttons */
            .order-actions {
                display: flex;
                gap: 5px;
                justify-content: center;
            }

            .order-actions .btn {
                padding: 5px 8px;
                font-size: 14px;
                border-radius: 5px;
            }

            .btn-edit {
                background-color: #136426;
                color: white;
            }

            .btn-delete {
                background-color: #dc3545;
                color: white;
            }



        </style>


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
    @stack('scripts')


</body>
</html>
