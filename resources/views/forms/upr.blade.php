<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="stylesheet" href="{{base_path('fonts/Nunito_Sans/css/nunitosans.css')}}">
        <link rel="stylesheet" href="{{base_path('public/css/main.css')}}">
        <style type="text/css">
            body{
                margin:0;
            }
        </style>
    </head>

    <body>
        <div class="printable-form-wrapper">
            <!-- third-upr.xps -->
            <div class="printable-form">
                <!-- form header -->
                <div class="printable-form__head">
                    <p class="printable-form__head__vision">AFP Vision 2028: A World-class Armed Forces, Source of National Pride</p>
                    <div class="printable-form__head__letterhead">
                        <span class="printable-form__head__letterhead__logo">
                        </span>
                        <span class="printable-form__head__letterhead__details">
                            HEADQUARTERS<br>
                            <strong>CAMP GEN EMILIO AGUINALDO</strong><br>
                            Quezon City, Metro Manila, Philippines
                        </span>
                    </div>
                </div>
                <!-- form content -->
                <div class="printable-form__body">
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
                </div>
                <!-- form footer -->
                <div class="printable-form__foot">
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
                </div>
            </div>

        </div>
    </body>
</html>