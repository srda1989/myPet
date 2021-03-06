@extends('layout.main')

@section('content')

<script>
	$(function() {
		$('.datepicker').datepicker();
	});
</script>


<script>
	$(document).on('change', '.btn-file :file', function() {
	  var input = $(this),
	      numFiles = input.get(0).files ? input.get(0).files.length : 1,
	      label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
	  input.trigger('fileselect', [numFiles, label]);
	});

	$(document).ready( function() {
	    $('.btn-file :file').on('fileselect', function(event, numFiles, label) {

	        var input = $(this).parents('.input-group').find(':text'),
	            log = numFiles > 1 ? numFiles + ' files selected' : label;

	        if( input.length ) {
	            input.val(log);
	        } else {
	            if( log ) alert(log);
	        }

	    });
	});
</script>

	<h2 style="text-align: center;">Add new pet!</h2>

	<div class="new-pet-container" ng-app="myPet" ng-controller="addNewController as add">

		<hr>

		<form ng-submit="submitNewPet()">

		<div class="row">

			<div class="col-xs-3">
				<input type="text" ng-model="petData.name" class="form-control" placeholder="Name">
			</div>

			<div class="col-xs-3">
				<select ng-model="petData.species_id" class="form-control" name="species" id="">
					@foreach($species as $specie)
						<option value="{{ $specie['id'] }}">{{ $specie['name'] }}</option>
					@endforeach
				</select>
			</div>

			<div class="col-xs-3">
				<input type="text" ng-model="petData.breed" class="form-control" placeholder="Breed">
			</div>

			<div class="col-xs-3">
				<select ng-model="petData.gender" class="form-control" name="gender" id="">
					<option value="1">Male</option>
					<option value="2">Female</option>
				</select>
			</div>

		</div>

		<hr>



		<div class="row">

			<div class="col-xs-5">
				<p class="input-group">
	              <input type="text" class="form-control" datepicker-popup="<%format%>" ng-model="petData.birthDate" is-open="opened" min-date="'1980-01-01'" max-date="todayDate" datepicker-options="dateOptions" date-disabled="disabled(date, mode)" ng-required="true" close-text="Close" />
	              <span class="input-group-btn">
	                <button type="button" class="btn btn-default" ng-click="open($event)"><i class="fa fa-calendar"></i></button>
	              </span>
	            </p>
	        </div>
			<div class="col-xs-4">
				<input ng-model="petData.state" type="text" class="form-control" name="state" placeholder="State">
			</div>

			<div class="col-xs-3">
				<input ng-model="petData.city" type="text" class="form-control" name="city" placeholder="City">
			</div>

		</div>

		<hr>





		<h4>Pet images</h4>

		<div ng-controller="uploadController" nv-file-drop="" uploader="uploader">
					<div class="row">

		                    <div class="col-xs-2">
		                    	<span class="btn btn-primary btn-file">
			                    	Browse&hellip; <input type="file" nv-file-select="" uploader="uploader" multiple  /><br/>
			                    </span>
		                    </div>
		                    <div class="col-xs-10">
		                    	<div ng-show="uploader.isHTML5">
			                        <!-- 3. nv-file-over uploader="link" over-class="className" -->
			                        <div class="well my-drop-zone" nv-file-over="" uploader="uploader">
			                            Drop images here
			                        </div>
			                    </div>
		                    </div>

		                <div class="row" style="margin-bottom: 40px">

							<!-- pocetak test diva -->
							<div class="col-xs-12">
		                    <p>Number of images: <% uploader.queue.length %></p>

		                    <table class="table" style="max-width: 620px;">
		                        <thead>
		                            <tr>
		                                <th width="50%">Name</th>
		                                <th ng-show="uploader.isHTML5">Size</th>
		                                <th ng-show="uploader.isHTML5">Progress</th>
		                                <th>Status</th>
		                                <th>Actions</th>
		                            </tr>
		                        </thead>
		                        <tbody>
		                            <tr ng-repeat="item in uploader.queue">
		                                <td>

		                                    <strong><% item.file.name | limitTo:17 %>...</strong>
		                                    <div ng-show="uploader.isHTML5" ng-thumb="{ file: item._file, height: 100 }"></div>
		                                </td>
		                                <td ng-show="uploader.isHTML5" nowrap><% item.file.size/1024/1024|number:2 %> MB</td>
		                                <td ng-show="uploader.isHTML5">
		                                    <div class="progress" style="margin-bottom: 0;">
		                                        <div class="progress-bar" role="progressbar" ng-style="{ 'width': item.progress + '%' }"></div>
		                                    </div>
		                                </td>
		                                <td class="text-center">
		                                    <span ng-show="item.isSuccess"><i class="glyphicon glyphicon-ok"></i></span>
		                                    <span ng-show="item.isCancel"><i class="glyphicon glyphicon-ban-circle"></i></span>
		                                    <span ng-show="item.isError"><i class="glyphicon glyphicon-remove"></i></span>
		                                </td>
		                                <td nowrap>
		                                    <button type="button" class="btn btn-success btn-xs" ng-click="item.upload(); registerPhoto(item.file.name)" ng-disabled="item.isReady || item.isUploading || item.isSuccess">
		                                        <span class="glyphicon glyphicon-upload"></span> Upload
		                                    </button>
		                                    <button type="button" class="btn btn-danger btn-xs" ng-click="item.remove(); unregisterPhoto(item.file.name)">
		                                        <span class="glyphicon glyphicon-trash"></span> Remove
		                                    </button>
		                                </td>
		                            </tr>
		                        </tbody>
		                    </table>

		                    <div>
		                        <div>
		                            Queue progress:
		                            <div class="progress" style="">
		                                <div class="progress-bar" role="progressbar" ng-style="{ 'width': uploader.progress + '%' }"></div>
		                            </div>
		                        </div>
		                        <button type="button" class="btn btn-success btn-s" ng-click="uploader.uploadAll(); registerAll(uploader.queue)" ng-disabled="!uploader.getNotUploadedItems().length">
		                            <span class="glyphicon glyphicon-upload"></span> Upload all
		                        </button>
		                        <button type="button" class="btn btn-warning btn-s" ng-click="uploader.cancelAll()" ng-disabled="!uploader.isUploading">
		                            <span class="glyphicon glyphicon-ban-circle"></span> Cancel all
		                        </button>
		                        <button type="button" class="btn btn-danger btn-s" ng-click="uploader.clearQueue()" ng-disabled="!uploader.queue.length">
		                            <span class="glyphicon glyphicon-trash"></span> Remove all
		                        </button>
		                    </div>

		                    </div>

		                </div>

		            </div>
		</div>

		<hr>

		<div class="alert alert-warning alert-dismissible" role="alert" ng-show="errorIndicator">
		  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
		  <strong>Došlo je do greške!</strong> <% errorMessage %>
		</div>

		<div class="alert alert-success alert-dismissible" role="alert" ng-show="successIndicator">
		  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
		  <strong><% successMessage %></strong>
		</div>

		<div class="row">

			<div class="col-xs-4">
				<select ng-model="petData.privacy" class="form-control" name="privacy" id="">

						<option value="1">Public</option>
						<option value="0">Private(Visible only to friends)</option>

				</select>
			</div>

			<div class="form-group text-center col-xs-4">
				<button type="submit" class="btn btn-primary">Save pet</button>
			</div>
		</div>

		</form>

		 {{-- <pre><% petData %></pre> --}}

	</div>
@stop
