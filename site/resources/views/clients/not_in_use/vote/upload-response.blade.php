@extends('layout')

@section('content')
	<div ng-controller="voteCtrl">
        
        <div class="page-title-cont">
        	<div class="row">
        		<div class="col-md-6">
        			<h2 class="page-title">Upload Response Files</h2>
        		</div>
        	</div>
        </div>
    	

        <button type="button" class="button btn blue upload-btn" ngf-select="uploadBulkResponse($files)" ladda="uploading" multiple >Upload Response Files</button> @{{upload_progress}}

        <div class="row" ng-repeat="file in preview" style="margin-top: 15px;">
            <div class="col-md-2">
                @{{file.file_name}}
            </div>
            <div class="col-md-6">
                <div class="progress" ng-show="file.progress" >
                  <div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar"
                  aria-valuenow="0" aria-valuemin="@{{file.progress}}" aria-valuemax="100" ng-style="{'width' : file.progress } ">
                        @{{file.progress}}
                  </div>
                </div>
            </div>
        </div>
    </div>
	
@endsection