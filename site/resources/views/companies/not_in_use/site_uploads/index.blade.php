@extends('layout')

@section('content')
	
	<div class="page-title-cont">
		<h2 class="page-title">Uploads</h2>
	</div>

	@if(Session::has("failure"))
		<div class="alert alert-danger">
			{{Session::get("failure")}}
		</div>
	@endif

	@if(Session::has("success"))
		<div class="alert alert-success">
			{!! Session::get("success") !!}
		</div>
	@endif

	<div class="row">
		<div class="col-md-3">
			<div class="form-group">
				<label>Select Upload Type</label>
				{{Form::select("type",$types,$type,["class"=>"form-control upload-select"])}}
			</div>
		</div>
	</div>
	<hr>

	<div class="upload-div" id="div_1" @if($type != 1) style="display:none" @endif>
		{{Form::open(array("url" => "admin/uploads/cdsl","method"=>"post","files"=>"true"))}}
		<h4>CDSL Holding Upload</h4>
		<div class="row">
			<div class="col-md-3">
				<div class="form-group">
					<label>Select File</label>
					{{Form::file("attach_files[]",["required"=>"true","multiple"=>"true"])}}
				</div>
			</div>
		</div>
		<button type="submit" class="btn blue">Submit</button>
		{{Form::close()}}
	</div>

	<div class="upload-div" id="div_2" @if($type != 2) style="display:none" @endif>
		{{Form::open(array("url" => "admin/uploads/nsdl","method"=>"post","files"=>"true"))}}
		<h4>NSDL Holding Upload</h4>
		<div class="row">
			<div class="col-md-3">
				<div class="form-group">
					<label>Select File</label>
					{{Form::file("attach_files[]",["required"=>"true","multiple"=>"true"])}}
				</div>
			</div>
		</div>
		<button type="submit" class="btn blue">Submit</button>
		{{Form::close()}}
	</div>

	<div class="upload-div" id="div_3" @if($type != 3) style="display:none" @endif>
		{{Form::open(array("url" => "admin/uploads/schemes","method"=>"post","files"=>"true"))}}
		<h4>Scheme Upload</h4>
		<div class="row">
			<div class="col-md-3">
				<div class="form-group">
					<label>Select File</label>
					{{Form::file("attach_file",["required"=>"true"])}}
				</div>
			</div>
		</div>
		<button type="submit" class="btn blue">Submit</button>
		{{Form::close()}}
	</div>

	<div class="upload-div" id="div_4" @if($type != 4) style="display:none" @endif>
		{{Form::open(array("url" => "admin/uploads/meetings","method"=>"post","files"=>"true"))}}
		<h4>Meeting Details Upload</h4>
		<div class="row">
			<div class="col-md-3">
				<div class="form-group">
					<label>Select File</label>
					{{Form::file("attach_file",["required"=>"true"])}}
				</div>
			</div>
		</div>
		<button type="submit" class="btn blue">Submit</button>
		{{Form::close()}}
	</div>

	<div class="upload-div" id="div_5" @if($type != 5) style="display:none" @endif>
		{{Form::open(array("url" => "admin/uploads/physical","method"=>"post","files"=>"true"))}}
		<h4>Physical Holding File</h4>
		<div class="row">
			<div class="col-md-3">
				<div class="form-group">
					<label>Select File</label>
					{{Form::file("attach_file",["required"=>"true"])}}
					<br>
					<small><a href="{{url('view-file?file=formats/physical_format.xlsx')}}">Download Format</a></small>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label>Date of Holding</label>
					{{Form::text("date","",["class"=>"form-control datepicker","required"=>"true"])}}
				</div>
			</div>
		</div>
		<button type="submit" class="btn blue">Submit</button>
		{{Form::close()}}
	</div>


@endsection