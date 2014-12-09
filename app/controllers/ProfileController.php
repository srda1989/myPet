<?php

class ProfileController extends BaseController {

	public function user($username) {
		$user = User::where('username', '=', $username);

		if($user->count()) {
			$user = $user->first();
			return 	View::make('profile.user')
					->with('user', $user);
		}

		return App::abort(404);
	}

	public function searchUser() {

		$query = Input::get('user_name');

		$users = $this->searchForUser($query);

		//var_dump($users);

		$myself = Auth::user();
		$friends = $myself->friends;

		

		$friend_array = array();

		foreach($friends as $friend) {
			$friend_array[] = $friend->id;
		}

		return View::make('profile.users')->with('users', $users)->with('term', $query)->with('friends', $friend_array);

	}

	public function searchForUser($term) {

		$user = User::where('username', 'LIKE', "%$term%")->get();

		return $user;

	}

}