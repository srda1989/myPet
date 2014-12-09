@extends('layout.main')

@section('content')

	<div class="newsfeed-container">
		
		<div class="alert alert-success" role="alert">Registration</div>

		<form class="form-horizontal" action="{{ URL::route('account-create-post') }}" method="post" role="form">
			
			<div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
				<label for="email" class="col-sm-2 control-label">Email</label>
				<div class="col-sm-8">
					<input type="text" name="email" class="form-control" id="inputError" {{ (Input::old('email') ? 'value="' . Input::old('email') . '"' : '') }}>

					@if($errors->has('email'))
						<span class="help-inline" role="alert">{{ $errors->first('email') }}</span>
					@endif

				</div>
			</div>

			<div class="form-group {{ $errors->has('username') ? 'has-error' : '' }}">
				<label for="useername" class="col-sm-2 control-label">Username</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" name="username" {{ (Input::old('username') ? ' value="' . e(Input::old('username')) . '"' : '') }}>

					@if($errors->has('username'))
						<span class="help-inline" role="alert">{{ $errors->first('username') }}</span>
					@endif

				</div>
			</div>

			<div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
				<label for="password" class="col-sm-2 control-label">Password</label>
				<div class="col-sm-8">
					<input type="password" name="password" class="form-control" id="inputError">

					@if($errors->has('password'))
						<span class="help-inline" role="alert">{{ $errors->first('password') }}</span>
					@endif

				</div>
			</div>

			<div class="form-group {{ $errors->has('password_again') ? 'has-error' : '' }}">
				<label for="password" class="col-sm-2 control-label">Password</label>
				<div class="col-sm-8">
					<input type="password" name="password_again" class="form-control" id="inputError">

					@if($errors->has('password_again'))
						<span class="help-inline" role="alert">{{ $errors->first('password_again') }}</span>
					@endif

				</div>
			</div>

			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<button type="submit" class="btn btn-default">Create account</button>
				</div>
			</div>

			{{ Form::token() }}

		</form>

	</div>


@stop