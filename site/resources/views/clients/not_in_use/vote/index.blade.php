@extends('layout')

@section('content')
	<div ng-controller="voteCtrl" ng-init="screen_type = {{$type}}; specific_report_id = {{ isset($specific_report_id) ? $specific_report_id : 0 }}; fetchMeetings({{$type}});">
        
        <div class="page-title-cont">
        	<div class="row">
        		<div class="col-md-6">
        			<h2 class="page-title" >{{$title}}</h2>
        		</div>
        		
        	</div>
        </div>
    	
        <div ng-show="meetings.length > 0">
            <table class="table table-sorterd table-bordered table-hower" datatable ="ng">
                <thead>
                    <th>SN</th>
                    <th>Company Name</th>
                    <th>Meeting Date</th>
                    <th>e-Voting Deadline</th>
                    <th>DB Deadline</th>
                    <th>Type</th>
                    <th>E-Voting Plateform / EVEN</th>
                    <th>SES Report</th>
                    <th>@{{screen_type != 3 ? 'Record' : 'Approve'}} Vote</th>
                    <th>Details</th>
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
                        <td><span ng-hide="meeting.evoting_plateform == '' && meeting.even ==''">@{{meeting.evoting_plateform +' / ' + meeting.even}} </span></td>
                        <td>
                            <div ng-if="meeting.show_report">
                                <a href="{{url('/view-report')}}/@{{meeting.report_id}}" class="btn btn-sm default" target="_blank" ng-if="client.ses_client == 1">View</a>

                                <button ng-click="subscribe(meeting)" class="btn btn-block btn-sm default" ng-if="client.ses_client == 0">Subscribe</button>

                                <a href="https://sesgovernance.com/buy-pa-reports/@{{meeting.report_id}}" class="btn btn-block btn-sm default" target="_blank" ng-if="client.ses_client == 0" style="margin-top:5px">Buy</a>
                            </div>

                        </td>
                        
                        <td>
                            <button class="btn blue" ng-click="showCompanyScheme(meeting.report_id, meeting.com_name, meeting.debenture)" ng-if="meeting.show_vote_button && screen_type != 3">
                                @{{meeting.vote_button_name}}
                            </button>

                            <button class="btn blue" ng-click="approveVotesScheme(meeting.report_id, meeting.com_name)" ng-if="screen_type == 3">Approve</button>

                            <button ng-if="!meeting.show_vote_button" ng-click="requestVote(meeting.report_id, meeting.com_name)" class="btn default">
                                Request
                            </button>
                        </td>
                        <td>
                            <button class="btn btn-sm default btn-block" ng-click="showReportDetails(meeting.report_id)">Details</button>
                            
                            <button style="margin-top: 5px;" ng-click="uploadResponseFile(meeting)" class="btn btn-sm btn-block default" >Response Files</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="alert alert-warning ng-cloak" ng-if="meetings.length == 0 && !processing">
            No meetings found
        </div>

        @include('clients.vote.angular_modal')
    </div>
@endsection