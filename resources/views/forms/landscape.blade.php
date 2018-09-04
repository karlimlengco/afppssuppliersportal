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

        <div class="printable-form-wrapper" >

            <div class="printable-form printable-form--landscape">
                <!-- form header -->

                <!-- form header -->
                <div class="printable-form__head">
                    <p class="printable-form__head__vision">AFP Vision 2028: A World-class Armed Forces, Source of National Pride</p>
                </div>
                <!-- form body -->
                <div class="printable-form__body">
                    <!-- letterhead -->
                    <div class="printable-form__letterhead">
                       {{--  <span class="printable-form__letterhead__logo">
                            <img src="{{base_path('public/img/form-logo.png')}}" alt="">
                        </span> --}}
                        <span class="printable-form__letterhead__details">
                            {!!$data['unitHeader']!!}
                        </span>
                    </div>
                    <!-- title -->
                    <span class="printable-form__body__title">Abstract of Canvass and Recommendation of Award</span>
                    <!-- content -->

                    <table class="printable-form__body__table">
                        <tr>
                            <td width="10%"><strong>RFQ No</strong></td>
                            <td width="20%">{{$data['rfq_number']}}</td>
                            <td width="15%"><strong>Approved Budget for Contract (ABC)</strong></td>
                            <td width="20%">Php {{formatPrice($data['total_amount'])}}</td>
                            <td width="15%"><strong>Date and Time</strong></td>
                            <td width="20%">{{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$data['date'])->format('dHi F Y')}}</td>
                        </tr>
                        <tr>
                            <td><strong>Unit / End User</strong></td>
                            <td>{{$data['unit']}}</td>
                            <td><strong>Place of Canvass</strong></td>
                            <td>{{$data['venue']}}</td>
                            <td><strong>Place of Delivery</strong></td>
                            <td>{{$data['place_of_delivery']}}</td>
                        </tr>
                    </table>
                    {{--  --}}

                    <table class="printable-form__body__table">
                        <tr>
                            <td class="align-middle" width="10%"><strong>Name of Supplier</strong></td>
                            <td width="15%">{{$data['proponents'][0]->supplier->name}}</td>
                            <td width="15%">@if(isset($data['proponents'][1]))  {{$data['proponents'][1]->supplier->name}} @endif</td>
                            <td width="15%"> @if(isset($data['proponents'][2])) {{$data['proponents'][2]->supplier->name}} @endif </td>
                            <td width="15%"> @if(isset($data['proponents'][3])) {{$data['proponents'][3]->supplier->name}} @endif </td>
                            <td width="15%"> @if(isset($data['proponents'][4])) {{$data['proponents'][4]->supplier->name}} @endif </td>
                            <td class="align-middle" width="15%">Remarks (Lowest Price Quotation/Proposal)</td>
                        </tr>
                        <tr>
                            <td><strong>Canvass Amount</strong></td>
                            <td>PHP {{formatPrice($data['proponents'][0]->bid_amount)}}</td>
                            <td>@if(isset($data['proponents'][1])) PHP {{formatPrice($data['proponents'][1]->bid_amount)}} @endif</td>
                            <td> @if(isset($data['proponents'][2])) PHP {{formatPrice($data['proponents'][2]->bid_amount)}} @endif </td>
                            <td> @if(isset($data['proponents'][3])) PHP {{formatPrice($data['proponents'][2]->bid_amount)}} @endif </td>
                            <td> @if(isset($data['proponents'][4])) PHP {{formatPrice($data['proponents'][2]->bid_amount)}} @endif </td>
                            <td rowspan="4" class="v-align-middle align-center">
                            <strong>
                              {{-- {{$data['proponents'][0]->supplier->name}} --}}
                              @if($data['minProp'])
                              {{$data['minProp']->supplier->name}}
                              @endif

                            </strong></td>
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
                                @if(isset($data['proponents'][1]) && $data['proponents'][1]->supplier->attachmentByType("dti") != null && $data['proponents'][1]->supplier->attachmentByType("dti")->validity_date >= $data['today'])

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
                            <td class="align-center">
                                @if(isset($data['proponents'][3]))
                                @if($data['proponents'][3]->supplier->attachmentByType("dti") != null && $data['proponents'][3]->supplier->attachmentByType("dti")->validity_date >= $data['today'])

                                    <span>
                                    {{\Carbon\Carbon::createFromFormat('!Y-m-d',$data['proponents'][3]->supplier->attachmentByType("dti")->issued_date )->format('d F Y')}}
                                    -

                                    {{\Carbon\Carbon::createFromFormat('!Y-m-d',$data['proponents'][3]->supplier->attachmentByType("dti")->validity_date )->format('d F Y')}}

                                    </span>

                                    <p style='margin-bottom:5px'>{{$data['proponents'][3]->supplier->attachmentByType("dti")->ref_number}}</p>
                                    <p style='margin-bottom:5px'>{{$data['proponents'][3]->supplier->attachmentByType("dti")->place}}</p>

                                     @else
                                         &nbsp;
                                @endif
                                @endif
                            </td>
                            <td class="align-center">
                                @if(isset($data['proponents'][4]))
                                @if($data['proponents'][4]->supplier->attachmentByType("dti") != null && $data['proponents'][4]->supplier->attachmentByType("dti")->validity_date >= $data['today'])

                                    <span>
                                    {{\Carbon\Carbon::createFromFormat('!Y-m-d',$data['proponents'][4]->supplier->attachmentByType("dti")->issued_date )->format('d F Y')}}
                                    -

                                    {{\Carbon\Carbon::createFromFormat('!Y-m-d',$data['proponents'][4]->supplier->attachmentByType("dti")->validity_date )->format('d F Y')}}

                                    </span>

                                    <p style='margin-bottom:5px'>{{$data['proponents'][4]->supplier->attachmentByType("dti")->ref_number}}</p>
                                    <p style='margin-bottom:5px'>{{$data['proponents'][4]->supplier->attachmentByType("dti")->place}}</p>

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
                                @if(isset($data['proponents'][1]) && $data['proponents'][1]->supplier->attachmentByType("mayors_permit") != null && $data['proponents'][1]->supplier->attachmentByType("mayors_permit")->validity_date >= $data['today'])
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

                            <td class="align-center">
                                @if(isset($data['proponents'][3]))
                                @if($data['proponents'][3]->supplier->attachmentByType("mayors_permit") != null && $data['proponents'][3]->supplier->attachmentByType("mayors_permit")->validity_date >= $data['today'] == 1)
                                    <span>
                                    {{\Carbon\Carbon::createFromFormat('!Y-m-d',$data['proponents'][3]->supplier->attachmentByType("mayors_permit")->issued_date )->format('d F Y')}}
                                    -

                                    {{\Carbon\Carbon::createFromFormat('!Y-m-d',$data['proponents'][3]->supplier->attachmentByType("mayors_permit")->validity_date )->format('d F Y')}}

                                    </span>

                                    <p style='margin-bottom:5px'>{{$data['proponents'][3]->supplier->attachmentByType("mayors_permit")->ref_number}}</p>
                                    <p style='margin-bottom:5px'>{{$data['proponents'][3]->supplier->attachmentByType("mayors_permit")->place}}</p>

                                     @else
                                        &nbsp;
                                @endif
                                @endif
                            </td>

                            <td class="align-center">
                                @if(isset($data['proponents'][4]))
                                @if($data['proponents'][4]->supplier->attachmentByType("mayors_permit") != null && $data['proponents'][4]->supplier->attachmentByType("mayors_permit")->validity_date >= $data['today'] == 1)
                                    <span>
                                    {{\Carbon\Carbon::createFromFormat('!Y-m-d',$data['proponents'][4]->supplier->attachmentByType("mayors_permit")->issued_date )->format('d F Y')}}
                                    -

                                    {{\Carbon\Carbon::createFromFormat('!Y-m-d',$data['proponents'][4]->supplier->attachmentByType("mayors_permit")->validity_date )->format('d F Y')}}

                                    </span>

                                    <p style='margin-bottom:5px'>{{$data['proponents'][4]->supplier->attachmentByType("mayors_permit")->ref_number}}</p>
                                    <p style='margin-bottom:5px'>{{$data['proponents'][4]->supplier->attachmentByType("mayors_permit")->place}}</p>

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
                                @if(isset($data['proponents'][1]) && $data['proponents'][1]->supplier->attachmentByType("tax_clearance") != null && $data['proponents'][1]->supplier->attachmentByType("tax_clearance")->validity_date >= $data['today'] == 1)
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

                            <td class="align-center">
                                @if(isset($data['proponents'][3]))
                                @if($data['proponents'][3]->supplier->attachmentByType("tax_clearance") != null && $data['proponents'][3]->supplier->attachmentByType("tax_clearance")->validity_date >= $data['today'])
                                    <span>
                                    {{\Carbon\Carbon::createFromFormat('!Y-m-d',$data['proponents'][3]->supplier->attachmentByType("tax_clearance")->issued_date )->format('d F Y')}}
                                    -

                                    {{\Carbon\Carbon::createFromFormat('!Y-m-d',$data['proponents'][3]->supplier->attachmentByType("tax_clearance")->validity_date )->format('d F Y')}}

                                    </span>

                                    <p style='margin-bottom:5px'>{{$data['proponents'][3]->supplier->attachmentByType("tax_clearance")->ref_number}}</p>
                                    <p style='margin-bottom:5px'>{{$data['proponents'][3]->supplier->attachmentByType("tax_clearance")->place}}</p>

                                     @else
                                         &nbsp;
                                @endif
                                @endif
                            </td>

                            <td class="align-center">
                                @if(isset($data['proponents'][4]))
                                @if($data['proponents'][4]->supplier->attachmentByType("tax_clearance") != null && $data['proponents'][4]->supplier->attachmentByType("tax_clearance")->validity_date >= $data['today'])
                                    <span>
                                    {{\Carbon\Carbon::createFromFormat('!Y-m-d',$data['proponents'][4]->supplier->attachmentByType("tax_clearance")->issued_date )->format('d F Y')}}
                                    -

                                    {{\Carbon\Carbon::createFromFormat('!Y-m-d',$data['proponents'][4]->supplier->attachmentByType("tax_clearance")->validity_date )->format('d F Y')}}

                                    </span>

                                    <p style='margin-bottom:5px'>{{$data['proponents'][4]->supplier->attachmentByType("tax_clearance")->ref_number}}</p>
                                    <p style='margin-bottom:5px'>{{$data['proponents'][4]->supplier->attachmentByType("tax_clearance")->place}}</p>

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
                                @if(isset($data['proponents'][1]) && $data['proponents'][1]->supplier->attachmentByType("philgeps_registraion") != null && $data['proponents'][1]->supplier->attachmentByType("philgeps_registraion")->validity_date >= $data['today'])
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

                            <td class="align-center">
                                @if(isset($data['proponents'][3]))
                                @if($data['proponents'][3]->supplier->attachmentByType("philgeps_registraion") != null && $data['proponents'][3]->supplier->attachmentByType("philgeps_registraion")->validity_date >= $data['today'] == 1)
                                    <span>
                                    {{\Carbon\Carbon::createFromFormat('!Y-m-d',$data['proponents'][3]->supplier->attachmentByType("philgeps_registraion")->issued_date )->format('d F Y')}}
                                    -

                                    {{\Carbon\Carbon::createFromFormat('!Y-m-d',$data['proponents'][3]->supplier->attachmentByType("philgeps_registraion")->validity_date )->format('d F Y')}}

                                    </span>

                                    <p style='margin-bottom:5px'>{{$data['proponents'][3]->supplier->attachmentByType("philgeps_registraion")->ref_number}}</p>
                                    <p style='margin-bottom:5px'>{{$data['proponents'][3]->supplier->attachmentByType("philgeps_registraion")->place}}</p>

                                     @else
                                         &nbsp;
                                @endif
                                @endif
                            </td>

                            <td class="align-center">
                                @if(isset($data['proponents'][4]))
                                @if($data['proponents'][4]->supplier->attachmentByType("philgeps_registraion") != null && $data['proponents'][4]->supplier->attachmentByType("philgeps_registraion")->validity_date >= $data['today'] == 1)
                                    <span>
                                    {{\Carbon\Carbon::createFromFormat('!Y-m-d',$data['proponents'][4]->supplier->attachmentByType("philgeps_registraion")->issued_date )->format('d F Y')}}
                                    -

                                    {{\Carbon\Carbon::createFromFormat('!Y-m-d',$data['proponents'][4]->supplier->attachmentByType("philgeps_registraion")->validity_date )->format('d F Y')}}

                                    </span>

                                    <p style='margin-bottom:5px'>{{$data['proponents'][4]->supplier->attachmentByType("philgeps_registraion")->ref_number}}</p>
                                    <p style='margin-bottom:5px'>{{$data['proponents'][4]->supplier->attachmentByType("philgeps_registraion")->place}}</p>

                                     @else
                                         &nbsp;
                                @endif
                                @endif
                            </td>
                            <td rowspan="2" class="v-align-middle align-center">
                            <strong>
                            {{-- PHP {{formatPrice($data['proponents'][0]->bid_amount)}} --}}
                            @if($data['minProp'])
                            PHP {{formatPrice($data['minProp']->bid_amount)}}
                            @endif

                            </strong>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Remarks</strong></td>
                            <td>{{$data['proponents'][0]->status}}</td>
                            <td>@if(isset($data['proponents'][1])) {{$data['proponents'][1]->status}} @endif</td>
                            <td> @if(isset($data['proponents'][2])) {{$data['proponents'][2]->status}} @endif</td>
                            <td> @if(isset($data['proponents'][3])) {{$data['proponents'][3]->status}} @endif</td>
                            <td> @if(isset($data['proponents'][4])) {{$data['proponents'][4]->status}} @endif</td>
                        </tr>
                    </table>
                    <p><strong>WE HEREBY CERTIFY</strong> that the Above Abstract of Canvass is correct and complying and therefore recommend the award to <strong>
                    @if($data['minProp'])
                      {{$data['minProp']->supplier->name}}
                    @endif
                    </strong> having the lowest and most responsive calculated price offer.</p>

                    <table class="printable-form__body__table no-border no-padding">
                      <tr>
                          <td class="align-bottom align-left" width="25%" height="60px">
                              {{-- @if(isset($data['signatories'][0])) --}}
                              @if($data['mfo_attendance'] != 1) ABSENT <br> @endif
                              <strong>{{$data['mfo'][1]}} {{$data['mfo'][0]}} {{$data['mfo'][2]}}</strong><br>
                              {{$data['mfo'][3]}}
                              {{-- @endif --}}
                          </td>
                          <td width="10%"></td>
                          <td class="align-bottom align-left" width="25%" height="60px">
                              @if($data['unit_head_attendance'] != 1) ABSENT <br> @endif
                              <strong>{{$data['unit_head_signatory'][1]}} {{$data['unit_head_signatory'][0]}} {{$data['unit_head_signatory'][2]}}</strong><br>
                              {{$data['unit_head_signatory'][3]}}
                          </td>
                          <td width="10%"></td>
                          <td class="align-bottom align-left" width="25%" height="60px">
                              <strong>{{$data['sec'][1]}} {{$data['sec'][0]}} {{$data['sec'][2]}}</strong><br>
                              {{$data['sec'][3]}}
                          </td>
                      </tr>
                      <tr>
                          <td class="align-bottom align-left" height="60px">
                              @if($data['legal_attendance'] != 1) ABSENT <br> @endif
                              <strong>{{$data['legal'][1]}} {{$data['legal'][0]}} {{$data['legal'][2]}}</strong><br>
                              {{$data['legal'][3]}}
                          </td>
                          <td></td>
                          <td class="align-bottom align-left" height="60px">
                              @if($data['chief_attendance'] != 1) ABSENT <br> @endif
                              <strong>{{$data['presiding'][1]}} {{$data['presiding'][0]}} {{$data['presiding'][2]}}</strong><br>
                              {{$data['presiding'][3]}}
                          </td>
                      </tr>
                  </table>

                </div>
            </div>


        </div>

    </body>
</html>