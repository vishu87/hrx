@extends('layout')

@section('page_name')
    Settings
@endsection

@section('content')

	@if(Session::has('success'))
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <i class="fa fa-ban-circle"></i><strong>Success!</strong> {{Session::get('success')}}
        </div>
    @endif
    @if(Session::has('failure'))
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <i class="fa fa-ban-circle"></i><strong>Failure!</strong> {{Session::get('failure')}}
        </div>
    @endif

    <div class="row">
        <div class="col-md-6">
            <div class="card card-custom">
                <div class="card-header">
                    <div class="card-title">
                        <h3 class="card-label">Personal Information</h3>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item"><strong>Name :</strong> {{Auth::user()->name}}</li>
                        <li class="list-group-item"><strong>Email :</strong> {{Auth::user()->email}}</li>
                        @if(Auth::user()->privilege != 2)
                        <li class="list-group-item"><strong>Organization :</strong> {{Auth::user()->organization_name}}</li>
                        @endif
                    </ul>
                </div>
            </div>

            @if(Auth::user()->privilege == 31)
                <div class="card card-custom mt-8">
                    <div class="card-header">
                        <div class="card-title"><h3 class="card-label">Update Settings</h3></div>
                    </div>
                    <div class="card-body">
                        {{Form::open(["url"=>"/client-admin/settings","method"=>"post"])}}
                            <div class="form-group">
                                <label>Disable Checker</label>
                                {{Form::select('disable_checker',$yes_no,$disable_checker,["class"=>"form-control" , "required"=>true])}}
                                <span class="errors">{{$errors->first('disable_checker')}}</span>
                            </div>

                            <div>
                                <button class="btn btn-primary">Update</button>
                            </div>
                        {{Form::close()}}
                    </div>
                </div>
            @endif

        </div>

        <div class="col-md-6">
            <!-- change password -->
            <div class="card card-custom">
                <div class="card-header">
                    <div class="card-title">
                        <h3 class="card-label">Change Password </h3>
                    </div>
                </div>
                <div class="card-body">
                    {{Form::open(["url"=>"update-password","class"=>"check-form","method"=>"post"])}}
                        <div class="form-group">
                            <label>Old Password</label>
                            {{Form::password('old_password',["class"=>"form-control" , "required"=>true])}}
                            <span class="errors">{{$errors->first('old_password')}}</span>
                        </div>

                        <div class="form-group">
                            <label>New Password</label>
                            {{Form::password('new_password',["class"=>"form-control" , "required"=>true])}}
                            <span class="errors">{{$errors->first('new_password')}}</span>
                        </div>

                        <div class="form-group">
                            <label>Confirm Password</label>
                            {{Form::password('confirm_password',["class"=>"form-control" , "required"=>true])}}
                            <span class="errors">{{$errors->first('confirm_password')}}</span>
                        </div>
                        <div>
                            <button class="btn btn-primary">Update</button>
                        </div>
                    {{Form::close()}}
                </div>
            </div>
            <!-- change password end -->
        </div>
        
    </div>
    


@endsection