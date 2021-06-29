@extends('layout')

@section('content')
	<div ng-controller="reportCtrl" class="ng-cloak">
        <div>
            <h2 class="page-title">MIS / Audit Reports</h2>
        </div>
        
    </div>
	
    @if(Session::has('failure'))
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <i class="fa fa-ban-circle"></i><strong>Failure!</strong> {{Session::get('failure')}}
        </div>
    @endif

    <div class="portlet box blue">
        <div class="portlet-title">
            <div class="caption">
                Generate SEBI Compliance MIS Report</div>
            <div class="tools">
                <a href="javascript:;" class="collapse" data-original-title="" title=""> </a>
            </div>
        </div>

        <div class="portlet-body">
            {{Form::open(["url"=>"admin/mis-report/sebi-report", "method"=>"post"])}}
            
            <div class="row">
                
                @if(Auth::user()->privilege == 2)
                <div class="col-md-4 form-group">
                    <label>Select Client</label>
                    {{Form::select('client_ids[]',[""=>"Select Client"]+$clients,'',["class"=>" selectize" , "required"=>true])}}
                    <span class="errors">{{$errors->first('client_id')}}</span>
                </div>
                @endif

                <div class="col-md-2 form-group">
                    <label>Date From</label><br>
                    {{Form::text('date_from','',["class"=>"form-control datepicker", "required"=>true, "autocomplete"=>"off"])}}
                    <span class="errors">{{$errors->first('date_from')}}</span>
                </div>
                <div class="col-md-2 form-group">
                    <label>Date To</label><br>
                    {{Form::text('date_to','',["class"=>"form-control datepicker", "required"=>true, "autocomplete"=>"off"])}}
                    <span class="errors">{{$errors->first('date_to')}}</span>
                </div>
                <!-- <div class="col-md-2 form-group">
                    <label>Type</label><br>
                    {{Form::select('type',$types,'',["class"=>"form-control", "required"=>true, "autocomplete"=>"off"])}}
                    <span class="errors">{{$errors->first('date_to')}}</span>
                </div> -->
            </div>
            
            <div>
                <button class="btn blue">Generate</button>
            </div>
            {{Form::close()}}
        </div>
    </div>

    <div class="portlet box blue">
        <div class="portlet-title">
            <div class="caption">
                Generate SEBI Compliance MIS Report (Consolidated)</div>
            <div class="tools">
                <a href="javascript:;" class="collapse" data-original-title="" title=""> </a>
            </div>
        </div>

        <div class="portlet-body">
            {{Form::open(["url"=>"admin/mis-report/sebi-report/consolidated", "method"=>"post"])}}
            
            <div class="row">
                
                @if(Auth::user()->privilege == 2)
                <div class="col-md-4 form-group">
                    <label>Select Client</label>
                    {{Form::select('client_ids[]',[""=>"Select Client"]+$clients,'',["class"=>" selectize" , "required"=>true])}}
                    <span class="errors">{{$errors->first('client_id')}}</span>
                </div>
                @endif

                <div class="col-md-2 form-group">
                    <label>Date From</label><br>
                    {{Form::text('date_from','',["class"=>"form-control datepicker", "required"=>true, "autocomplete"=>"off"])}}
                    <span class="errors">{{$errors->first('date_from')}}</span>
                </div>
                <div class="col-md-2 form-group">
                    <label>Date To</label><br>
                    {{Form::text('date_to','',["class"=>"form-control datepicker", "required"=>true, "autocomplete"=>"off"])}}
                    <span class="errors">{{$errors->first('date_to')}}</span>
                </div>
            </div>
            
            <div>
                <button class="btn blue">Generate</button>
            </div>
            {{Form::close()}}
        </div>
    </div>

    <div class="portlet box blue">
        <div class="portlet-title">
            <div class="caption">
                Generate SEBI Compliance MIS Report - Quarter Wise (Consolidated)</div>
            <div class="tools">
                <a href="javascript:;" class="collapse" data-original-title="" title=""> </a>
            </div>
        </div>

        <div class="portlet-body">
            {{Form::open(["url"=>"admin/mis-report/sebi-report/consolidated?type=revised", "method"=>"post"])}}
            
            <div class="row">
                
                @if(Auth::user()->privilege == 2)
                <div class="col-md-4 form-group">
                    <label>Select Client</label>
                    {{Form::select('client_ids[]',[""=>"Select Client"]+$clients,'',["class"=>" selectize" , "required"=>true])}}
                    <span class="errors">{{$errors->first('client_id')}}</span>
                </div>
                @endif

                <div class="col-md-2 form-group">
                    <label>Date From</label><br>
                    {{Form::text('date_from','',["class"=>"form-control datepicker", "required"=>true, "autocomplete"=>"off"])}}
                    <span class="errors">{{$errors->first('date_from')}}</span>
                </div>
                <div class="col-md-2 form-group">
                    <label>Date To</label><br>
                    {{Form::text('date_to','',["class"=>"form-control datepicker", "required"=>true, "autocomplete"=>"off"])}}
                    <span class="errors">{{$errors->first('date_to')}}</span>
                </div>
            </div>
            
            <div>
                <button class="btn blue">Generate</button>
            </div>
            {{Form::close()}}
        </div>
    </div>

    <div class="portlet box blue hidden">
        <div class="portlet-title">
            <div class="caption">
                IRDA Compliance Report</div>
            <div class="tools">
                <a href="javascript:;" class="collapse" data-original-title="" title=""> </a>
            </div>
        </div>

        <div class="portlet-body">
            {{Form::open(["url"=>"admin/mis-report/irda-report", "method"=>"post"])}}
            
            <div class="row">
                @if(Auth::user()->privilege != 3)
                <div class="col-md-4 form-group">
                    <label>Select Client</label>
                    {{Form::select('irda_client_id',[""=>"select"]+$clients,'',["class"=>"form-control" , "required"=>true])}}
                    <span class="errors">{{$errors->first('irda_client_id')}}</span>
                </div>
                @endif
                <div class="col-md-4 form-group">
                    <label>Date From</label><br>
                    {{Form::text('irda_date_from','',["class"=>"form-control datepicker", "required"=>true , "autocomplete"=>"off"])}}
                    <span class="errors">{{$errors->first('irda_date_from')}}</span>
                </div>
                <div class="col-md-4 form-group">
                    <label>Date To</label><br>
                    {{Form::text('irda_date_to','',["class"=>"form-control datepicker", "required"=>true, "autocomplete"=>"off"])}}
                    <span class="errors">{{$errors->first('irda_date_to')}}</span>
                </div>
            </div>
            
            <div>
                <button class="btn blue">Generate</button>
            </div>
            {{Form::close()}}
        </div>
    </div>

    <div class="portlet box blue hidden">
        <div class="portlet-title">
            <div class="caption">
                Generate Internal MIS Report</div>
            <div class="tools">
                <a href="javascript:;" class="collapse" data-original-title="" title=""> </a>
            </div>
        </div>

        <div class="portlet-body">
            {{Form::open(["url"=>"admin/mis-report/internal-mis-report", "method"=>"post"])}}
            
            <div class="row">
                <div class="col-md-4 form-group">
                    <label>Select Client</label>
                    {{Form::select('client_id',[""=>"select"]+$clients,'',["class"=>"form-control" , "required"=>true])}}
                    <span class="errors">{{$errors->first('client_id')}}</span>
                </div>
                <div class="col-md-4 form-group">
                    <label>Date From</label><br>
                    {{Form::text('date_from','',["class"=>"form-control datepicker", "required"=>true, "autocomplete"=>"off"])}}
                    <span class="errors">{{$errors->first('date_from')}}</span>
                </div>
                <div class="col-md-4 form-group">
                    <label>Date To</label><br>
                    {{Form::text('date_to','',["class"=>"form-control datepicker", "required"=>true, "autocomplete"=>"off"])}}
                    <span class="errors">{{$errors->first('date_to')}}</span>
                </div>
                <div class="col-md-12 form-group">
                    <label>
                        {{Form::checkbox('proposed_by',1,'')}}&nbsp; Proposed By
                    </label>&nbsp;&nbsp;&nbsp;
                    <label>
                        {{Form::checkbox('man_reco',1,'')}}&nbsp; Investee company's Management Recommendation
                    </label>&nbsp;&nbsp;&nbsp;
                    <br>
                    <label>
                        {{Form::checkbox('admin_conflict',1,'')}}&nbsp; Admin conflict of Interest
                    </label>&nbsp;&nbsp;&nbsp;
                    <label>
                        {{Form::checkbox('admin_conflict_comment',1,'')}}&nbsp; Admin's Comment on conflict of Interest
                    </label>&nbsp;&nbsp;&nbsp;
                    <br>
                    <label>
                        {{Form::checkbox('pm_vote',1,'')}}&nbsp; PM Vote
                    </label>&nbsp;&nbsp;&nbsp;
                    <label>
                        {{Form::checkbox('pm_reason',1,'')}}&nbsp; PM Reason
                    </label>&nbsp;&nbsp;&nbsp;
                    <label>
                        {{Form::checkbox('pm_reason',1,'')}}&nbsp; PM Date
                    </label>&nbsp;&nbsp;&nbsp;
                    <label>
                        {{Form::checkbox('pm_reason',1,'')}}&nbsp; PM Conflict of Interest
                    </label>&nbsp;&nbsp;&nbsp;

                    <label>
                        {{Form::checkbox('pm_reason',1,'')}}&nbsp; PM Comment on Conflict of interest
                    </label>
                </div>

            </div>
            
            <div>
                <button class="btn blue">Generate</button>
            </div>
            {{Form::close()}}
        </div>
    </div>

@endsection