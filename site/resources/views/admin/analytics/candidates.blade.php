@extends('layout')
@section('content')
<div ng-controller="CandidateCtrl" ng-init="getList();" class="ng-cloak">
   <div>
      <div class="row">
         <div class="col-md-8">
            <h2 class="page-title">Candidates</h2>
         </div>
      </div>
   </div>
   <div class="card card-custom">
      <div class="card-body">
         <div class="filters mb-5">
            <div class="row">
               <div class="col-md-3">
                  <label>Pan no</label>
                  <input type="text" ng-model="filter.pan_no" class="form-control">
               </div>
               <div class="col-md-3">
                  <label>First name</label>
                  <input type="text" ng-model="filter.first_name" class="form-control">
               </div>
               <div class="col-md-3">
                  <label>Last name</label>
                  <input type="text" ng-model="filter.last_name" class="form-control">
               </div>
               <div class="col-md-3">
                  <label>DOB</label>
                  <input type="text" ng-model="filter.dob" class="form-control datepicker">
               </div>
            </div>
            <div class="row mt-3">
               <div class="col-md-3">
                  <label>Email</label>
                  <input type="text" ng-model="filter.email" class="form-control">
               </div>
               <div class="col-md-3">
                  <label>Mobile</label>
                  <input type="text" ng-model="filter.mobile" class="form-control">
               </div>
               <div class="col-md-3">
                  <label>Offer Date</label>
                  <div class="table-div">
                     <div>
                        <input type="text" ng-model="filter.offer_date_start" class="datepicker form-control" />
                     </div>
                     <div>
                        <input type="text" ng-model="filter.offer_date_end" class="datepicker form-control" />
                     </div>
                  </div>
               </div>
               <div class="col-md-3">
                  <label>Joining Date</label>
                  <div class="table-div">
                     <div>
                        <input type="text" ng-model="filter.join_date_start" class="datepicker form-control" />
                     </div>
                     <div>
                        <input type="text" ng-model="filter.join_date_end" class="datepicker form-control" />
                     </div>
                  </div>
               </div>
               <div class="col-md-3">
                  <div style="margin-top: 25px;">
                     <button type="button" class="btn btn-primary" ng-click="searchList()" ladda="filter.searching">Search</button>
                     <button type="button" class="btn btn-warning" ng-click="clear()" ladda="filter.clearing">Clear</button>
                  </div>
               </div>
            </div>
         </div>
         <div table-paginate></div>
         <table class="table table-bordered table-hover ">
            <thead>
               <tr>
                  <th>SN</th>
                  <th>
                     <th-sort column-name="First name" column-id="first_name" />
                  </th>
                  <th>
                     <th-sort column-name="Last name" column-id="last_name" />
                  </th>
                  <th>
                     <th-sort column-name="DOB" column-id="dob" />
                  </th>
                  <th>
                     <th-sort column-name="Email" column-id="email" />
                  </th>
                  <th>
                     <th-sort column-name="Mobile" column-id="mobile" />
                  </th>
                  <th>
                     <th-sort column-name="Active offers" column-id="active_offers" />
                  </th>
               </tr>
            </thead>
            <tbody>
               <tr ng-repeat="item in dataset track by $index">
                  <td>@{{ $index + (filter.page_no-1)*filter.max_per_page + 1 }}</td>
                  <td>
                     @{{ item.first_name }}
                  </td>
                  <td>
                     @{{ item.last_name }}
                  </td>
                  <td>
                     @{{ item.dob }}
                  </td>
                  <td>
                     @{{ item.email }}
                  </td>
                  <td>
                     @{{ item.mobile }}
                  </td>
                  <td>
                     <a href="{{ url('analytics/job-offers?can_id=') }}@{{ item.id }}" target="_blank">
                        @{{ item.active_offers }}
                     </a>
                  </td>
               </tr>
            </tbody>
         </table>
      </div>
   </div>
</div>
@endsection