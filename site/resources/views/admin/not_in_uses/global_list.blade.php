@extends('layout')

@section('content')
	<div class="row">
		<div class="col-md-8">
			<h2 class="page-title">Global List</h2>
		</div>
		<div class="col-md-4 form-group">
			@if(isset($addForm))
				<a class="btn btn-primary pull-right"  href="{{url('ses/global-list')}}">Go Back</a>			
			@else
				<a class="btn btn-primary pull-right"  href="{{url('ses/global-list/upload')}}">Upload List</a>			
				<a class="btn btn-success pull-right"  href="{{url('ses/global-list/export')}}" style="margin-right: 5px">Export List</a>			
			@endif
		</div>
	</div>

	@if(Session::has('success'))
		<div class="alert alert-success">{{Session::get('success')}}</div>
	@endif

	@if(Session::has('failure'))
		<div class="alert alert-danger">{{Session::get('failure')}}</div>
	@endif

	@if(isset($addForm))
	{{Form::open(["url"=>"ses/global-list/add","files"=>true,"method"=>"post"])}}
	<div class="row">
		<div class="col-md-7">
			<div class="row">
				
				<div class="col-md-8 form-group">
					<label>Upload List</label>
					{{Form::file('global_list',["class"=>"form-control","required"])}}
				</div>
				<div class="col-md-4 form-group">
					<button class="btn btn-primary" style="margin-top: 23px">Upload</button>			
				</div>
				@if(Session::has('messages'))

					<div class="col-md-12">
						<table class="table table-bordered">
							<thead>
								<tr>
									<td>SN</td>
									<td>ISIN</td>
									<td>Message</td>
								</tr>
							</thead>
							<tbody>
								@foreach(Session::get("messages") as $count => $message)
								<tr style="color:red">
									<td>{{$count+1}}</td>
									<td>{{$message['isin']}}</td>
									<td>{{$message['message']}}</td>
								</tr>

								@endforeach
							</tbody>
						</table>
					</div>
				@endif
			</div>
		</div>
		<div class="col-md-5">
			<h3 class="modal-title">Last 5 Upload Records</h3>
			<?php $records = App\GlobalList::lastFiveRecords(); ?>
			<ul class="list-group" style="margin-top: 10px;">
				@foreach($records as $record)
				<li class="list-group-item">{{date('d-m-Y h:i:s',strtotime($record->updated_at))}} by {{$record->name}}</li>
				@endforeach
			</ul>
		</div>
		
	</div>
	{{Form::close()}}
	@endif




	@if(isset($lists))
		<table class="table table-bordered" id="datatable">
			<thead>
				<tr>
					<td>SN</td>
					<td>Company</td>
					<td>ISIN</td>
					<td>Add Date</td>
					<td>#</td>
				</tr>
			</thead>
			<tbody>
				@foreach($lists as $count => $list)
				<tr id="list_{{$list->id}}">
					<td>{{$count+1}}</td>
					<td>{{$list->com_name}}</td>
					<td>{{$list->isin}}</td>
					<td>{{date("d-m-Y",strtotime($list->add_date))}}</td>
					<td><button div-id="list_{{$list->id}}" class="btn btn-danger delete-div" action="{{'ses/global-list/delete/'.$list->id}}"><i class="fa fa-remove"></i></button></td>
				</tr>

				@endforeach
			</tbody>
		</table>
		
	@endif


@endsection