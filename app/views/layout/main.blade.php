<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>My pet social network</title>
		<script src="{{ URL::asset('assets/js/upload/angular-file-upload-shim.js') }}"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<!-- <link rel="stylesheet" href="{{ URL::asset('assets/css/bootstrap.min.css') }}"> -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css">
		<script src="{{ URL::asset('assets/js/bootstrap.min.js') }}"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.26/angular.min.js"></script>
		
		<script src="//angular-ui.github.io/bootstrap/ui-bootstrap-tpls-0.11.2.js"></script>
		<script src="{{ URL::asset('assets/js/bootstrap-datepicker.js') }}"></script>
		<script src="{{ URL::asset('assets/js/upload/angular-file-upload.min.js') }}"></script>
		<script src="{{ URL::asset('assets/js/upload/console-sham.min.js') }}"></script>
		<script src="{{ URL::asset('assets/js/angular-animate.min.js')}}"></script>
		<script src="{{ URL::asset('assets/js/app.js') }}"></script>

		<link rel="stylesheet" href="{{ URL::asset('assets/css/style.css') }}">
		<link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css">
		
	</head>
	<body>
		
		@include('layout.navigation2')

		<div class="container">
			<div class="row">
				<div class="content-left">
					
					<div class="newsfeed-container">

						@if(Session::has('global'))
							<div class="alert alert-info alert-dismissible" role="alert">
								<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span>
								<span class="sr-only">Close</span></button>
								<strong>{{ Session::get('global') }}</strong>
							</div>
						@endif

						@if(Session::has('global-danger'))
							<div class="alert alert-danger alert-dismissible" role="alert">
								<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span>
								<span class="sr-only">Close</span></button>
								<strong>{{ Session::get('global-danger') }}</strong>
							</div>
						@endif

						@if(Session::has('global-success'))
							<div class="alert alert-success alert-dismissible" role="alert">
								<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span>
								<span class="sr-only">Close</span></button>
								<strong>{{ Session::get('global-success') }}</strong>
							</div>
						@endif

					</div>

					@section('content')
						This is content
					@show
				</div>
				<div class="content-right">
					@section('sidebar')
						<div style="background-color: white; height: 500px;">
							<img src="{{ URL::asset('images/mypet/fon.gif') }}" width="90%" alt="" style="margin-top: 100px; margin-left: 5%; margin-right: 5%;">
						</div>
					@show
				</div>
			</div>
		</div>
	</body>
</html>