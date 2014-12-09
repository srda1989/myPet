<?php

class HomeController extends BaseController {
	public function home() {

		//get all pets that user likes

		$user = Auth::user();

		if(!$user) {
			return Redirect::route('account-sign-in');
		}


		$liked_pets = $user->likes;

		//try to get pets of my friends

		$friends = $user->friends;

		//var_dump($friends);

		return View::make('home')->with('friends', $friends);
	}

	public function test() {
		return View::make('test');
	}
}
