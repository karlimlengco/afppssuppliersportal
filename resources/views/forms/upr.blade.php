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
                            ARMED FORCES OF THE PHILIPPINES<br>
                            OFFICE OF THE ADJUTANT GENERAL, AFP<br>
                            Camp General Emilio Aguinaldo, Quezon City
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
                            <td class="border-left-only" width="150px" nowrap>PLACE OF DELIVERY</td>
                            <td class="border-bottom-only" width="350px">{{$data['center']}}</td>
                            <td class="border-right-only" width="10%"></td>
                        </tr>
                        <tr>
                            <td class="border-left-only" width="150px" nowrap>MODE OF PROCUREMENT</td>
                            <td class="border-bottom-only" width="350px">{{$data['mode']}}</td>
                            <td class="border-right-only" width="10%"></td>
                            <td class="no-border-bottom">AFPPS REF NO:</td>
                        </tr>
                        <tr>
                            <td class="border-left-only" width="150px" nowrap>CHARGEABILITY</td>
                            <td class="border-bottom-only" width="350px">{{$data['charge']}}</td>
                            <td class="border-right-only" width="10%"></td>
                            <td class="align-center align-middle no-border-top" rowspan="2">{{$data['ref_number']}}</td>
                        </tr>
                        <tr>
                            <td class="border-left-only" width="150px" nowrap>ACCOUNT CODE</td>
                            <td class="border-bottom-only" width="350px">{{$data['codes']}}</td>
                            <td class="border-right-only" width="10%"></td>
                        </tr>
                        <tr>
                            <td class="border-left-only" width="150px" nowrap>FUND VALIDITY</td>
                            <td class="border-bottom-only" width="350px">{{$data['fund_validity']}}</td>
                            <td class="border-right-only" width="10%"></td>
                            <td class="no-border-bottom">Date Prepared:</td>
                        </tr>
                        <tr>
                            <td class="border-left-only" width="150px" nowrap>TERMS OF PAYMENT</td>
                            <td class="border-bottom-only" width="350px">{{$data['terms']}}</td>
                            <td class="border-right-only" width="10%"></td>
                            <td class="align-center align-middle no-border-top" rowspan="3">{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$data['date_prepared'])->format('d F Y') }}</td>
                        </tr>
                        <tr>
                            <td class="border-left-only" width="150px" nowrap>OTHER ESSENTIAL INFO</td>
                            <td class="border-bottom-only" width="350px">{{$data['other_infos']}}</td>
                            <td class="border-right-only" width="10%"></td>
                        </tr>
                        <tr>
                            <td class="no-border-top no-border-right" colspan="3"></td>
                        </tr>
                    </table>
                    <table class="printable-form__body__table">
                        <tr>
                            <td class="no-border-top align-center" width="10%"><strong>ITEM NO</strong></td>
                            <td class="no-border-top align-center" width="40%"><strong>ITEM DESCRIPTION</strong></td>
                            <td class="no-border-top align-center" width="10%"><strong>QTY</strong></td>
                            <td class="no-border-top align-center" width="10%"><strong>UNIT</strong></td>
                            <td class="no-border-top align-center" width="15%"><strong>UNIT PRICE</strong></td>
                            <td class="no-border-top align-center" width="15%"><strong>AMOUNT</strong></td>
                        </tr>
                            @foreach($data['items'] as $key => $item)
                            <tr>
                                <td class="align-center">{{$key + 1}}</td>
                                <td class="align-left">{{$item->item_description}}</td>
                                <td class="align-center">{{$item->quantity}}</td>
                                <td class="align-center">{{$item->unit_measurement}}</td>
                                <td class="align-right">{{formatPrice($item->unit_price)}}</td>
                                <td class="align-right">{{formatPrice($item->total_amount)}}</td>
                            </tr>
                            @endforeach
                        <tr>
                            <td class="align-center" colspan="6"><strong>x-x-x-x-x Nothing Follows x-x-x-x-x</strong></td>
                        </tr>
                        <tr>
                            <td colspan="6">{{$data['purpose']}}</td>
                        </tr>
                        <tr>
                            <td class="align-right" colspan="4"><strong>Total Amount PhP</strong></td>
                            <td colspan="2" class="align-right"> <strong>{{formatPrice($data['total_amount'])}}</strong></td>
                        </tr>
                    </table>
                    <table class="printable-form__body__table">
                        <tr>
                            <td class="no-border-bottom no-border-top" width="33%">STOCKS REQUESTED ARE CERTIFIED TO BE WITHIN APPROVED APP/PPMP</td>
                            <td class="no-border-bottom no-border-top" width="33%">FUNDS CERTIFIED AVAILABLE:</td>
                            <td class="no-border-bottom no-border-top" width="33%">APPROVED:</td>
                        </tr>
                        <tr>
                            <td class="align-bottom no-border-top" height="60px"><strong>{{$data['approver']->name}}</strong></td>
                            <td class="align-bottom no-border-top" height="60px"><strong>{{$data['funder']->name}}</strong></td>
                            <td class="align-bottom no-border-top" height="60px"><strong>{{$data['requestor']->name}}</strong></td>
                        </tr>
                        <tr>
                            <td class="align-justify">
                                <div class="signatory-rank-justify">
                                    <strong>{{$data['approver']->ranks}} {{$data['approver']->branch}}</strong>
                                    <span></span>
                                </div>
                            </td>
                            <td class="align-justify">
                                <div class="signatory-rank-justify">
                                    <strong>{{$data['funder']->ranks}} {{$data['funder']->branch}}</strong>
                                    <span></span>
                                </div>
                            </td>
                            <td class="align-justify">
                                <div class="signatory-rank-justify">
                                    <strong>{{$data['requestor']->ranks}} {{$data['requestor']->branch}}</strong>
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

            </div>

        </div>
    </body>
</html>