@extends('layout')

@section('content')
	<div ng-controller="voteCtrl">
        
        <div class="page-title-cont">
        	<div class="row">
        		<div class="col-md-6">
        			<h2 class="page-title">Response Files</h2>
        		</div>
        	</div>
        </div>
    	
        <table class="table table-bordered" id="datatable">
            <thead>
                <tr>
                    <th>SN</th>
                    <th>Date</th>
                    <th>Response File</th>
                    <th>Company Name</th>
                    <th>Meeting Date</th>
                    <th>Meeting Type</th>
                    <th>Uploaded By</th>
                    <th>Status</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
                <?php $sn = 1; ?>
                @foreach($files as $file)
                
                <tr>
                    <td>{{$sn++}}</td>
                    <td>{{$file->updated_at}}</td>
                    <td>
                        <a href="{{url('view-file/')}}?file={{urlencode($file->response_file)}}" target="_blank">View</a>
                    </td>
                    <td>{{$file->com_name}}</td>
                    <td>{{$file->meeting_date}}</td>
                    <td>{{$file->meeting_type}}</td>
                    <td>{{$file->user_name}}</td>
                    <td>
                        @if($file->status == -1) Failed @endif
                        @if($file->status == 1) Completed @endif
                        @if($file->status == 0) Pending @endif
                    </td>
                    <td>
                        @if($file->status == -1)
                            {{$file->remarks}}
                        @endif

                        @if($file->status == 1)
                            <a href="javascript:;" class="details" data-title="Reponse File Log" action="admin/response-file/records/show/{{$file->id}}">Details</a>
                        @endif

                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>
	
@endsection