<nav class="navbar navbar-default" role="navigation">
	<div class="container-fluid">
		<div class="navbar-header">
	      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
	        <span class="sr-only">Toggle navigation</span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	      </button>
	      <a class="navbar-brand" href="#">MyPet.com</a>
	    </div>
	    <!-- Collect the nav links, forms, and other content for toggling -->
	    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav">
		        <li class="{{Request::path() == '/' ? 'active' : '';}}"><a href="{{ URL::route('home') }}">Home</a></li>
		        <li class="{{Request::path() == 'pets' ? 'active' : '';}}"><a href="{{ URL::route('petsPage') }}">Pets</a></li>
		        <li class="{{ Request::path() == 'friends' ? 'active' : ''; }}"><a href="{{ URL::route('friend-list') }}">Friends</a></li>
		        <li class="dropdown {{Request::path() == 'pet/add-new' || Request::path() == 'pet/edit-pet' ? 'active' : '';}}">
		          <a href="#" class="dropdown-toggle" data-toggle="dropdown">My pets<span class="caret"></span></a>
		          <ul class="dropdown-menu" role="menu">
		            <li><a href="{{ URL::route('pet-add-new') }}">Add new pet</a></li>
		            <li><a href="#">Edit pet</a></li>
		          </ul>
		        </li>
	      	</ul>
	      	@if(Auth::check())
		      	<form class="navbar-form navbar-left" role="search" action="{{URL::route('search-user')}}" method="post">
			        <div class="form-group">
			          <input type="text" name="user_name" class="form-control" placeholder="Find people">
			        </div>
			        <button type="submit" class="btn btn-default">Search</button>
			        
		      	</form>
	      	@endif
	      	<ul class="nav navbar-nav navbar-right">
		        @if(Auth::check())

					{{-- check if there is some friend requests --}}
					
					@if(FriendRequest::checkRequest(Auth::user()->id))
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">Friend Requests <span class="badge">{{FriendRequest::checkRequest(Auth::user()->id)}}</span><span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
						
							@foreach(FriendRequest::getRequestIds(Auth::user()->id) as $request)
								<li>
									<button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
	  									<a href="{{ URL::route('profile-user', array('username' => $request->sender->username)) }}">{{$request->sender->username}}</a>
									</button>

									
									<button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
	  									<a href="{{ URL::route('confirm-friend-request', array('request_id' => $request->id)) }}"><span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span></a>
									</button>

									<button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
	  									<a href="{{ URL::route('decline-friend-request', array('request_id' => $request->id)) }}"><span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span></a>
									</button>
								</li>
							@endforeach
						</ul>
					</li>
				
				@endif

				{{-- end of checking --}}

		        <li class="dropdown">
		          <a href="#" class="dropdown-toggle" data-toggle="dropdown">{{ Auth::user()->username }} <span class="caret"></span></a>
		          <ul class="dropdown-menu" role="menu">
		            <li><a href="{{ URL::route('profile-user', array('username' => Auth::user()->username)) }}">View profile</a></li>
		            <li><a href="{{ URL::route('account-change-password') }}">Change password</a></li>
		            <li class="divider"></li>
		            <li><a href="{{ URL::route('account-sign-out') }}">Log out</a></li>
		          </ul>
		        </li>
		        @else
		        <li><a href="{{ URL::route('account-create') }}">Register</a></li>
		        <li><a href="{{ URL::route('account-sign-in') }}">Log in</a></li>
		        <li><a href="{{ URL::route('account-forgot-password') }}">Forgot a password</a></li>
		        @endif
	      	</ul>
	    </div>
	</div>
</nav>