<!-- Begin  Modal -->
            
    @include("admin.vote.report_details")


    <div  class="modal fade " id="showCompanyScheme" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">
                        @{{com_name}}
                        <span class="deb-count big" ng-if="debenture > 1">
                            + @{{debenture-1}}
                        </span>
                    </h4>
                    <a href="#" class="btn blue btn-sm" style="position:absolute; top:15px; right:50px" ng-click="showReportDetails(report_id)">Meeting Details</a>
                </div>
                <div class="loader" ng-show="scheme_loading"></div>
                <div class="modal-body" ng-hide="scheme_loading">
                    <div ng-if="last_votefile_downloaded" class="alert alert-info">
                        Vote file for this meeting was last downloaded on @{{last_votefile_downloaded}}
                    </div>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                @if(Auth::user()->access_mode == 1)
                                <td style="width: 50px">
                                    <input type="checkbox" ng-click="selectAll()" ng-checked="select_all">
                                </td>
                                @endif
                                <td>Scheme Name</td>
                                <td>Shares Held</td>
                                <td>Status</td>
                                <td>PM</td>
                                <td>Approved / Referred Back By</td>
                                <td>Details</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="scheme in record_schemes">
                                @if(Auth::user()->access_mode == 1)
                                <td><input type="checkbox" ng-click="selectScheme(scheme.scheme_id)" ng-checked="selectedSchemes.indexOf(scheme.scheme_id) != -1 ? true : false " ng-if="scheme.editable"></td>
                                @endif
                                <td>
                                    @{{scheme.scheme_name}}<br>
                                    <span style="font-size:10px; color:#555">(@{{scheme.short_code}})</span>
                                </td>
                                <td>
                                    <div ng-if="scheme.debenture == 0">
                                        @{{scheme.shares_held}}
                                    </div>
                                    <div ng-if="scheme.companies.length > 0 && scheme.debenture > 0">
                                        <span style="display: block; font-size:12px" ng-repeat="com in scheme.companies track by $index">@{{com}}</span>
                                    </div>
                                </td>
                                <td>@{{scheme.status}}</td>
                                <td>@{{scheme.user_name}}</td>
                                <td>
                                    <div ng-if="scheme.approved_on || scheme.referred_back_on">
                                        <span style="display: block;">@{{scheme.approval_name}}</span>
                                        <span style="font-size:12px; color:#555">
                                            @{{scheme.approval_remarks}}
                                        </span>
                                    </div>
                                    <span class="label label-sm label-danger" ng-if="scheme.referred_back_on" style="font-size: 11px">Refer Back
                                    </span>
                                </td>
                                <td>
                                    <a href="javascript:;" class="details" data-title="Voting Details" action="api/clients/scheme-record/@{{scheme.id}}">
                                        View
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    
                </div>
                
                @if(Auth::user()->access_mode == 1)
                <div class="modal-footer" ng-hide="scheme_loading">
                    <button ng-click="showReportResolutions()" class="btn btn-primary ">Vote</button>
                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Close</button>
                </div>
                @endif
            </div>
            <!-- /.modal-content -->
        </div>
    <!-- /.modal-dialog -->
    </div>
    <!-- End modal -->

    <div class="modal fade modal-large" id="addVote" tabindex="-1" role="basic" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="position: relative;">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="position: absolute; right: 20px; z-index: 1000"></button>
                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="modal-title">
                                @{{ loading ? 'Loading' : reportData.com_name}}
                            </h4>
                        </div>
                        <div class="col-md-6">
                            <div class="text-right" style="padding-right: 30px">
                                <button type="button" class="btn yellow" ng-show="!edit_mode" ng-click="edit_mode = true">Edit</button>

                                <button type="button" class="btn green" ng-show="!edit_mode && show_freeze" ladda="processing" ng-click="freeze()">{{ $checker_disabled ? 'Freeze & Approve' : 'Freeze' }}</button>

                                <button type="button" class="btn blue" ng-show="edit_mode" ladda="processing" ng-click="submitVotes()" >Save Final Votes</button>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="loader" ng-show="loading"></div>
                <form  >
                    <div ng-hide="loading">
                        
                        <div class="modal-body">
                            <div ng-repeat="scheme in schemes">
                                <div class="scheme-row" ng-class="edit_mode ? 'yellow' : scheme.freezed ? 'green' : '' ">
                                    <div class="row">
                                        <div class="col-md-4">
                                            @{{scheme.scheme_name}}
                                            <div style="display: none">
                                                <div class="table-div">
                                                    <div>
                                                        <strong>Scheme Depository - </strong>
                                                        @{{scheme.depository}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <strong>DP ID :</strong> @{{scheme.dp_id}} <br>
                                            <strong>Client ID :</strong> @{{scheme.client_id}}
                                        </div>
                                    
                                        <div class="col-md-4">
                                            <span ng-show="scheme.freezed">
                                                <strong>Freezed on</strong> - @{{scheme.freeze_on|date}}
                                            </span>
                                        </div>

                                        <div class="col-md-5 hidden">
                                            
                                            <button type="button" class="btn btn-warning pull-right" ng-show="!scheme.edit_mode && !scheme.freezed" ng-click="scheme.edit_mode = true">Edit</button> 

                                            <button type="button" class="btn green pull-right" ng-show="!scheme.freezed && !scheme.edit_mode" ladda="scheme.processing" ng-click="freeze(scheme)" >Freeze</button>

                                            <button type="button" class="btn red pull-right" ng-show="scheme.freezed" ladda="scheme.processing"  ng-click="Unfreeze(scheme)">Unfreeze</button>  
                                        </div>
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
                                                <th ng-show="edit_mode">
                                                    Copy
                                                    <button type="button" class="btn btn-sm yellow" ng-click="copyAllSESVote(scheme)" ng-if="client.ses_client"><i class="fa fa-angle-right"></i></button>
                                                </th>
                                                <th style="width: 150px">Your Vote</th>
                                                <th style="width: 150px">Rationale</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr ng-repeat="resolution in scheme.resolutions">
                                                <td>@{{resolution.resolution_number}}</td>
                                                <td>@{{resolution.resolution_name}}</td>
                                                <td>@{{resolution.man_reco}}</td>
                                                <td>@{{resolution.man_share_reco}}</td>
                                                <td ng-show="client.ses_client">
                                                    <span class="ses-reco @{{resolution.ses_reco}}" ng-if="!resolution.invalid">
                                                        @{{resolution.ses_reco}}
                                                        <div class="details">
                                                            @{{resolution.detail}}
                                                        </div>
                                                        <div class="arrow-down"></div>
                                                    </span>
                                                </td>
                                                <td ng-show="edit_mode">
                                                    <div ng-if="client.ses_client">
                                                        <button type="button" class="btn btn-sm yellow" ng-click="copySESVote(resolution)" ng-if="!resolution.invalid"><i class="fa fa-angle-right"></i></button>
                                                    </div>
                                                    <div ng-if="!client.ses_client">
                                                        <a href="{{url('/view-report')}}/@{{meeting.report_id}}" class="btn btn-block btn-sm default" target="_blank">Subscribe</a>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div ng-if="!resolution.invalid">
                                                        <select class="form-control" ng-model="resolution.vote"convert-to-number ng-show="edit_mode" ng-change="setVoteValue(resolution)">
                                                            <option value="">Select</option>
                                                            <option ng-repeat="(id,value) in man_recos" ng-value="id" >@{{value}}</option>
                                                        </select>
                                                        <div ng-show="$index == 0 && edit_mode">
                                                            <a href="javascript:;" ng-click="copyToOtherResolution(resolution.vote, scheme)" style="font-size:12px; color:#666">Copy To All</a>
                                                        </div>
                                                        <span ng-show="!edit_mode">
                                                            @{{resolution.vote_value}}
                                                        </span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div ng-if="!resolution.invalid">
                                                        <textarea ng-model="resolution.comment" class="form-control" ng-show="edit_mode" placeholder="comment .."></textarea>
                                                        <div ng-show="$index == 0 && edit_mode">
                                                            <a href="javascript:;" ng-click="copyRationalToOtherResolution(resolution.comment, scheme)" style="font-size:12px; color:#666">Copy To All</a>
                                                        </div>
                                                        <span ng-show="!edit_mode">
                                                            @{{resolution.comment}}
                                                        </span>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <hr ng-hide="$index == 0">
                                    <div ng-show=" $index == 0 && edit_mode && schemes.length > 1" style="margin-bottom:20px">
                                        <label>
                                            <a href="javascript:;" ng-click="copyVote()" class="btn blue">Copy To All Schemes</a>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                
            </div>
            <!-- /.modal-content -->
        </div>
    <!-- /.modal-dialog -->
    </div>

    <div  class="modal fade " id="showApprove">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">@{{com_name}}</h4>
                </div>
                <div class="loader" ng-show="loading"></div>
                <div ng-show="record_schemes.length == 0 && !loading_approve">
                    <div class="alert alert-warning">
                        No entries found
                    </div>
                </div>
                <div class="modal-body" ng-hide="loading || record_schemes.length == 0">
                    <table class="table">
                        <thead>
                            <tr>
                                @if(Auth::user()->access_mode == 1)
                                <td style="width: 50px"><input type="checkbox" ng-click="selectAll()" ng-checked="select_all"></td>
                                @endif
                                <td>Scheme Name</td>
                                <td>Shares held</td>
                                <td>Status</td>
                                <td>PM</td>
                                <td>Details</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="scheme in record_schemes">
                                @if(Auth::user()->access_mode == 1)
                                <td><input type="checkbox" ng-click="selectScheme(scheme.id)" ng-checked="selectedSchemes.indexOf(scheme.id) != -1 ? true : false " ng-if="scheme.editable"></td>
                                @endif
                                <td>@{{scheme.scheme_name}}</td>
                                <td>
                                    <div ng-if="scheme.debenture == 0">
                                        @{{scheme.shares_held}}
                                    </div>
                                    <div ng-if="scheme.companies.length > 0 && scheme.debenture > 0">
                                        <span style="display: block; font-size:12px" ng-repeat="com in scheme.companies track by $index">@{{com}}</span>
                                    </div>
                                </td>
                                <td>@{{scheme.status}}</td>
                                <td>@{{scheme.user_name}}</td>
                                <td>
                                    <a href="javascript:;" class="details" data-title="Voting Details" action="api/clients/scheme-record/@{{scheme.id}}">
                                        View Details
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                        
                </div>
                @if(Auth::user()->access_mode == 1)
                <div class="modal-footer" ng-hide="loading || record_schemes.length == 0">
                    <div class="row">
                        <div class="col-md-4"></div>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-7">
                                    <input type="text" ng-model="approval_remarks" placeholder="Remarks" class="form-control">
                                </div>
                                <div class="col-md-5">
                                    <button ng-click="approveVotes(1)" ng-disabled="selectedSchemes.length < 1 || approving || referring" class="btn blue" ladda="approving">Approve</button>
                                    <button ng-click="approveVotes(2)" ng-disabled="selectedSchemes.length < 1 || approving || referring" class="btn yellow" ladda="referring">Refer Back</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            <!-- /.modal-content -->
        </div>
    <!-- /.modal-dialog -->
    </div>
    <!-- End modal -->
<!-- End modal -->

    <div  class="modal fade" id="requestVote" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">@{{com_name}}</h4>
                </div>
                <div class="loader" ng-show="request_loading"></div>
                <div class="modal-body">
                    Send a reminder to update resolutions.
                    <button ng-click="sendRequest(report_id)" class="btn blue" ladda="processing_request">Send Request</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
    <!-- /.modal-dialog -->
    </div>

    <div class="modal fade" id="uploadResponse" tabindex="-1" role="basic" aria-hidden="true" >
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="position:relative">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">
                        @{{ loading ? 'Loading' : reportData.com_name }}
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
                                <th>Uploaded By</th>
                                <th>Date</th>
                                <th>Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="response_file in response_files">
                                <td>@{{$index+1}}</td>
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
                

                    <div ng-if="all_abstained.length > 0" style="margin-top:50px">
                        <b>All votes were abstained for</b>
                        <table class="table table-bordered" style="margin-top:10px">
                            <thead>
                                <tr>
                                    <th>SN</th>
                                    <th>Client Name</th>
                                </tr>
                            </thead>
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