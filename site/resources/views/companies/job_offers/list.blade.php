@extends('layout')

@section('content')
    
    <div class="card card-custom ng-cloak">
        <div class="card-header">
        	<div class="card-title"><h3 class="card-label">Job Offers</h3></div>
            <div class="card-toolbar">
                <a href="{{url('/company/job-offers/add/')}}" class="btn btn-primary" >Add New</a>
            </div>
        </div>

        <div class="fade-in card-body">


            @if(Session::has('success'))
                <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <i class="fa fa-ban-circle"></i><strong>Success!</strong> {{Session::get('success')}}
                </div>
            @endif
            @if(Session::has('failure'))
                <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <i class="fa fa-ban-circle"></i><strong>Failure!</strong> {{Session::get('failure')}}
                </div>
            @endif
         
            <table class="table table-hover" id="datatable">
                <thead>
                    <th>SN</th>
                    <th>Candidate Name</th>
                    <th>Email</th>
                    <th>Phone No</th>
                    <th style="width: 100px">#</th>
                </thead>
                <tbody>
                    <?php $count =1;?>
                    @foreach($offers as $offer)
                    <tr id="client_{{$offer->id}}">
                        <td>{{$count++}}</td>
                        <td>{{$offer->candidate_name}}</td>
                        <td>{{$offer->email}}</td>
                        <td>{{$offer->phone_no}}</td>
                        <td>
                            <a href="{{url('/company/job-offers/add/?id='.$offer->id)}}" class="btn btn-sm btn-warning"> Edit</a>

                            <a class="btn btn-icon btn-sm btn-danger" href="{{url('company/job-offers/delete/'.$offer->id)}}" onclick="return confirm('Are you sure to delete?');" ><i class="fa fa-close" style="font-size: 14px;"></i></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
	</div>

@endsection