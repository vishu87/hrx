@extends('layout')
@section('content')
<div ng-controller="JobOfferCtrl" ng-init="getList();" class="ng-cloak">
   <div>
      <div class="row">
         <div class="col-md-8">
            <h2 class="page-title">Job Offers</h2>
         </div>
      </div>
   </div>
   <div class="card card-custom">
      <div class="card-body">

         <div class="filters mb-5">
            <div class="row">
               <div class="col-md-3">
                  <label>Company</label>
                  <select ng-model="filter.company_id" class="form-control">
                     <option value="">Select</option>
                     <option ng-value="item.value" ng-repeat="item in params.companies" > @{{item.label}} </option>
                  </select>
               </div>
               <div class="col-md-3">
                  <label>Offer date</label>
                  <div class="table-div">
                     <div>
                        <input type="text" ng-model="filter.offer_date_start" class="form-control datepicker">
                     </div>
                     <div>
                        <input type="text" ng-model="filter.offer_date_end" class="form-control datepicker">
                     </div>
                  </div>
               </div>
               <div class="col-md-3">
                  <label>Expected joining date</label>
                  <div class="table-div">
                     <div>
                        <input type="text" ng-model="filter.expected_joining_date_start" class="form-control datepicker">
                     </div>
                     <div>
                        <input type="text" ng-model="filter.expected_joining_date_end" class="form-control datepicker">
                     </div>
                  </div>
               </div>
               <div class="col-md-3">
                  <label>Status</label>
                  <select ng-model="filter.status" class="form-control">
                     <option value=""></option>
                     <option value="0">Active</option>
                     <option value="1">Withdrawn</option>
                     <option value="2">Joined</option>
                  </select>
               </div>
            </div>
            <div class="row mt-3">
               <div class="col-md-3">
                  <label>Req no</label>
                  <input type="text" ng-model="filter.req_no" class="form-control">
               </div>
               <div class="col-md-3">
                  <div style="margin-top: 25px;">
                     <button type="button" class="btn btn-primary" ng-click="searchList()" ladda="filter.searching">Search</button>
                     <button type="button" class="btn btn-dark" ng-click="clear()" ladda="filter.clearing">Clear</button>
                  </div>
               </div>
            </div>
         </div>

         <div table-paginate></div>
         <table class="table table-bordered table-hover mt-5">
            <thead>
               <tr>
                  <th>SN</th>
                  <th>
                     <th-sort column-name="Company " column-id="company_id" />
                  </th>
                  <th>
                     <th-sort column-name="Offer date" column-id="offer_date" />
                  </th>
                  <th>
                     <th-sort column-name="Candidate" column-id="can_id" />
                  </th>
                  <th>
                     <th-sort column-name="Expected joining date" column-id="expected_joining_date" />
                  </th>
                  <th>
                     <th-sort column-name="Status" column-id="status" />
                  </th>
                  <th>
                     <th-sort column-name="Req no" column-id="req_no" />
                  </th>
                  <th>
                     <th-sort column-name="Created at" column-id="created_at" />
                  </th>
                  
               </tr>
            </thead>
            <tbody>
               <tr ng-repeat="item in dataset track by $index">
                  <td>@{{ $index + (filter.page_no-1)*filter.max_per_page + 1 }}</td>
                  <td>
                     @{{ item.name }}
                  </td>
                  <td>
                     @{{ item.first_name }} @{{ item.last_name }}
                  </td>
                  <td>
                     @{{ item.offer_date }}
                  </td>
                  <td>
                     @{{ item.expected_joining_date }}
                  </td>
                  <td>
                     @{{ item.status_name }}
                  </td>
                  <td>
                     @{{ item.req_no }}
                  </td>
                  <td>
                     @{{ item.created_at }}
                  </td>
               </tr>
            </tbody>
         </table>
      </div>
   </div>
</div>

<script type="text/javascript">
   var can_id = {{ $can_id ? $can_id : 0 }};
</script>

@endsection