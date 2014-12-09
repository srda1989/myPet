<?php 

class FriendRequest extends Eloquent {

	protected $table = 'friend_request';

	protected $fillable = array('sender_id', 'receiver_id', 'status'); //status can be pending, after user confirm friendship this row will be deleted and created two new rows in table friendship

	public static function checkRequest($requested_user_id) {
		
		$friend_requests = FriendRequest::where('receiver_id', '=', $requested_user_id)->count();

		return $friend_requests;

	}

	public static function getRequestIds($requested_user_id) {

		$friend_requests = FriendRequest::where('receiver_id', '=', $requested_user_id)->get();

		return $friend_requests;

	}


	public function sender() {
		return $this->belongsTo('User', 'sender_id');
	}

}