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

                <!-- form header -->
                <div class="printable-form__head">
                    <p class="printable-form__head__vision">AFP Vision 2028: A World-class Armed Forces, Source of National Pride</p>
                </div>
                <!-- form body -->
                <div class="printable-form__body">
                    <!-- letterhead -->
                    <div class="printable-form__letterhead">
                        <span class="printable-form__letterhead__logo">
                            <img src="{{base_path('public/img/form-logo.png')}}" alt="">
                        </span>
                        <span class="printable-form__letterhead__details">
                            {!!$data['unitHeader']!!}
                        </span>
                    </div>
                    <!-- title -->
                    <span class="printable-form__body__title">Abstract of Canvass and Recommendation of Award</span>
                    <!-- content -->
                    <table class="printable-form__body__table">
                        <tr>
                            <td width="20%"><strong>RFQ No</strong></td>
                            <td width="30%">{{$data['rfq_number']}}</td>
                            <td width="30%"><strong>Approved Budget for Contract (ABC)</strong></td>
                            <td width="20%">Php {{formatPrice($data['total_amount'])}}</td>
                        </tr>
                        <tr>
                            <td><strong>Date and Time</strong></td>
                            <td>{{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$data['date'])->format('dHi F Y')}}</td>
                            <td><strong>Unit / End User</strong></td>
                            <td>{{$data['unit']}}</td>
                        </tr>
                        <tr>
                            <td><strong>Place of Canvass</strong></td>
                            <td colspan="3">{{$data['venue']}}</td>
                        </tr>
                        <tr>
                            <td><strong>Place of Delivery</strong></td>
                            <td colspan="3">{{$data['place_of_delivery']}}</td>
                        </tr>
                    </table>
                    <table class="printable-form__body__table">
                        <tr>
                            <td class="align-middle" width="20%"><strong>Name of Supplier</strong></td>
                            <td width="20%">{{$data['proponents'][0]->supplier->name}}</td>
                            <td width="20%">{{$data['proponents'][1]->supplier->name}}</td>
                            <td width="20%"> @if(isset($data['proponents'][2])) {{$data['proponents'][2]->supplier->name}} @endif </td>
                            <td class="align-middle" width="20%">Remarks (Lowest Price Quotation/Proposal)</td>
                        </tr>
                        <tr>
                            <td><strong>Canvass Amount</strong></td>
                            <td>PHP {{formatPrice($data['proponents'][0]->bid_amount)}}</td>
                            <td>PHP {{formatPrice($data['proponents'][1]->bid_amount)}}</td>
                            <td> @if(isset($data['proponents'][2])) PHP {{formatPrice($data['proponents'][2]->bid_amount)}} @endif </td>
                            <td rowspan="4" class="v-align-middle align-center"><strong>{{$data['proponents'][0]->supplier->name}}</strong></td>
                        </tr>
                        <tr>
                            <td><strong>DTI Registration</strong></td>
                            <td class="align-center">
                                @if($data['proponents'][0]->supplier->attachmentByType("dti") != null && $data['proponents'][0]->supplier->attachmentByType("dti")->validity_date >= $data['today'])

                                    <span>
                                    {{\Carbon\Carbon::createFromFormat('!Y-m-d',$data['proponents'][0]->supplier->attachmentByType("dti")->issued_date )->format('d F Y')}}
                                    -

                                    {{\Carbon\Carbon::createFromFormat('!Y-m-d',$data['proponents'][0]->supplier->attachmentByType("dti")->validity_date )->format('d F Y')}}

                                    </span>

                                    <p style='margin-bottom:5px'>{{$data['proponents'][0]->supplier->attachmentByType("dti")->ref_number}}</p>
                                    <p style='margin-bottom:5px'>{{$data['proponents'][0]->supplier->attachmentByType("dti")->place}}</p>
                                @else
                                    &nbsp;
                                @endif
                            </td>
                            <td class="align-center">
                                @if($data['proponents'][1]->supplier->attachmentByType("dti") != null && $data['proponents'][1]->supplier->attachmentByType("dti")->validity_date >= $data['today'])

                                    <span>
                                    {{\Carbon\Carbon::createFromFormat('!Y-m-d',$data['proponents'][1]->supplier->attachmentByType("dti")->issued_date )->format('d F Y')}}
                                    -

                                    {{\Carbon\Carbon::createFromFormat('!Y-m-d',$data['proponents'][1]->supplier->attachmentByType("dti")->validity_date )->format('d F Y')}}

                                    </span>

                                    <p style='margin-bottom:5px'>{{$data['proponents'][1]->supplier->attachmentByType("dti")->ref_number}}</p>
                                    <p style='margin-bottom:5px'>{{$data['proponents'][1]->supplier->attachmentByType("dti")->place}}</p>

                                     @else
                                         &nbsp;
                                @endif
                            </td>
                            <td class="align-center">
                                @if(isset($data['proponents'][2]))
                                @if($data['proponents'][2]->supplier->attachmentByType("dti") != null && $data['proponents'][2]->supplier->attachmentByType("dti")->validity_date >= $data['today'])

                                    <span>
                                    {{\Carbon\Carbon::createFromFormat('!Y-m-d',$data['proponents'][2]->supplier->attachmentByType("dti")->issued_date )->format('d F Y')}}
                                    -

                                    {{\Carbon\Carbon::createFromFormat('!Y-m-d',$data['proponents'][2]->supplier->attachmentByType("dti")->validity_date )->format('d F Y')}}

                                    </span>

                                    <p style='margin-bottom:5px'>{{$data['proponents'][2]->supplier->attachmentByType("dti")->ref_number}}</p>
                                    <p style='margin-bottom:5px'>{{$data['proponents'][2]->supplier->attachmentByType("dti")->place}}</p>

                                     @else
                                         &nbsp;
                                @endif
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Mayor's Permit</strong></td>
                            <td class="align-center">
                                @if($data['proponents'][0]->supplier->attachmentByType("mayors_permit") != null && $data['proponents'][0]->supplier->attachmentByType("mayors_permit")->validity_date >= $data['today'])

                                    <span>
                                    {{\Carbon\Carbon::createFromFormat('!Y-m-d',$data['proponents'][0]->supplier->attachmentByType("mayors_permit")->issued_date )->format('d F Y')}}
                                    -

                                    {{\Carbon\Carbon::createFromFormat('!Y-m-d',$data['proponents'][0]->supplier->attachmentByType("mayors_permit")->validity_date )->format('d F Y')}}

                                    </span>

                                    <p style='margin-bottom:5px'>{{$data['proponents'][0]->supplier->attachmentByType("mayors_permit")->ref_number}}</p>
                                    <p style='margin-bottom:5px'>{{$data['proponents'][0]->supplier->attachmentByType("mayors_permit")->place}}</p>

                                     @else
                                         &nbsp;
                                @endif
                            </td>

                            <td class="align-center">
                                @if($data['proponents'][1]->supplier->attachmentByType("mayors_permit") != null && $data['proponents'][1]->supplier->attachmentByType("mayors_permit")->validity_date >= $data['today'])
                                    <span>
                                    {{\Carbon\Carbon::createFromFormat('!Y-m-d',$data['proponents'][1]->supplier->attachmentByType("mayors_permit")->issued_date )->format('d F Y')}}
                                    -

                                    {{\Carbon\Carbon::createFromFormat('!Y-m-d',$data['proponents'][1]->supplier->attachmentByType("mayors_permit")->validity_date )->format('d F Y')}}

                                    </span>

                                    <p style='margin-bottom:5px'>{{$data['proponents'][1]->supplier->attachmentByType("mayors_permit")->ref_number}}</p>
                                    <p style='margin-bottom:5px'>{{$data['proponents'][1]->supplier->attachmentByType("mayors_permit")->place}}</p>

                                     @else
                                         &nbsp;
                                @endif
                            </td>

                            <td class="align-center">
                                @if(isset($data['proponents'][2]))
                                @if($data['proponents'][2]->supplier->attachmentByType("mayors_permit") != null && $data['proponents'][2]->supplier->attachmentByType("mayors_permit")->validity_date >= $data['today'] == 1)
                                    <span>
                                    {{\Carbon\Carbon::createFromFormat('!Y-m-d',$data['proponents'][2]->supplier->attachmentByType("mayors_permit")->issued_date )->format('d F Y')}}
                                    -

                                    {{\Carbon\Carbon::createFromFormat('!Y-m-d',$data['proponents'][2]->supplier->attachmentByType("mayors_permit")->validity_date )->format('d F Y')}}

                                    </span>

                                    <p style='margin-bottom:5px'>{{$data['proponents'][2]->supplier->attachmentByType("mayors_permit")->ref_number}}</p>
                                    <p style='margin-bottom:5px'>{{$data['proponents'][2]->supplier->attachmentByType("mayors_permit")->place}}</p>

                                     @else
                                        &nbsp;
                                @endif
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Tax Clearance</strong></td>
                            <td class="align-center">
                                @if($data['proponents'][0]->supplier->attachmentByType("tax_clearance") != null && $data['proponents'][0]->supplier->attachmentByType("tax_clearance")->validity_date >= $data['today'] == 1)
                                    <span>
                                    {{\Carbon\Carbon::createFromFormat('!Y-m-d',$data['proponents'][0]->supplier->attachmentByType("tax_clearance")->issued_date )->format('d F Y')}}
                                    -

                                    {{\Carbon\Carbon::createFromFormat('!Y-m-d',$data['proponents'][0]->supplier->attachmentByType("tax_clearance")->validity_date )->format('d F Y')}}

                                    </span>

                                    <p style='margin-bottom:5px'>{{$data['proponents'][0]->supplier->attachmentByType("tax_clearance")->ref_number}}</p>
                                    <p style='margin-bottom:5px'>{{$data['proponents'][0]->supplier->attachmentByType("tax_clearance")->place}}</p>

                                     @else
                                         &nbsp;
                                @endif
                            </td>

                            <td class="align-center">
                                @if($data['proponents'][1]->supplier->attachmentByType("tax_clearance") != null && $data['proponents'][1]->supplier->attachmentByType("tax_clearance")->validity_date >= $data['today'] == 1)
                                    <span>
                                    {{\Carbon\Carbon::createFromFormat('!Y-m-d',$data['proponents'][1]->supplier->attachmentByType("tax_clearance")->issued_date )->format('d F Y')}}
                                    -

                                    {{\Carbon\Carbon::createFromFormat('!Y-m-d',$data['proponents'][1]->supplier->attachmentByType("tax_clearance")->validity_date )->format('d F Y')}}

                                    </span>

                                    <p style='margin-bottom:5px'>{{$data['proponents'][1]->supplier->attachmentByType("tax_clearance")->ref_number}}</p>
                                    <p style='margin-bottom:5px'>{{$data['proponents'][1]->supplier->attachmentByType("tax_clearance")->place}}</p>

                                     @else
                                         &nbsp;
                                @endif
                            </td>

                            <td class="align-center">
                                @if(isset($data['proponents'][2]))
                                @if($data['proponents'][2]->supplier->attachmentByType("tax_clearance") != null && $data['proponents'][2]->supplier->attachmentByType("tax_clearance")->validity_date >= $data['today'])
                                    <span>
                                    {{\Carbon\Carbon::createFromFormat('!Y-m-d',$data['proponents'][2]->supplier->attachmentByType("tax_clearance")->issued_date )->format('d F Y')}}
                                    -

                                    {{\Carbon\Carbon::createFromFormat('!Y-m-d',$data['proponents'][2]->supplier->attachmentByType("tax_clearance")->validity_date )->format('d F Y')}}

                                    </span>

                                    <p style='margin-bottom:5px'>{{$data['proponents'][2]->supplier->attachmentByType("tax_clearance")->ref_number}}</p>
                                    <p style='margin-bottom:5px'>{{$data['proponents'][2]->supplier->attachmentByType("tax_clearance")->place}}</p>

                                     @else
                                         &nbsp;
                                @endif
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>PhilGeps Registration</strong></td>
                            <td class="align-center">
                                @if($data['proponents'][0]->supplier->attachmentByType("philgeps_registraion") != null && $data['proponents'][0]->supplier->attachmentByType("philgeps_registraion")->validity_date >= $data['today'])
                                    <span>
                                    {{\Carbon\Carbon::createFromFormat('!Y-m-d',$data['proponents'][0]->supplier->attachmentByType("philgeps_registraion")->issued_date )->format('d F Y')}}
                                    -

                                    {{\Carbon\Carbon::createFromFormat('!Y-m-d',$data['proponents'][0]->supplier->attachmentByType("philgeps_registraion")->validity_date )->format('d F Y')}}

                                    </span>

                                    <p style='margin-bottom:5px'>{{$data['proponents'][0]->supplier->attachmentByType("philgeps_registraion")->ref_number}}</p>
                                    <p style='margin-bottom:5px'>{{$data['proponents'][0]->supplier->attachmentByType("philgeps_registraion")->place}}</p>

                                     @else
                                         &nbsp;
                                @endif
                            </td>

                            <td class="align-center">
                                @if($data['proponents'][1]->supplier->attachmentByType("philgeps_registraion") != null && $data['proponents'][1]->supplier->attachmentByType("philgeps_registraion")->validity_date >= $data['today'])
                                    <span>
                                    {{\Carbon\Carbon::createFromFormat('!Y-m-d',$data['proponents'][1]->supplier->attachmentByType("philgeps_registraion")->issued_date )->format('d F Y')}}
                                    -

                                    {{\Carbon\Carbon::createFromFormat('!Y-m-d',$data['proponents'][1]->supplier->attachmentByType("philgeps_registraion")->validity_date )->format('d F Y')}}

                                    </span>

                                    <p style='margin-bottom:5px'>{{$data['proponents'][1]->supplier->attachmentByType("philgeps_registraion")->ref_number}}</p>
                                    <p style='margin-bottom:5px'>{{$data['proponents'][1]->supplier->attachmentByType("philgeps_registraion")->place}}</p>

                                     @else
                                         &nbsp;
                                @endif
                            </td>

                            <td class="align-center">
                                @if(isset($data['proponents'][2]))
                                @if($data['proponents'][2]->supplier->attachmentByType("philgeps_registraion") != null && $data['proponents'][2]->supplier->attachmentByType("philgeps_registraion")->validity_date >= $data['today'] == 1)
                                    <span>
                                    {{\Carbon\Carbon::createFromFormat('!Y-m-d',$data['proponents'][2]->supplier->attachmentByType("philgeps_registraion")->issued_date )->format('d F Y')}}
                                    -

                                    {{\Carbon\Carbon::createFromFormat('!Y-m-d',$data['proponents'][2]->supplier->attachmentByType("philgeps_registraion")->validity_date )->format('d F Y')}}

                                    </span>

                                    <p style='margin-bottom:5px'>{{$data['proponents'][2]->supplier->attachmentByType("philgeps_registraion")->ref_number}}</p>
                                    <p style='margin-bottom:5px'>{{$data['proponents'][2]->supplier->attachmentByType("philgeps_registraion")->place}}</p>

                                     @else
                                         &nbsp;
                                @endif
                                @endif
                            </td>
                            <td rowspan="2" class="v-align-middle align-center"><strong>PHP {{formatPrice($data['proponents'][0]->bid_amount)}}</strong></td>
                        </tr>
                        <tr>
                            <td><strong>Remarks</strong></td>
                            <td>{{$data['proponents'][0]->status}}</td>
                            <td>{{$data['proponents'][1]->status}}</td>
                            <td> @if(isset($data['proponents'][2])) {{$data['proponents'][2]->status}} @endif</td>
                        </tr>
                    </table>
                    <p><strong>WE HEREBY CERTIFY</strong> that the Above Abstract of Canvass is correct and complying and therefore recommend the award to <strong>{{$data['proponents'][0]->supplier->name}}</strong> having the lowest and most responsive calculated price offer.</p>
                    <table class="printable-form__body__table no-border no-padding">
                        <tr>
                            <td class="align-bottom align-left" width="45%" height="80px">
                                {{-- @if(isset($data['signatories'][0])) --}}
                                <strong>{{$data['presiding'][1]}} {{$data['presiding'][0]}} {{$data['presiding'][2]}}</strong><br>
                                {{$data['presiding'][3]}}
                                {{-- @endif --}}
                            </td>
                            <td width="10%"></td>
                            <td class="align-bottom align-left" height="80px">
                                <strong>{{$data['unit_head_signatory'][1]}} {{$data['unit_head_signatory'][0]}} {{$data['unit_head_signatory'][2]}}</strong><br>
                                {{$data['unit_head_signatory'][3]}}
                            </td>
                        </tr>
                        <tr>
                            <td class="align-bottom align-left" height="80px">
                                <strong>{{$data['mfo'][1]}} {{$data['mfo'][0]}} {{$data['mfo'][2]}}</strong><br>
                                {{$data['mfo'][3]}}
                            </td>
                            <td></td>
                            <td class="align-bottom align-left" height="80px">
                                <strong>{{$data['legal'][1]}} {{$data['legal'][0]}} {{$data['legal'][2]}}</strong><br>
                                {{$data['legal'][3]}}
                            </td>
                        </tr>
                        <tr>
                            <td class="align-bottom align-left" height="80px">
                                <strong>{{$data['sec'][1]}} {{$data['sec'][0]}} {{$data['sec'][2]}}</strong><br>
                                {{$data['sec'][3]}}
                            </td>
                            <td></td>
                        </tr>
                    </table>
                </div>
            </div>


        </div>

    </body>
</html>