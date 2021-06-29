<div>
    @if(isset($records[0]))
        <b>
            {{$records[0]->com_name}} |
            {{$records[0]->meeting_date}} |
            {{$records[0]->meeting_type}}
        </b>
    @endif

    @if(sizeof($records) > 0)
    <table class="table table-bordered" id="datatable" style="margin-top: 20px">
        <thead>
            <tr>
                <th>SN</th>
                <th>Scheme Name</th>
                <th style="width: 170px">DPID/CLIENTID</th>
                <th>Resolution Number</th>
                <th>Vote Recorded</th>
                <th>Your Vote</th>
                <!-- <th>Votes For</th> -->
                <!-- <th>Votes Against</th>
                <th>Votes Abstain</th> -->
                <!-- <th style="width: 150px">Status</th> -->
                <th style="width: 150px">Validity</th>
            </tr>
        </thead>
        <tbody>
            <?php $sn = 1; ?>
            @foreach($records as $record)
            <tr style="background: @if($record->votefile_record_id) @if($record->process_status == 1) #7df1a1 @else #f17d7d @endif @else #f17d7d @endif">
                <td>{{$sn++}}</td>
                <td>{{($record->scheme_name)?$record->scheme_name:"NOT FOUND"}}</td>
                <td>{{$record->dp_id.' / '.$record->client_id}}</td>
                <td>{{$record->resolution_no}}</td>
                <td>
                    @if($record->process_status == 1)
                        @if($record->vote_for > 0) FOR @endif
                        @if($record->vote_against > 0) AGAINST @endif
                        @if($record->vote_abstain > 0) ABSTAIN @endif
                    @endif
                </td>
                <td>
                    @if($record->vote == 1) FOR @endif
                    @if($record->vote == 2) AGAINST @endif
                    @if($record->vote == 3) ABSTAIN @endif
                </td>
                <!-- <td>{{$record->vote_for}}</td>
                <td>{{$record->vote_against}}</td>
                <td>{{$record->vote_abstain}}</td> -->
                <!-- <td>{{$record->status}}</td> -->
                <td>
                    @if($record->votefile_record_id)
                        @if($record->process_status == 1) MATCH @else MISMATCH @endif
                    @else
                        NOT FOUND
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
        <div class="alert alert-warning">No records found</div>
    @endif

    @if(sizeof($abstained) > 0)
        <hr>
        <h4 style="font-size: 14px; color: #888;">Since the client has cast "Abstain" votes for the below resolutions, there would be no response file available/the resolution would be missing from the response file - </h4>
        <table class="table table-bordered" id="datatable" style="margin-top: 20px">
            <thead>
                <tr>
                    <th>SN</th>
                    <th>Scheme Name</th>
                    <th>Resolution Number</th>
                </tr>
            </thead>
            <tbody>
                <?php $sn = 1; ?>
                @foreach($abstained as $abstain)
                <tr>
                    <td>{{$sn++}}</td>
                    <td>{{$abstain->scheme_name}}</td>
                    <td>{{$abstain->resolution_number}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    @if(sizeof($invalid_votes) > 0)
        <hr>
        <h4 style="font-size: 14px; color: #888;">Not Eligible</h4>
        <table class="table table-bordered" id="datatable" style="margin-top: 20px">
            <thead>
                <tr>
                    <th>SN</th>
                    <th>Scheme Name</th>
                    <th>Reason</th>
                </tr>
            </thead>
            <tbody>
                <?php $sn = 1; ?>
                @foreach($invalid_votes as $item)
                <tr>
                    <td>{{$sn++}}</td>
                    <td style="width:200px">{{$item->scheme_name}}</td>
                    <td style="font-size:13px">{{$item->quote}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif

</div>