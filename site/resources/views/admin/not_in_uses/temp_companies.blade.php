@extends('layout')

@section('content')
	
    <div class="page-title-cont">
        
    	<div class="row">
    		<div class="col-md-6">
    			<h2 class="page-title">Temporary Companies</h2>
    		</div>
    		
    	</div>
    </div>

    @if(Session::has("success"))
        <div class="alert alert-success">
            {{Session::get("success")}}
        </div>
    @endif
    @if(Session::has('failure'))
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <i class="fa fa-ban-circle"></i><strong>Failure!</strong> {{Session::get('failure')}}
        </div>
    @endif

       
    <div>
        
        <div class="text-right">
            <a href="{{url('/ses/temp-isin?type='.$type.'&export=1')}}" target="_blank" class="btn blue">Export</a>
        </div>

        <ul class="nav nav-tabs">
            <li @if($type == 1)class="active" @endif>
                <a href="{{url('ses/temp-isin?type=1')}}">Pending</a>
            </li>
            <li @if($type == 2)class="active" @endif>
                <a href="{{url('ses/temp-isin?type=2')}}">Mapped</a>
            </li>
            <li @if($type == 3)class="active" @endif>
                <a href="{{url('ses/temp-isin?type=3')}}">Skipped</a>
            </li>
        </ul>
        
        <div>
            {{Form::open(array("url" =>'/ses/temp-isin/skip', "method"=>"POST" ))}}
                <table class="table table-sorterd table-bordered table-hower" id="datatable">
                    <thead>
                        <th>SN</th>
                        <th>Temp Company ID</th>
                        <th>Company ISIN</th>
                        @if($type == 2)
                        <th>Company</th>
                        @endif
                        @if($type == 3)
                        <th>Reason</th>
                        @endif
                        <th>Last Updated</th>
                        <th>Created at</th>
                    </thead>
                    <tbody>
                        <?php $index = 1;?>
                        @foreach($temp_companies as $temp_comp)
                        <tr>
                            <td>
                                {{$index++}}
                                @if($type == 1)
                                    <input type="checkbox" name="temp_ids[]" value="{{$temp_comp->id}}" />
                                @endif
                            </td>
                            <td>
                                {{$temp_comp->temp_com_id}}

                            </td>
                            <td>{{$temp_comp->com_isin}}</td>
                            @if($type == 2)
                            <td>{{$temp_comp->com_name}}</td>
                            @endif
                            @if($type == 3)
                            <td>{{$temp_comp->reason}}</td>
                            @endif
                            <td>{{$temp_comp->updated_at}}</td>
                            <td>{{$temp_comp->created_at}}</td>
                            
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="row" style="margin:10px 0 20px 0">
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="reason" placeholder="Please input reason for marking skip" required />        
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn yellow" >Mark Skip</button>
                    </div>
                </div>
            {{Form::close()}}
        </div>
        
    </div>

@endsection