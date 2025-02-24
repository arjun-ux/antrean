<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">

	<title>CEK Antrian</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <!-- End fonts -->

	<!-- core:css -->
	<link rel="stylesheet" href="{{ asset('assets/vendors/core/core.css') }}">
	<!-- endinject -->

	<!-- Plugin css for this page -->
	<!-- End plugin css for this page -->

	<!-- inject:css -->
    <link rel="stylesheet" href="{{ asset('assets/vendors/toastr/toastr.min.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/fonts/feather-font/css/iconfont.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/vendors/flag-icon-css/css/flag-icon.min.css') }}">
	<!-- endinject -->

    <!-- Layout styles -->
    <link rel="stylesheet" href="{{ asset('assets/css/light/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/light/toastr.css') }}">
    <!-- End layout styles -->

    {{--  <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" />  --}}
</head>
<body>
	<div class="main-wrapper">
		<div class="page-wrapper full-page">
			<div class="page-content d-flex align-items-center justify-content-center">
				<div class="row w-100 mx-0 auth-page">
					<div class="col-md-4 col-xl-3 mx-auto text-center">
						<div class="card">
                            @if (Auth::user() == null)
                                <h2>SILAHKAN LOGIN</h2>
                                <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
                            @elseif (Auth::user()->ref_group_id == "2")
                                <a href="{{ route('pasien.index') }}" class="btn btn-primary">Dashboard</a>
                            @elseif (Auth::user()->ref_group_id == '1')
                                <a href="{{ route('admin.index') }}" class="btn btn-primary">Dashboard</a>
                            @endif
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- core:js -->
	<script src="{{ asset('assets/vendors/core/core.js') }}"></script>
	<!-- endinject -->

	<!-- Plugin js for this page -->
	<!-- End plugin js for this page -->

	<!-- inject:js -->
	<script src="{{ asset('assets/vendors/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/toastr/toastr.min.js') }}"></script>
	<script src="{{ asset('assets/js/template.js') }}"></script>
	<!-- endinject -->

	<!-- Custom js for this page -->
	<!-- End custom js for this page -->


</body>
</html>
