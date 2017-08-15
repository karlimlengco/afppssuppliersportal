<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="stylesheet" href="{{base_path('fonts/Nunito_Sans/css/nunitosans.css')}}">
        <link rel="stylesheet" href="{{base_path('public/css/main.css')}}">
        <style type="text/css">
            body{
                margin:0;
            }
            @page{margin:0;padding:0;}
        </style>
    </head>

    <body>
        <div class="printable-form-wrapper">
            <!-- third-upr.xps -->
            <div class="printable-form">
                <!-- form header -->

                <div class="printable-form__head">
                    <p class="printable-form__head__vision">AFP Vision 2028: A World-class Armed Forces, Source of National Pride</p>
                </div>
                <!-- form content -->

                <div class="printable-form__body no-letterhead boxed">
                    <span class="printable-form__head__letterhead__details inside">
                        HEADQUARTERS<br>
                        CAMP GEN EMILIO AGUINALDO<br>
                        QUEZON CITY<br>
                        METRO MANILA<br>
                        PHILIPPINES
                    </span>
                    <!-- <span class="printable-form__body__title">Unit Purchase Request</span> -->
                    <table class="printable-form__body__table classic">
                        <tr>
                            <td class="align-center" colspan="3" width="65%"><strong>UNIT PURCHASE REQUEST</strong></td>
                            <td class="no-border-bottom" width="35%">UPR NO:</td>
                        </tr>
                        <tr>
                            <td class="no-border-top no-border-right no-border-bottom" colspan="3"></td>
                            <td class="align-center v-align-middle no-border-top" rowspan="2">{{$data['upr_number']}}</td>
                        </tr>
                        <tr>
                            <td class="no-border-top no-border-right no-border-bottom" width="150px" nowrap>PLACE OF DELIVERY</td>
                            <td class="no-border-top no-border-right no-border-left" width="350px">{{$data['center']}}</td>
                            <td class="no-border-top no-border-bottom no-border-left" width="10%"></td>
                        </tr>
                        <tr>
                            <td class="no-border-top no-border-right no-border-bottom" width="150px" nowrap>MODE OF PROCUREMENT</td>
                            <td class="no-border-top no-border-right no-border-left" width="350px">{{$data['mode']}}</td>
                            <td class="no-border-top no-border-bottom no-border-left" width="10%"></td>
                            <td class="no-border-bottom">AFPPS REF NO:</td>
                        </tr>
                        <tr>
                            <td class="no-border-top no-border-right no-border-bottom" width="150px" nowrap>CHARGEABILITY</td>
                            <td class="no-border-top no-border-right no-border-left" width="350px">{{$data['charge']}}</td>
                            <td class="no-border-top no-border-bottom no-border-left" width="10%"></td>
                            <td class="align-center v-align-middle no-border-top" rowspan="2">{{$data['ref_number']}}</td>
                        </tr>
                        <tr>
                            <td class="no-border-top no-border-right no-border-bottom" width="150px" nowrap>ACCOUNT CODE</td>
                            <td class="no-border-top no-border-right no-border-left" width="350px">{{$data['codes']}}</td>
                            <td class="no-border-top no-border-bottom no-border-left" width="10%"></td>
                        </tr>
                        <tr>
                            <td class="no-border-top no-border-right no-border-bottom" width="150px" nowrap>FUND VALIDITY</td>
                            <td class="no-border-top no-border-right no-border-left" width="350px">{{$data['fund_validity']}}</td>
                            <td class="no-border-top no-border-bottom no-border-left" width="10%"></td>
                            <td class="no-border-bottom">Date Prepared:</td>
                        </tr>
                        <tr>
                            <td class="no-border-top no-border-right no-border-bottom" width="150px" nowrap>TERMS OF PAYMENT</td>
                            <td class="no-border-top no-border-right no-border-left" width="350px">{{$data['terms']}}</td>
                            <td class="no-border-top no-border-bottom no-border-left" width="10%"></td>
                            <td class="align-center v-align-middle no-border-top" rowspan="3">
                                {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$data['date_prepared'])->format('d F Y') }}
                            </td>
                        </tr>
                        <tr>
                            <td class="no-border-top no-border-right no-border-bottom" width="150px" nowrap>OTHER ESSENTIAL INFO</td>
                            <td class="no-border-top no-border-right no-border-left" width="350px">{{$data['other_infos']}}</td>
                            <td class="no-border-top no-border-bottom no-border-left" width="10%"></td>
                        </tr>
                        <tr>
                            <td class="no-border-top no-border-right" colspan="3"></td>
                        </tr>
                    </table>
                    <table class="printable-form__body__table classic">
                        <tr>
                            <td class="no-border-top" width="10%">ITEM NO</td>
                            <td class="no-border-top" width="40%">ITEM DESCRIPTION</td>
                            <td class="no-border-top" width="10%">QTY</td>
                            <td class="no-border-top" width="10%">UNIT</td>
                            <td class="no-border-top" width="15%">UNIT PRICE</td>
                            <td class="no-border-top" width="15%">AMOUNT</td>
                        </tr>

                        @foreach($data['items'] as $key => $item)
                        <tr>
                            <td style="text-align:center">{{$key + 1}}</td>
                            <td style="text-align:left">{{$item->item_description}}</td>
                            <td>{{$item->quantity}}</td>
                            <td>{{$item->unit_measurement}}</td>
                            <td>{{formatPrice($item->unit_price)}}</td>
                            <td>{{formatPrice($item->total_amount)}}</td>
                        </tr>
                        @endforeach
                        <tr>
                            <td class="align-center" colspan="6"><strong>x-x-x-x-x Nothing Follows x-x-x-x-x</strong></td>
                        </tr>
                        <tr>
                            <td colspan="6">FOR THE USE OF {{$data['center']}}</td>
                        </tr>
                        <tr>
                            <td class="align-right" colspan="4"><strong>Total Amount PhP</strong></td>
                            <td colspan="2"><strong>{{formatPrice($data['total_amount'])}}</strong></td>
                        </tr>
                    </table>
                    <table class="printable-form__body__table classic">
                        <tr>
                            <td class="no-border-bottom no-border-top" width="33%">STOCKS REQUESTED ARE CERTIFIED TO BE WITHIN APPROVED APP/PPMP</td>
                            <td class="no-border-bottom no-border-top" width="33%">FUNDS CERTIFIED AVAILABLE:</td>
                            <td class="no-border-bottom no-border-top" width="33%">APPROVED:</td>
                        </tr>
                        <tr>
                            <td class="v-align-bottom no-border-top" height="60px"><strong>{{$data['approver']->name}}</strong></td>
                            <td class="v-align-bottom no-border-top" height="60px"><strong>{{$data['funder']->name}}</strong></td>
                            <td class="v-align-bottom no-border-top" height="60px"><strong>{{$data['requestor']->name}}</strong></td>
                        </tr>
                        <tr>
                            <td class="align-justify">
                                <div class="signatory-rank-justify">
                                    <strong>{{$data['approver']->ranks}}</strong>
                                    <span></span>
                                </div>
                            </td>
                            <td class="align-justify">
                                <div class="signatory-rank-justify">
                                    <strong>{{$data['funder']->ranks}}</strong>
                                    <span></span>
                                </div>
                            </td>
                            <td class="align-justify">
                                <div class="signatory-rank-justify">
                                    <strong>{{$data['requestor']->ranks}}</strong>
                                    <span></span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="align-center">{{$data['approver']->designation}}</td>
                            <td class="align-center">{{$data['funder']->designation}}</td>
                            <td class="align-center">{{$data['requestor']->designation}}</td>
                        </tr>
                    </table>
                </div>


                {{-- <div class="printable-form__body">
                    <span class="printable-form__body__title">Unit Purchase Request</span>
                    <table class="printable-form__body__table">
                        <tbody>
                            <tr>
                                <td class="align-left" width="33.333%">
                                    <span class="label">UPR No</span>
                                    {{$data['upr_number']}}
                                </td>
                                <td class="align-left" width="33.333%">
                                    <span class="label">Ref No.</span>
                                    {{$data['ref_number']}}
                                </td>
                                <td class="align-left" width="33.333%">
                                    <span class="label">Date Prepared</span>
                                    {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$data['date_prepared'])->format('d F Y') }}
                                </td>
                            </tr>
                            <tr>
                                <td class="align-left" width="33.333%">
                                    <span class="label">Place of delivery</span>
                                    {{$data['center']}}
                                </td>
                                <td class="align-left" width="33.333%">
                                    <span class="label">Mode of Procurement</span>
                                    {{$data['mode']}}
                                </td>
                                <td class="align-left" width="33.333%">
                                    <span class="label">Chargeability</span>
                                    {{$data['charge']}}
                                </td>
                            </tr>
                            <tr>
                                <td class="align-left" width="33.333%">
                                    <span class="label">Account Code</span>
                                    {{$data['codes']}}
                                </td>
                                <td class="align-left" width="33.333%">
                                    <span class="label">Fund Validity</span>
                                    {{$data['fund_validity']}}
                                </td>
                                <td class="align-left" width="33.333%">
                                    <span class="label">Terms of Payment</span>
                                    {{$data['terms']}}
                                </td>
                            </tr>
                            <tr>
                                <td class="align-left" colspan="3">
                                    <span class="label">Other Essesntial Information</span>
                                    {{$data['other_infos']}}
                                </td>
                            </tr>
                            <tr>
                                <td class="has-child" colspan="3">
                                    <table class="child-table">
                                        <tr>
                                            <td style="text-align:center" class="head"  width="10%"> Item No </td>
                                            <td class="head"  width="30%">Item Description</td>
                                            <td class="head"  width="10%">Quantity</td>
                                            <td class="head"  width="10%">Unit</td>
                                            <td class="head"  width="10%">Unit Price</td>
                                            <td  style="text-align:center" class="head"  width="10%">Amount</td>
                                        </tr>
                                        @foreach($data['items'] as $key => $item)
                                        <tr>
                                            <td style="text-align:center">{{$key + 1}}</td>
                                            <td style="text-align:left">{{$item->item_description}}</td>
                                            <td>{{$item->quantity}}</td>
                                            <td>{{$item->unit_measurement}}</td>
                                            <td>{{formatPrice($item->unit_price)}}</td>
                                            <td>{{formatPrice($item->total_amount)}}</td>
                                        </tr>
                                        @endforeach

                                        <tr>
                                            <td class="align-center" colspan="6">*** Nothing Follows ***</td>
                                        </tr>
                                        <tr>
                                            <td colspan="6">
                                                <span class="label">Purpose</span>
                                                {{$data['purpose']}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="4">Total Amount</td>
                                            <td colspan="2"> {{formatPrice($data['total_amount'])}}</td>
                                        </tr>
                                        <tr>
                                            <td class="has-child" colspan="6">
                                                <table class="child-table">
                                                    <tr>
                                                        <td class="align-center" width="33.333%">Stocks Requested are Certified to be within<br>Approved APP/PPMP</td>
                                                        <td class="head align-center v-align-middle" width="33.333%">Funds Certified Available</td>
                                                        <td class="head align-center v-align-middle" width="33.333%">Approved</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="align-center v-align-bottom" height="75px"></td>
                                                        <td class="align-center v-align-bottom" height="75px"></td>
                                                        <td class="align-center v-align-bottom" height="75px"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="align-center">
                                                            <span class="label">{{$data['requestor']->name}}</span>
                                                            {{$data['requestor']->designation}}
                                                        </td>
                                                        <td class="align-center">
                                                            <span class="label">{{$data['funder']->name}}</span>
                                                            {{$data['funder']->designation}}
                                                        </td>
                                                        <td class="align-center">
                                                            <span class="label">{{$data['approver']->name}}</span>
                                                            {{$data['approver']->designation}}
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div> --}}
                <!-- form footer -->
                {{-- <div class="printable-form__foot">
                    <table class="printable-form__foot__table">
                        <tr>
                            <td colspan="2">
                                <p class="printable-form__foot__values">AFP Core Values: Honor, Service, Patriotism</p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="printable-form__foot__ref">302ND-NLC-SPOF-016-15 111685 281033H December 2015</span>
                            </td>
                            <td>
                                <span class="printable-form__foot__code">
                                    <img src="{{base_path('public/img/barcode.png')}}" alt="">
                                </span>
                            </td>
                        </tr>
                    </table>
                </div> --}}
            </div>

        </div>
    </body>
</html>