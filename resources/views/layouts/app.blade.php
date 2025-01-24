<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
		<!-- <link href="libraries/css/tiny-slider.css" rel="stylesheet">
		<link href="libraries/css/style.css" rel="stylesheet"> -->
		
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
	<!-- <link rel="stylesheet" href="{{ asset('css/tiny-slider.css') }}"> -->
	<link rel="stylesheet" href="{{ asset('css/style.css') }}">

	
    
</head>
<body>
	@include('components.nav')
    @yield('content')

</body>
</html>
