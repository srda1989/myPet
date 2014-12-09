@extends('layout.main')

@section('content')

	<div class="newsfeed-container" ng-app="myPet" ng-controller="viewAllController as allPets">

		{{-- <pre><% allPets.likes %></pre> --}}

		<div class="row">

			<p class="text-center" ng-show="allPets.loading"><i class="fa fa-refresh fa-spin"></i></p>

			<div class="col-md-4"  ng-repeat="pet in allPets.pets">
				<div class="pet-container">
					<div class="pet-preview-picture">
						<img ng-src="<% pet.profile_image.location %>" alt="" width="100%" height="150px">
					</div>
					<div class="post-control-buttons" ng-show="allPets.isFriend(pet.vlasnik_id)">
						<button type="button" ng-class="{'btn-success' : allPets.isLikedId(pet.id)}" class="btn btn-primary"  ng-click='allPets.like(pet.id)'>Cool</button>
					</div>
					<div class="post-control-buttons">
						<a class="btn btn-default" ng-href="pet/view/<%pet.id%>">More</a>
					</div>
				</div>
			</div>
		</div>
	</div>

@stop
