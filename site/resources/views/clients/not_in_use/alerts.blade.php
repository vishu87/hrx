@extends('layout')

@section('content')
	<div >
		<div class="row">
	        <div class="col-md-6">
	            <h1 class="page-title" style="margin-top: 0">
	                Meeting Alerts
	            </h1>
	        </div>
	    </div>
	    @if(Session::get('success'))
	    <div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
        {{Session::get('success')}} </div>
        @endif

        @if(Session::get('failue'))
	    <div class="alert alert-danger alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
        {{Session::get('failue')}} </div>
        @endif

        <div class="row">
        	<div class="col-md-6">
        		{{Form::open(["url"=>"client/meeting-alerts/add","method"=>"post"])}}
			    <div class="row">
			    	<div class="col-md-4 form-group">
			    		<label>No of working day(s)</label>
			    		{{Form::text('num_days','',["class"=>"form-control"])}}
			    		<span class="error">{{$errors->first('num_days')}}</span>
			    	</div>
			    	<div class="col-md-3">
			    		<button class="btn btn-primary" style="margin-top: 23px">Add</button>
			    	</div>
			    </div>
			    {{Form::close()}}

			    <div class="row" style="margin-top: 10px;">
			    	@foreach($alerts as $alert)
			    	<div class="col-md-4" id="alert_{{$alert->id}}" style="margin-bottom: 10px">
			    		<a href="javascript:;" class="btn pull-left btn-default">{{$alert->num_days}} day{{($alert->num_days > 1)?'s':'&nbsp;&nbsp;'}}</a>
			    		<a class="btn red pull-left " href="{{url('client/meeting-alerts/delete/'.$alert->id)}}" ><i class="fa fa-remove"></i></a>
			    	</div>
			    	@endforeach
			    </div>
        	</div>

        	<div class="col-md-6">
        		{{Form::open(["url"=>"client/meeting-alerts/add-email","method"=>"post"])}}
			    <div class="row">
			    	<div class="col-md-6 form-group">
			    		<label>Email for alert</label>
			    		{{Form::text('email','',["class"=>"form-control"])}}
			    		<span class="error">{{$errors->first('email')}}</span>
			    	</div>
			    	<div class="col-md-3">
			    		<button class="btn btn-primary" style="margin-top: 23px">Add</button>
			    	</div>
			    </div>
			    {{Form::close()}}

			    @if(sizeof($emails) > 0)
			    <div style="margin-top: 10px;">
			    	<table class="table table-bordered">
			    		<thead>
			    			<tr>
			    				<th>SN</th>
			    				<th>Email</th>
			    				<th></th>
			    			</tr>
			    		</thead>
			    		<tbody>
			    			@foreach($emails as $count => $email)
			    			<tr id="email_{{$email->id}}">
			    				<td>{{$count + 1}}</td>
			    				<td>{{$email->email}}</td>
			    				<td>
			    					<a class="btn btn-sm red pull-left " href="{{url('client/meeting-alerts/delete-email/'.$email->id)}}" ><i class="fa fa-remove"></i></a>
			    				</td>
			    			</tr>
			    			@endforeach
			    		</tbody>
			    	</table>
			    	
			    </div>
			    @endif
        	</div>

        </div>

	    
	    <hr>
	    @if(sizeof($alerts) > 0)
	    	
	    	@foreach($meetings as $num_days => $meeting_ar)
	    	<div style="margin-top: 30px;">
	    		<b>Meeting alerts for {{$num_days}} working day{{($num_days > 1)?'s':''}}</b>
	    	</div>
	    	<table class="table table-bordered">
	    		<tr>
	    			<th>SN</th>
	    			<th>ISIN</th>
	    			<th>Company Name</th>
	    			<th>Meeting Type</th>
	    			<th>Meeting Date</th>
	    			<th>Evoting End</th>
	    			<th>DB Deadline</th>
	    		</tr>
	    		<?php $count = 1; ?>
	    		@foreach($meeting_ar as $meeting)
	    		<tr>
	    			<td>{{$count++}}</td>
	    			<td style="word-break: break-all;">{{$meeting->com_isin}}</td>
	    			<td>{{$meeting->com_name}}</td>
	    			<td>{{$meeting->meeting_type_name}}</td>
	    			<td>{{$meeting->meeting_date}}</td>
	    			<td>@if($meeting->evoting_plateform != "Physical") {{$meeting->evoting_end}} @else Physical Votes @endif</td>
	    			<td>{{$meeting->deadline_date}}</td>
	    		</tr>
	    		@endforeach
	    		@if(sizeof($meeting_ar) == 0)
	    		<tr>
	    			<td colspan="7">No meetings found</td>
	    		</tr>
	    		@endif
	    	</table>
	    	@endforeach
	    @endif
	</div>

@endsection