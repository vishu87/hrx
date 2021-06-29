@extends('layout')

@section('content')
	
	<div class="page-title-cont">
		<h2 class="page-title">Export status</h2>
	</div>
	
	{{Form::open(["url"=>"admin/exports/status","method"=>"post","target"=>"_blank"])}}
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

	<hr>

	<div class="page-title-cont">
		<h2 class="page-title">Vote download status</h2>
	</div>
	
	{{Form::open(["url"=>"admin/exports/status","method"=>"get"])}}
		<div class="row">
			<div class="col-md-3 form-group">
				<label>eVoting Deadline <span class="error">*</span></label>
				{{Form::text('vote_date',date("d-m-Y",strtotime($evoting_date)),["class"=>"form-control datepicker","required"=>"true", "autocomplete"=>"off"])}}
				<span class="errors">{{$errors->first('vote_date')}}</span>
			</div>
			<div class="col-md-3 form-group">
				<label>Type</label>
				{{Form::select('vote_type',["1"=>"Pending","2"=>"All"],$vote_type,["class"=>"form-control","required"=>"true", "autocomplete"=>"off"])}}
				<span class="errors">{{$errors->first('vote_type')}}</span>
			</div>
			<div class="col-md-2">
				<button class="btn blue" style="margin-top: 23px;">Submit</button>
			</div>
		</div>
	{{Form::close()}}

	<div>

		@if(sizeof($reports) == 0)
		<div class="alert alert-warning">
			No meetings found for this date
		</div>
		@endif

		@foreach($reports as $report)
			<?php
				if($vote_type == 1 && sizeof($report->status) == 0) continue;
			?>
			<h4>{{$report->com_name}} <small>{{$report->meeting_type}} on {{date("d-m-Y",strtotime($report->meeting_date))}}</small></h4>

			@if(sizeof($report->status) > 0)
			<table class="table table-hover table-bordered">
				<thead>
					<th>SN</th>
					<th>Client Name</th>
					<th>Scheme Name</th>
					<th>Vote File Downloaded</th>
					<th>Date/Time</th>
				</thead>
				@foreach($report->status as $index => $scheme)
				<tbody>
					<td>{{$index + 1}}</td>
					<td>{{$scheme->user_name}}</td>
					<td>{{$scheme->scheme_name}}</td>
					<td>
						@if($scheme->vote_file_download)
							<i class="fa fa-check"></i>
						@else
							Pending
						@endif
					</td>
					<td>
						@if($scheme->vote_file_download)
							{{date("d-m-Y H:i",strtotime($scheme->vote_file_download))}}
						@endif
					</td>
				</tbody>
				@endforeach
			</table>
			@endif

			@if(sizeof($report->status) == 0)
				<div class="alert alert-warning">
					No approved records found for this meeting
				</div>
			@endif

			<hr>
		@endforeach
	</div>

@endsection