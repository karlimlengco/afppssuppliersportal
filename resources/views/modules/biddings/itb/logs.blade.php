@section('title')
Invitation To Bid
@stop


@section('breadcrumbs')

    @if(isset($breadcrumbs))
      @foreach($breadcrumbs as $route => $crumb)
        @if($crumb->hasLink())
        <a href="{{ $crumb->link() }}" class="topbar__breadcrumbs__item">{{ $crumb->title() }}</a>
        @else
        <a href="#" class="topbar__breadcrumbs__item">{{ $crumb->title() }}</a>
        @endif
      @endforeach
    @else
    <li><a href="#">Application</a></li>
    @endif

@stop

@section('contents')
<div class="row">
    <div class="twelve columns align-right utility utility--align-right">
        <a href="{{route($indexRoute, $rfq->id)}}" class="button button--pull-left" tooltip="Back">
            <i class="nc-icon-mini arrows-1_tail-left"></i>
        </a>
    </div>
</div>

<div>
    <div>
        <table class='table table--equal-column' id="item_table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Event</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $log)
                    @if($log->event != 'created' )
                        @if($log->old_values != "[]" && $log->new_values != "[]" )
                        <tr>
                            <td>{{($log->user) ? $log->user->first_name  ." ". $log->user->surname :" "}}</td>
                            <td>{{$log->event}}</td>
                            <td >{{$log->created_at}}</td>
                            {{-- <td> --}}
                              {{--   <table class="table table--with-border">
                                    <tr>
                                        <th></th>
                                        <th>Field</th>
                                        <th>Value</th>
                                    </tr> --}}
                                    @foreach( json_decode($log->old_values) as $oldKey => $oldValue )
                                        @if($oldKey != 'update_remarks')
                                        <tr style="background: #eee">
                                            <td>Old</td>
                                            <td>{{$oldKey}}</td>
                                            <td>{{$oldValue}}</td>
                                        </tr>
                                        @endif
                                    @endforeach
                                {{-- </table> --}}
                                {{-- <table class="table table--with-border"> --}}
                                    @foreach( json_decode($log->new_values) as $newKey => $newValue )
                                        <tr style="background: #eee">
                                            <td>New</td>
                                            <td>{{$newKey}}</td>
                                            <td>{{$newValue}}</td>
                                        </tr>
                                    @endforeach
                                {{-- </table> --}}
                            {{-- </td> --}}
                        </tr>
                        @endif
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@stop

@section('scripts')
@stop