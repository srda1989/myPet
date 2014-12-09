<?php

class PetsController extends BaseController {

	public function singlePetData($petId) {
		return $petId;
	}

	public function getLikedPets() {

		$user = Auth::user();

		$likes = $user->likes;

		foreach($likes as $like) {

			$pet = Pet::find($like->likeable_id);
			$pet_profile_image = $pet->profileImage;

			$like->pet = $pet;
			$like->pet->profile_image = $pet_profile_image;
			$like->pet->page = URL::route('view-pet-get', array('pet_id' => $pet->id));
		}

		return Response::json($likes);
	}

	public function getMyPets() {

		$user = Auth::user();

		$pets = $user->pets;

		foreach($pets as $pet) {
			$pet->profile_image = $pet->profileImage;
			$pet->page = URL::route('view-pet-get', array('pet_id' => $pet->id));
		}

		return Response::json($pets);
	}

	/*
	 * Get all data for angular js
	 */
	public function getSinglePetData($petId) {

		$pet = Pet::find($petId);

		$uid = Auth::user()->id;

		$like = Like::where('likeable_id', '=', $pet->id)->where('user_id', '=', $uid)->first();

		if($like) {
			$pet->isLiked = true;
		} else {
			$pet->isLiked = false;
		}

		return Response::json($pet);

	}

	/*
	* Saving like for pet
	*/
	public function submitNewLikeForPet() {

		$user_id = Auth::user()->id;
		$pet_id = Input::get('id');
		$model = 'Pet';

		$message = array(
			'success' => 0,
			'message' => 'Server eror!'
		);

		$like = Like::create(array(
					'user_id' => $user_id,
					'likeable_id' => $pet_id,
					'likeable_type' => $model
				));

		if($like) {
			$message['success'] = 1;
			$message['message'] = 'Success!';
		}

		return Response::json($message);
	}



	/*
	 * Removes the like for Pet from db
	 */
	public function removeLikeForPet() {

		$user_id = Auth::user()->id;
		$pet_id = Input::get('id');
		$model = 'Pet';

		$message = array(
			'success' => 0,
			'message' => 'Server error'
		);

		$like = Like::where('user_id', '=', $user_id)->where('likeable_id', '=', $pet_id)->where('likeable_type', '=', 'Pet')->delete();

		if($like) {
			$message['success'] = 1;
			$message['message'] = 'Successfuly deleted like';
		}

		return Response::json($message);
	}



	public function viewPet($petId) {
		$pet = Pet::find($petId);

		$user_id = Auth::user()->id;
		$owner_id = $pet->vlasnik_id;

		$friendship = Friendship::where('user_id', '=', $user_id)->where('friend_id','=', $owner_id)->get();

		//see if they are friends or it is a same profile

		if($user_id == $owner_id) {
			$likeable = false;
		} elseif(!$friendship->count()) {
			$likeable = false;
		} else{
			$likeable = true;
		}

		if(!$pet) return Response::make('Not Found!!!', 404);

		return View::make('pets.singleProfile')->with('pet', $pet)->with('likeable', $likeable);
	}


	/*
	 * Returns all pets to angular js
	 */
	public function index() {
		$pets = Pet::get();

		//get likes for that user
		$user = Auth::user();

		$likes = Like::where('user_id', '=', Auth::user()->id)->get();

		$profile_images = Image::where('profile', '=', 1)->get();

		$all = array(
			'pets' => $pets,
			'likes' => $likes,
			'profile_images' => $profile_images,
			'friends' => $user->friends
		);

		//try to get pets of your friens

		return Response::json($all);
	}

	public function all() {
		return View::make('pets.pets');
	}

	public function get_all_pets() {
		$pets = Pet::get();

		//return Response::json($pets);
	}

	/**
	 * Returns addNew view with array of species
	 */
	public function getAddNewPet() {

		$species = Specie::all()->toArray();

		// dd($species);

		return View::make('pets.addNew')->with('species', $species);
	}

