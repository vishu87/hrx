@extends('layout')
  
@section('content')
    
    <div class="row">
    	<div class="col-md-3 bg-info px-6 py-8 rounded-xl mr-7 mb-7 ml-3">
			<span class="svg-icon svg-icon-3x svg-icon-warning d-block my-2">
				<!--begin::Svg Icon | path:assets/media/svg/icons/Media/Equalizer.svg-->
				<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
					<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
						<rect x="0" y="0" width="24" height="24"></rect>
						<rect fill="#FFFFFF" opacity="0.3" x="13" y="4" width="3" height="16" rx="1.5"></rect>
						<rect fill="#FFFFFF" x="8" y="9" width="3" height="11" rx="1.5"></rect>
						<rect fill="#FFFFFF" x="18" y="11" width="3" height="9" rx="1.5"></rect>
						<rect fill="#FFFFFF" x="3" y="13" width="3" height="7" rx="1.5"></rect>
					</g>
				</svg>
				<!--end::Svg Icon-->
			</span>
			<a href="{{ url('admin/companies') }}" class="text-light font-weight-bold font-size-h6">
				{{$companies}} Total number of company
			</a>
		</div>

		<div class="col-md-3 bg-warning px-6 py-8 rounded-xl mr-7 mb-7">
			<span class="svg-icon svg-icon-3x svg-icon-warning d-block my-2">
				<!--begin::Svg Icon | path:assets/media/svg/icons/Media/Equalizer.svg-->
				<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
					<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
						<rect x="0" y="0" width="24" height="24"></rect>
						<path d="M12.7037037,14 L15.6666667,10 L13.4444444,10 L13.4444444,6 L9,12 L11.2222222,12 L11.2222222,14 L6,14 C5.44771525,14 5,13.5522847 5,13 L5,3 C5,2.44771525 5.44771525,2 6,2 L18,2 C18.5522847,2 19,2.44771525 19,3 L19,13 C19,13.5522847 18.5522847,14 18,14 L12.7037037,14 Z" fill="#FFFFFF" opacity="0.3"></path>
						<path d="M9.80428954,10.9142091 L9,12 L11.2222222,12 L11.2222222,16 L15.6666667,10 L15.4615385,10 L20.2072547,6.57253826 C20.4311176,6.4108595 20.7436609,6.46126971 20.9053396,6.68513259 C20.9668779,6.77033951 21,6.87277228 21,6.97787787 L21,17 C21,18.1045695 20.1045695,19 19,19 L5,19 C3.8954305,19 3,18.1045695 3,17 L3,6.97787787 C3,6.70173549 3.22385763,6.47787787 3.5,6.47787787 C3.60510559,6.47787787 3.70753836,6.51099993 3.79274528,6.57253826 L9.80428954,10.9142091 Z" fill="#FFFFFF"></path>
					</g>
				</svg>
				<!--end::Svg Icon-->
			</span>
			<a href="#" class="text-light font-weight-bold font-size-h6">
				{{$job_offers}} Total number of jobs
			</a>
		</div>

    </div>
    <div class="row">
    	<div class="col-md-12">
    		<div class="card card-custom ng-cloak">
	        <div class="card-header">
	        	<div class="card-title"><h3 class="card-label">Job Offers Details</h3></div>
	        </div>

	        <div class="fade-in card-body"> 
	            <table class="table table-hover" id="datatable">
	                <thead>
	                    <th>SN</th>
	                    <th>Candidate Name</th>
	                    <th>Email</th>
	                    <th>Phone No</th>
	                    <th>Company name</th>
	                    <th style="width: 170px;">Notification (Days)</th>
	                    <th>Created at</th>
	                </thead>
	                <tbody>
	                    <?php $count =1;?>
	                    @foreach($offers as $offer)
	                    <tr>
	                        <td>{{$count++}}</td>
	                        <td>{{$offer->candidate_name}}</td>
	                        <td>{{$offer->email}}</td>
	                        <td>{{$offer->phone_no}}</td>
	                   		<td>{{$offer->company_name}}</td>
	                   		<td style="text-align: center;">{{$offer->notification}}</td>
	                   		<td>{{date('d-m-Y',strtotime($offer->created_at))}}</td>
	                    </tr>
	                    @endforeach
	                </tbody>

	            </table>
	        </div>
    	</div>
	 	
	</div>

    </div>
@endsection