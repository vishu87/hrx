<!-- Begin  Modal -->
            
    <div  class="modal fade " id="reportDetails" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">@{{ loading ? 'Loading' : reportData.com_name}}</h4>
                </div>
                <div class="loader" ng-show="loading"></div>
                <div class="modal-body" ng-hide="loading">
                    <div>
                        <table class="table">
                            <tr>
                                <td>Meeting Date</td>
                                <td>@{{reportData.meeting_date | date}}</td>
                            </tr>
                            <tr>
                                <td>Record Date</td>
                                <td>@{{reportData.record_date | date}}</td>
                            </tr>
                            <tr>
                                <td>e-Voting Start Date</td>
                                <td>@{{reportData.evoting_start | date}}</td>
                            </tr>
                            <tr>
                                <td>e-Voting Deadline</td>
                                <td>@{{reportData.evoting_end | date}}</td>
                            </tr>
                            <tr>
                                <td>e-Voting Plateform</td>
                                <td>@{{reportData.evoting_plateform}}</td>
                            </tr>
                            <tr>
                                <td>Meeting Type</td>
                                <td>@{{reportData.meeting_type}}</td>
                            </tr>
                            <tr>
                                <td>Report</td>
                                <td>
                                    <a href="@{{reportData.annual_report}}" target="_blank" class="btn btn-sm default" ng-show="reportData.release_on != null">View</a>
                                </td>
                            </tr>
                            <tr>
                                <td>Notice</td>
                                <td><a href="@{{reportData.notice_link}}" target="_blank">View</a></td>
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


    <div  class="modal fade " id="showCompanyScheme" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">@{{com_name}}</h4>

                </div>
                <div class="loader" ng-show="loading"></div>
                <div class="modal-body" ng-hide="loading">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <td style="width: 50px">Select</td>
                                <td>Scheme Name</td>
                                <td>Status</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="scheme in record_schemes">
                                <td><input type="checkbox" ng-click="selectScheme(scheme.id)" ng-checked="selectedSchemes.indexOf(scheme.id) != -1 ? true : false "></td>
                                <td>@{{scheme.scheme_name}}</td>
                                <td>@{{scheme.status}}</td>
                            </tr>
                        </tbody>
                    </table>
                    
                </div>
                
                <div class="modal-footer">
                    <button ng-click="showReportResolutions()" ng-disabled="selectedSchemes.length < 1 " class="btn btn-primary ">Vote</button>
                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
    <!-- /.modal-dialog -->
    </div>
    <!-- End modal -->

    <div class="modal fade modal-large" id="addVote" tabindex="-1" role="basic" aria-hidden="true" >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="modal-title">
                                @{{ loading ? 'Loading' : reportData.com_name}}
                            </h4>
                        </div>
                        <div class="col-md-6">
                            <div class="text-right">
                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="loader" ng-show="loading"></div>
                <form ng-submit="submitVotes()" >
                    <div ng-hide="loading">
                        
                        <div class="modal-body" ng-repeat="scheme in schemes">
                            <div class="row">
                                <div class="col-md-4">
                                    <strong>Scheme Name : </strong> @{{scheme.scheme_name}}
                                </div>
                                <div class="col-md-2">
                                    <strong>Depository : </strong> @{{scheme.depository}}
                                </div>
                            
                                <div class="col-md-7 clear">
                                    <span ng-show="scheme.freezed">
                                        Freezed on @{{scheme.freeze_on|date}}
                                    </span>
                                </div>

                                <div class="col-md-5">
                                    
                                    <button type="button" class="btn btn-warning pull-right" ng-show="!scheme.edit_mode && !scheme.freezed" ng-click="scheme.edit_mode = true">Edit</button> 

                                    <button type="button" class="btn green pull-right" ng-show="!scheme.freezed && !scheme.edit_mode" ladda="scheme.processing" ng-click="freeze(scheme)" >Freeze</button>

                                    <button type="button" class="btn red pull-right" ng-show="scheme.freezed" ladda="scheme.processing"  ng-click="Unfreeze(scheme)">Unfreeze</button>  
                                </div>
                            </div>
                            <div>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>SN</th>
                                            <th>Resolution Name</th>
                                            <th>Management Recommendation</th>
                                            <th>Proposal by Management or Shareholder</th>
                                            <th ng-show="client.ses_client">SES Recommendation</th>
                                            <th ng-show="reportData.edit_mode && client.ses_client">Copy</th>
                                            <th style="width: 150px">Your Vote</th>
                                            <th style="width: 150px">Rationale</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr ng-repeat="resolution in scheme.resolutions">
                                            <td>@{{$index+1}}</td>
                                            <td>@{{resolution.resolution_name}}</td>
                                            <td>@{{resolution.man_reco}}</td>
                                            <td>@{{resolution.man_share_reco}}</td>
                                            <td ng-show="client.ses_client">@{{resolution.ses_reco}}</td>
                                            <td ng-show="scheme.edit_mode && client.ses_client">
                                                <button type="button" class="btn btn-warning"><i class="fa fa-angle-right"></i></button>
                                            </td>
                                            <td>
                                                <select class="form-control" ng-model="resolution.vote"convert-to-number ng-show="scheme.edit_mode">
                                                    <option value="">Select</option>
                                                    <option ng-repeat="(id,value) in man_recos" ng-value="id">@{{value}}</option>
                                                </select>
                                                <span ng-show="!scheme.edit_mode">
                                                    @{{resolution.vote_value}}
                                                </span>
                                            </td>
                                            <td>
                                                <textarea ng-model="resolution.comment" class="form-control" ng-show="scheme.edit_mode" placeholder="comment .."></textarea>
                                                <span ng-show="!scheme.edit_mode">
                                                    @{{resolution.comment}}
                                                </span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div ng-show="$index==0 && reportData.edit_mode && !reportData.freezed">
                                    <label>
                                        <input type="checkbox" ng-click="copyVote()" ng-checked="copyAll? true : false "> Copy To All
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer" style="text-align: center;">
                            <button type="submit" ng-show="reportData.edit_mode" ladda="processing" class="btn blue pull-right" >Save Final Votes</button>
                        </div>
                    </div>
                </form>
                
            </div>
            <!-- /.modal-content -->
        </div>
    <!-- /.modal-dialog -->
    </div>
<!-- End modal -->