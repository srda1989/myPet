<?php

class Like extends Eloquent {

	protected $fillable = array('user_id', 'likeable_id', 'likeable_type');

	public function likeable() {
		return $this->morphTo();
	}

	public function user() {
		return $this->hasOne('User', 'user_id');
	}

	public function pet() {
		return $this->hasOne('Pet','id', 'likeable_id');
	}

}
