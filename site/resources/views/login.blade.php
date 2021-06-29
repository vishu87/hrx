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
		<div class="login-aside d-flex flex-row-auto bgi-size-cover bgi-no-repeat p-10 p-lg-10" style="background-image: url(assets/dist/media/bg/bg-4.jpg);">
			<!--begin: Aside Container-->
			<div class="d-flex flex-row-fluid flex-column justify-content-between">
				<!--begin: Aside header-->

				<a href="#" class="flex-column-auto mt-5" style="background: #FFF; text-align:center; padding: 10px; border-radius: 60px;">
					<img src="{{url('assets/img/logo.png')}}" class="max-h-70px" alt=""/>

				</a>

				<!--end: Aside header-->
				<!--begin: Aside content-->
				
				<div class="flex-column-fluid d-flex flex-column justify-content-center">
					<h3 class="font-size-h1 mb-5 text-white text-center" style="font-size: 36px">Welcome to HRX !</h3>
				</div>

				<!--end: Aside content-->
				<!--begin: Aside footer for desktop-->
				<div class="d-none flex-column-auto d-lg-flex justify-content-between mt-10">
					<div class="opacity-70 font-weight-bold text-white">© 2021 HRX</div>
					<div class="d-flex">
						<a href="#" class="text-white">Privacy</a>
						<a href="#" class="text-white ml-10">Contact</a>
					</div>
				</div>
				<!--end: Aside footer for desktop-->
			</div>
			<!--end: Aside Container-->
		</div>
		<!--begin::Aside-->
		<!--begin::Content-->
		<div class="d-flex flex-column flex-row-fluid position-relative p-7 overflow-hidden">
			<!--begin::Content body-->
			<div class="d-flex flex-column-fluid flex-center mt-30 mt-lg-0">
				<!--begin::Signin-->
				<div class="login-form login-signin">
					<div class="text-center mb-10 mb-lg-20">
						<h3 class="font-size-h1">Sign In</h3>
						<p class="text-muted font-weight-bold">Enter your username and password</p>
					</div>

					@if(Session::has('failure'))
			      		<div class="alert alert-danger" style="margin-top: 10px;">
			        		<i class="fa fa-ban-circle"></i><strong>Failure!</strong> 	{{Session::get('failure')}}
			      		</div>
			      	@endif

			      	@if(Session::has('success'))
						<div class="alert alert-success">
							<i class="fa fa-ban-circle"></i><strong>success!</strong> 	{{Session::get('success')}}
						</div>
			      	@endif
					<!--begin::Form-->
					{{ Form::open(array('url' => '/login','class' => 'login-form form check-form',"novalidate"=>"novalidate","method"=>"POST")) }}
						<div class="form-group">
							{{Form::text("email","",array( "class" => "form-control form-control-solid h-auto py-5 px-6", "autocomplete" => "off", "required" => true, "placeholder" => "Email" ))}}
						</div>
						<div class="form-group">
							{{Form::password("password",array( "class" => "form-control form-control-solid h-auto py-5 px-6", "autocomplete" => "off", "required" => true, "placeholder" => "Password" ))}}
						</div>
						<!--begin::Action-->
						<div class="form-group d-flex flex-wrap justify-content-between align-items-center">
							<a href="javascript:;" class="text-dark-50 text-hover-primary my-3 mr-2" id="kt_login_forgot">Forgot Password ?</a>
							<button type="submit" id="kt_login_signin_submit" class="btn btn-primary font-weight-bold px-9 py-4 my-3">Sign In</button>
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
							<input class="form-control form-control-solid h-auto py-5 px-6" type="email" placeholder="Email" name="email" autocomplete="off" />
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
				<div class="text-dark-50 font-weight-bold order-2 order-sm-1 my-2">© 2020 SES Governance</div>
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