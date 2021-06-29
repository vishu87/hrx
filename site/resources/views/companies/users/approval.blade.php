<div>
    <table class="table table-sorterd table-bordered table-hower">
        <thead>
            <th>SN</th>
            <th>Created By</th>
            <th>Approved By</th>
            <th>Status</th>
            <th>Remarks</th>
            <th>Date</th>
        </thead>
        <tbody>
            <?php $count =1;?>
            @foreach($approval_logs as $approval_log)
            <tr id="approval_log_{{$approval_log->id}}">
                <td>{{$count++}}</td>
                <td>{{$approval_log->created_by_name}}</td>
                <td>{{$approval_log->approved_by_name}}</td>
                <td>{{$approval_log->getStatus()}}</td>
                <td>{{$approval_log->remarks}}</td>
                <td>{{ date("d-m-Y",strtotime($approval_log->created_at)) }}</td>
            </tr>
            @endforeach
            @if(sizeof($approval_logs) == 0)
            <tr>
                <td colspan="6">No records found</td>
            </tr>
            @endif
        </tbody>
    </table>
</div>