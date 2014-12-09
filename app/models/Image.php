<?php

class Image extends Eloquent {

	protected $table = 'images';

	protected $fillable = array('image_name', 'location', 'user_id', 'pet_id', 'profile', 'public');

	public function imageable() {
		return $this->morphTo();
	}

}
