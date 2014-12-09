@extends('layout.main')

@section('content')

	<div class="single-pet-container" ng-app="myPet" ng-controller="single-pet-controller" explicit-value="{{$pet->id}}">

		{{-- <pre><% data %></pre> --}}
	@if($likeable)
		<button ng-hide='data.isLiked' type="button" class="btn btn-default" ng-click='likePet()'>
		  <span class="glyphicon glyphicon-heart-empty" aria-hidden="true"></span>
		</button>

		<button ng-show='data.isLiked' type="button" class="btn btn-default" ng-click='unlikePet()'>
		  <span class="glyphicon glyphicon-heart" aria-hidden="true"></span>
		</button>
	@endif
		<a href="mailto:{{$pet->user->email}}">
		<button type="button" class="btn btn-default">
		  <span class="glyphicon glyphicon-envelope" aria-hidden="true"></span>
		</button></a>


		<h1>{{$pet->name}}</h1>
		{{--<pre>{{$pet}}</pre>--}}

		<strong>Owner :</strong> <a href="{{URL::route('profile-user', array('username' => $pet->user->username))}}">{{$pet->user->username}}</a> <br>
		<strong>Specie :</strong> {{$pet->specie->name}} <br>
		<strong>Breed :</strong> {{$pet->breed}} <br>
		<strong>State :</strong> {{$pet->state}} <br>
		<strong>City :</strong> {{$pet->city}} <br>
		<strong>Birthdate :</strong> {{$pet->birthDate}}
	</div>

	<div class="single-pet-profile-image-container">
		<img src="{{URL::asset($pet->profileImage->location)}}" width="358px" height="" alt="">
	</div>



	<p class="clear"></p>



	<div class="newsfeed-container">

		<hr>

		<h2>Photos</h2>

		<div class="row">
			@foreach($pet->images as $image)
			<div class="col-md-4">
				<div class="photo-container">
					<img src="{{URL::asset($image->location)}}" width="234px" alt="">
				</div>
			</div>
			@endforeach
		</div>

		<hr>

	</div>

@stop
