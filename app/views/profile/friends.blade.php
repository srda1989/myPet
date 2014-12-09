@extends('layout.main')

@section('content')
	



<div class="newsfeed-container">
	<div class="panel panel-default">
	  <div class="panel-heading">
	    <h2 class="panel-title">{{$user->username}} 's friends</h2>
	  </div>
	  <div class="panel-body">
	  	@foreach ($user->friends as $friend)
			<div class="row">
				<div class="col-md-2"><a href="{{URL::route('profile-user', array('username' => $friend->username))}}">{{$friend->username}}</a></div>
				<div class="col-md-5">{{$friend->email}}</div>
				{{--<div class="col-md-2"><button class="btn btn-default">View more</button></div>--}}
				<div class="col-md-2"><a href="{{URL::route('profile-user', array('username' => $friend->username))}}" class="btn btn-default" role="button">View more</a></div>
				{{--<div class="col-md-3"><button class="btn btn-default">Remove from friends</button></div>--}}
				<div class="col-md-3"><a href="{{URL::route('remove-from-friend-list', array('friend_id' => $friend->id))}}" class="btn btn-default" role="button">Remove from friends</a></div>
			</div>

			<hr>

	  	@endforeach
	  </div>
	</div>
</div>


@stop