
<div >

    @if(sizeof($alerts) > 0)
    	
    	@foreach($meetings as $num_days => $meeting_ar)
    	<div style="margin-top: 30px;">
    		<b>Meeting alerts for {{$num_days}} day{{($num_days > 1)?'s':''}}</b>
    	</div>
    	<table class="table table-bordered">
    		<tr>
    			<th>SN</th>
    			<th>ISIN</th>
    			<th>Company Name</th>
    			<th>Meeting Type</th>
    			<th>Meeting Date</th>
    			<th>Evoting End</th>
    			<th>DB Deadline</th>
    		</tr>
    		<?php $count = 1; ?>
    		@foreach($meeting_ar as $meeting)
    		<tr>
    			<td>{{$count++}}</td>
    			<td>{{$meeting->com_isin}}</td>
    			<td>{{$meeting->com_name}}</td>
    			<td>{{$meeting->meeting_type_name}}</td>
    			<td>{{$meeting->meeting_date}}</td>
    			<td>{{$meeting->evoting_end}}</td>
    			<td>{{$meeting->deadline_date}}</td>
    		</tr>
    		@endforeach
    		@if(sizeof($meeting_ar) == 0)
    		<tr>
    			<td colspan="7">No meetings found</td>
    		</tr>
    		@endif
    	</table>
    	@endforeach
    @endif
</div>