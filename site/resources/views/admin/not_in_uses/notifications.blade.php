@extends('layout')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/at.js/1.4.0/css/jquery.atwho.min.css">
@section('content')
	
    <div class="page-title-cont">
        
    	<div class="row">
    		<div class="col-md-6">
    			<h2 class="page-title">Notifications <small>Manage Notifications</small></h2>
    		</div>
            <div class="col-md-6">
                @if(isset($notification))
                    <a href="{{url('/ses/notifications')}}" class="btn btn-success pull-right">Go Back</a>    
                @endif
            </div>
    		
    	</div>
    </div>
    @if(Session::has('success'))
        <div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
            {{Session::get('success')}}
        </div>
    @endif

    <div>
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-list"></i>@if(isset($notification)) Update @else Add @endif  Notification</div>
                <div class="tools">
                    <a href="javascript:;" class="collapse" data-original-title="" title=""> </a>
                </div>
            </div>
            <div class="portlet-body">
                <!-- BEGIN FORM-->
                @if(isset($notification))
                    {{Form::open(["url"=>"ses/notifications/add?id=".$notification->id,"method"=>"post","files"=>true])}}
                @else
                    {{Form::open(["url"=>"ses/notifications/add","method"=>"post","files"=>true])}}
                @endif
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Notification</label>
                                    {{Form::textarea('notification',(isset($notification))?$notification->notification:'',["class"=>"form-control ckeditor","id"=>"editor","rows"=>4])}}
                                    <span class="error">{{$errors->first('notification')}}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>File</label>
                                            {{Form::file('file',["class"=>"form-control"])}}
                                            @if(isset($notification) && $notification->file)
                                                <a href="{{url($notification->file)}}" target="_blank">view file</a>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>SES Event ID</label>
                                            {{Form::number('report_id',(isset($notification))?$notification->report_id:'',["id"=>"report_id","class"=>"form-control","autocomplete"=>"off"])}}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Reason</label>
                                              {{Form::select('reason',$reasons,(isset($notification))?$notification->reason:'',["class"=>"form-control check-records","autocomplete"=>"off"])}}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nos of schemes to be unfreezed</label>
                                            <div id="no_of_schemes">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn green">Submit</button>
                    </div>
                {{Form::close()}}           
                <!-- END FORM-->
            </div>
        </div>
        
    </div>

    @if(!isset($notification))
        @include('notifications.list')

    @endif

@endsection
