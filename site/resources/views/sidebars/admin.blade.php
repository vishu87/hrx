<li class="menu-item @if($sidebar == 'dashboard') menu-item-active @endif" aria-haspopup="true">
    <a href="{{url('admin/dashboard')}}" class="menu-link">
        <i class="menu-icon flaticon-home"></i>
        <span class="menu-text">Dashboard</span>
    </a>
</li>
<li class="menu-section">
    <h4 class="menu-text">User Management</h4>
    <i class="menu-icon ki ki-bold-more-hor icon-md"></i>
</li>
<li class="menu-item @if($sidebar == 'companies') menu-item-active @endif" aria-haspopup="true">
    <a href="{{url('admin/companies/')}}" class="menu-link">
        <i class="menu-icon flaticon-apps"></i>
        <span class="menu-text">Companies</span>
    </a>
</li>

@if(Auth::id() == Auth::user()->parent_user_id)
<li class="menu-item @if($sidebar == 'users') menu-item-active @endif" aria-haspopup="true">
    <a href="{{url('admin/users')}}" class="menu-link">
        <i class="menu-icon flaticon-users"></i>
        <span class="menu-text">Users</span>
    </a>
</li>
@endif


<li class="menu-section">
    <h4 class="menu-text">Analytics</h4>
    <i class="menu-icon ki ki-bold-more-hor icon-md"></i>
</li>

<li class="menu-item @if($sidebar == 'job-offers') menu-item-active @endif">
    <a href="{{url('analytics/job-offers')}}" class="menu-link">
        <i class="menu-icon flaticon-feed"></i>
        <span class="menu-text">Job Offers</span>
    </a>
</li>

<li class="menu-item @if($sidebar == 'candidates') menu-item-active @endif">
    <a href="{{url('analytics/candidates')}}" class="menu-link">
        <i class="menu-icon flaticon-users"></i>
        <span class="menu-text">Candidates</span>
    </a>
</li>

<li class="menu-item @if($sidebar == 'activities') menu-item-active @endif">
    <a href="{{url('analytics/activities')}}" class="menu-link">
        <i class="menu-icon flaticon-calendar"></i>
        <span class="menu-text">Activities</span>
    </a>
</li>

<li class="menu-section">
    <h4 class="menu-text">Other</h4>
    <i class="menu-icon ki ki-bold-more-hor icon-md"></i>
</li>
<!-- <li class="menu-item @if($sidebar == 'clients') menu-item-active @endif">
    <a href="{{url('admin/temp-isin')}}" class="menu-link">
        <i class="menu-icon flaticon-list"></i>
        <span class="menu-text">Temp ISIN</span>
    </a>
</li> -->