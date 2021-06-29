@extends('layout')

@section('content')

    <div class="page-title-cont">
	   <h2 class="page-title">Scheme Upload Status</h2>
    </div>

    <div>
        <table class="table table-sorterd table-bordered table-hower" id="datatable">
            <thead>
                <th>SN</th>
                <th>Date</th>
                <th>Schemes</th>
            </thead>
            <tbody>
                <?php $count =1;?>

                @foreach($entries as $entry)
                <tr id="entry_{{$entry->id}}">
                    <td>{{$count++}}</td>
                    <td>{{date("d-m-Y",strtotime($entry->date))}}</td>
                    
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

                            <br><a href="{{url('view-file/')}}?file={{urlencode($entry->scheme_response_file)}}" target="_blank" >Response</a>
                            @endif
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
