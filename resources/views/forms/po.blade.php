<!DOCTYPE html>
<html lang="en">
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link rel="stylesheet" href="{{base_path('fonts/Nunito_Sans/css/nunitosans.css')}}">
        <link rel="stylesheet" href="{{base_path('public/css/main.css')}}">
        <style type="text/css">
            body{
                margin:0;
            }
        </style>
    </head>

    <body>
        <div class="printable-form"  style="padding-top:20px">

            <!-- form header -->
            <div class="printable-form__head">
                <p class="printable-form__head__vision">AFP Vision 2028: A World-class Armed Forces, Source of National Pride</p>
            </div>
            <!-- form body -->
            <div class="printable-form__body boxed">
                <!-- letterhead -->
                <div class="printable-form__letterhead">
                    <span class="printable-form__letterhead__details">
                        {!! $data['unitHeader']!!}
                    </span>
                </div>
                <!-- title -->
              <span class="printable-form__body__title">{{str_replace('_', ' ', $data['type'])}}</span>
                <!-- content -->
                <table class="printable-form__body__table">
                    <tr>
                        <td class="no-border-bottom" colspan="4"></td>
                    </tr>
                    <tr>
                        <td class="border-left-only" width="80px" nowrap>Supplier</td>
                        <td class="no-border" width="50%"><strong>{{$data['winner']->name}}</strong></td>
                        <td class="no-border" width="80px" nowrap>P.O. No.</td>
                        <td class="border-right-only" width="30%"><strong>{{$data['po_number']}}</strong></td>
                    </tr>
                    <tr>
                        <td class="border-left-only" nowrap>Address</td>
                        <td class="no-border"><strong>{{$data['winner']->address}}</strong></td>
                        <td class="no-border" nowrap>Date</td>
                        <td class="border-right-only"><strong>{{\Carbon\Carbon::createFromFormat('!Y-m-d',$data['purchase_date'])->format('d F Y')}}</strong></td>
                    </tr>
                    <tr>
                        <td class="border-left-only" nowrap>Email Address</td>
                        <td class="no-border"><strong>{{$data['winner']->email_1}}</strong></td>
                        <td class="no-border" nowrap>Proc Mode</td>
                        <td class="border-right-only"><strong>{{$data['mode']}}</strong></td>
                    </tr>
                    <tr>
                        <td class="border-left-only" nowrap>Telephone No</td>
                        <td class="no-border"><strong>{{$data['winner']->phone_1}}</strong></td>
                        <td class="no-border" nowrap>{{-- Account Code --}}</td>
                        <td class="border-right-only"><strong>{{-- {{$data['accounts']}} --}}</strong></td>
                    </tr>
                    <tr>
                        <td class="border-left-only" nowrap>TIN</td>
                        <td class="border-right-only" colspan="3"><strong>{{$data['winner']->tin}}</strong></td>
                    </tr>
                    <tr>
                        <td class="no-border-top no-border-bottom" colspan="4"></td>
                    </tr>
                    <tr>
                        <td colspan="4">
                            Sir/Madam,<br>Please furnish this office the following articles subject to the terms and conditions contained herein:
                        </td>
                    </tr>
                    <tr>
                        <td class="no-border-top no-border-bottom" colspan="4"></td>
                    </tr>
                    <tr>
                        <td class="border-left-only" nowrap>Place of Delivery</td>
                        <td class="no-border"><strong>{{$data['unit']}}</strong></td>
                        <td class="no-border" nowrap>Delivery Schedule</td>
                        <td class="border-right-only"><strong>{{($data['delivery']) ? $data['delivery']->expected_date : ""}}</strong></td>
                    </tr>
                    <tr>
                        <td class="border-left-only" nowrap>Delivery Term</td>
                        <td class="no-border"><strong>{{$data['delivery_term']}} Calendar Days upon conformed of this NOTICE TO PROCEED</strong></td>
                        <td class="no-border" nowrap>Payment Term</td>
                        <td class="border-right-only"><strong>{{$data['term']}}</strong></td>
                    </tr>
                    <tr>
                        <td class="no-border-top no-border-bottom" colspan="4"></td>
                    </tr>
                </table>
                <table class="printable-form__body__table">
                    <tr>
                        <td class="align-center" width="10%"><strong>ITEM NO</strong></td>
                        <td class="align-center" width="10%"><strong>UOM</strong></td>
                        <td class="align-center" width="40%"><strong>DESCRPTION</strong></td>
                        <td class="align-center" width="10%"><strong>QTY</strong></td>
                        <td class="align-center" width="15%"><strong>UNIT COST</strong></td>
                        <td class="align-center" width="15%"><strong>AMOUNT</strong></td>
                    </tr>

                    <?php $total = 0; ?>
                    <?php $count = 1; ?>
                    @foreach($data['items'] as $key=>$item)
                        @if($data['type'] != 'contract' && $data['type'] != 'contract_and_po')
                            <tr>
                                <td class="align-center">{{$count++}}</td>
                                <td class="align-center">{{$item->unit}}</td>
                                <td class="align-left">{{$item->description}}</td>
                                <td class="align-center">{{$item->quantity}}</td>
                                <td class="align-right">{{formatPrice($item->price_unit)}}</td>
                                <td class="align-right">{{formatPrice($item->total_amount)}}</td>
                                <?php $total += $item->total_amount; ?>
                            </tr>
                        @elseif($item->type != 'contract')
                            <tr>
                                <td class="align-center">{{$count++}}</td>
                                <td class="align-center">{{$item->unit}}</td>
                                <td class="align-left">{{$item->description}}</td>
                                <td class="align-center">{{$item->quantity}}</td>
                                <td class="align-right">{{formatPrice($item->price_unit)}}</td>
                                <td class="align-right">{{formatPrice($item->total_amount)}}</td>
                                <?php $total += $item->total_amount; ?>
                            </tr>
                        @endif
                    @endforeach
                    <tr>
                        <td class="align-center" colspan="4"><strong style="text-transform:uppercase">{{translateToWords($total)}} PESOS ONLY.</strong></td>
                        <td colspan="2" class="align-right"><strong>{{formatPrice($total)}}</strong></td>
                    </tr>
                </table>
                <table class="printable-form__body__table">
                    <tr>
                        <td colspan="6">
                            BASIS: {{$data['purpose']}}<br>
                            REFERENCES: UPR No. {{$data['upr_number']}}
                        </td>
                    </tr>
                    <tr>
                        <td class="align-top no-border-bottom" colspan="6" height="40px">In case of failure to make the full delivery within the time specified above, a penalty of one-tenth (1/10) of one percent (1%) for every day of delay shall be imposed.</td>
                    </tr>
                    <tr>
                        <td class="no-border" colspan="6" height="40px"</td>
                    </tr>
                    <tr>
                        <td class="border-left-only" width="45%"></td>
                        <td class="no-border" width="10%"></td>
                        <td class="border-right-only" width="45%">
                            @if($data['requestor'] != null)
                            Very truly yours,
                            <br>
                            <br>
                            <br>
                            <strong class="margin-top">{{$data['requestor'][1]}} {{$data['requestor'][0]}} {{$data['requestor'][2]}}</strong>
                            <br>
                            {{$data['requestor'][3]}}
                            @endif
                        </td>
                    </tr>
                </table>
                <div style="page-break-inside: avoid;">
                <table class="printable-form__body__table">
                    <tr>
                        <td class="border-left-only" width="9%" height="80px"></td>
                        <td class="no-border" width="1%" height="40px">Conforme:</td>
                        <td class="border-bottom-only" width="40%" height="80px"></td>
                        <td class="border-right-only" width="50%" height="80px"></td>
                    </tr>
                    <tr>
                        <td class="border-left-only" colspan="2" height="80px"></td>
                        <td class="align-center no-padding border-bottom-only" height="80px">Signature over printed name of Supplier</td>
                        <td class="border-right-only" height="80px"></td>
                    </tr>
                    <tr>
                        <td class="border-left-only" colspan="2"></td>
                        <td class="align-center no-padding no-border">Date</td>
                        <td class="border-right-only"></td>
                    </tr>
                    <tr>
                        <td class="no-border-top" colspan="4"></td>
                    </tr>
                </table>
                <table class="printable-form__body__table">
                    <tr>
                        <td class="border-left-only" width="13%" height="30px">Funds Available:</td>
                        <td class="no-border" width="1%"></td>
                        <td class="no-border" width="40%"></td>
                        <td class="no-border" width="15%"></td>
                        <td class="no-border" width="1%"></td>
                        <td class="no-border" width="20%"></td>
                        <td class="border-right-only" width="10%"></td>
                    </tr>
                    <tr>
                        <td class="border-left-only" width="13%" height="60px"></td>
                        <td class="no-border" width="1%"></td>
                        <td class="no-border" width="40%"></td>
                        <td class="no-border" width="15%"></td>
                        <td class="no-border" width="1%"></td>
                        <td class="no-border" width="20%"></td>
                        <td class="border-right-only" width="10%"></td>
                    </tr>
                    <tr>
                        <td class="no-padding border-left-only"></td>
                        <td class="no-padding no-border" colspan="2"><strong>{{$data['accounting'][1]}} {{$data['accounting'][0]}} {{$data['accounting'][2]}}</strong></td>
                        <td class="no-padding no-border"></td>
                        <td class="no-padding no-border" nowrap>OBR/BUR No</td>
                        <td class="no-padding border-bottom-only"></td>
                        <td class="no-padding border-right-only"></td>
                    </tr>
                    <tr>
                        <td class="no-padding border-left-only"></td>
                        <td class="no-padding no-border" colspan="2">{{$data['accounting'][3]}}</td>
                        <td class="no-padding no-border"></td>
                        <td class="no-padding no-border"></td>
                        <td class="no-padding no-border"></td>
                        <td class="no-padding border-right-only"></td>
                    </tr>
                    <tr>
                        <td class="no-padding border-left-only"></td>
                        <td class="no-padding no-border">Date</td>
                        <td class="no-padding border-bottom-only"></td>
                        <td class="no-padding no-border"></td>
                        <td class="no-padding no-border" nowrap>Amount</td>
                        <td class="no-padding border-bottom-only"></td>
                        <td class="no-padding border-right-only"></td>
                    </tr>
                    <tr>
                        <td class="no-border-top" colspan="7" height="20px"></td>
                    </tr>
                </table>
                <table class="printable-form__body__table">
                    <tr>
                        <td class="border-left-only" width="12%" height="30px">Approved By:</td>
                        <td class="no-border" width="40%"></td>
                        <td class="no-border" width="19%"></td>
                        <td class="no-border" width="1%"></td>
                        <td class="no-border" width="20%"></td>
                        <td class="border-right-only" width="8%"></td>
                    </tr>
                    <tr>
                        <td class="border-left-only" width="12%" height="60px"></td>
                        <td class="no-border" width="40%"></td>
                        <td class="no-border" width="19%"></td>
                        <td class="no-border" width="1%"></td>
                        <td class="no-border" width="20%"></td>
                        <td class="border-right-only" width="8%"></td>
                    </tr>
                    <tr>
                        <td class="no-padding border-left-only"></td>
                        <td class="no-padding no-border"><strong>{{$data['approver'][1]}} {{$data['approver'][0]}} {{$data['approver'][2]}}</strong></td>
                        <td class="no-padding no-border"></td>
                        <td class="no-padding no-border" nowrap>Date</td>
                        <td class="no-padding border-bottom-only"></td>
                        <td class="no-padding border-right-only"></td>
                    </tr>
                    <tr>
                        <td class="no-padding border-left-only"></td>
                        <td class="no-padding no-border">{{$data['approver'][3]}}</td>
                        <td class="no-padding no-border"></td>
                        <td class="no-padding no-border"></td>
                        <td class="no-padding no-border"></td>
                        <td class="no-padding border-right-only"></td>
                    </tr>
                    <tr>
                        <td class="no-border-top no-border-bottom" colspan="6" height="15px"></td>
                    </tr>
                </table>
               </div>
            </div>


        </div>
    </body>
</html>