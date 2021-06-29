@extends('layout')

@section('content')
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
    <div class="page-title-cont">
        
    	<div class="row">
    		<div class="col-md-6">
    			<h2 class="page-title">Schemes</h2>
    		</div>
    		
    	</div>
    </div>
	
    <div ng-controller="schemeCtrl">
        @if(sizeof($schemes) > 0)
            <div>
                <table class="table table-sorterd table-bordered table-hower" id="datatable">
                    <thead>
                        <th>SN</th>
                        <th>Scheme Name</th>
                        <th>DIP ID</th>
                        <th>Client ID</th>
                        <th>Depository</th>
                        <th>Companies</th>
                    </thead>
                    <tbody>
                        <?php $count =1;?>
                        @foreach($schemes as $scheme)
                        <tr id="scheme_{{$scheme->id}}">
                            <td>{{$count++}}</td>
                            <td>{{$scheme->scheme_name}}</td>
                            <td>{{$scheme->dp_id}}</td>
                            <td>{{$scheme->client_id}}</td>
                            <td>
                                {{$scheme->depository}}
                            </td>
                            <td><button class="btn default" ng-click="showCompanies('{{$scheme->scheme_name}}',{{$scheme->id}})">View</button></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
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
    

@endsection
