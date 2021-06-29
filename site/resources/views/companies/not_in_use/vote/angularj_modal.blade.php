<!-- Begin  Modal -->
            
    @include("admin.vote.report_details")


    <div class="modal fade" id="castVote" tabindex="-1" role="basic" aria-hidden="true" >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="position:relative">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">
                        <span>
                            @{{reportData.com_name}}<br>
                            @{{reportData.com_isin}}
                        </span>
                        <span class="deb-count big" ng-if="reportData.debenture > 1 && !loading">
                            + @{{reportData.debenture-1}}
                        </span>
                        <div></div>
                        <button type="button" class="btn btn-xs blue" ng-click="showReportDetails(reportData.report_id)" style="margin-top: 5px">View Details
                        </button>
                    </h4>
                    <div style="position: absolute; right: 35px; top: 10px" ng-if="!reportData.evoting_ended">
                        <a href="{{url('admin/vote-file')}}/@{{reportData.report_id}}" target="_blank" class="btn blue">
                            @{{reportData.evoting_plateform}} Vote File
                        </a>
                    </div>
                </div>
                <div class="loader" ng-show="loading"></div>

                <div class="modal-body" ng-show="!loading">
                    <div ng-show="reportData.meeting_clients.length > 0">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>SN</th>
                                    <!-- <th ng-if="!reportData.evoting_ended">
                                        <input type="checkbox" ng-click="selectAll()" ng-checked="select_all">
                                    </th> -->
                                    <th>Client</th>
                                    <th>Status</th>
                                    <th>Total Schemes</th>
                                    <th>Total Approved</th>
                                    <th>Total Abstained</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="client in reportData.meeting_clients">
                                    <td>@{{$index+1}}</td>
                                    <!-- <td ng-if="!reportData.evoting_ended"><input type="checkbox" ng-click="selectMeetingClient(client.user_id)" ng-checked="selectedMeetingClients.indexOf(client.user_id) != -1 ? true : false" ng-if="client.vote_approved > 0 " ></td> -->
                                    <td>@{{client.user_name}}</td>
                                    <td>
                                        @{{client.status}}
                                    </td>
                                    <td>
                                        @{{client.total_schemes}}
                                    </td>
                                    <td>@{{client.vote_approved}}</td>
                                    <td>@{{client.total_abstained}}</td>
                                </tr>
                            </tbody>
                        </table>
                        <!-- <button ng-if="!reportData.evoting_ended" class="btn red btn-sm" ladda="unfreezing" ng-click="unfreezeVotes()">Unfreeze Votes</button> -->
                    </div>

                    <div ng-hide="reportData.meeting_clients.length > 0" class="alert alert-warning">No details found</div>
                </div>

            </div>
            <!-- /.modal-content -->
        </div>
    <!-- /.modal-dialog -->
    </div>
<!-- End modal -->

    <div class="modal fade" id="uploadResponse" tabindex="-1" role="basic" aria-hidden="true" >
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="position:relative">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">
                        @{{ loading ? 'Loading' : reportData.com_name + ' ( '+reportData.com_isin+' )'}}
                    </h4>
                </div>
                <div class="loader" ng-if="loading"></div>

                <div class="modal-body">
                    <h4>Response Files</h4>
                    <!-- <button type="button" class="btn green" ngf-select="uploadFile($file,'file')" ladda="uploading_file" data-style="expand-right" >Upload file</button> -->

                    <table class="table table-bordered" style="margin-top:20px"  ng-show="response_files.length > 0">
                        <thead>
                            <tr>
                                <th>SN</th>
                                <th>Reponse File</th>
                                <th>Uploaded By</th>
                                <th>Date</th>
                                <th>Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="response_file in response_files">
                                <td>@{{$index+1}}</td>
                                <td>
                                    <a href="@{{response_file.response_file}}" target="_blank">View</a>
                                </td>
                                <td>@{{response_file.user_name}}</td>
                                <td>@{{response_file.updated_at}}</td>
                                <td>
                                    <a href="javascript:;" class="details" data-title="Reponse File Log" action="admin/response-file/records/show/@{{response_file.id}}">Details</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div ng-if="response_files.length == 0" style="margin-top:0px; color:#555;">
                        <div>No response files are available</div>
                    </div>

                    <div ng-if="all_abstained.length > 0">
                        <h4>All votes were abstained for</h4>
                        <table class="table table-bordered" style="margin-top:20px">
                            <tbody>
                                <tr ng-repeat="all_abstain in all_abstained">
                                    <td>@{{$index+1}}</td>
                                    <td>@{{all_abstain.user_name}}</td>
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

    <div class="modal fade" id="checkHolding" tabindex="-1" role="basic" aria-hidden="true" >
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="position:relative">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">
                        @{{holding_company_info}}
                        <span class="deb-count big" ng-if="debenture_info > 1 && !loading">
                            + @{{debenture_info-1}}
                        </span>
                    </h4>
                </div>
                <div class="loader" ng-if="loading"></div>

                <div class="modal-body">
                    <div>
                        <ul class="nav nav-tabs">
                            <li ng-class="check_vote_tab == 1 ? 'active' : '' ">
                                <a href="javascript:;" ng-click="check_vote_tab = 1; checkHoldingClient();">Scheme Holding</a>
                            </li>
                            <li ng-class="check_vote_tab == 2 ? 'active' : '' ">
                                <a href="javascript:;" ng-click="check_vote_tab = 2; checkHoldingClient()">Actual Upload</a>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <div ng-show="check_vote_tab == 1"> <!--ongoing holding-->
                            <div ng-repeat="user_holding in user_holdings" ng-if="user_holding.companies.length > 0">
                                <h4 style="background: #EEE; padding: 5px; font-size: 14px; font-weight: bold;">@{{user_holding.name}}</h4>
                                <div ng-repeat="company in user_holding.companies" ng-if="company.total_holding > 0">
                                    @{{company.com_name}} - @{{company.total_holding}}
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>SN</th>
                                                <th>Scheme Name</th>
                                                <th>Shares Held</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr ng-repeat="holding in company.holdings">
                                                <td>@{{$index+1}}</td>
                                                <td>@{{holding.scheme_name}}</td>
                                                <td>@{{holding.shares_held}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div ng-show="check_vote_tab == 2"> <!--actual holding-->
                            <div ng-repeat="user_holding in user_holdings" ng-if="user_holding.companies.length > 0">
                                <h4 style="background: #EEE; padding: 5px; font-size: 14px; font-weight: bold;">@{{user_holding.name}}</h4>
                                <div ng-repeat="company in user_holding.companies" ng-if="company.total_holding > 0">
                                    @{{company.com_name}} - @{{company.total_holding}}
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>SN</th>
                                                <th>Scheme Name</th>
                                                <th>Shares Held</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr ng-repeat="holding in company.holdings">
                                                <td>@{{$index+1}}</td>
                                                <td>@{{holding.scheme_name}}</td>
                                                <td>@{{holding.shares_held}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div ng-if="overall_holding == 0 && !loading" class="alert alert-warning">
                                No actual uplaod of holdings as on Record date
                            </div>
                        </div>

                    </div>
                </div>

            </div>
            <!-- /.modal-content -->
        </div>
    <!-- /.modal-dialog -->
    </div>