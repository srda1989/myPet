@extends('layout.main')

@section('content')
	
	<div class="new-pet-container" ng-app="myPet" ng-controller="singleProfileController" explicit-value="{{$user->id}}">
		
		<h1>{{ $user->username }}</h2>

		<button ng-show="requestStatus == 'none'" type="button" class="btn btn-default" ng-click='sendFriendRequest({{$user->id}})'>
			<span class="glyphicon glyphicon glyphicon-plus" aria-hidden="true"></span>
			<strong>Add to Friends</strong>
		</button>

		<button ng-show="requestStatus == 'requested_me'" type="button" class="btn btn-default" ng-click='confirmFriendRequest({{$user->id}})'>
			<span class="glyphicon glyphicon glyphicon-plus" aria-hidden="true"></span>
			<strong>Confirm</strong>
		</button>

		<button ng-show="requestStatus == 'requestSended'" type="button" class="btn btn-default" ng-click='removeRequest({{$user->id}})'>
			<i class="fa fa-spinner fa-spin"></i> <strong>Cancel request</strong>
		</button>

		<button ng-show="requestStatus == 'friends'" type="button" class="btn btn-default" ng-click='removeFromFriends({{$user->id}})'>
			<span class="glyphicon glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
			<strong>Remove from Friends</strong>
		</button>
		
		<br><br>

		<p><small>username: </small>{{ $user->username }}</p>
		<p><small>email: </small>{{ $user->email }}</p>

		<div class="row">

			

			
		</div>

	
	</div>

	<hr>
	
	<div class="newsfeed-container">
	
	<h2>Pets</h2>

	<div class="row">

	@if($user->pets->first())

		@foreach($user->pets as $pet)
			<div class="col-xs-6 col-md-2">
				<a href="{{ URL::route('view-pet-get', array('pet_id' => $pet->id)) }}" class="thumbnail">
					<img src="{{URL::asset($pet->profileImage->location)}}" width="171px" height="180px" alt="">
				</a>
			</div>
		@endforeach

	@else

		<div class="alert alert-danger" role="alert">User has no pets</div>

	@endif

	</div>

	</div>

	

@stop