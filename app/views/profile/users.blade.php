@extends('layout.main')

@section('content')
	



<div class="newsfeed-container">
	<div class="panel panel-default">
	  <div class="panel-heading">
	    <h2 class="panel-title">Results for "{{$term}}"</h2>
	  </div>
	  <div class="panel-body">
	  @if($users->first())
	  	@foreach ($users as $user)

			@if($user->id == Auth::user()->id)
				<?php continue; ?>
			@endif

			<div class="row">
				<div class="col-md-2"><a href="{{URL::route('profile-user', array('username' => $user->username))}}">{{$user->username}}</a></div>
				<div class="col-md-5">{{$user->email}}</div>
				{{--<div class="col-md-2"><button class="btn btn-default">View more</button></div>--}}
				<div class="col-md-2"><a href="{{URL::route('profile-user', array('username' => $user->username))}}" class="btn btn-default" role="button">View more</a></div>
				@if(in_array($user->id, $friends))
					<div class="col-md-3"><a href="{{URL::route('remove-from-friend-list', array('friend_id' => $user->id))}}" class="btn btn-default" role="button">Remove from friends</a></div>
				@else
					<div class="col-md-3"><a href="{{URL::route('add-to-friend-list', array('friend_id' => $user->id))}}" class="btn btn-default" role="button">Add to friends</a></div>
				@endif
				
			</div>

			<hr>

	  	@endforeach
	  @else
		<div class="row">
			<div class="alert alert-danger" role="alert" style="margin-left: 20px; margin-right: 20px;">There is no results for term you have searched for!</div>
		</div>
	  @endif
	  </div>
	</div>
</div>


@stop