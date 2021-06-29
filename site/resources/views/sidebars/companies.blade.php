<li class="menu-item @if($sidebar == 'dashboard') menu-item-active @endif" >
    <a href="{{url('company/dashboard')}}" class="menu-link">
        <i class="menu-icon flaticon-home"></i>
        <span class="menu-text">Dashboard</span>
    </a>
</li>
<li class="menu-section">
    <h4 class="menu-text">Jobs</h4>
    <i class="menu-icon ki ki-bold-more-hor icon-md"></i>
</li>
<li class="menu-item @if($sidebar == 'job_offers') menu-item-active @endif" >
    <a href="{{url('company/job-offers')}}" class="menu-link">
        <i class="menu-icon flaticon-users"></i>
        <span class="menu-text">Job Offers</span>
    </a>
</li>


@if(Auth::id() == Auth::user()->parent_user_id)
<li class="menu-section">
    <h4 class="menu-text">User Management</h4>
    <i class="menu-icon ki ki-bold-more-hor icon-md"></i>
</li>

<li class="menu-item @if($sidebar == 'users') menu-item-active @endif" >
    <a href="{{url('company/users')}}" class="menu-link">
        <i class="menu-icon flaticon-users"></i>
        <span class="menu-text">Users</span>
    </a>
</li>
@endif

<li class="menu-section">
    <h4 class="menu-text">Other</h4>
    <i class="menu-icon ki ki-bold-more-hor icon-md"></i>
</li>