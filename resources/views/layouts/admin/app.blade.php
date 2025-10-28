
<!DOCTYPE html>
<html lang="en">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<!-- Primary Meta Tags -->


	<!-- Volt CSS -->
	@include('layouts.admin.css')


</head>

<body>
	<!-- Volt Navbar -->
	@include('layouts.admin.navbar')

	@yield('content')

	<!-- Volt JS -->
	@include('layouts.admin.js')

</body>

</html>
