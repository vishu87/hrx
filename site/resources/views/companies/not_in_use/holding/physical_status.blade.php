@extends('layout')

@section('content')

    <div class="page-title-cont">
	   <h2 class="page-title">Physical Holding Processing Status</h2>
    </div>

    <div>
        <table class="table table-sorterd table-bordered table-hower" id="datatable">
            <thead>
                <th>SN</th>
                <th>Holding Upload Date</th>
                <th>Holding Status</th>
                <th>Portfolio Update</th>
            </thead>
            <tbody>
                <?php $count =1;?>

                @foreach($entries as $entry)
                <tr id="entry_{{$entry->id}}">
                    <td>{{$count++}}</td>
                    <td>{{date("d-m-Y",strtotime($entry->date))}}</td>
                    <td>
                        @if($entry->status == -1)
                            Processing
                        @elseif($entry->status == -2)
                            Failed
                        @elseif($entry->status == 1)
                            Completed
                        @else
                            Pending
                        @endif

                        @if(in_array($entry->status,[1]))
                            @if(isset($response_file_dates[$entry->date]))
                                @foreach($response_file_dates[$entry->date] as $file)
                                <br><a href="{{url('view-file/')}}?file={{urlencode($file->response_file)}}" target="_blank" style="font-size:11px">Response ({{$file->updated_at}})</a>
                                @endforeach
                            @endif
                        @endif
                    </td>
                    <td>
                        @if($entry->reports == -1)
                            Failed
                        @elseif($entry->reports == 1)
                            Completed
                        @else
                            Pending
                        @endif

                        @if(in_array($entry->reports,[-1,1]))
                        <br>{{date("d-m-Y H:i:s",strtotime($entry->report_update))}}
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
