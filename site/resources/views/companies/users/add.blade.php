@extends('layout')

@section('content')

    
    <div class="card card-custom">

        <div class="card-header">
            <div class="card-title"><h3 class="card-label">{{($user)?'Update User Details':'Add New user'}}</h3></div>
            <div class="card-toolbar">
                <a href="{{url('/company/users')}}" class="btn btn-dark" >Go Back</a>
            </div>
        </div>

        <div class="card-body">

            @if(Session::has("success"))
                <div class="alert alert-success">
                    {{Session::get("success")}}
                </div>
            @endif
            @if(Session::has('failure'))
                <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <i class="fa fa-ban-circle"></i><strong>Failure!</strong> {{Session::get('failure')}}
                </div>
            @endif
            @if($user)
                {{Form::open(["url"=>"company/users/store/".$user->id, "method"=>"post","class"=>"check-form"])}}
            @else
                {{Form::open(["url"=>"company/users/store", "method"=>"post","class"=>"check-form"])}}
               
            @endif
            
            <div class="row">
                <div class="col-md-4 form-group">
                    <label>Name <span class="error">*</span> </label>
                    {{Form::text('name',($user)?$user->name:'',["class"=>"form-control" , "required"=>true])}}
                    <span class="errors">{{$errors->first('name')}}</span>
                </div>
            
                <div class="col-md-4 form-group">
                    <label>Email <span class="error">*</span></label>
                    {{Form::email('email',($user)?$user->email:'',["class"=>"form-control" , "required"=>true])}}
                    <span class="errors">{{$errors->first('email')}}</span>
                </div>
                <div class="col-md-3 form-group">
                    <label>Phone Number</label>
                    {{Form::text('phone_number',($user)?$user->phone_number:'',["class"=>"form-control"])}}
                    <span class="errors">{{$errors->first('phone_number')}}</span>
                </div>
            </div>
            <div>
                <button class="btn btn-primary">{{($user)?'Update':'Add'}}</button> 
            </div>
            {{Form::close()}}
        </div>
    </div>
    

@endsection