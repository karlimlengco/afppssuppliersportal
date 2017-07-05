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

            <div class="printable-form">
                <!-- form header -->
                <div class="printable-form__head">
                    <p class="printable-form__head__vision">AFP Vision 2028: A World-class Armed Forces, Source of National Pride</p>
                    <div class="printable-form__head__letterhead">
                        <span class="printable-form__head__letterhead__logo">
                            <img src="{{base_path('public/img/form-logo.png')}}" alt="">
                        </span>
                        <span class="printable-form__head__letterhead__details">
                            <strong>302nd Contracting Office</strong><br>
                            Armed Forces of the Philippines Procurement Service<br>
                            Naval Base Pascual Ledesma<br>
                            Fort San Felipe, Cavite City Philippines
                        </span>
                    </div>
                </div>
                <!-- form content -->
                <div class="printable-form__body">
                    <span class="printable-form__body__title">Abstract of Canvass and Recommendation of Award</span>
                    <table class="printable-form__body__table">
                        <tbody>
                            <tr>
                                <td class="align-left" width="33.333%">
                                    <span class="label">RFQ No.</span>
                                    {{$data['rfq_number']}}
                                </td>
                                <td class="align-left" width="33.333%">
                                    <span class="label">Approved Budget for Contract (ABC)</span>
                                    Php {{formatPrice($data['total_amount'])}}
                                </td>
                                <td class="align-left" width="33.333%">
                                    <span class="label">Date and Time</span>
                                    {{$data['date']}}
                                </td>
                            </tr>
                            <tr>
                                <td class="align-left">
                                    <span class="label">Unit / End User</span>
                                    {{$data['unit']}}
                                </td>
                                <td class="align-left">
                                    <span class="label">Place of Canvass</span>
                                    {{$data['venue']}}
                                </td>
                                <td class="align-left">
                                    <span class="label">Place of Delivery</span>
                                    {{$data['center']}}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="printable-form__body__table">
                        <thead>
                            <tr>
                                <th class="v-align-middle head" width="25%">Name of Supplier</th>
                                <th class="v-align-middle head" width="15%">Canvass Amount</th>
                                <th class="v-align-middle head" width="10%">DTI Registration</th>
                                <th class="v-align-middle head" width="10%">Mayor's Permit</th>
                                <th class="v-align-middle head" width="10%">Tax Clearance</th>
                                <th class="v-align-middle head" width="10%">PhilGeps Registration</th>
                                <th class="v-align-middle head" width="10%">Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($data['proponents'] as $proponent)
                            <tr>
                                <td>{{$proponent->supplier->name}}</td>
                                <td>{{formatPrice($proponent->bid_amount)}}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>Passed</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <table class="printable-form__body__table">
                        <thead>
                            <tr>
                                <th class="head" colspan="2">Remarks (Lowest Price Quotation/ Proposal)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                @foreach($data['proponents'] as $proponent)
                                    @if($data['min_bid'] == $proponent->bid_amount)
                                    <td width="70%">{{$proponent->supplier->name}}</td>
                                    <td width="30%">{{formatPrice($proponent->bid_amount)}}</td>
                                    @endif
                                @endforeach
                            </tr>
                        </tbody>
                    </table>

                    <p>We <strong>HEREBY CERTIFY</strong> that the Above Abstract of Canvass is correct and complying and therefore recommend the award to <strong>
                            @foreach($data['proponents'] as $proponent)
                            @if($data['min_bid'] == $proponent->bid_amount)
                            {{$proponent->supplier->name}}
                            @endif @endforeach
                        </strong> having the lowest and most responsive calculated price offer.</p>
                    <!-- form signatories -->
                    <table class="printable-form__body__table
                                  printable-form__body__table--borderless">
                        @if(isset($data['signatories'][0]))
                        <tr>
                            <td class="v-align-bottom align-center" width="45%" height="80px"></td>
                            <td class="v-align-bottom align-center" width="10%" height="80px"></td>
                            <td class="v-align-bottom align-center" width="45%" height="80px"></td>
                        </tr>
                        <tr>
                            <td class="signatory align-center v-align-middle" width="45%">
                                <div class="signatory-name">
                                    <table>
                                        <tr>
                                            <td width="50%"></td>
                                            <td nowrap>{{$data['signatories'][0]->signatory->name}}</td>
                                            <td width="50%"></td>
                                        </tr>
                                        <tr>
                                            <td width="50%"></td>
                                            <td class="align-justify" style="text-align-last: justify !important; text-align: justify;"> <pre style="border:none"> <?php echo $data['signatories'][0]->signatory->ranks; ?></pre> </td>
                                            <td width="50%"></td>
                                        </tr>
                                    </table>
                                </div>
                                {{$data['signatories'][0]->signatory->designation}}
                            </td>
                            <td width="10%"></td>
                            <td class="signatory align-left v-align-middle" width="45%"></td>
                        </tr>
                        @endif

                        @if(isset($data['signatories'][1]) &&  isset($data['signatories'][2]) )
                        <tr>
                            <td class="v-align-bottom align-center" width="45%" height="60px">Signature</td>
                            <td class="v-align-bottom align-center" width="10%" height="60px"></td>
                            <td class="v-align-bottom align-center" width="45%" height="60px">Signature</td>
                        </tr>
                        <tr>
                            <td class="signatory align-center v-align-middle" width="45%">
                                <div class="signatory-name">
                                    <table>
                                        <tr>
                                            <td width="50%"></td>
                                            <td nowrap>{{$data['signatories'][1]->signatory->name}}</td>
                                            <td width="50%"></td>
                                        </tr>
                                        <tr>
                                            <td width="50%"></td>
                                            <td class="align-justify" style="text-align-last: justify !important; text-align: justify;"> <pre style="border:none"> <?php echo $data['signatories'][1]->signatory->ranks; ?></pre> </td>
                                            <td width="50%"></td>
                                        </tr>
                                    </table>
                                </div>
                                {{$data['signatories'][1]->signatory->designation}}
                            </td>
                            <td width="10%"></td>
                            <td class="signatory align-center v-align-middle" width="45%">

                                <div class="signatory-name">
                                    <table>
                                        <tr>
                                            <td width="50%"></td>
                                            <td nowrap>{{$data['signatories'][2]->signatory->name}}</td>
                                            <td width="50%"></td>
                                        </tr>
                                        <tr>
                                            <td width="50%"></td>
                                            <td class="align-justify" style="text-align-last: justify !important; text-align: justify;"> <pre style="border:none"> <?php echo $data['signatories'][2]->signatory->ranks; ?></pre> </td>
                                            <td width="50%"></td>
                                        </tr>
                                    </table>
                                </div>
                                {{$data['signatories'][2]->signatory->designation}}
                            </td>
                        </tr>
                        @endif

                        @if(isset($data['signatories'][3]) &&  isset($data['signatories'][4]) )
                        <tr>
                            <td class="v-align-bottom align-center" width="45%" height="60px">Signature</td>
                            <td class="v-align-bottom align-center" width="10%" height="60px"></td>
                            <td class="v-align-bottom align-center" width="45%" height="60px">Signature</td>
                        </tr>
                        <tr>
                            <td class="signatory align-center v-align-middle" width="45%">
                                <div class="signatory-name">
                                    <table>
                                        <tr>
                                            <td width="50%"></td>
                                            <td nowrap>{{$data['signatories'][3]->signatory->name}}</td>
                                            <td width="50%"></td>
                                        </tr>
                                        <tr>
                                            <td width="50%"></td>
                                            <td class="align-justify" style="text-align-last: justify !important; text-align: justify;"> <pre style="border:none"> <?php echo $data['signatories'][3]->signatory->ranks; ?></pre> </td>
                                            <td width="50%"></td>
                                        </tr>
                                    </table>
                                </div>
                                {{$data['signatories'][3]->signatory->designation}}
                            </td>
                            <td width="10%"></td>
                            <td class="signatory align-center v-align-middle" width="45%">
                                <div class="signatory-name">
                                    <table>
                                        <tr>
                                            <td width="50%"></td>
                                            <td nowrap>{{$data['signatories'][4]->signatory->name}}</td>
                                            <td width="50%"></td>
                                        </tr>
                                        <tr>
                                            <td width="50%"></td>
                                            <td class="align-justify" style="text-align-last: justify !important; text-align: justify;"> <pre style="border:none"> <?php echo $data['signatories'][4]->signatory->ranks; ?></pre> </td>
                                            <td width="50%"></td>
                                        </tr>
                                    </table>
                                </div>
                                {{$data['signatories'][4]->signatory->designation}}
                            </td>
                        </tr>
                        @endif
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
                                <span class="printable-form__foot__code"><img src="{{base_path('public/img/barcode.png')}}" alt=""></span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>


        </div>

    </body>
</html>