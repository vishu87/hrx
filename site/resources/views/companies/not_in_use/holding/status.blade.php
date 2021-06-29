@extends('layout')

@section('content')

    <div class="page-title-cont">
	   <h2 class="page-title">Holding Processing Status</h2>
    </div>

    <div>
        <table class="table table-sorterd table-bordered table-hower" id="datatable">
            <thead>
                <th>SN</th>
                <th>Holding Upload Date</th>
                <!-- <th>Schemes</th> -->
                <th>CDSL Holding</th>
                <th>NSDL Holding</th>
                <th>Portfolio Update</th>
            </thead>
            <tbody>
                <?php $count =1;?>

                @foreach($entries as $entry)
                <tr id="entry_{{$entry->id}}">
                    <td>{{$count++}}</td>
                    <td>{{date("d-m-Y",strtotime($entry->date))}}</td>
                    <!--
                    <td>
                        @if($entry->schemes == -1)
                            Failed
                        @elseif($entry->schemes == 1)
                            Completed
                        @else
                            NA
                        @endif

                        @if(in_array($entry->schemes,[-1,1]))
                            <br>{{date("d-m-Y H:i:s",strtotime($entry->scheme_update))}}
                            @if($entry->scheme_response_file)
                            <br><a href="{{url($entry->scheme_response_file)}}" target="_blank">Response</a>
                            @endif
                        @endif
                    </td>
                    !-->
                    <td>
                        @if($entry->cdsl_holding == -1)
                            Processing
                        @elseif($entry->cdsl_holding == -2)
                            Failed
                        @elseif($entry->cdsl_holding == 1)
                            Completed
                        @else
                            Pending
                        @endif

                        @if(in_array($entry->cdsl_holding,[1]))
                            @if(isset($response_file_dates[$entry->date]))
                                @foreach($response_file_dates[$entry->date] as $file)
                                    @if($file->type == "CDSL")
                                        <br><a href="{{url('view-file/')}}?file={{urlencode($file->response_file)}}" target="_blank" style="font-size:11px">Response ({{$file->updated_at}})</a>
                                    @endif
                                @endforeach
                            @endif
                        @endif
                    </td>
                    <td>
                        @if($entry->nsdl_holding == -1)
                            Processing
                        @elseif($entry->nsdl_holding == -2)
                            Failed
                        @elseif($entry->nsdl_holding == 1)
                            Completed
                        @else
                            Pending
                        @endif

                        @if(in_array($entry->nsdl_holding,[1]))
                            @if(isset($response_file_dates[$entry->date]))
                                @foreach($response_file_dates[$entry->date] as $file)
                                    @if($file->type == "NSDL")
                                        <br><a href="{{url('view-file/')}}?file={{urlencode($file->response_file)}}" target="_blank" style="font-size:11px">Response ({{$file->updated_at}})</a>
                                    @endif
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
                        <br>{{date("d-m-Y H:i:s",strtotime($entry->reports_update))}}
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
