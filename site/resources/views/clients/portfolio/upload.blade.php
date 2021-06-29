@extends('layout')

@section('content')
	
    <div ng-controller="uploadPortfolioCtrl" ng-init="initialize({{Auth::user()->parent_user_id}});" class="">
        
        <div class="card card-custom">            

            <div class="card-header">
                <div class="card-title"><h3 class="card-label">Upload Portfolio</h3></div>
            </div>

            <div class="card-body">

                @if( Session::has('success') )
                    <div class="alert alert-success">
                        {{Session::get("success")}}
                    </div>
                @endif

                @if( Session::has('failure') )
                    <div class="alert alert-danger">
                        {{Session::get("failure")}}
                    </div>
                @endif

                <div class="row fade-in" >
                    <div class="col-md-10">
                        {{ Form::open(array("url" => 'mf/portfolio/upload', "method"=>"POST", "files" => true )) }}
                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <input type="file" class="form-control" name="portfolio_file">
                                    <a href="{{ url('assets/formats/Portfolio_Update.xlsx') }}" target="_blank">Download Format</a>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary">Upload</button>
                                </div>
                            </div>                    
                        </form>
                    </div>

                </div>

            </div>
        </div>

        <div class="card card-custom mt-5">

            <div class="card-header">
                <div class="card-title">
                    <h3 class="card-label">
                        Logs
                    </h3>
                </div>
            </div>

            <div class="card-body ng-cloak">

                <div class="d-flex justify-content-center" ng-if="loading">
                  <div class="spinner-border" role="status">
                    <span class="sr-only">Loading...</span>
                  </div>
                </div>

                <table class="table hover" ng-if="logs.length > 0">
                    <thead>
                        <tr>
                            <td>SN</td>
                            <td>User Name</td>
                            <td>Added Name</td>
                            <td>Response File</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="log in logs track by $index">
                            <td>@{{$index + 1}}</td>
                            <td>@{{log.user_name}}</td>
                            <td>@{{log.added_name}}</td>
                            <td><a href="{{url('/')}}/@{{log.response_file}}" target="_blank" >View</td>
                        </tr>
                    </tbody>
                </table>

                <div class="alert alert-info" ng-if="logs.length == 0 && !loading">
                    No data found
                </div>
            </div>

        </div>

    </div>
	

@endsection