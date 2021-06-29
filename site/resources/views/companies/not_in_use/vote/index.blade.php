@extends('layout')

@section('content')
	<div ng-controller="adminVoteCtrl" ng-init="type={{$type}}; @if($type == 1 || $type == 2) initialize() @else getCompanies() @endif" class="ng-cloak">
        
        <div class="page-title-cont">
        	<div class="row">
        		<div class="col-md-8">
        			<h2 class="page-title">{{$title}}</h2>
        		</div>
            </div>
        </div>

        <form ng-submit="searchMeetings(type)" name="searchForm" novalidate>
            <div class="row">
                <div class="col-md-3 form-group">
                    <label>Client</label>
                    <select class="form-control select2" ng-model="searchMeeting.client_id">
                        <option ng-value="0">All Clients</option>
                        <option ng-value="client.id" ng-repeat="client in clients">@{{client.name}}</option>
                    </select>
                </div>
                @if($type==2)
                <div class="col-md-2 form-group">
                    <label>Start Date</label>
                    <input type="text" ng-model="searchMeeting.start_date" class="form-control datepicker">
                </div>
                <div class="col-md-2 form-group">
                    <label>End Date</label>
                    <input type="text" ng-model="searchMeeting.end_date" class="form-control datepicker">
                </div>

                <div class="col-md-3 form-group hidden">
                    <label>Companies</label>
                    <select class="form-control" ng-model="searchMeeting.com_id">
                        <option ng-value="company.com_id" ng-repeat="company in companies">@{{company.com_name}}</option>
                    </select>
                </div>
                @endif
                <div class="col-md-2">
                    <button class="btn blue" ladda="processing" style="margin-top: 23px;">Search</button>
                </div>
            </div>
        </form>
    	
        <div ng-show="meetings.length > 0 ">
            <table class="table table-sorterd table-bordered table-hower" datatable ="ng">
                <thead>
                    <th>SN</th>
                    <th>Company Name</th>
                    <th>Meeting Date</th>
                    <th>e-Voting Deadline</th>
                    <th>DB Deadline</th>
                    <th>Type</th>
                    <th>E-Voting Plateform / EVEN</th>
                    <th>Cast Vote</th>
                    <th>Meeting Details</th>
                </thead>
                <tbody>
                    <tr ng-repeat="meeting in meetings">
                        <td>@{{$index+1}}</td>
                        <td>
                            @{{meeting.com_name}}
                            <span class="deb-count" ng-if="meeting.debenture > 1">
                                +@{{meeting.debenture-1}}
                            </span>
                        </td>
                        <td>@{{meeting.meeting_date}}</td>
                        <td>@{{meeting.evoting_end}}</td>
                        <td>@{{meeting.deadline_date}}</td>
                        <td>@{{meeting.meeting_type}}</td>
                        <td>
                            <span ng-hide="meeting.evoting_plateform == '' && meeting.even ==''">@{{meeting.evoting_plateform +' / ' + meeting.even}} </span>
                        </td>
                        <td>
                            <button ng-click="castVote(meeting)" class="btn btn-block" ng-class="meeting.evoting_ended ? 'yellow' :'blue' ">@{{meeting.evoting_ended  ? 'View' : 'Vote' }}</button>
                            <button class="btn btn-xs btn-block default" ng-click="checkHolding(meeting.report_id)">Check Holding</button>
                        </td>
                        <!-- <td>
                            <button ng-click="uploadResponseFile(meeting)" class="btn default" >View</button>
                        </td> -->
                        <td>
                            <button class="btn btn-block default" ng-click="showReportDetails(meeting.report_id)">Details</button>
                            <button style="margin-top: 5px;" ng-click="uploadResponseFile(meeting)" class="btn btn-sm btn-block default" >Response Files</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div ng-hide="hide_alert">
            <div class="alert alert-danger">
                No records found
            </div>
        </div>
        @include('admin.vote.angularj_modal')
    </div>
	

@endsection