<?php

Route::get('/', array(
	'as' => 'home',
	'uses' => 'HomeController@home'
));



Route::get('/user/{username}', array(
	'as' => 'profile-user',
	'uses' => 'ProfileController@user'
));

//Authenticated group
Route::group(array('before' => 'auth'), function() {

	//CRSF protection group
	Route::group(array('before' => 'csrf'), function() {
		//Change password (POST)
		Route::post('account/change-password', array(
			'as' => 'account-change-password-post',
			'uses' => 'AccountController@postChangePassword'
		));
	});

	Route::get('/pets', array(
		'as' => 'petsPage',
		'uses' => 'PetsController@all'
	));

	//Change password (GET)
	Route::get('account/change-password', array(
		'as' => 'account-change-password',
		'uses' => 'AccountController@getChangePassword'
	));

	//Sign out (GET)
	Route::get('account/sign-out', array(
		'as' => 'account-sign-out',
		'uses' => 'AccountController@getSignOut'
	));

	//Add new pet (GET)
	Route::get('pet/add-new', array(
		'as' => 'pet-add-new',
		'uses' => 'PetsController@getAddNewPet'
	));

	// Upload a photo (POST)
	Route::post('pet/test/upload', array(
		'as' => 'photo-upload',
		'uses' => 'PetsController@postNewPhoto'
	));

	Route::post('/search', array(
		'as' => 'search-user',
		'uses' => 'ProfileController@searchUser'
	));

	Route::post('pet/saveNewPet', array(
		'as' => 'save-new-pet-post',
		'uses' => 'PetsController@submitNewPet'
	));

	Route::get('pet/view/{pet_id}', array(
		'as' => 'view-pet-get',
		'uses' => 'PetsController@viewPet'
	));

	Route::get('confirmRequest/{request_id}', array(
		'as' => 'confirm-friend-request',
		'uses' => 'FriendController@confirmFriendRequest'
	));

	Route::get('declineFriendRequest/{request_id}', array(
		'as' => 'decline-friend-request',
		'uses' => 'FriendController@declineFriendRequest'
	));

	Route::get('removeFromFriendList/{friend_id}', array(
		'as' => 'remove-from-friend-list',
		'uses' => 'FriendController@removeFromFriendList'
	));

	Route::get('addToFriendList/{friend_id}', array(
		'as' => 'add-to-friend-list',
		'uses' => 'FriendController@addToFriendList'
	));

	Route::get('friends', array(
		'as' => 'friend-list',
		'uses' => 'FriendController@allFriends'
	));

	//ROUTE FOR SAVING FILE
// 	Route::post('pet/test/upload', function(){

//     //$files = Input::file('files');
// 	$files = Input::file();

//     if(isset($files)) {
//     	echo '<pre>',print_r($files),'</pre>';
//     }
//     // foreach($files as $file) {
//     //             // public/uploads
//     //     $file->move('uploads/');
//     // }
// });

});


/**
 * Unauthenticated group
 */
Route::group(array('before' => 'guest'), function() {

	//CSRF protection group
	Route::group(array('before' => 'csrf'), function() {

		//create account (POST)
		Route::post('/account/create', array(
			'as' => 'account-create-post',
			'uses' => 'AccountController@postCreate'
		));

		//sign in (POST)
		Route::post('/account/sign-in', array(
			'as' => 'account-sign-in-post',
			'uses' => 'AccountController@postSignIn'
		));

		//Forgot password(POST)
		Route::post('/account/forgot',array(
			'as' => 'account-forgot-password-post',
			'uses' => 'AccountController@postForgotPassword'
		));

	});

	// Forgot password(GET)
	Route::get('/account/forgot',array(
		'as' => 'account-forgot-password',
		'uses' => 'AccountController@getForgotPassword'
	));

	// Route that handles recovery users password
	Route::get('/account/recover/{code}', array(
		'as' => 'account-recover',
		'uses' => 'AccountController@getRecover'
	));

	// Sign in (GET)
	Route::get('/account/sign-in', array(
		'as' => 'account-sign-in',
		'uses' => 'AccountController@getSignIn'
	));

	//Create account (GET)
	Route::get('/account/create', array(
		'as' => 'account-create',
		'uses' => 'AccountController@getCreate'
	));

	Route::get('/account/activate/{code}', array(
		'as' => 'account-activate',
		'uses' => 'AccountController@getActivate'
	));

	Route::get('/test', array(
		'as' => 'test-home',
		'uses' => 'HomeController@test'
	));

});


/*
 * API routes
 */
Route::group(array('prefix' => 'api'), function() {

	// since we will be using this just for CRUD, we won't need create and edit
	// Angular will handle both of those forms
	// this ensures that a user can't access api/create or api/edit when there's nothing there
	Route::resource('pets', 'PetsController', 
		array('only' => array('index', 'submitNewPet', 'get_all_pets', 'singlePetData')));

	Route::post('saveNewPet', array(
		'as' => 'submit-new-pet-post',
		'uses' => 'PetsController@submitNewPet'
	));

	Route::get('singlePet/{petId}', array(
		'as' => 'get-single-pet-data',
		'uses' => 'PetsController@getSinglePetData'
	));

	Route::post('addLike', array(
		'as' => 'submit-new-like-pet',
		'uses' => 'PetsController@submitNewLikeForPet'
	));

	Route::post('removeLike', array(
		'as' => 'remove-like-pet',
		'uses' => 'PetsController@removeLikeForPet'
	));

	Route::post('checkFriendsStatus', array(
		'as' => 'check-friend-status-post',
		'uses' => 'FriendController@checkStatus'
	));

	Route::post('sendFriendRequest', array(
		'as' => 'send-friend-request',
		'uses' => 'FriendController@sendRequest'
	));

	Route::post('removeFriendRequest', array(
		'as' => 'remove-friend-request',
		'uses' => 'FriendController@removeRequest'
	));

	Route::post('confirmFriendRequest', array(
		'as' => 'confirm-friend-request-api',
		'uses' => 'FriendController@confirmRequestApi'
	));

	Route::post('removeFromFriends', array(
		'as' => 'remove-from-friends-api',
		'uses' => 'FriendController@removeFromFriendsApi'
	));

	Route::get('/likedPets', array(
		'as' => 'get-liked-pets-api',
		'uses' => 'PetsController@getLikedPets'
	));

	Route::get('/myPets', array(
		'as' => 'get-my-pets',
		'uses' => 'PetsController@getMyPets'
	));

	// Route::get('/api/singlePet/{petId}', function($petId) {

	// 	$petData = Pet::find($petId);

	// 	dd($petData);

	// });

	// Route::resource('singlePetData/{petId}', function($petId) {
	// 	$petData = Pet::find($petId);

	// 	dd($petData);
	// });

});
