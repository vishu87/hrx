@extends('layout')
@section('content')
<div ng-controller="ActivitiesCtrl" ng-init="getList();" class="ng-cloak">
   <div>
      <div class="row">
         <div class="col-md-8">
            <h2 class="page-title">Analytics</h2>
         </div>
         <div class="col-md-4 text-right">
            <a class="btn blue" href="{{url('/activities/add')}}">Add New</a>
         </div>
      </div>
   </div>
   <div class="card card-custom">
      <div class="card-body">
         <div class="filters pb-5">
            <div class="row">
               <div class="col-md-3">
                  <label>Company</label>
                  <select ng-model="filter.company_id" class="form-control">
                     <option value="">Select</option>
                     <option ng-value="item.value" ng-repeat="item in params.companies" > @{{item.label}} </option>
                  </select>
               </div>
               <div class="col-md-3">
                  <label>User id</label>
                  <select ng-model="filter.user_id" class="form-control">
                     <option value="">Select</option>
                     <option ng-value="item.value" ng-repeat="item in params.user_names" ng-if="filter.company_id == item.company_id"> @{{item.label}} </option>
                  </select>
               </div>
               <!-- <div class="col-md-3">
                  <label>Activity</label>
                  <input type="text" ng-model="filter.activity" class="form-control">
               </div> -->
               <div class="col-md-3">
                  <label>Activity Date</label>
                  <div class="table-div">
                     <div>
                        <input type="text" ng-model="filter.created_start" class="form-control datepicker">
                     </div>
                     <div>
                        <input type="text" ng-model="filter.created_end" class="form-control datepicker">
                     </div>
                  </div>
               </div>
            </div>
            <div class="row mt-4">
               <div class="col-md-3">
                  <label>Group By</label>
                  <select class="form-control" ng-model="filter.group_by">
                     <option value=""></option>
                     <option value="user">User</option>
                     <option value="company">Company</option>
                  </select>
               </div>
               <div class="col-md-3">
                  <div style="margin-top: 25px;">
                     <button type="button" class="btn btn-primary" ng-click="searchList()" ladda="filter.searching">Search</button>
                     <button type="button" class="btn btn-secondary" ng-click="clear()" ladda="filter.clearing">Clear</button>
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
                     <th-sort column-name="Company" column-id="company_id" />
                  </th>
                  <th>
                     <th-sort column-name="User" column-id="user_id" />
                  </th>
                  <th>
                     <th-sort column-name="Activity" column-id="activity" />
                  </th>
                  <th>
                     <th-sort column-name="Timestamp" column-id="created_at" />
                  </th>
               </tr>
            </thead>
            <tbody>
               <tr ng-repeat="item in dataset track by $index">
                  <td>@{{ $index + (filter.page_no-1)*filter.max_per_page + 1 }}</td>
                  <td>
                     @{{item.company_name}}
                  </td>
                  <td>
                     @{{ item.name }}
                  </td>
                  <td>
                     @{{ item.activity }}
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
@endsection