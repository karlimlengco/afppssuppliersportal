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
        <div class="printable-form">

            <!-- main page -->
            <div class="printable-form__head">
                <p class="printable-form__head__vision">AFP Vision 2028: A World-class Armed Forces, Source of National Pride</p>
                <div class="printable-form__head__letterhead">
                    <span class="printable-form__head__letterhead__logo">
                        <img src="{{base_path('public/img/form-logo.png')}}" alt="">
                    </span>
                    <span class="printable-form__head__letterhead__details">
                        <strong>302ND Contracting Office</strong><br>
                        ARMED FORCES OF THE PHILIPPINES PROCUREMENT SERVICE<br>
                        Naval Base Pascual Ledesma<br>
                        Fort San Felipe, Cavite City Philippines
                    </span>
                </div>
            </div>

            <div class="printable-form__body">
                <span class="printable-form__body__title">ABSTRACT OF CANVASS AND RECOMMENDATION OF AWARD</span>
                <table class="printable-form__body__table">
                    <tr>
                        <td>RFQ No</td>
                        <td>{{$data['rfq_number']}}</td>
                        <td>Approved Budget for Contract (ABC)</td>
                        <td>Php {{formatPrice($data['total_amount'])}}</td>
                    </tr>
                    <tr>
                        <td>Date and Time</td>
                        <td>{{\Carbon\Carbon::now()->format('dHi M Y')}}</td>
                        <td>Unit / End User</td>
                        <td>{{$data['unit']}}</td>
                    </tr>
                    <tr>
                        <td>Place of Canvass</td>
                        <td colspan="3">{{$data['venue']}}</td>
                    </tr>
                    <tr>
                        <td>Place of Delivery</td>
                        <td colspan="3">{{$data['center']}}</td>
                    </tr>
                </table>
                <table class="printable-form__body__table">
                    <tr>
                        <td>Name of Supplier</td>
                        @foreach($data['proponents'] as $proponent)
                            <td>{{$proponent->supplier->name}}</td>
                        @endforeach
                        <td>Remarks (Lowest Price Quotation/ Proposal)</td>
                    </tr>
                    <tr>
                        <td>Canvass Amount</td>
                        @foreach($data['proponents'] as $proponent)
                            <td>PHP {{formatPrice($proponent->bid_amount)}}</td>
                        @endforeach
                        @foreach($data['proponents'] as $proponent)
                            @if($data['min_bid'] == $proponent->bid_amount)
                            <td> {{$proponent->supplier->name}} </td>
                            @endif
                        @endforeach
                    </tr>
                    <tr>
                        <td>DTI Registration</td>
                        @foreach($data['proponents'] as $proponent)
                            <td></td>
                        @endforeach
                        <td></td>
                    </tr>
                    <tr>
                        <td>Mayor's Permit</td>
                        @foreach($data['proponents'] as $proponent)
                            <td></td>
                        @endforeach
                        <td></td>
                    </tr>
                    <tr>
                        <td>Tax Clearance</td>
                        @foreach($data['proponents'] as $proponent)
                            <td></td>
                        @endforeach
                        <td></td>
                    </tr>
                    <tr>
                        <td>PhilGeps Registration</td>
                        @foreach($data['proponents'] as $proponent)
                            <td></td>
                        @endforeach
                        <td rowspan="2">PHP {{formatPrice($data['min_bid'])}}</td>
                    </tr>
                    <tr>
                        <td>Remarks</td>
                        @foreach($data['proponents'] as $proponent)
                            <td></td>
                        @endforeach
                    </tr>
                </table>
                <p>WE HEREBY CERTIFY that the Above Abstract of Canvass is correct and complying and therefore recommend the award to
                        @foreach($data['proponents'] as $proponent)
                            @if($data['min_bid'] == $proponent->bid_amount)
                            {{$proponent->supplier->name}}
                            @endif
                        @endforeach having the lowest and most responsive calculated price offer.</p>

                <div class="signatory">
                    <table class="signatory__table">
                        <tr>
                            @if(isset($data['signatories'][0]))
                            <td>
                                <div class="signatory__details">
                                    <span class="signatory__details__name">{{$data['signatories'][0]->signatory->name}}</span>
                                    <span class="signatory__details__position">{{$data['signatories'][0]->signatory->designation}}</span>
                                </div>
                            </td>
                            @else
                            <td></td>
                            @endif
                            <td></td>
                        </tr>
                        <tr>
                            @if(isset($data['signatories'][1]))
                            <td>
                                <div class="signatory__details">
                                    <span class="signatory__details__name">{{$data['signatories'][1]->signatory->name}}</span>
                                    <span class="signatory__details__position">{{$data['signatories'][1]->signatory->designation}}</span>
                                </div>
                            </td>
                            @else
                            <td></td>
                            @endif
                            @if(isset($data['signatories'][2]))
                            <td>
                                <div class="signatory__details">
                                    <span class="signatory__details__name">{{$data['signatories'][2]->signatory->name}}</span>
                                    <span class="signatory__details__position">{{$data['signatories'][2]->signatory->designation}}</span>
                                </div>
                            </td>
                            @else
                            <td></td>
                            @endif
                        </tr>
                        <tr>
                            @if(isset($data['signatories'][3]))
                            <td>
                                <div class="signatory__details">
                                    <span class="signatory__details__name">{{$data['signatories'][3]->signatory->name}}</span>
                                    <span class="signatory__details__position">{{$data['signatories'][3]->signatory->designation}}</span>
                                </div>
                            </td>
                            @else
                            <td></td>
                            @endif
                            @if(isset($data['signatories'][4]))
                            <td>
                                <div class="signatory__details">
                                    <span class="signatory__details__name">{{$data['signatories'][4]->signatory->name}}</span>
                                    <span class="signatory__details__position">{{$data['signatories'][4]->signatory->designation}}</span>
                                </div>
                            </td>
                            @else
                            <td></td>
                            @endif
                        </tr>
                    </table>
                </div>
            </div>

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
    </body>
</html>