<?php
class AccountController extends BaseController {

	//returns the sign in form to user
	public function getSignIn() {
		return View::make('account.signin');
	}

	public function getSignOut() {
		Auth::logout();
		return Redirect::route('home');
	}

	//function that is doing the process of singing in
	public function postSignIn() {
		$validator = Validator::make(Input::all(), array(
			'email' => 'required|email',
			'password' => 'required'
			)
		);

		if($validator->fails()) {
			//send user back to form and display errors
			return Redirect::route('account-sign-in')
					->withErrors($validator)
					->withInput();
		} else {

			$remember = (Input::has('remember')) ? true : false; //if Input model has remember property set the remember variable to true, otherwise set it to false

			$auth = Auth::attempt(array(
				'email' => Input::get('email'),
				'password' => Input::get('password'),
				'active' => 1
			), $remember);

			if($auth) {
				//redirect to the intended page
				return Redirect::intended('/');
			} else {
				return Redirect::route('account-sign-in')
						->with('global-danger', 'Email/password wrong, or account not activated');
			}
		}

		return Redirect::route('account-sign-in')
				->with('global-danger', 'There was the problem signing in. Have you activated ???');
	}

	//returns the view with the form to the User
	public function getCreate() {
		return View::make('account.create');
	}

	//when User submits the form, all the data will be send to this method
	public function postCreate() {
		//lets create the array of params with keys
		$rules = array(
			'email' => 'required|max:50|email|unique:users',
			'username'=> 'required|max:20|min:3|unique:users',
			'password' => 'required|min:6',
			'password_again' => 'required|same:password'
		);

		//we have to pass two params into this method, firts is input what we expect to receive and the second is rules that inputs are asociated with
		$validator = Validator::make(Input::all(), $rules); 

		if($validator->fails()) {
			return Redirect::route('account-create')
					->withErrors($validator)
					->withInput();
		} else {
			//create account
			$email = Input::get('email');
			$username = Input::get('username');
			$password = Input::get('password');

			//creating the random string for activation code

			$code = str_random(60);

			$user = User::create(array(
				'email' => $email,
				'username' => $username,
				'password' => Hash::make($password),
				'code' => $code,
				'active' => 0
			));

			if($user) {

				//send an email with activation link
				Mail::send('emails.activate', array('link' => URL::route('account-activate', $code), 'username' => $username), function ($message) use ($user) {
					$message->to($user->email, $user->username)->subject('Activate your account');
				});

				//we have to use $user because $user object is not accessible inside closure of mail function naturaly

				return Redirect::route('home')
						->with('global-success', 'Your account has been created! We have send you an email with activation link');
			}
		}
	}

	public function getActivate($code) {
		$user = User::where('code', '=', $code)->where('active', '=', 0);

		if($user->count()) {
			$user = $user->first();

			//update user to active state
			$user->active = 1;
			$user->code = '';

			if($user->save()) {
				return Redirect::route('home')
						->with('global-success', 'Activated! You can now sign in');
			} 
		}

		return Redirect::route('home')
				->with('global-danger', 'We could not activate your account, please try again later');
	}

	//prikazuje formu korisniku, dakle vraca View password koji se nalazi u direktorijumu account
	public function getChangePassword() {
		return View::make('account.password');
	}

	//hendluje unos sa forme, dakle ova funkcija je pogodjena rutom koja je zadata u form action=
	public function postChangePassword() {
		$validator = Validator::make(Input::all(), array(
			'old_password' => 'required|',
			'password' => 'required|min:6',
			'password_again' => 'required|same:password'
			)
		);

		if($validator->fails()) {
			return Redirect::route('account-change-password')
					->withErrors($validator);
		} else {
			//change password
			$user = User::find(Auth::user()->id);
			$old_password = Input::get('old_password');
			$password     = Input::get('password');

			if(Hash::check($old_password, $user->getAuthPassword())) {
				//password user provided matches
				$user->password = Hash::make($password);

				if($user->save()) {
					return Redirect::route('home')
							->with('global-success', 'Your password has been changed');
				}
			} else {
				return Redirect::route('home')
						->with('global-danger', 'Current password not corect!!!!');
			}

		}

		return Redirect::route('account-change-password')
				->with('global-danger', 'Your password could not be changed');
	}

	//returns the view with the form for forgotten password
	public function getForgotPassword() {
		return View::make('account.forgot');
	}

	// Handles the submition of the forgot password form
	public function postForgotPassword() {
		$validator = Validator::make(Input::all(), array(
			'email' => 'required|email'
			)
		);

		if($validator->fails()) {
			return Redirect::route('account-forgot-password')
					->withErrors($validator)
					->withInput();
		} else {
			//get user with that email, if not available put message about that
			$user = User::where('email', '=', Input::get('email'));

			if($user->count()) {
				$user = $user->first();

				// Generate a new code and password
				$code 	  = str_random(60);
				$password = str_random(10);

				$user->code 		 = $code;
				$user->password_temp = Hash::make($password);

				if($user->save()) {
					Mail::send('emails.auth.forgot', array('link' => URL::route('account-recover', $code), 'username' => $user->username, 'password' => $password), function($message) use ($user){
						$message->to($user->email, $user->username)->subject('Your new password');
					});

					return 	Redirect::route('account-sign-in')
							->with('global-success', 'We have send you a new message with new password.');
				}
			}
		}

		return Redirect::route('account-forgot-password')
				->with('global-danger', 'Could not request new password');
	}

	public function getRecover($code) {
		$user = User::where('code', '=', $code)
				->where('password_temp', '!=', '');

		if($user->count()) {
			$user = $user->first();

			$user->password 	 = $user->password_temp;
			$user->password_temp = '';
			$user->code 		 = '';

			if($user->save()) {
				return Redirect::route('home')
						->with('global-success', 'Your account has been recovered and you can now sign in with new password');
			}
		}

		return Redirect::route('home')
				->with('global-danger', 'Could not recover your account');
	}
}