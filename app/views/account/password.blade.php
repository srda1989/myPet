@extends('layout.main')

@section('content')

	
	<div class="newsfeed-container">
		
		<div class="alert alert-success" role="alert">Password recovery</div>

		<form class="form-horizontal" action="{{ URL::route('account-change-password-post') }}" method="post" role="form">
			
			<div class="form-group {{ $errors->has('old_password') ? 'has-error' : '' }}">
				<label for="old_password" class="col-sm-2">Old password</label>
				<div class="col-sm-8">
					<input type="password" name="old_password" id="inputError" class="form-control">

					@if($errors->has('old_password'))
						<span class="help-inline" role="alert">{{ $errors->first('old_password') }}</span>
					@endif
				</div>
			</div>

			<div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
				<label for="password" class="col-sm-2">New password</label>
				<div class="col-sm-8">
					<input type="password" name="password" id="inputError" class="form-control">
					@if($errors->has('password'))
						<span class="help-inline" role="alert">{{ $errors->first('password') }}</span>
					@endif
				</div>
			</div>

			<div class="form-group {{ $errors->has('password_again') ? 'has-error' : '' }}">
				<label for="password_again" class="col-sm-2">New password again</label>
				<div class="col-sm-8">
					<input type="password" name="password_again" id="inputError" class="form-control">
					@if($errors->has('password_again'))
						<span class="help-inline" role="alert">{{ $errors->first('password_again') }}</span>
					@endif
				</div>
			</div>

			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<button type="submit" class="btn btn-default">Change password</button>
				</div>
			</div>
			
			{{ Form::token() }}

		</form>

	</div>


	{{--
	<form action="{{ URL::route('account-change-password-post') }}" method="post">

		<div class="field">
			Old password: <input type="password" name="old_password">
			@if($errors->has('old_password'))
				{{ $errors->first('old_password') }}
			@endif
		</div>

		<div class="field">
			New password: <input type="password" name="password">
			@if($errors->has('password'))
				{{ $errors->first('password') }}
			@endif
		</div>

		<div class="field">
			New password again: <input type="password" name="password_again">
			@if($errors->has('password_again'))
				{{ $errors->first('password_again') }}
			@endif
		</div>

		<input type="submit" value="change password">
		{{ Form::token() }}
	</form> --}}
@stop