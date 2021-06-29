@extends('layout')

@section('content') 
	
    <div class="card card-custom">
        <div class="card-header flex-wrap border-0 pb-0">
            <div class="card-title"><h3 class="card-label">Companies</h3></div>
        </div>
        <div class="card-body pt-0">
            <div ng-controller="companyCtrl" ng-init="listing()">   
                <div class="row">
                    <div class="col-md-12 text-center">
                        <div class="spinner-border" role="status" ng-if="processing">
                          <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
                <div class="table-responsive ng-cloak">
                    <table class="table">
                        <thead>
                            <tr >
                                <th class="text-dark-50 font-weight-lighter">SN</th>
                                <th class="text-dark-50 font-weight-lighter">Name</th>
                                <th class="text-dark-50 font-weight-lighter">Email</th>
                                <th class="text-dark-50 font-weight-lighter">Mobile</th>
                                <th class="text-dark-50 font-weight-lighter">#</th>
                            </tr>
                        </thead>
                        <tbody>
                           
                        </tbody>
                    </table>
                </div>
                

                
            </div>
        </div>
    </div>

@endsection