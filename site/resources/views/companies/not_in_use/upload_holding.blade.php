@extends('layout')

@section('content')
<div class="page-title-cont">
    <h2 class="page-title">Upload Holding</h2>
</div>

@if($entries_remaining != 0)
    <div class="alert alert-warning">
        Kindly wait for {{round($entries_remaining/100)+1}} minutes.
    </div>
@else
<div ng-controller="HoldingUploadController" class="ng-cloak">
    <div class="row">
        <div class="col-md-4">
            <form role="form">
                @if($last_date)
                    <div style="color: #555">
                        You can update holding on or after {{$last_date}}
                    </div>
                @endif
                <div class="form-body mt-15">
                    <div class="form-group">
                        <label>Select Date</label>
                        <input type="text" class="form-control datepicker" placeholder="Date" autocomplete="off" ng-model="upload_info.date">
                    </div>
                    <div class="form-group">
                        <label>Shareholding file (<a href="{{url('formats/holding-format.xlsx')}}" target="_blank">Download Format</a>)</label><br>
                        <button type="button" ng-if="file_name == ''" class="btn green" ngf-select="uploadFile($file,'file')" ladda="uploading_file" data-style="expand-right" >Upload file</button>
                    
                        <div  ng-if="file_name != ''" class="text-center">
                            <a ng-href="@{{file_url}}" target="_blank" class="btn blue" style="display: block;">View file</a>
                            <a href="javascript:;" ng-click="removeFile()">Remove file</a>
                        </div>
                        <div class="text-center" ng-if="uploading_file">Uploading ... @{{uploading_percentage}}</div>
                    </div>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn blue btn-block"  ng-click="submitForm()">Submit</button>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn blue btn-block"  ng-click="convertFile()">Convert File</button>
                </div>
            </form>
        </div>
        <div class="col-md-8">
            <div class="process-log">
                <div style="background: #222; color: #FFF; padding: 10px; font-size: 12px ">Process Log</div>
                <div id="log">
                
                </div>
            </div>
        </div>
    </div>   
</div>
@endif

@endsection

@section('footer_scripts')

@endsection