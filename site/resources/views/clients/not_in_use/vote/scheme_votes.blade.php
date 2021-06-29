<table class="table table-bordered">
    <thead>
        <tr>
            <th>SN</th>
            <th>Resolution Name</th>
            <th style="width: 150px">Your Vote</th>
            <th style="width: 150px">Rationale</th>
        </tr>
    </thead>
    <tbody>
        <?php $sn = 1; ?>
        @foreach($resolutions as $resolution)
        <tr >
            <td>{{$resolution->resolution_number}}</td>
            <td>
                <b>{{$resolution->resolution_name}}</b><br>
                <span style="font-size:12px">Management Recommendation - {{$resolution->man_reco}}</span>
            </td>
            <td>
                {{$resolution->vote_value}}
            </td>
            <td>
                {{$resolution->comment}}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>