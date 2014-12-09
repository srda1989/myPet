@extends('layout.main')

@section('content')

	<div class="newsfeed-container" ng-app="myPet">

		@if(Auth::check())
			<div class="alert alert-success" role="alert">Hello, {{ Auth::user()->username }}</div>
		@else
			<div class="alert alert-danger" role="alert">You are not signed in, please sign in/register to start using our social network</div>
		@endif

		<div class="home-left" ng-controller="homeController">

			<div class="panel panel-primary">

				<div class="panel-heading">
					<h2 class="panel-title">Pets of your friends</h2>
				</div>

				<div class="panel-body">

					@foreach($friends as $friend)

						<div class="panel panel-default">
							<div class="panel-heading">
								<h2 class="panel-title"><a href="{{URL::route('profile-user', array('username' => $friend->username))}}"> {{ $friend->username }} </a></h2>
							</div>

							<div class="panel-body">

							@if($friend->pets->first())

								@foreach($friend->pets as $pet)

								<div class="homepage-pet-container">

									<div class="each-pet-left">
										<li><em>Name: </em><a href="{{ URL::route('view-pet-get', array('id' => $pet->id)) }}"> {{ $pet->name }} </a></li>
										<li><em>Specie: </em>{{ $pet->specie->name }}</li>
										<li><em>Breed: </em>{{ $pet->breed }}</li>
										<li><em>State: </em>{{ $pet->state }}</li>
										<li><em>City: </em>{{ $pet->city }}</li>
										<li><em>Birthdate: </em>{{ $pet->birthDate }}</li>
									</div>

									<div class="each-pet-right">
										<a ng-href="{{ URL::route('view-pet-get', array('id' => $pet->id)) }}" class="thumbnail" style="margin-left: 15px; margin-right: 15px;">
											<img ng-src="{{ $pet->profileImage->location }}" width="153px" alt="">
										</a>
									</div>

								</div>

								@endforeach

							@else
								Nema ljubimaca
							@endif

							</div>

						</div>



					@endforeach


				</div>
			</div>

		</div>

		<div class="home-right">

			<div class="home-right" ng-controller="likedPetsController">
				<div class="panel panel-default">

					<div class="panel-heading">
						<h2 class="panel-title">Pets you liked</h2>
					</div>

					<div class="panel-body">

						<p class="text-center" ng-show="loading"><i class="fa fa-refresh fa-spin"></i></p>

						<div class="row my-show-hide-animation" ng-repeat="pet in pets" ng-hide="loading">

							<a ng-href="<% pet.pet.page %>" class="thumbnail" style="margin-left: 15px; margin-right: 15px;">
								<img ng-src="<% pet.pet.profile_image.location %>" width="153px" alt="">
							</a>

						</div>


					</div>
				</div>
			</div>

			<div class="home-right" ng-controller="myPetsController">
				<div class="panel panel-default">

					<div class="panel-heading">
						<h2 class="panel-title">Your pets</h2>
					</div>

					<div class="panel-body">

						<p class="text-center" ng-show="loading"><i class="fa fa-refresh fa-spin"></i></p>

						<div class="row my-show-hide-animation" ng-repeat="pet in pets" ng-hide="loading">

							<a ng-href="<% pet.page %>" class="thumbnail" style="margin-left: 15px; margin-right: 15px;">
								<img ng-src="<% pet.profile_image.location %>" width="153px" alt="">
							</a>

						</div>

					</div>

				</div>

			</div>

		</div>





	</div>
@stop
