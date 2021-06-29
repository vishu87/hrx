@if($type == "registration")
    <p>
        Dear {{$user->name}},
    </p>
    <p>
        Your account has been created. You can login with following credentials<br>
        Weblink - <b>{{url('/')}}</b><br>
        Username - <b>{{$user->email}}</b><br>
        Password - <b>{{$password}}</b><br>
    </p>
    <p>
        Thanks.
    </p>
@endif

@if($type == "new_companies")
    <p>
        Dear SES Team,
    </p>

    <p>
        Following are some companies not present in our system - 
    </p>
    
    <table cellpadding="4" cellspacing="0" border="1">
        <tr>
            <th>SN</th>
            <th>Name</th>
            <th>ISIN</th>
            <th>Meeting Type</th>
            <th>Meeting Date</th>
            <th>Record Date</th>
        </tr>
        <?php $count = 1; ?>
        @foreach($new_companies as $new_company)
        <tr>
            <th>{{$count++}}</th>
            <th>{{($new_company["com_name"]) ? $new_company["com_name"]: 'NA'}}</th>
            <th>{{($new_company["isin"]) ? $new_company["isin"]: 'NA'}}</th>
            <th>{{($new_company["meeting_type"]) ? $new_company["meeting_type"]: 'NA'}}</th>
            <th>{{($new_company["record_date"]) ? $new_company["record_date"]: 'NA'}}</th>
            <th>{{($new_company["meeting_date"]) ? $new_company["meeting_date"]: 'NA'}}</th>
        </tr>
        @endforeach
    </table>
    
    <p>
        Thanks.
    </p>
@endif

@if($type == "resolution_request")
    <p>
        Dear SES Team,
    </p>

    <p>
        Following request has been made by {{Auth::user()->name}} from {{Auth::user()->organization_name}} : 
    </p>
    
    <table cellpadding="4" cellspacing="0" border="1">
        <tr>
            <th>Request Type</th>
            <th>Company Name</th>
            <th>Meeting Type</th>
            <th>Meeting Date</th>
        </tr>
        <tr>
            <th>Resolutions Request</th>
            <th>{{$meeting->com_name}}</th>
            <th>{{$meeting->meeting_type}}</th>
            <th>{{$meeting->meeting_date}}</th>
        </tr>
    </table>
    
    <p>
        Thanks.
    </p>
@endif

@if($type == "password_reset")
    <p>
        Dear {{$user->name}},
    </p>

    <p>
        Your password has been reset successfully, <b>{{$user->password_check}}</b> is your new password , <a target="_blank" href="{{url('/')}}">Click here </a> to login to your account
    </p>
    
    <p>
        Thanks.
    </p>
@endif

@if($type == "voting_status")
    <p>
        Dear User,
    </p>

    <p>
        Votes were approved for following entries between {{date("d-m-Y")}} 12:00PM to 06:00PM -
    </p>
    
    <table cellpadding="4" cellspacing="0" border="1">
        <tr>
            <th>SN</th>
            <th>Client Name</th>
            <th>Company</th>
            <th>ISIN</th>
            <th>Meeting</th>
            <th>Evoting Deadline</th>
        </tr>
        <?php $count = 1; ?>
        @foreach($records as $report)
        <tr>
            <td>{{$count++}}</td>
            <td>{{$report->user_name}}</td>
            <td>{{$report->com_full_name}}</td>
            <td>{{$report->com_isin}}</td>
            <td>{{$report->meeting_type." ".$report->meeting_date}}</td>
            <td>{{$report->evoting_end}}</td>
        </tr>
        @endforeach
        @if(sizeof($records) == 0)
        <tr>
            <td colspan="7">No records found</td>
        </tr>
        @endif
    </table>
    
    <p>
        Thanks.
    </p>
@endif

@if($type == "evoting_alert")
    <p>
        Dear User,
    </p>

    <p>
        Please find the attached eVoting alert file for upcoming meetings.
    </p>
    
    <p>
        Thanks.
    </p>
@endif

@if($type == "subscription_request")
    <p>
        Dear User,
    </p>

    <p>
        {{Auth::user()->name}} requested for subscription of Report ID - {{$report_id}}, Company Name - {{$com_name}}
    </p>
    
    <p>
        Thanks.
    </p>
@endif