@extends('layout_login')

@section('styles')
  <link href="{{url('/assets/dist/css/pages/login/classic/login-1.css')}}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
<!--begin::Main-->
<div class="d-flex flex-column flex-root">
  <!--begin::Login-->
  <div class="login login-1 login-signin-on d-flex flex-column flex-lg-row flex-column-fluid bg-white" id="kt_login">
    <!--begin::Aside-->
    
    <!--begin::Aside-->
    <!--begin::Content-->
    <div class="d-flex flex-column flex-row-fluid position-relative p-7 overflow-hidden">
      <!--begin::Content body-->
      <div class="d-flex flex-column-fluid flex-center mt-30 mt-lg-0">
        <!--begin::Signin-->
        <div class="login-form login-signin">
          <div class="text-center mb-10 mb-lg-20">
            <h3 class="font-size-h1">Update Password</h3>
            <p class="text-muted font-weight-bold">Enter your username and password</p>
          </div>

            @if(Session::has('failure'))
              <div class="alert alert-danger" style="margin-top: 10px;">
                <i class="fa fa-ban-circle"></i><strong>Failure!</strong>   {{Session::get('failure')}}
              </div>
            @endif

            @if(Session::has('success'))
            <div class="alert alert-success">
              <i class="fa fa-ban-circle"></i><strong>success!</strong>   {{Session::get('success')}}
            </div>
            @endif
          <!--begin::Form-->
          {{ Form::open(array('url' => '/update-password-first','class' => 'login-form form check-form',"novalidate"=>"novalidate","method"=>"POST")) }}
            <div class="form-group ">
              <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
              <label class="control-label">Old Password</label>
              <div style="position: relative;">
                <i class="fa fa-key" style="position: absolute;right: 10px; top: 12px;color:#aaa;"></i>
                {{Form::password('old_password',["class"=>"form-control form-control-solid placeholder-no-fix","autocomplete"=>"off","required"=>"true","placeholder" =>"Enter Old Password"])}}
                <span class="error">{{$errors->first('old_password')}}</span>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label">New Password</label>
              <div style="position: relative;">
                <i class="fa fa-key" style="position: absolute;right: 10px; top: 12px;color:#aaa;"></i>
                {{Form::password('new_password',["class"=>"form-control form-control-solid","required"=>"true","password_pat"=>"true","placeholder" =>"Enter New Password"])}}
                <span class="error">{{$errors->first('new_password')}}</span>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label">Confirm Password</label>
              <div style="position: relative;">
                <i class="fa fa-key" style="position: absolute;right: 10px; top: 12px;color:#aaa;"></i>
                {{Form::password('confirm_password',["class"=>"form-control form-control-solid","required"=>"true","placeholder" =>"Enter Confirm Password"])}}
                <span class="error">{{$errors->first('confirm_password')}}</span>
              </div>
            </div>
            <div class="d-flex flex-wrap justify-content-between align-items-center">
              <a href="{{url('/')}}" class="btn btn-dark">Go Back</a>
              <button type="submit" class="btn btn-success">
                Update
              </button>
            </div>
            <!--end::Action-->
          {{Form::close()}}
          <!--end::Form-->
        </div>
        <!--end::Signin-->
        <!--begin::Forgot-->
        <div class="login-form login-forgot">
          <div class="text-center mb-10 mb-lg-20">
            <h3 class="font-size-h1">Forgotten Password ?</h3>
            <p class="text-muted font-weight-bold">Enter your email to reset your password</p>
          </div>
          <!--begin::Form-->
          <form class="form" novalidate="novalidate" id="kt_login_forgot_form">
            <div class="form-group">
              <input class="form-control form-control-solid form-control form-control-solid-solid h-auto py-5 px-6" type="email" placeholder="Email" name="email" autocomplete="off" />
            </div>
            <div class="form-group d-flex flex-wrap flex-center">
              <button type="button" id="kt_login_forgot_submit" class="btn btn-primary font-weight-bold px-9 py-4 my-3 mx-4">Submit</button>
              <button type="button" id="kt_login_forgot_cancel" class="btn btn-light-primary font-weight-bold px-9 py-4 my-3 mx-4">Cancel</button>
            </div>
          </form>
          <!--end::Form-->
        </div>
        <!--end::Forgot-->
      </div>
      <!--end::Content body-->
      <!--begin::Content footer for mobile-->
      <div class="d-flex d-lg-none flex-column-auto flex-column flex-sm-row justify-content-between align-items-center mt-5 p-5">
        <div class="text-dark-50 font-weight-bold order-2 order-sm-1 my-2">&copy; 2020 Metronic</div>
        <div class="d-flex order-1 order-sm-2 my-2">
          <a href="#" class="text-dark-75 text-hover-primary">Privacy</a>
          <a href="#" class="text-dark-75 text-hover-primary ml-4">Legal</a>
          <a href="#" class="text-dark-75 text-hover-primary ml-4">Contact</a>
        </div>
      </div>
      <!--end::Content footer for mobile-->
    </div>
    <!--end::Content-->
  </div>
  <!--end::Login-->
</div>
<!--end::Main-->

@endsection

@section('scripts')
@endsection