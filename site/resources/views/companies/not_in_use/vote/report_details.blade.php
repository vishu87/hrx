
<div  class="modal fade modal-front" id="reportDetails" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">
                    @{{ loading ? 'Loading' : reportShowData.com_name}}
                    <span class="deb-count big" ng-if="reportShowData.debenture > 1 && !loading">
                        + @{{reportShowData.debenture-1}}
                    </span>
                </h4>

            </div>
            <div class="loader" ng-show="loading"></div>
            <div class="modal-body" ng-hide="loading">

                <div ng-if="reportShowData.companies.length > 0">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>SN</th>
                                <th>Company</th>
                                <th>ISIN</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="com in reportShowData.companies">
                                <td>@{{$index+1}}</td>
                                <td>@{{com.com_name}}</td>
                                <td>@{{com.com_isin}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div>
                    <table class="table">
                        <tr>
                            <td style="border-top: 0">ISIN</td>
                            <td style="border-top: 0; word-break: break-all;">
                                @{{ reportShowData.companies.length > 0 ? 'Multiple' : reportShowData.meeting_isin}}
                            </td>
                        
                            <td style="border-top: 0">Meeting Type</td>
                            <td style="border-top: 0">@{{reportShowData.meeting_type}}</td>
                        </tr>
                        <tr>
                            <td>Meeting Date & Time</td>
                            <td>@{{reportShowData.meeting_date | date}} @{{reportShowData.meeting_time}}</td>
                        
                            <td>Meeting City</td>
                            <td>@{{reportShowData.meeting_city}}</td>
                        </tr>
                        <tr>
                            <td>Record Date</td>
                            <td>@{{reportShowData.record_date | date}}</td>
                            <td>e-Voting Platform</td>
                            <td>@{{reportShowData.evoting_plateform}}</td>
                            
                        </tr>
                        <tr>
                            <td>e-Voting Start Date</td>
                            <td>@{{reportShowData.evoting_start | date}}</td>
                            <td>e-Voting Deadline</td>
                            <td>@{{reportShowData.evoting_end | date}}</td>
                        </tr>
                        <tr>
                            <td>Annual Report</td>
                            <td>
                                <a href="@{{reportShowData.annual_report}}" target="_blank" class="btn btn-sm default" ng-if="reportShowData.annual_report">View</a>
                                <span ng-show="!reportShowData.annual_report">Pending</span>
                            </td>
                        
                            <td>Notice</td>
                            <td>
                                <a href="@{{reportShowData.notice_link}}" target="_blank" class="btn btn-sm default" ng-if="reportShowData.notice_link">View</a>
                                <span ng-show="!reportShowData.notice_link">Pending</span>
                            </td>
                        </tr>
                        <tr>
                            <td>Result of Meeting</td>
                            <td>
                                @{{reportShowData.result_of_voting}}
                                <span ng-show="!reportShowData.result_of_voting">Pending</span>
                            </td>
                            <td>Voting Results Link</td>
                            <td>
                                <a href="@{{reportShowData.meeting_results}}" target="_blank" class="btn btn-sm default" ng-show="reportShowData.meeting_results">View</a>
                                <span ng-show="!reportShowData.meeting_results">Pending</span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn default pull-right" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
<!-- /.modal-dialog -->
</div>
<!-- End modal -->