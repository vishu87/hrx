@extends('layout')

@section('content')
	
    <div class="page-title-cont">
        
    	<div class="row">
    		<div class="col-md-6">
    			<h2 class="page-title">Meetings</h2>
    		</div>
    		
    	</div>
    </div>

    <div ng-controller="NewMeetingCtrl" ng-init="init()" class="ng-cloak">
        
        <div ng-if="!loading">
            <table class="table table-bordered table-hower" datatable="ng" data-page-length="50">
                <thead>
                    <th>SN</th>
                    <th>ISIN</th>
                    <th>Company</th>
                    <th>Meeting Type</th>
                    <th>Record Date</th>
                    <th>Meeting Date</th>
                    <th>Remarks</th>
                    <th>Resolved On</th>
                    @if(Auth::user()->privilege == 1)
                    <th>#</th>
                    @endif
                </thead>
                <tbody>
                    <tr ng-repeat="meeting in meetings">
                        <td>@{{$index+1}}</td>
                        <td>@{{meeting.isin}}</td>
                        <td>@{{meeting.com_name}}</td>
                        <td>@{{meeting.meeting_type_name}}</td>
                        <td>@{{meeting.record_date|date:'dd-MM-yyyy'}}</td>
                        <td>@{{meeting.meeting_date|date:'dd-MM-yyyy'}}</td>
                        <td>@{{meeting.remarks}}</td>
                        <td>@{{meeting.resolved_on|date:'dd-MM-yyyy'}}</td>
                        @if(Auth::user()->privilege == 1)
                            <td>
                                <button class="btn btn-sm btn-info" ng-click="resolve(meeting)" ladda="meeting.processing"><i class="fa fa-tag"> </i> Add Remark</button>
                            </td>
                        @endif
                    </tr>
                </tbody>
            </table>
        </div>
        
    </div>

@endsection