(function() {

var app = angular.module('myPet', ['ui.bootstrap', 'angularFileUpload', 'ngAnimate'], function($interpolateProvider) {
        $interpolateProvider.startSymbol('<%');
        $interpolateProvider.endSymbol('%>');
	});

/*
 * Angular service implemented for sharing picture names between controllers
 * This service is implemented because user can delete some pictures during process
 * of adding a new Pet, in that case we should delete picture on server side also
 */
app.factory('photoShareService', function($rootScope) {

    var photoService = {};

    photoService.message = 'test';
    photoService.photos = [];

    photoService.addMessage = function(msg) {
        this.message = msg;
        this.broadcast();
    };

    photoService.addPhoto = function(photoName) {
        this.photos.push(photoName);
        this.broadcast();

        console.log('SERVICE ADD : ');
        console.log(this.photos);
    };

    photoService.deletePhoto = function(photoName) {
        var index = this.photos.indexOf(photoName);

        if(index > -1) {
            this.photos.splice(index, 1);
        }

        this.broadcast();

        console.log('SERVICE DELETE : ');
        console.log(this.photos);
    };

    photoService.broadcast = function() {
        $rootScope.$broadcast('handleBroadcast');
    };

    return photoService;

});

app.controller("homeController", ['$scope', '$http', function($scope, $http) {

    $scope.test = "RADI HOME CONTROLLER";

    $scope.loading = true;

}]);

app.controller("myPetsController", ['$scope', '$http', function($scope, $http) {

    $scope.test = "RADI myPetsController";

    $scope.loading = true;

    $scope.pets = {};

    $http.get('api/myPets').success(function(data) {

        $scope.pets = data;
        $scope.loading = false;

        console.log($scope.pets);
    });

}]);

app.controller("likedPetsController", ['$scope', '$http', function($scope, $http) {

    $scope.test = "RADI likedPetsController";

    $scope.loading = true;

    $scope.pets = {};

    $http.get('api/likedPets').success(function(data){

        //console.log(data);
        $scope.pets = data;
        $scope.loading = false; //zavrseno je ucitavanje

        console.log($scope.pets);
    });

}]);

app.controller("single-pet-controller", ['$scope','$http', '$attrs', function($scope, $http, $attrs) {

    $scope.requested_pet_id = $attrs.explicitValue;

    $scope.init = function(id) {
        $scope.generateId(id);
    };

    $scope.isLiked = false;

    $scope.data = {};

    $http.get('../../api/singlePet/' + $scope.requested_pet_id + '').success(function(data1) {
            //console.log(data1);
            $scope.data = data1;
        });

    $scope.testButton = function() {
        $scope.data = $scope.getPetData;
    };


    //unlike pet
    $scope.unlikePet = function() {

        var petId = $scope.data.id;

        $http({
            method: 'POST',
            url: '../../api/removeLike',
            headers: { 'Content-Type' : 'application/x-www-form-urlencoded' },
            data: $.param($scope.data)
        }).success(function(data) {
            if(data.success == 1) {
                $scope.data.isLiked = false;
            }
        });
    };

    //like pet
    $scope.likePet = function() {
        //send a request to server to save this like we need user id (find it on the server) and pet_id that is here on page
        var petId = $scope.data.id;

        $http({
            method: 'POST',
            url: '../../api/addLike',
            headers: { 'Content-Type' : 'application/x-www-form-urlencoded' },
            data: $.param($scope.data)
        }).success(function(data) {
            console.log(data);
            console.log(data.success);

            if(data.success == '1') {
                $scope.data.isLiked = true;
            }
        });
    };

    $scope.setIsLike = function() {
        $scope.isLiked = true;
    };




    $scope.getPetData = function() {
        $http.get('public/api/singlePet/' + $scope.data.id).success(function(data) {
            return data;
        });
    };

}]);

app.controller("sampleAppController", ['photoShareService', '$scope', function(photoShareService, $scope) {
	$scope.test = 'Marko Srdic';

    $scope.testComunication = function() {
        var msg = 'test poruka da vidimo jel radi';
        photoShareService.addMessage(msg);
    };

    $scope.$on('handleBroadcast', function() {
        $scope.message = photoShareService.message;
    });

    $scope.images = {};

}]);

app.controller("sampleAppController2", ['photoShareService', '$scope', function(photoShareService, $scope) {

    $scope.message = "test poruka inicijalna";

    $scope.$on('handleBroadcast', function() {
        $scope.message = 'Drugi kontroller: ' + photoShareService.message;
    });
}]);

app.controller("addNewController", [ '$http', '$scope', 'photoShareService', '$timeout', function($http, $scope, photoShareService, $timeout) {
	this.test = "radi kontroler";

	$scope.petData = {};

    $scope.petData.photos = [];
    $scope.petData.name = '';
    $scope.petData.species_id = 1; //set default to Alpaca
    $scope.petData.breed = '';
    $scope.petData.gender = 1; //set default to Male
    $scope.petData.privacy = 1; //set default to Public
    $scope.petData.state = '';
    $scope.petData.city = '';

    $scope.errorIndicator = false;
    $scope.errorMessage = '';
    $scope.successIndicator = false;
    $scope.successMessage = '';



    $scope.$on('handleBroadcast', function() {
        $scope.petData.photos = photoShareService.photos;
    });

	//initialisation of birth date
	$scope.today = function() {
		$scope.petData.birthDate = new Date();
	}

	$scope.todayDate = new Date();

	$scope.today();

	$scope.submitNewPet = function() {

        if($scope.checkBeforeSubmit($scope.petData)) {
            //alert('SVe je ok');

            var newPet = $scope.petData;

            $http({
                method: 'POST',
                url: 'saveNewPet',
                headers: { 'Content-Type' : 'application/x-www-form-urlencoded' },
                data: $.param(newPet)
            }).success(function(data) {
                //http request succesfully sended to server
                if(data.success == 1) {
                    console.log('USPESNO SACUVAN U BAZU');
                    $scope.successIndicator = true;
                    $scope.successMessage = data.message;

                    $scope.destination = data.location;

                    var redirectToNewLocation = $timeout(function(destination) {
                        document.location.href = $scope.destination;
                    }, 1000);

                    //$scope.redirectToNewLocation;

                } else {
                    console.log('NIJE USPELO CUVANJE U BAZU');
                    $scope.errorIndicator = true;
                    $scope.errorMessage = data.message;
                }

            });
        } else {
            $scope.errorIndicator = true;
            $scope.errorMessage = 'Niste popunili sva polja, molimo popunite sva polja!';
        }
		console.log($scope.petData);
	};



    $scope.savePet = function() {

        var dateOfBirth = $scope.petData.birthDate;
        dateOfBirth = dateOfBirth.substring(0,6);
        console.log(dateOfBirth);

        $scope.petData.birthDate = dateOfBirth;

        var newPet = $scope.petData;

        $http({
            method: 'POST',
            url: 'saveNewPet',
            headers: { 'Content-Type' : 'application/x-www-form-urlencoded' },
            data: $.param(newPet)
        }).success(function(data) {
            console.log(data);
            var indicator = data;
        });

        return indicator;
    };

    $scope.checkBeforeSubmit = function(petData) {

        if(petData.birthDate !== "" && petData.name !== "" && petData.spec !== "" && petData.species_id !== "" && petData.breed !== "" && petData.gender !== "" && petData.state !== "" && petData.city !== "") {
            return true;
        } else {
            return false;
        }

    };

  	//disable weekend selection
  	$scope.disabled = function(date, mode) {
    	return ( mode === 'day' && ( date.getDay() === 0 || date.getDay() === 6 ) );
  	};

  	$scope.toggleMin = function() {
    	$scope.minDate = $scope.minDate ? null : new Date();
  	};

  	$scope.toggleMin();

  	$scope.open = function($event) {
	    $event.preventDefault();
	    $event.stopPropagation();

	    $scope.opened = true;
  	};

  	$scope.dateOptions = {
    	formatYear: 'yy',
    	startingDay: 1
  	};

  	$scope.formats = ['yyyy-MM-dd', 'dd-MMMM-yyyy', 'yyyy/MM/dd', 'dd.MM.yyyy', 'shortDate'];
  	$scope.format = $scope.formats[1];

  	//end of datepicker properties

}]);

app.controller('singleProfileController', [ '$http', '$scope', '$attrs', function($http, $scope, $attrs) {

    //get explicit value for user (user-id)

    $scope.profile_id = $attrs.explicitValue;
    console.log($scope.profile_id);

    //get info for that profile id (are they friends or is maybe friend requeste sended)

    $scope.getFriendshipStatus = function(userId) {

        var data = {};

        data.id = userId;

        $http({
            method: 'POST',
            url: '../api/checkFriendsStatus',
            headers: { 'Content-Type' : 'application/x-www-form-urlencoded' },
            data: $.param(data)
        }).success(function(data) {
            console.log(data);
            var status = data.status;

            $scope.requestStatus = data.status;

        });

        //return status;
    };

    $scope.getFriendshipStatus($scope.profile_id);

    $scope.sendFriendRequest = function(userId) {

        //change requestStatus to requestSended
        $scope.requestStatus = 'requestSended';

        var data = {};

        data.id = $scope.profile_id;

        //send a request to server
        $http({
            method: 'POST',
            url: '../api/sendFriendRequest',
            headers: { 'Content-Type' : 'application/x-www-form-urlencoded' },
            data: $.param(data)
        }).success(function(data) {
            console.log('Uspesno poslat request!');
        });

    };

    $scope.confirmFriendRequest = function(userId) {

        $scope.requestStatus = 'friends';

        var data = {};

        data.id = $scope.profile_id;

        $http({
            method: 'POST',
            url: '../api/confirmFriendRequest',
            headers: { 'Content-Type' : 'application/x-www-form-urlencoded' },
            data: $.param(data)
        }).success(function(data) {
            console.log('Uspesno dodat prijatelj!');
        });

    }

    $scope.removeRequest = function(userId) {

        //change requestStatus to none
        $scope.requestStatus = 'none';

        var data = {};

        data.id = $scope.profile_id;

        //send a request to server
        $http({
            method: 'POST',
            url: '../api/removeFriendRequest',
            headers: { 'Content-Type' : 'application/x-www-form-urlencoded' },
            data: $.param(data)
        }).success(function(data) {
            console.log('Uspesno obrisan request!');
        });

    };

    $scope.removeFromFriends = function(userId) {

        //change requestStatus to none
        $scope.requestStatus = 'none';

        //send a request to server
        var data = {};
        data.id = $scope.profile_id;

        $http({
            method: 'POST',
            url: '../api/removeFromFriends',
            headers: { 'Content-Type' : 'application/x-www-form-urlencoded' },
            data: $.param(data)
        }).success(function(data) {
            console.log('Korisnik obrisan sa liste prijatelja');
        });

    };

}]);

app.controller('viewAllController',[ '$http', function($http) {
	var petsData = this;

	petsData.pets = [];
	petsData.loading = true;
    petsData.likes = [];
    petsData.profileImages = [];
    petsData.friends = [];

	//test
	this.isLiked = true;
    var allLikes = this;

	//get all the pets
	$http.get('api/pets').success(function(data){
        console.log(data);
		//petsData.loading = false;
    	petsData.pets = data.pets;
        // petsData.friends = data.friends;
        // console.log(petsData.friends);

        for(var i=0; i<data.friends.length; i++) {
            petsData.friends.push(data.friends[i].id);
        }

        console.log(petsData.friends);

        for(var i=0; i<data.likes.length; i++){
            //add liked id to array
            petsData.likes.push(data.likes[i].likeable_id);
        }
        //add all profile images to array

        for(var k=0; k<petsData.pets.length; k++) {
            var id_pet = petsData.pets[k].id;
            //find image that has that pet id
            for(var i=0; i<data.profile_images.length; i++) {
                if(id_pet == data.profile_images[i].pet_id) {
                    petsData.pets[k].profile_image = data.profile_images[i];
                }
            }
        }



        likes = data.likes;

        petsData.loading = false;

    	console.log(petsData);
    });

    this.isFriend = function(vlasnik_id) {
        //check if input id is in the array

        var index = petsData.friends.indexOf(vlasnik_id);

        if(index > -1) {
            console.log('true');
            return true;
        } else {
            console.log('false');
            return false;
        }

    }

    this.like = function(petId) {
        //first check if id is already in array

        var index = petsData.likes.indexOf(petId);

        if(index > -1) {
            //vec je lajkovan, pokreni akciju za dislajkovanje i izbaci ga iz niza
            petsData.likes.splice(index, 1);
            //send request to server (delete like)

            var data = {};

            data.id = petId;

            $http({
                method: 'POST',
                url: 'api/removeLike',
                headers: { 'Content-Type' : 'application/x-www-form-urlencoded' },
                data: $.param(data)
            }).success(function(data) {
                console.log(data);
                console.log(data.success);
            });
            //alert('not completed');
        } else {
            //nije unutra, samo ga dodaj u niz lajkovanih
            petsData.likes.push(petId);
            //send http request to server to save that like

            var data = {};

            data.id = petId;

            $http({
                method: 'POST',
                url: 'api/addLike',
                headers: { 'Content-Type' : 'application/x-www-form-urlencoded' },
                data: $.param(data)
            }).success(function(data) {
                console.log(data);
                console.log(data.success);

            });
        //petsData.likes.push(petId);
    	//console.log('radi jbt');
    };

    };

    this.unlike = function(postId) {
    	alert('radi unlike, prosledjen je ' + postId);
    	//console.log('radi unlike');
    };


    //check if id is in array of posts that have been liked
    this.isLikedId = function(postId) {

        if(petsData.likes.indexOf(postId) > -1) {
            return true;
        } else {
            return false;
        }
    };

    this.addComment = function(postId, comment) {
    	alert('dodat je komentar za ' + postId + ' sa sadrzajem : ' + comment);
    };

}]);

app.controller('uploadController', ['$scope', 'FileUploader', 'photoShareService', function($scope, FileUploader, photoShareService) {
	var uploader = $scope.uploader = new FileUploader({
		url: 'test/upload'
	});

    $scope.registerPhoto = function(photoName) {
        console.log('DODATA JE SLIKA : ' + photoName);
        photoShareService.addMessage(photoName);
        photoShareService.addPhoto(photoName);
    };


    /*
     * Ideja je da se za sve slike koje se uploaduju, posalje ime slike na kontroler koji kontrolise cuvanje posta
     * Ukoliko se neka slika obrise, potrebno je da se ime te slike posalje na kontroler kako bi se slika obrisala iz niza slika koji ce se cuvati
     * nakon sto se zapocne cuvanje ljubimca, sve slike koje se nalaze u direktorijumu a ne nalaze se u nizu sa slikama neophodno je obrisati iz direktorijuma tog korisnika
     */
    $scope.unregisterPhoto = function(photoName) {
        console.log('OBRISANA JE SLIKA : ' + photoName);
        photoShareService.deletePhoto(photoName);
    };

    $scope.registerAll = function(queue) {
        console.log('svi dodati: ');
        console.log(queue);
        for(var i=0; i< queue.length; i++) {
            photoShareService.addPhoto(queue[i].file.name);
        }
        //sve ih treba poslati kao jedan niz da budu ubaceni u niz slika koje su uploadovane trenutno !!!
    };

	uploader.filters.push({
		name: 'imageFilter',
		fn: function(item /*{File|FileLikeObject}*/, options) {
			var type = '|' + item.type.slice(item.type.lastIndexOf('/') + 1) + '|';
			return '|jpg|png|jpeg|bmp|gif|'.indexOf(type) !== -1;
		}
	});

	// CALLBACKS

        uploader.onWhenAddingFileFailed = function(item /*{File|FileLikeObject}*/, filter, options) {
            console.info('onWhenAddingFileFailed', item, filter, options);
        };
        uploader.onAfterAddingFile = function(fileItem) {
            console.info('onAfterAddingFile', fileItem);
        };
        uploader.onAfterAddingAll = function(addedFileItems) {
            console.info('onAfterAddingAll', addedFileItems);
        };
        uploader.onBeforeUploadItem = function(item) {
            console.info('onBeforeUploadItem', item);
        };
        uploader.onProgressItem = function(fileItem, progress) {
            console.info('onProgressItem', fileItem, progress);
        };
        uploader.onProgressAll = function(progress) {
            console.info('onProgressAll', progress);
        };
        uploader.onSuccessItem = function(fileItem, response, status, headers) {
            console.info('onSuccessItem', fileItem, response, status, headers);
        };
        uploader.onErrorItem = function(fileItem, response, status, headers) {
            console.info('onErrorItem', fileItem, response, status, headers);
        };
        uploader.onCancelItem = function(fileItem, response, status, headers) {
            console.info('onCancelItem', fileItem, response, status, headers);
        };
        uploader.onCompleteItem = function(fileItem, response, status, headers) {
            console.info('onCompleteItem', fileItem, response, status, headers);
        };
        uploader.onCompleteAll = function() {
            console.info('onCompleteAll');
        };

        console.info('uploader', uploader);

}]);

// Directive made for view of uploaded images
app.directive('ngThumb', ['$window', function($window) {
	var helper = {
        support: !!($window.FileReader && $window.CanvasRenderingContext2D),
        isFile: function(item) {
            return angular.isObject(item) && item instanceof $window.File;
        },
        isImage: function(file) {
            var type =  '|' + file.type.slice(file.type.lastIndexOf('/') + 1) + '|';
            return '|jpg|png|jpeg|bmp|gif|'.indexOf(type) !== -1;
        }
    };

    return {
        restrict: 'A',
        template: '<canvas/>',
        link: function(scope, element, attributes) {
            if (!helper.support) return;

            var params = scope.$eval(attributes.ngThumb);

            if (!helper.isFile(params.file)) return;
            if (!helper.isImage(params.file)) return;

            var canvas = element.find('canvas');
            var reader = new FileReader();

            reader.onload = onLoadFile;
            reader.readAsDataURL(params.file);

            function onLoadFile(event) {
                var img = new Image();
                img.onload = onLoadImage;
                img.src = event.target.result;
            }

            function onLoadImage() {
                var width = params.width || this.width / this.height * params.height;
                var height = params.height || this.height / this.width * params.width;
                canvas.attr({ width: width, height: height });
                canvas[0].getContext('2d').drawImage(this, 0, 0, width, height);
            }
        }
    };
}]);


app.animation('.my-show-hide-animation', function() {
  return {
    beforeAddClass : function(element, className, done) {
      if(className == 'ng-hide') {
        jQuery(element).animate({
          opacity:0
        }, done);
      }
      else {
        done();
      }
    },
    removeClass : function(element, className, done) {
      if(className == 'ng-hide') {
        element.css('opacity',0);
        jQuery(element).animate({
          opacity:1
        }, done);
      }
      else {
        done();
      }
    }
  };
});

})();
