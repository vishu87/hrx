@extends('layout')

@section('content')
	<div ng-controller="voteCtrl" ng-init="calendar();">
		<div class="row">
	        <div class="col-md-6">
	            <h1 class="page-title" style="margin-top: 0">
	                Calendar <span style="font-size:14px">As per e-Voting deadline (except physical votes)</span>
	            </h1>
	        </div>
	    </div>
	    <div class="row">
	        <div class="col-md-8">
	        	<div style="margin-bottom:10px">
	        		<div class="meeting big type_0" ng-click="selectType(0)" ng-class="show_meeting.type_0 ? 'selected' : '' "><a href="javascript:;">All</a></div>
	        		<div class="meeting big type_1" ng-click="selectType(1)" ng-class="show_meeting.type_1 ? 'selected' : '' "><a href="javascript:;">AGM</a></div>
		        	<div class="meeting big type_2" ng-click="selectType(2)" ng-class="show_meeting.type_2 ? 'selected' : '' "><a href="javascript:;">EGM</a></div>
		        	<div class="meeting big type_3" ng-click="selectType(3)" ng-class="show_meeting.type_3 ? 'selected' : '' "><a href="javascript:;">PBL</a></div>
		        	<div class="meeting big type_4" ng-click="selectType(4)" ng-class="show_meeting.type_4 ? 'selected' : '' "><a href="javascript:;">CCM</a></div>
		        	<div class="meeting big type_5" ng-click="selectPhysical()" ng-class="physical ? 'selected' : '' "><a href="javascript:;">Physical</a></div>
	        	</div>
	        </div>
	        <div class="col-md-4">
	            <div class="pull-right" ng-show="!loading">
	                <a href="javascript:;" class="btn blue" ng-click="prev_month()"><i class="fa fa-chevron-left"></i></a>
	                <a href="javascript:;" style="text-decoration:none; font-size:13px; color:#888">&nbsp;&nbsp;@{{month_name+", "+year}}&nbsp;&nbsp;</a>
	                <a href="javascript:;" class="btn blue" ng-click="next_month()"><i class="fa fa-chevron-right"></i></a>
	            </div>
	        </div>
	    </div>

	    <div class="row ng-cloak">
	        <div class="col-md-12">
	            <table class="table table-bordered">
	            	<thead>
	            		<tr>
	            			<td>Sun</td>
	            			<td>Mon</td>
	            			<td>Tue</td>
	            			<td>Wed</td>
	            			<td>Thu</td>
	            			<td>Fri</td>
	            			<td>Sat</td>
	            		</tr>
	            	</thead>
	            	<tbody>
	            		<tr>
	            			<tr ng-repeat="week in weeks">
	                            <td ng-repeat="day in week" class="day-cell" ng-class="day.in_month ? '' : 'grey' ">
	                                <div>
	                                    <span class="day-meta">@{{day.date_show}}</span>
	                                    
	                                    <div class="meeting type_@{{meeting.meeting_type}} @{{meeting.evoting_plateform == 'Physical' ? '':'not_physical'}}" ng-repeat="meeting in day.meetings" ng-show=" show_meeting['type_'+meeting.meeting_type] || show_meeting.type_0 ">
	                                    	<span class="physical" ng-if="meeting.evoting_plateform == 'Physical'">P</span>
	                                        <a href="javascript:;" ng-click="showCompanyScheme(meeting.report_id, meeting.com_name, meeting.debenture)">
	                                        	@{{meeting.com_name}} <span class="com-count" ng-if="meeting.debenture > 1">+@{{meeting.debenture - 1}}</span>
	                                        </a>

	                                    </div>
	                                    
	                                </div>
	                            </td>
	                        </tr>
	            		</tr>
	            	</tbody>
	            </table>
	        </div>
	    </div>

	    @include('clients.vote.angular_modal')
	</div>

@endsection