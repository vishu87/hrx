@if(sizeof($change_logs) > 0)
<div>
    <h4>Change Log</h4>
    <table class="table">
        <tr>
            <th>Field</th>
            <th>Old Value</th>
            <th>New Value</th>
            <th>User</th>
        </tr>
        @foreach($change_logs as $change_log)
        <tr>
            <td>{{$change_log->field_tag}}</td>
            <td style="color:#f00">{{$change_log->old_value}}</td>
            <td style="color:#1a891a"><b>{{$change_log->new_value}}</b></td>
            <td>{{$change_log->user_name}}</td>
        </tr>
        @endforeach
    </table>
</div>
@endif

<h4>Details</h4>
<table class="table">
    <tr>
        <th>Name</th>
        <td>{{$user->name}}</td>
    </tr>
    <tr>
        <th>Organization Name</th>
        <td>{{$user->organization_name}}</td>
    </tr>
    <tr>
        <th>Email</th>
        <td>{{$user->email}}</td>
    </tr>
    <tr>
        <th>Group Numbers</th>
        <td>{{$user->group_no}}</td>
    </tr>
</table>