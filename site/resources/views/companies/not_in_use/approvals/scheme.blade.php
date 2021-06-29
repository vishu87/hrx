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
        <td>{{$scheme->scheme_name}}</td>
    </tr>
    <tr>
        <th>DP ID</th>
        <td>{{$scheme->dp_id}}</td>
    </tr>
    <tr>
        <th>Client ID</th>
        <td>{{$scheme->client_id}}</td>
    </tr>
    <tr>
        <th>User</th>
        <td>{{$scheme->user_name}}</td>
    </tr>
    <tr>
        <th>Depository</th>
        <td>{{$scheme->depository}}</td>
    </tr>
</table>