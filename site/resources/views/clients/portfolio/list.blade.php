@extends('layout')

@section('content')
	
    <div ng-controller="portfolioCtrl" ng-init="type={{$type}}; initialize();" class="">
        
        <div class="card card-custom">

            <div class="card-header">
                <div class="card-title"><h3 class="card-label">Portfolio</h3></div>
            </div>

            <div class="card-body ng-cloak">
                <div class="row fade-in" >
                    <div class="col-md-10">
                        <form ng-submit="searchPortfolio()">
                            <div class="row">
                                <div class="col-md-4 form-group" ng-if="type==1">
                                    <selectize placeholder='Select clients ...' options='clients' config="{maxItems:10}" ng-model="formData.client_id" ></selectize>
                                </div>

                                <div class="col-md-4 form-group">
                                    <input type="text" ng-model="formData.add_date" class="form-control datepicker" placeholder="Select Date">
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary" ng-class="processing ? 'spinner spinner-right' : '' " ng-disabled="processing" >Search</button>
                                </div>
                            </div>                    
                        </form>
                    </div>
                    

                    <div class="col-md-2 text-right" ng-show="portfolio.length > 0">
                        <a href="javascript:;" ng-click="exportPortfolio()" class="btn btn-secondary pull-right">Export List</a>
                    </div>

                </div>

                <div ng-show="portfolio.length > 0 && !processing">
                    <table class="table table-sorterd table-bordered table-hover" datatable="ng" dt-options="dtOptions" id="dt-ng">
                        <thead>
                            <th>SN</th>
                            <th>Company Name</th>
                            <th>ISIN</th>
                            <th>Add Date</th>
                            <th>Delete Date</th>
                        </thead>
                        <tbody>
                            <tr ng-repeat="company in portfolio">
                                <td>@{{$index+1}}</td>
                                <td>@{{company.com_name}}</td>
                                <td>@{{company.com_isin}}</td>
                                <td>@{{company.add_date|date:"dd-MM-yyyy"}}</td>
                                <td>@{{company.delete_date|date:"dd-MM-yyyy"}}</td>
                            </tr>
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
	

@endsection