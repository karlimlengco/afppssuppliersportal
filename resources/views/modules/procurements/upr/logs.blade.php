@section('title')
Unit Purchase Request
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
        <a href="{{route($indexRoute, $upr->id)}}" class="button button--pull-left" tooltip="Back">
            <i class="nc-icon-mini arrows-1_tail-left"></i>
        </a>
    </div>
</div>

<h1>{{$upr->upr_number}}</h1>
<div>
    <div>
        <table class='table table--equal-column' id="item_table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Action</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $log)

                    @if($log->event == 'created' ||  $log->old_values != "[]" && $log->new_values != "[]" )
                    <tr>
                        <td>{{($log->user) ? $log->user->first_name  ." ". $log->user->surname :" "}}</td>
                        <td>{{$log->event}}</td>
                        <td >{{$log->created_at}}</td>

                        @if($log->event != 'created' )
                        @foreach( json_decode($log->old_values) as $oldKey => $oldValue )
                            @if($oldKey != 'update_remarks')
                            <tr style="background: #f0a35f; ">
                                <td>Old</td>
                                <td>{{$oldKey}}</td>
                                <td>
                                    @if($oldKey == 'procurement_office' )
                                        {{( $office = \Revlv\Settings\ProcurementCenters\ProcurementCenterEloquent::where('id',$oldValue)->first() ) ? $office->name : "" }}
                                    @elseif($oldKey == 'mode_of_procurement' )

                                        {{( $procs = \Revlv\Settings\ModeOfProcurements\ModeOfProcurementEloquent::where('id',$oldValue)->first() ) ? $procs->name : "" }}
                                    @elseif($oldKey == 'chargeability' )

                                        {{( $charges = \Revlv\Settings\Chargeability\ChargeabilityEloquent::where('id',$oldValue)->first() ) ? $charges->name : "" }}
                                    @elseif($oldKey == 'new_account_code' )

                                        {{( $accounts = \Revlv\Settings\AccountCodes\AccountCodeEloquent::where('id',$oldValue)->first() ) ? $accounts->new_account_code : "" }}
                                    @elseif($oldKey == 'procurement_type' )

                                        {{( $types = \Revlv\Settings\ProcurementTypes\ProcurementTypeEloquent::where('id',$oldValue)->first() ) ? $types->code : "" }}
                                    @elseif($oldKey == 'terms_of_payment' )

                                        {{( $terms = \Revlv\Settings\PaymentTerms\PaymentTermEloquent::where('id',$oldValue)->first() ) ? $terms->name : "" }}
                                    @elseif($oldKey == 'units' )

                                        {{( $terms = \Revlv\Settings\Units\UnitEloquent::where('id',$oldValue)->first() ) ? $terms->name : "" }}
                                    @else
                                        {{$oldValue}}
                                    @endif
                                </td>
                            </tr>
                            @endif
                        @endforeach

                        @foreach( json_decode($log->new_values) as $newKey => $newValue )
                            <tr style="background: #99ffc4;">
                                <td>New</td>
                                <td>
                                    {{$newKey}}
                                </td>
                                @if(is_object($newValue))
                                <td>{{json_encode($newValue)}}</td>
                                @else
                                <td>
                                    @if($newKey == 'procurement_office' )
                                        {{( $office = \Revlv\Settings\ProcurementCenters\ProcurementCenterEloquent::where('id',$newValue)->first() ) ? $office->name : "" }}
                                    @elseif($newKey == 'mode_of_procurement' )

                                        {{( $procs = \Revlv\Settings\ModeOfProcurements\ModeOfProcurementEloquent::where('id',$newValue)->first() ) ? $procs->name : "" }}
                                    @elseif($newKey == 'chargeability' )

                                        {{( $charges = \Revlv\Settings\Chargeability\ChargeabilityEloquent::where('id',$newValue)->first() ) ? $charges->name : "" }}
                                    @elseif($newKey == 'new_account_code' )

                                        {{( $accounts = \Revlv\Settings\AccountCodes\AccountCodeEloquent::where('id',$newValue)->first() ) ? $accounts->new_account_code : "" }}
                                    @elseif($newKey == 'procurement_type' )

                                        {{( $types = \Revlv\Settings\ProcurementTypes\ProcurementTypeEloquent::where('id',$newValue)->first() ) ? $types->code : "" }}
                                    @elseif($newKey == 'terms_of_payment' )

                                        {{( $terms = \Revlv\Settings\PaymentTerms\PaymentTermEloquent::where('id',$newValue)->first() ) ? $terms->name : "" }}
                                    @elseif($newKey == 'units' )

                                        {{( $terms = \Revlv\Settings\Units\UnitEloquent::where('id',$newValue)->first() ) ? $terms->name : "" }}
                                    @else
                                        {{$newValue}}
                                    @endif
                                </td>
                                @endif
                            </tr>
                        @endforeach
                        @endif
                    </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@stop

@section('scripts')
@stop