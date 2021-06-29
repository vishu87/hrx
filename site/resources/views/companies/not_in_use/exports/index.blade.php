@extends('layout')

@section('content')

	<div class="row">
		<div class="col-md-12">
			
			<div class="page-title-cont">
				<h2 class="page-title">Export</h2>
			</div>
			
			{{Form::open(["url"=>"admin/exports/exportEvenDetails","method"=>"post"])}}
				<h4>Export Meeting Details &amp; Synopsis</h4>
				<div class="row">
					<div class="col-md-3 form-group">
						<label>Start Date <span class="error">*</span></label>
						{{Form::text('start_date','',["class"=>"form-control datepicker","required"=>"true", "autocomplete"=>"off"])}}
						<span class="errors">{{$errors->first('start_date')}}</span>
					</div>
					<div class="col-md-3 form-group">
						<label>End Date <span class="error">*</span></label>
						{{Form::text('end_date','',["class"=>"form-control datepicker","required"=>"true", "autocomplete"=>"off"])}}
						<span class="errors">{{$errors->first('end_date')}}</span>
					</div>
					@if(Auth::user()->privilege == 2)
					<div class="col-md-4 form-group">
						<label>Report Format <span class="error">*</span></label><br>
						<label>{{Form::radio('report_format',1,["checked"=>"checked"])}} Synopsis</label>&nbsp;&nbsp;
						<label>{{Form::radio('report_format',2)}} Meeting Details</label><br>
						<span class="errors">{{$errors->first('report_format')}}</span>
					</div>
					@endif
					<div class="col-md-2">
						<button class="btn blue" style="margin-top: 23px;">Submit</button>
					</div>
				</div>
			{{Form::close()}}
			<hr>

			@if(Auth::user()->privilege == 4)
			{{Form::open(["url"=>"admin/exports/weekly-report","method"=>"post"])}}
				<h4>Weekly Reports Export</h4>
				<div class="row">

					<div class="col-md-3 form-group">
						<label>Start Date <span class="error">*</span></label>
						{{Form::text('start_date','',["class"=>"form-control datepicker","required"=>"true", "autocomplete"=>"off"])}}
						<span class="errors">{{$errors->first('start_date')}}</span>
					</div>
					<div class="col-md-3 form-group">
						<label>End Date <span class="error">*</span></label>
						{{Form::text('end_date','',["class"=>"form-control datepicker","required"=>"true", "autocomplete"=>"off"])}}
						<span class="errors">{{$errors->first('end_date')}}</span>
					</div>
					
					<div class="col-md-2">
						<button class="btn blue" style="margin-top: 23px;">Submit</button>
					</div>
				</div>
			{{Form::close()}}
			@endif
		</div>
		@if(Auth::user()->privilege == 4)
		<div class="col-md-12" style="margin-top:30px">
			<div class="page-title-cont">
				<h2 class="page-title">Upload Vote File</h2>
			</div>

				@if(Session::has("success"))
			        <div class="alert alert-success">
			        	<button type="button" class="close" data-dismiss="alert">×</button>
			            {!! Session::get("success") !!}
			        </div>
			    @endif
			    @if(Session::has('failure'))
			        <div class="alert alert-danger">
			            <button type="button" class="close" data-dismiss="alert">×</button>
			            {!! Session::get('failure') !!}
			        </div>
			    @endif

			{{Form::open(["url"=>"client/exports/uploadVotes","method"=>"post","files"=>true])}}
				<div class="row">
					<div class="col-md-6 form-group">
						<label>Upload Vote File (Synopsis) <span class="error">*</span></label>
						{{Form::file('voting_file',["class"=>"form-control","required"=>"true"])}}
						<p style="color:#F00; font-size: 12px; margin-top: 5px">
							*The votes shall be applied across all schemes in that particular ISIN/event
						</p>
						<p style="font-style: italic; color: #888; font-size: 12px">The upload format should be as per output format of synopsis and must be at sheet no. 2</p>
						<span class="errors">{{$errors->first('voting_file')}}</span>
					</div>
					<div class="col-md-2">
						<button class="btn blue" style="margin-top: 23px;">Submit</button>
					</div>
				</div>
			{{Form::close()}}
		</div>
		@endif
	</div>
@endsection