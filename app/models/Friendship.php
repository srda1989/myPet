<?php

class Friendship extends Eloquent {

	protected $table = 'friendship';

	protected $fillable = array('user_id', 'friend_id');

}