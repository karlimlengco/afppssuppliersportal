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
                <div class="printable-form__body">
                    <span class="printable-form__body__title">Abstract of Canvass and Recommendation of Award</span>
                    <table class="printable-form__body__table classic">
                        <tr>
                            <td width="20%">RFQ No</td>
                            <td width="30%">{{$data['rfq_number']}}</td>
                            <td width="30%">Approved Budget for Contract (ABC)</td>
                            <td width="20%">Php {{formatPrice($data['total_amount'])}}</td>
                        </tr>
                        <tr>
                            <td>Date and Time</td>
                            <td>{{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$data['date'])->format('dHi F Y')}}</td>
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
                    <table class="printable-form__body__table classic">
                        <tr>
                            <td width="20%">Name of Supplier</td>
                            <td width="20%">{{$data['proponents'][0]->supplier->name}}</td>
                            <td width="20%">{{$data['proponents'][1]->supplier->name}}</td>
                            <td width="20%">{{$data['proponents'][2]->supplier->name}}</td>
                            <td width="20%">Remarks (Lowest Price Quotation/Proposal)</td>
                        </tr>
                        <tr>
                            <td>Canvass Amount</td>
                            <td>PHP {{formatPrice($data['proponents'][0]->bid_amount)}}</td>
                            <td>PHP {{formatPrice($data['proponents'][1]->bid_amount)}}</td>
                            <td>PHP {{formatPrice($data['proponents'][2]->bid_amount)}}</td>
                            <td rowspan="4" class="v-align-middle align-center"><strong>{{$data['proponents'][0]->supplier->name}}</strong></td>
                        </tr>
                        <tr>
                            <td>DTI Registration</td>
                            <td>
                                @if($data['proponents'][0]->supplier->attachmentByType("dti") != null && $data['proponents'][0]->supplier->attachmentByType("dti")->validity_date >= $data['today'])
                                     ok
                                @endif
                            </td>
                            <td>
                                @if($data['proponents'][1]->supplier->attachmentByType("dti") != null && $data['proponents'][1]->supplier->attachmentByType("dti")->validity_date >= $data['today'])
                                     ok
                                @endif
                            </td>
                            <td>
                                @if($data['proponents'][2]->supplier->attachmentByType("dti") != null && $data['proponents'][2]->supplier->attachmentByType("dti")->validity_date >= $data['today'])
                                     ok
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Mayor's Permit</td>

                            <td>
                                @if($data['proponents'][0]->supplier->attachmentByType("mayors_permit
                                ") != null && $data['proponents'][0]->supplier->attachmentByType("mayors_permit")->validity_date >= $data['today'])
                                     ok
                                @endif
                            </td>

                            <td>
                                @if($data['proponents'][1]->supplier->attachmentByType("mayors_permit
                                ") != null && $data['proponents'][1]->supplier->attachmentByType("mayors_permit")->validity_date >= $data['today'])
                                     ok
                                @endif
                            </td>

                            <td>
                                @if($data['proponents'][2]->supplier->attachmentByType("mayors_permit
                                ") != null && $data['proponents'][2]->supplier->attachmentByType("mayors_permit")->validity_date >= $data['today'])
                                     ok
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Tax Clearance</td>

                            <td>
                                @if($data['proponents'][0]->supplier->attachmentByType("tax_clearance
                                ") != null && $data['proponents'][0]->supplier->attachmentByType("tax_clearance")->validity_date >= $data['today'])
                                     ok
                                @endif
                            </td>

                            <td>
                                @if($data['proponents'][1]->supplier->attachmentByType("tax_clearance
                                ") != null && $data['proponents'][1]->supplier->attachmentByType("tax_clearance")->validity_date >= $data['today'])
                                     ok
                                @endif
                            </td>

                            <td>
                                @if($data['proponents'][2]->supplier->attachmentByType("tax_clearance
                                ") != null && $data['proponents'][2]->supplier->attachmentByType("tax_clearance")->validity_date >= $data['today'])
                                     ok
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>PhilGeps Registration</td>

                            <td>
                                @if($data['proponents'][0]->supplier->attachmentByType("philgeps_registration
                                ") != null && $data['proponents'][0]->supplier->attachmentByType("philgeps_registration")->validity_date >= $data['today'])
                                     ok
                                @endif
                            </td>

                            <td>
                                @if($data['proponents'][1]->supplier->attachmentByType("philgeps_registration
                                ") != null && $data['proponents'][1]->supplier->attachmentByType("philgeps_registration")->validity_date >= $data['today'])
                                     ok
                                @endif
                            </td>

                            <td>
                                @if($data['proponents'][2]->supplier->attachmentByType("philgeps_registration
                                ") != null && $data['proponents'][2]->supplier->attachmentByType("philgeps_registration")->validity_date >= $data['today'])
                                     ok
                                @endif
                            </td>

                            <td rowspan="2" class="v-align-middle align-center"><strong>PHP {{formatPrice($data['proponents'][0]->bid_amount)}}</strong></td>
                        </tr>
                        <tr>
                            <td>Remarks</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </table>

                    <p><strong>WE HEREBY CERTIFY</strong> that the Above Abstract of Canvass is correct and complying and therefore recommend the award to <strong>{{$data['proponents'][0]->supplier->name}}</strong> having the lowest and most responsive calculated price offer.</p>
                    <!-- form signatories -->
                    <table class="printable-form__body__table
                                  printable-form__body__table--borderless">
                        <tr>
                            <td width="45%" height="80px"></td>
                            <td width="10%" height="80px"></td>
                            <td width="45%" height="80px"></td>
                        </tr>
                        <tr>
                            <td class="signatory v-align-middle" width="45%">
                                <div class="signatory-name">
                                    <table>
                                        <tr>
                                            <td><strong>{{$data['signatories'][0]->signatory->name}}</strong></td>
                                        </tr>
                                        <tr>
                                            <td>{{$data['signatories'][0]->signatory->designation}}</td>
                                        </tr>
                                    </table>
                                </div>
                            </td>
                            <td width="10%"></td>
                            <td class="signatory align-left v-align-middle" width="45%"></td>
                        </tr>
                        <tr>
                            <td width="45%" height="60px"></td>
                            <td width="10%" height="60px"></td>
                            <td width="45%" height="60px"></td>
                        </tr>
                        <tr>
                            <td class="signatory v-align-middle" width="45%">
                                <div class="signatory-name">
                                    <table>
                                        <tr>
                                            <td><strong>{{$data['signatories'][1]->signatory->name}}</strong></td>
                                        </tr>
                                        <tr>
                                            <td>{{$data['signatories'][1]->signatory->designation}}</td>
                                        </tr>
                                    </table>
                                </div>
                            </td>
                            <td width="10%"></td>
                            <td class="signatory v-align-middle" width="45%">
                                <div class="signatory-name">
                                    <table>
                                        <tr>
                                            <td><strong>{{$data['signatories'][2]->signatory->name}}</strong></td>
                                        </tr>
                                        <tr>
                                            <td>{{$data['signatories'][2]->signatory->designation}}</td>
                                        </tr>
                                    </table>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td width="45%" height="60px"></td>
                            <td width="10%" height="60px"></td>
                            <td width="45%" height="60px"></td>
                        </tr>
                        <tr>
                            <td class="signatory v-align-middle" width="45%">
                                <div class="signatory-name">
                                    <table>
                                        <tr>
                                            <td><strong>{{$data['signatories'][3]->signatory->name}}</strong></td>
                                        </tr>
                                        <tr>
                                            <td>{{$data['signatories'][3]->signatory->designation}}</td>
                                        </tr>
                                    </table>
                                </div>
                            </td>
                            <td width="10%"></td>
                            <td class="signatory v-align-middle" width="45%">
                                <div class="signatory-name">
                                    <table>
                                        <tr>
                                            <td><strong>{{$data['signatories'][4]->signatory->name}}</strong></td>
                                        </tr>
                                        <tr>
                                            <td>{{$data['signatories'][4]->signatory->designation}}</td>
                                        </tr>
                                    </table>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
                <!-- form content -->
                {{-- <div class="printable-form__body">
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
                                    {{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$data['date'])->format('d F Y')}}
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
                                <td>
                                    @if($proponent->supplier->attachmentByType("dti") != null && $proponent->supplier->attachmentByType("dti")->validity_date >= $data['today'])
                                         ok
                                    @endif
                                </td>
                                <td>
                                    @if($proponent->supplier->attachmentByType("mayors_permit") != null && $proponent->supplier->attachmentByType("mayors_permit")->validity_date >= $data['today'])
                                        ok
                                    @endif
                                </td>
                                <td>
                                    @if($proponent->supplier->attachmentByType("tax_clearance") != null && $proponent->supplier->attachmentByType("tax_clearance")->validity_date >= $data['today'])
                                        ok
                                    @endif
                                </td>
                                <td>
                                    @if($proponent->supplier->attachmentByType("philgeps_registration") != null && $proponent->supplier->attachmentByType("philgeps_registration")->validity_date >= $data['today'])
                                        ok
                                    @endif
                                </td>
                                <td>{{ucfirst($proponent->status)}}</td>
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
                            <td class="signatory align-center v-align-middle" width="30%">
                                <div class="signatory-name">
                                    <table>
                                        <tr>
                                            <td width="50%"></td>
                                            <td nowrap>{{$data['signatories'][0]->signatory->name}}</td>
                                            <td width="50%"></td>
                                        </tr>
                                        <tr>
                                            <td width="50%"></td>

                                            <td class="align-justify">
                                                <div class="signatory-rank-justify">
                                                    <strong>{{$data['signatories'][0]->signatory->ranks}}</strong>
                                                    <span></span>
                                                </div>
                                            </td>
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

                                            <td class="align-justify">
                                                <div class="signatory-rank-justify">
                                                    <strong>{{$data['signatories'][1]->signatory->ranks}}</strong>
                                                    <span></span>
                                                </div>
                                            </td>
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
                                            <td class="align-justify">
                                                <div class="signatory-rank-justify">
                                                    <strong>{{$data['signatories'][2]->signatory->ranks}}</strong>
                                                    <span></span>
                                                </div>
                                            </td>
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
                                            <td class="align-justify">
                                                <div class="signatory-rank-justify">
                                                    <strong>{{$data['signatories'][3]->signatory->ranks}}</strong>
                                                    <span></span>
                                                </div>
                                            </td>
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

                                            <td class="align-justify">
                                                <div class="signatory-rank-justify">
                                                    <strong>{{$data['signatories'][4]->signatory->ranks}}</strong>
                                                    <span></span>
                                                </div>
                                            </td>
                                            <td width="50%"></td>
                                        </tr>
                                    </table>
                                </div>
                                {{$data['signatories'][4]->signatory->designation}}
                            </td>
                        </tr>
                        @endif
                    </table>
                </div> --}}
                <!-- form footer -->
               {{--  <div class="printable-form__foot">
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
                </div> --}}
            </div>


        </div>

    </body>
</html>