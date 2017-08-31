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

                <div class="printable-form__body boxed">
                    <!-- letterhead -->
                    <div class="printable-form__letterhead">
                        <span class="printable-form__letterhead__details">
                            {!! $data['unitHeader'] !!}
                            {{-- <strong>{{$data['header']->name}}</strong><br>
                            Armed Forces of the Philippines Procurement Service<br>
                            {{$data['header']->address}} --}}
                        </span>
                    </div>
                    <!-- content -->
                    <table class="printable-form__body__table">
                        <tr>
                            <td class="align-center" colspan="3" width="65%"><strong>UNIT PURCHASE REQUEST</strong></td>
                            <td class="no-border-bottom" width="35%">UPR NO:</td>
                        </tr>
                        <tr>
                            <td class="border-left-only" colspan="3"></td>
                            <td class="align-center align-middle no-border-top" rowspan="2">{{$data['upr_number']}}</td>
                        </tr>
                        <tr>
                            <td class="border-left-only" width="200px" nowrap>PLACE OF DELIVERY</td>
                            <td class="border-bottom-only" width="300px">{{$data['place_of_delivery']}}</td>
                            <td class="border-right-only" width="10%"></td>
                        </tr>
                        <tr>
                            <td class="border-left-only" width="200px" nowrap>MODE OF PROCUREMENT</td>
                            <td class="border-bottom-only" width="300px">{{$data['mode']}}</td>
                            <td class="border-right-only" width="10%"></td>
                            <td class="no-border-bottom">AFPPS REF NO:</td>
                        </tr>
                        <tr>
                            <td class="border-left-only" width="200px" nowrap>CHARGEABILITY</td>
                            <td class="border-bottom-only" width="300px">{{$data['charge']}}</td>
                            <td class="border-right-only" width="10%"></td>
                            <td class="align-center align-middle no-border-top" rowspan="2">{{$data['ref_number']}}</td>
                        </tr>
                        <tr>
                            <td class="border-left-only" width="150px" nowrap></td>
                            <td class="no-border-bottom no-border-left no-border-right" width="350px"></td>
                            <td class="border-right-only" width="10%"></td>
                        </tr>
                        <tr>
                            <td class="border-left-only" width="200px" nowrap>FUND VALIDITY</td>
                            <td class="border-bottom-only" width="300px">{{$data['fund_validity']}}</td>
                            <td class="border-right-only" width="10%"></td>
                            <td class="no-border-bottom">Date Prepared:</td>
                        </tr>
                        <tr>
                            <td class="border-left-only" width="200px" nowrap>TERMS OF PAYMENT</td>
                            <td class="border-bottom-only" width="300px">{{$data['terms']}}</td>
                            <td class="border-right-only" width="10%"></td>
                            <td class="align-center align-middle no-border-top" rowspan="3">{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$data['date_prepared'])->format('d F Y') }}</td>
                        </tr>
                        <tr>
                            <td class="border-left-only" width="200px" nowrap>OTHER ESSENTIAL INFO</td>
                            <td class="border-bottom-only" width="350px">{{$data['other_infos']}}</td>
                            <td class="border-right-only" width="10%"></td>
                        </tr>
                        <tr>
                            <td class="no-border-top no-border-right" colspan="3"></td>
                        </tr>
                    </table>
                    <table class="printable-form__body__table">
                        <tr>
                            <td class="no-border-top align-center" width="15%"><strong>Account Code</strong></td>
                            <td class="no-border-top align-center" width="5%"><strong>ITEM NO</strong></td>
                            <td class="no-border-top align-center" width="35%"><strong>ITEM DESCRIPTION</strong></td>
                            <td class="no-border-top align-center" width="5%"><strong>QTY</strong></td>
                            <td class="no-border-top align-center" width="10%"><strong>UNIT</strong></td>
                            <td class="no-border-top align-center" width="15%"><strong>UNIT PRICE</strong></td>
                            <td class="no-border-top align-center" width="15%"><strong>AMOUNT</strong></td>
                        </tr>
                            @foreach($data['items'] as $key => $item)
                            <tr>
                                <td class="align-center"><small>{{($item->accounts) ? $item->accounts->new_account_code : ""}}</small></td>
                                <td class="align-center">{{$key + 1}}</td>
                                <td class="align-left">{{$item->item_description}}</td>
                                <td class="align-center">{{$item->quantity}}</td>
                                <td class="align-center">{{$item->unit_measurement}}</td>
                                <td class="align-right">{{formatPrice($item->unit_price)}}</td>
                                <td class="align-right">{{formatPrice($item->total_amount)}}</td>
                            </tr>
                            @endforeach
                        <tr>
                            <td class="align-center" colspan="7"><strong>x-x-x-x-x Nothing Follows x-x-x-x-x</strong></td>
                        </tr>
                        <tr>
                            <td colspan="7">{{$data['purpose']}}</td>
                        </tr>
                        <tr>
                            <td class="align-right" colspan="5"><strong>Total Amount PhP</strong></td>
                            <td colspan="2" class="align-right"> <strong>{{formatPrice($data['total_amount'])}}</strong></td>
                        </tr>
                        <tr>
                            <td class="align-center" colspan="7"><strong>NOTE: ALL SIGNATURES MUST BE OVER PRINTED NAME</strong></td>
                        </tr>
                    </table>


                    <table class="printable-form__body__table" >
                        <tr>
                            <td class="no-border-bottom no-border-top" width="33%">STOCKS REQUESTED ARE CERTIFIED TO BE WITHIN APPROVED APP/PPMP</td>
                            <td class="no-border-bottom no-border-top" width="33%">FUNDS CERTIFIED AVAILABLE:</td>
                            <td class="no-border-bottom no-border-top" width="33%">APPROVED:</td>
                        </tr>
                        <tr >
                            <td class="align-bottom no-border-top" height="100px">
                                <table class="signatory no-padding no-border" style="margin:0;padding:0">
                                    <tr>
                                        <td width="50%"></td>
                                        <td nowrap><strong>{{$data['approver'][0]}}</strong></td>
                                        <td width="50%"></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>
                                            <div class="signatory-rank-justify">
                                                <strong>{{$data['approver'][1]}} {{$data['approver'][2]}}</strong>
                                                <span></span>
                                            </div>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" style="border-bottom:1px solid #adadad"></td>
                                    </tr>
                                    <tr >
                                        <td ></td>
                                        <td class="align-left" colspan="2" >{{$data['approver']['3']}}</td>
                                    </tr>
                                </table>
                            </td>
                            <td class="align-bottom no-border-top" height="100px">
                                <table class="signatory no-padding no-border">
                                    <tr>
                                        <td width="50%"></td>
                                        <td nowrap><strong>{{$data['funder'][0]}}</strong></td>
                                        <td width="50%"></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>
                                            <div class="signatory-rank-justify">
                                                <strong>{{$data['funder'][1]}} {{$data['funder'][2]}}</strong>
                                                <span></span>
                                            </div>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" style="border-bottom:1px solid #adadad"></td>
                                    </tr>
                                    <tr>
                                        <td ></td>
                                        <td class="align-left" colspan="2">{{$data['funder']['3']}}</td>
                                    </tr>
                                </table>
                            </td>
                            <td class="align-bottom no-border-top" height="100px">
                                <table class="signatory no-padding no-border">
                                    <tr>
                                        <td width="50%"></td>
                                        <td nowrap><strong>{{$data['requestor'][0]}}</strong></td>
                                        <td width="50%"></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>
                                            <div class="signatory-rank-justify">
                                                <strong>{{$data['requestor'][1]}} {{$data['requestor'][2]}}</strong>
                                                <span></span>
                                            </div>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" style="border-bottom:1px solid #adadad"></td>
                                    </tr>
                                    <tr>
                                        <td ></td>
                                        <td class="align-left" colspan="2">{{$data['requestor']['3']}}</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </div>

            </div>

        </div>
    </body>
</html>