	public function postNewPhoto() {

		//$fname = $_POST["fname"];

		$lol = Input::get('all');

		$files = Input::file('file');

		echo '<pre>',print_r($files),'</pre>';

		$destination = URL::asset('uploads');

		//$files->move(storage_path(),$files->getClientOriginalName()); //ovaj radi
		//at the start images are located in temp folder, after the user submits new pet form this controller will move the images to user directory
		$files->move('images/temp', $files->getClientOriginalName());

		$test = array(
			'msg' => 'Uspelo',
			'data' => 'ma radi ovo do jaja',
			'ime fajla je' => $lol,
			'fajl je' => $files
		);

		//return Response::json($test);
	}


	/*
	 * Handles submition of new pet
	 *
	 * HTTP POST REQUEST
	 */
	public function submitNewPet() {

		$new_pet = Input::all();

		$user_id = Auth::user()->id;

		$pet_name = Input::get('name');
		$pet_species_id = Input::get('species_id');
		$pet_breed = Input::get('breed');
		$pet_gender = Input::get('gender');
		$pet_state = Input::get('state');
		$pet_city = Input::get('city');
		$pet_birth_date = Input::get('birthDate');
		$privacy = Input::get('privacy'); // 1 = public; 2 = private;

		//extract the pet_birth_date
		$pet_birth_date = substr($pet_birth_date, 3, 12);

		$photos_array = Input::get('photos');

		//echo '<pre>',print_r($photos_array),'</pre>';

		$data = array();

		if($pet_name != '' && $pet_species_id != '' && $pet_breed != '' && $pet_gender != '' && $pet_state != '' && $pet_city != '' && $pet_birth_date != '') {
			//save the pet and create data array with message

			$pet = Pet::create(array(
				'name' => $pet_name,
				'specie_id' => $pet_species_id,
				'breed' => $pet_breed,
				'birthDate' => $pet_birth_date,
				'image_link' => 'Link nece biti ovde',
				'state' => $pet_state,
				'city' => $pet_city,
				'vlasnik_id' => $user_id
			));

			//move images to user-directory with pet id

			foreach ($photos_array as $key => $photo_name) {
				//rename("/tmp/tmp_file.txt", "/home/user/login/docs/my_file.txt");

				$user_name = Auth::user()->username;
				$pet_id = $pet->id;
				$user_id = Auth::user()->id;
				$profile = 0;


				//test to get all the fiels
				//$test = File::files(public_path() . '/images/temp');
				//echo '<pre>',print_r($test),'</pre>';

				$temp_location = public_path() . '/images/temp/' . $photo_name;
				//$temp_location = $test[$key];

				if(!File::exists(public_path() . '/images/' . $user_name)) {
					//there is no direcotry for that user
					File::makeDirectory(public_path() . '/images/' . $user_name, $mode = 0777, true, true);
				}

				if(!File::exists(public_path() . '/images/' . $user_name . '/' . $pet_id)) {
					//there is no directory for pet
					File::makeDirectory(public_path() . '/images/' . $user_name . '/' . $pet_id, $mode = 0777, true, true);
				}

				$new_location = public_path() . '/images/' . $user_name . '/' . $pet_id . '/' . $photo_name;

				File::move($temp_location, $new_location);

				if($key == 0) {
					$profile = 1;

					$image_location = 'images/' . $user_name . '/' . $pet_id . '/' . $photo_name;

					Image::create(array(
						'image_name' => $photo_name,
						'location' => $image_location,
						'user_id' => $user_id,
						'pet_id' => $pet_id,
						'profile' => $profile,
						'public' => 1
					));

				} else {
					$image_location = 'images/' . $user_name . '/' . $pet_id . '/' . $photo_name;

					Image::create(array(
						'image_name' => $photo_name,
						'location' => $image_location,
						'user_id' => $user_id,
						'pet_id' => $pet_id,
						'profile' => $profile,
						'public' => $privacy
					));
				}

			}

			$destination = URL::route('view-pet-get', array('pet_id' => $pet->id));

			if($pet) {

				$destination = URL::route('view-pet-get', array('pet_id' => $pet->id));

				$data = array(
					'success' => 1,
					'message' => 'Pet created successful',
					'pet_id' => $pet->id,
					'location' => $destination
				);
			}

		} else {
			//create data array with message
			$data = array(
				'success' => 0,
				'message' => 'Server error'
			);
		}

		return Response::json($data);
	}

}
