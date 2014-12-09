<?php

class FriendController extends BaseController {

	public function checkStatus() {

		$data = Input::all();

		$profile_id = Input::get('id');

		$user_id = Auth::user()->id;

		//check if user request a page of its profile

		if($profile_id == $user_id) {

			$responce = array(
				'success' => 1,
				'status' => 'self_page'
			);

			return Response::json($responce);
		}

		$request_to_me = FriendRequest::where('receiver_id', '=', $user_id) ->where('sender_id', '=', $profile_id)->get();

		if($request_to_me->first()) {
			//user requested a profile page of user that sended request to him
			$responce = array(
				'success' => 1,
				'status' => 'requested_me'
			);

			return Response::json($responce);
		}

		$request = FriendRequest::where('receiver_id', '=', $profile_id)->where('sender_id', '=', $user_id)->get();

		if($request->first()) {
			//there is request in table friend_request
			$responce = array(
				'success' => 1,
				'status' => $request->first()->status,
				'ulazni_parametri' => $data
			);
		} else {
			//ne postoji nista u tabeli friend request, proveriti u tabeli friendship
			$friendship = Friendship::where('friend_id', '=', $profile_id)->where('user_id', '=', $user_id)->get();
			if(!$friendship->isEmpty()) {
				//pronadjeno je prijateljstvo izmedju ova dva usera
				$responce = array(
					'success' => 1,
					'status' => 'friends',
					'ulazni_parametri' => $data
				);
			} else {
				$responce = array(
					'success' => 1,
					'status' => 'none',
					'ulazni_parametri' => $data
				);
			}
		}


		return Response::json($responce);
	}

	public function removeFromFriendsApi() {

		$profile_id = Input::get('id');

		$user_id = Auth::user()->id;

		$friendship1 = Friendship::where('user_id', '=', $user_id)->where('friend_id', '=', $profile_id)->delete();
		$friendship2 = Friendship::where('user_id', '=', $profile_id)->where('friend_id', '=', $user_id)->delete();

		$responce = array(
			'success' => 1,
			'status' => 'none'
		);

		return Response::json($responce);

	}

	public function confirmRequestApi() {

		$profile_id = Input::get('id');

		$user_id = Auth::user()->id;

		$friend_request = FriendRequest::where('sender_id', '=', $profile_id)->where('receiver_id', '=', $user_id)->first();

		//$request_data->status = 'complete';

		$friend_request->delete();

		$first_friendship = Friendship::create(array(
			'user_id' => $profile_id,
			'friend_id' => $user_id
		));

		$second_friendship = Friendship::create(array(
			'user_id' => $user_id,
			'friend_id' => $profile_id
		));

		$responce = array(
			'success' => 1,
			'status' => 'friends'
		);

		return Response::json($responce);


	}

	public function sendRequest() {

		$profile_id = Input::get('id');

		$user_id = Auth::user()->id;

		$friend_request = FriendRequest::create(array(
			'sender_id' => $user_id,
			'receiver_id' => $profile_id,
			'status' => 'requestSended'
		));

		$responce = array(
			'success' => 1,
			'message' => 'Successfuly sended friend request'
		);

		if(!$friend_request) {
			$responce['success'] = 0;
			$responce['message'] = 'An error on the server occured, please try later';
		}

		return Response::json($responce);
	}

	public function removeRequest() {

		$profile_id = Input::get('id');

		$user_id = Auth::user()->id;

		$friend_request = FriendRequest::where('sender_id', '=', $user_id)->where('receiver_id', '=', $profile_id)->delete();

		print_r($friend_request);

	}

	public function confirmFriendRequest($request_id) {

		$friend_request = FriendRequest::find($request_id);

		$request_sender_id = $friend_request->sender_id;
		$request_receiver_id = $friend_request->receiver_id;

		$friend_request->status = 'confirmed';

		$friend_request->save();

		//create two new Friendships

		$first_friendship = Friendship::create(array(
			'user_id' => $request_sender_id,
			'friend_id' => $request_receiver_id
		));

		$second_friendship = Friendship::create(array(
			'user_id' => $request_receiver_id,
			'friend_id' => $request_sender_id
		));

		//delete request
		$friend_request->delete();

		return Redirect::back();

	}

	public function declineFriendRequest($request_id) {

		$friend_request = FriendRequest::find($request_id);

		$friend_request->delete();

		return Redirect::back();

	}

	public function allFriends() {

		$user = Auth::user();

		$friends = $user->friends;

		return View::make('profile.friends')->with('friends', $friends)->with('user', $user);

	}

	public function removeFromFriendList($friend_id) {

		$user_id = Auth::user()->id;

		$Friendship1 = Friendship::where('user_id', '=', $user_id)->where('friend_id', '=', $friend_id)->delete();
		$Friendship2 = Friendship::where('user_id', '=', $friend_id)->where('friend_id', '=', $user_id)->delete();

		return Redirect::back();

	}

	public function addToFriendList($receiver_id) {

		$profile_id = $receiver_id;

		$profile = User::find($profile_id);

		$user_id = Auth::user()->id;

		$friend_request = FriendRequest::create(array(
			'sender_id' => $user_id,
			'receiver_id' => $profile_id,
			'status' => 'requestSended'
		));

		$responce = array(
			'success' => 1,
			'message' => 'Successfuly sended friend request'
		);

		if(!$friend_request) {
			$responce['success'] = 0;
			$responce['message'] = 'An error on the server occured, please try later';
		}

		return Redirect::route('profile-user', array('username' => $profile->username));	
	}

}