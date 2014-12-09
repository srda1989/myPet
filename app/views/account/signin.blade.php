@extends('layout.main')

@section('content')

	
	<div class="newsfeed-container">
		
		<div class="alert alert-success" role="alert">Please fill your email and password</div>

		<form class="form-horizontal" action="{{ URL::route('account-sign-in-post') }}" method="post" role="form">
			
			<div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
				<label for="email" class="col-sm-2 control-label">Email</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" name="email" id="inputError" {{ (Input::old('email') ? ' value="' . Input::old('email') . '"' : '') }}>

					@if($errors->has('email'))
						<span class="help-inline" role="alert">{{ $errors->first('email') }}</span>
					@endif

				</div>
				
			</div>

			<div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
				<label for="password" class="col-sm-2 control-label">Password</label>
				<div class="col-sm-8">
					<input type="password" class="form-control" name="password" id="inputError">
					
					@if($errors->has('password'))
						<span class="help-inline" role="alert">{{ $errors->first('password') }}</span>
					@endif

				</div>

			</div>

			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<div class="checkbox">
						<label>
							<input type="checkbox" name="remember" id="remember"> Remember me
						</label>
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<button type="submit" class="btn btn-default">Sign in</button>
				</div>
			</div>

			{{ Form::token() }}

		</form>


	</div>
	
@stop