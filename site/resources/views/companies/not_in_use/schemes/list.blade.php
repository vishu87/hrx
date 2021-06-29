@extends('layout')

@section('content')

    <div class="page-title-cont">
	   <div class="row">
            <div class="col-md-4">
                <h2 class="page-title">Scheme Management</h2>
            </div>
            <div class="col-md-8 text-right">
                @if(Input::has('cl_id'))
                <a href="{{url('admin/schemes/export?cl_id='.$cl_ids.'&date='.Input::get('date'))}}" class="btn blue" target="_blank">Export</a>
                @endif
            </div>
       </div>
    </div>

    @if(isset($pending_approval) && isset($pending_deletion))
        @if($pending_approvals > -1 || $pending_deletion > -1)
            <table class="table">
                <tr>
                    <td>Pending approvals for new entry</td>
                    <td>{{$pending_approvals}}</td>
                    <td></td>
                    <td>Pending approvals for scheme deletion</td>
                    <td>{{$pending_deletion}}</td>
                </tr>
            </table>
        @endif
    @endif

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

    @if(Session::has('message'))
        <div class="alert alert-warning"><?php echo Session::get('message');?></div>
    @endif

    
    <div class="row ">
        @if(isset($scheme))
        <div class="col-md-12">
            <div class="portlet box blue">
                <div class="portlet-title">
                    <div class="caption">
                        Update Scheme</div>
                    <div class="tools">
                        <a href="javascript:;" class="collapse" data-original-title="" title=""> </a>
                    </div>
                </div>
                <div class="portlet-body">
                    {{Form::open(["url"=>"admin/schemes/update/".$scheme->id, "method"=>"post"])}}
                    
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Client</label><br>
                            {{Form::select('user_id',[""=>"select"]+$clients,(isset($scheme))?$scheme->user_id:'',["class"=>"form-control" , "required"=>true, "disabled"=>"true"])}}
                            <span class="errors">{{$errors->first('user_id')}}</span>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Scheme Name</label><br>
                            {{Form::text('scheme_name',(isset($scheme))?$scheme->scheme_name:'',["class"=>"form-control", "required"=>true])}}
                            <span class="errors">{{$errors->first('scheme_name')}}</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 form-group">
                            <label>DP ID</label>
                            {{Form::text('dp_id',(isset($scheme))?$scheme->dp_id:'',["class"=>"form-control", "required"=>true, "readonly" => "true"])}}
                            <span class="errors">{{$errors->first('dp_id')}}</span>
                        </div>
                        <div class="col-md-3 form-group">
                            <label>Client ID</label>
                            {{Form::text('client_id',(isset($scheme))?$scheme->client_id:'',["class"=>"form-control", "required"=>true, "readonly" => "true"])}}
                            <span class="errors">{{$errors->first('client_id')}}</span>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Depository</label>
                            {{Form::select('depository',[""=>"select"]+$depositories,(isset($scheme))?$scheme->depository:'',["class"=>"form-control" , "required"=>true, "disabled" => "true"])}}
                            <span class="errors">{{$errors->first('depository')}}</span>
                        </div>
                    </div>
                    <div>
                        <button class="btn blue">{{(isset($scheme))?'Update':'Add'}}</button>
                        <a href="{{url('admin/schemes?cl_id='.$scheme->user_id)}}" class="btn default">Cancel</a>
                    </div>
                    {{Form::close()}}
                </div>
            </div>
        </div>
        @endif
        <div class="col-md-4 hidden">
            @if(!isset($scheme))
            <div class="portlet box blue">
                <div class="portlet-title">
                    <div class="caption">
                        Upload Schemes</div>
                    <div class="tools">
                        <a href="javascript:;" class="collapse" data-original-title="" title=""> </a>
                    </div>
                </div>
                <div class="portlet-body" style="min-height: 210px;">
                    {{Form::open(["url"=>"admin/schemes/upload", "method"=>"post" , "files"=>true])}}
                    
                    <div class="form-group">
                        <label>Upload File (Excel) </label>
                        {{Form::file('upload_scheme',["class"=>"form-control" , "required"=>true])}}
                        <a href="{{url('/formats/scheme-format.xlsx')}}" class="pull-right" target="_blank">Download Format</a>
                        <span class="errors">{{$errors->first('upload_scheme')}}</span>
                    </div>
                    
                    <div>
                        <button class="btn blue">Upload</button>
                    </div>
                    {{Form::close()}}
                </div>
            </div>
            @endif
        </div>
    </div>

    @if(!isset($scheme))
    <div class="portlet box blue">
        <div class="portlet-title">
            <div class="caption">
                List of Schemes </div>
            <div class="tools">
                <a href="javascript:;" class="collapse" data-original-title="" title=""> </a>
            </div>
        </div>
        <div class="portlet-body">
            <div class="row">
                {{Form::open(["url"=>"admin/schemes","method"=>"get"])}}
                    <div class="form-group col-md-4">
                        {{Form::select('cl_id[]',[""=>"Select Client"]+$clients,$cl_ids, ["class"=>"selectize"])}}
                        
                    </div>
                    <div class="form-group col-md-3">
                        {{Form::text('date',(Input::has('date'))?Input::get('date'):'' , ["class"=>"form-control datepicker", "autocomplete"=>"off","placeholder"=>"Select Date"])}}
                    </div>
                    <div class="form-group col-md-4">
                        <button class="btn blue">Submit</button>
                    </div>
                {{Form::close()}}
            </div>

            @if(Input::has('cl_id'))
                @if(sizeof($schemes) > 0)
                    <div ng-controller="schemeCtrl">
                        <table class="table table-sorterd table-bordered table-hower" id="datatable">
                            <thead>
                                <th>SN</th>
                                <th>Client Name</th>
                                <th>Scheme Name</th>
                                <th>DIP ID</th>
                                <th>Client ID</th>
                                <th>Depository</th>
                                <th>Companies</th>
                                <th>Status</th>
                                <th style="width: 100px"></th>
                            </thead>
                            <tbody>
                                <?php $count =1;?>
                                @foreach($schemes as $scheme)
                                <tr id="scheme_{{$scheme->id}}">
                                    <td>{{$count++}}</td>
                                    <td>{{$scheme->client_name}}</td>
                                    <td>{{$scheme->scheme_name}}</td>
                                    <td>{{$scheme->dp_id}}</td>
                                    <td>{{$scheme->client_id}}</td>
                                    <td>
                                        {{$scheme->depository}}
                                    </td>
                                    <!-- <td><button class="btn default">View</button></td> -->
                                    <td><button class="btn default" ng-click="showCompanies('{{$scheme->scheme_name}}',{{$scheme->id}})">View</button></td>
                                    <td>
                                        <a href="javascript:;" class="details" data-title="Status Log" action="admin/schemes/status-log/{{$scheme->id}}">
                                            {{$scheme->getStatus()}}
                                        </a>
                                    </td>
                                    <td>
                                        @if($scheme->status == 0 || $scheme->status == 3)
                                        <a href="{{url('admin/schemes/edit/'.$scheme->id)}}" class="btn yellow">Edit</a>
                                        @endif
                                        <!-- <button type="button" class="btn btn-sm delete-div btn-danger" action="{{'admin/schemes/delete/'.$scheme->id}}" div-id="scheme_{{$scheme->id}}"><i class="fa fa-remove"></i> Remove</button> -->
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div  class="modal fade " id="companies" tabindex="-1" role="basic" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                        <h4 class="modal-title">@{{scheme_name}}</h4>

                                    </div>
                                    <div class="modal-body">
                                        <div >
                                            <!-- {{Form::open(["url"=>"api/schemes/companies" , "method"=>"post" ,"class"=>"ajax_check_form ajax_add_pop" , "role"=>"form"])}} -->
                                            <form ng-submit="searchCompanies()">
                                                
                                                <div class="row">
                                                    <div class="col-md-3 form-group">
                                                        <label>Shareholding as on</label>
                                                        <input type="text" name="date" ng-model="date" class="form-control datepicker" autocomplete="off">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <button ladda="processing" type="submit" style="margin-top: 23px;" class="btn blue">Search</button>
                                                    </div>
                                                </div>
                                            </form>
                                            <!-- {{Form::close()}} -->
                                            
                                        </div>
                                        
                                        <div ng-show="companies.length > 0 && !processing">
                                            <table class="table" datatable="ng">
                                                <thead>
                                                    <th>SN</th>
                                                    <th>Company Name</th>
                                                    <th>Share Held</th>
                                                    <th>ISIN</th>
                                                </thead>
                                                <tbody>
                                                    
                                                    <tr ng-repeat="company in companies">
                                                        <td>@{{$index+1}}</td>
                                                        <td>@{{company.com_name}}</td>
                                                        <td>@{{company.shares_held}}</td>
                                                        <td>@{{company.com_isin}}</td>
                                                    </tr>
                                                    
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    
                                </div>
                                <!-- /.modal-content -->
                            </div>
                        <!-- /.modal-dialog -->
                        </div>
                    </div>
                @else
                    <div class="alert alert-warning">
                        No Schemes Found
                    </div>
                @endif
            @endif
        </div>
    </div>
    @endif
@endsection
