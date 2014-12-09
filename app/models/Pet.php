<?php

class Pet extends Eloquent {

	protected $fillable = array('name', 'specie_id', 'breed', 'birthDate', 'image_link', 'state', 'city', 'vlasnik_id');

	protected $table = 'pets';

	public function images() {

		$user_id = Auth::user()->id;
		$pet_owner_id = $this->vlasnik_id;

		if($pet_owner_id == $user_id) {

			return $this->hasMany('Image', 'pet_id');

		} else {

			return $this->hasMany('Image', 'pet_id')->where('public', '=', 1);

		}

	}

	public function specie() {
		return $this->belongsTo('Specie', 'specie_id', 'id');
	}

	public function user() {
		return $this->belongsTo('User', 'vlasnik_id');
	}

	public function profileImage() {
		return $this->hasOne('Image', 'pet_id')->where('profile', '=', 1);
	}

	public function likes() {
		return $this->morphMany('Like', 'likeable');
	}

	public function user_likes($uid) {
		return $this->morphMany('Like', 'likeable')->where('user_id', '=', $uid);
	}

	public function likesD() {
		return $this->hasMany('Like', 'likeable_id');
	}


}
