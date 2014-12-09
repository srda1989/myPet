<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	//we have to define which fields exists in our database table
	protected $fillable = array('email', 'username', 'password', 'password_temp', 'role', 'code', 'active');

	//role can be admin and user

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');

	public function pets() {

		return $this->hasMany('Pet', 'vlasnik_id');

	}
	/**
	 * Returns all user likes
	 *
	 * Relation to Like mode, relation - has Many 0,M
	 */
	public function likes() {

		return $this->hasMany('Like', 'user_id');

	}

	/**
	 * Returns all the friend of current user
	 */
	public function friends() {

		return $this->belongsToMany('User', 'Friendship', 'user_id', 'friend_id');

	}

}
