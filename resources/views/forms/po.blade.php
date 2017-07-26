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
                <span class="printable-form__body__title">Purchase Order</span>
                <table class="printable-form__body__table
                              printable-form__body__table--custom">
                    <tbody>
                        <tr>
                            <td class="align-left">
                                <span class="label">Supplier</span>
                                {{$data['winner']->name}}
                            </td>
                            <td class="align-left" colspan="2">
                                <span class="label">Address</span>
                                {{$data['winner']->address}}
                            </td>
                        </tr>
                        <tr>
                            <td class="align-left" width="33.333%">
                                <span class="label">Email Address</span>
                                {{$data['winner']->email_1}}
                            </td>
                            <td class="align-left" width="33.333%">
                                <span class="label">Telephone No.</span>
                                {{$data['winner']->phone_1}}
                            </td>
                            <td class="align-left" width="33.333%">
                                <span class="label">TIN No</span>
                                {{$data['winner']->fax_1}}
                            </td>
                        </tr>
                        <tr>
                            <td class="has-child" colspan="3">
                                <table class="child-table">
                                    <tr>
                                        <td class="align-left" width="25%">
                                            <span class="label">PO No</span>
                                            {{$data['po_number']}}
                                        </td>
                                        <td class="align-left" width="25%">
                                            <span class="label">Date</span>
                                            {{\Carbon\Carbon::createFromFormat('Y-m-d',$data['purchase_date'])->format('d F Y')}}
                                        </td>
                                        <td class="align-left" width="25%">
                                            <span class="label">Proc Mode</span>
                                            {{$data['mode']}}
                                        </td>
                                        <td class="align-left" width="25%">
                                            <span class="label">Account Code</span>
                                            {{$data['accounts']}}
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3">
                                <span class="label">Remarks</span>
                                Madam, Please furnish this office the following articles subject to the terms and conditions contained herein:
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <span class="label">Place of Delivery</span>
                                {{$data['centers']}}
                            </td>
                            <td class="align-left">
                                <span class="label">Delivery Schedule</span>
                                {{($data['delivery']) ? $data['delivery']->expected_date : ""}}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <span class="label">Delivery Term</span>
                                {{$data['delivery_term']}} Days
                            </td>
                            <td class="align-left">
                                <span class="label">Payment Term</span>
                                {{$data['term']}}
                            </td>
                        </tr>
                        <tr>
                            <td class="has-child" colspan="3">
                                <table class="child-table">
                                    <tr>
                                        <td class="head" width="10%">Item No</td>
                                        <td class="head" width="10%">UOM</td>
                                        <td class="head" width="30%">Description</td>
                                        <td class="head" width="10%">Quantity</td>
                                        <td class="head" width="10%">Unit Cost</td>
                                        <td class="head" width="10%">Amount</td>
                                    </tr>
                                    @foreach($data['items'] as $key=>$item)
                                    <tr>
                                        <td style="text-align:center">{{$key+1}}</td>
                                        <td>{{$item->unit}}</td>
                                        <td style="text-align:left">{{$item->description}}</td>
                                        <td>{{$item->quantity}}</td>
                                        <td>{{formatPrice($item->price_unit)}}</td>
                                        <td>{{formatPrice($item->total_amount)}}</td>
                                    </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="4">Total Amount</td>
                                        <td colspan="2"> {{formatPrice($data['bid_amount'])}}</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <p>Purpose: {{$data['purpose']}}</p>
                <p>In case of failure to make the full delivery within the time specified above, a penalty of one-tenth (1/10) of one percent (1%) for every day of delay shall be imposed. </p>

            </div>


            <div class="printable-form">


                <div class="printable-form__body" style="page-break-inside:avoid">
                <p>Very truly yours,</p>
                <table class="printable-form__body__table
                              printable-form__body__table--custom
                              printable-form__body__table--borderless" >
                    <tr>
                        <td class="v-align-bottom align-center" width="40%" height="50px"></td>
                        <td class="v-align-bottom align-center" width="20%" height="50px"></td>
                        <td class="v-align-bottom align-center" width="40%" height="50px"></td>
                    </tr>
                    <tr>

                        <td class="signatory align-center" width="40%">
                            <div class="signatory-name">
                                <table>
                                    <tr>
                                        <td width="50%"></td>
                                        <td nowrap>

                                        @if($data['approver'])
                                        {{($data['approver']) ? $data['approver']->name :""}}
                                        @endif
                                        </td>
                                        <td width="50%"></td>
                                    </tr>
                                    <tr>
                                        <td width="50%"></td>

                                        <td class="align-justify">
                                            <div class="signatory-rank-justify">
                                            @if($data['approver'])
                                                <strong>{{$data['approver']->ranks}}</strong>
                                                <span></span>
                                            @endif
                                            </div>
                                        </td>
                                        <td width="50%"></td>
                                    </tr>
                                </table>
                            </div>
                            @if($data['approver'])
                            {{($data['approver']) ? $data['approver']->designation : ""}}
                            @endif
                        </td>

                        <td width="20%"></td>
                        <td class="signatory align-left v-align-middle" width="40%">Conforme:</td>
                    </tr>
                    <tr>
                        <td class="signatory align-center" width="40%"></td>
                        <td width="20%"></td>
                        <td class="signatory align-center v-align-bottom" width="40%" height="20px"></td>
                    </tr>
                    <tr>
                        <td class="signatory align-center" width="40%"></td>
                        <td width="20%"></td>
                        <td class="signatory align-center v-align-bottom" width="40%" height="10px">{{$data['requestor']->name}}</td>
                    </tr>
                    <tr>
                        <td class="signatory align-center" width="40%"></td>
                        <td width="20%"></td>
                        <td class="signatory align-center" width="40%">
                            <span class="conforme-label">Signature over Printed Name of Supplier</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="signatory align-center" width="40%"></td>
                        <td width="20%"></td>
                        <td class="signatory align-center v-align-bottom" width="40%" height="25px">01 June 2017</td>
                    </tr>
                    <tr>
                        <td class="signatory align-center" width="40%"></td>
                        <td width="20%"></td>
                        <td class="signatory align-center" width="40%">
                            <span class="conforme-label">Date</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="signatory align-left" width="40%">Fund Available:</td>
                        <td width="20%"></td>
                        <td class="signatory align-left v-align-middle" width="40%"></td>
                    </tr>
                    <tr>
                        <td class="signatory align-center v-align-bottom" width="40%" height="30px"></td>
                        <td width="20%"></td>
                        <td class="signatory align-center" width="40%"></td>
                    </tr>
                    <tr>
                        <td class="signatory align-center v-align-bottom" width="40%" height="10px">{{$data['accounting']->name}} / {{$data['mfo_release_date']}}</td>
                        <td width="20%"></td>
                        <td class="signatory align-center" width="40%"></td>
                    </tr>
                    <tr>
                        <td class="signatory align-center" width="40%">
                            <span class="conforme-label">Accountant</span>
                        </td>
                        <td width="20%"></td>
                        <td class="signatory align-center" width="40%">
                            <span class="conforme-label">OBR/BUR No.</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="signatory align-center v-align-bottom" width="40%"></td>
                        <td width="20%"></td>
                        <td class="signatory align-center v-align-bottom" width="40%" height="25px">{{formatPrice($data['bid_amount'])}}</td>
                    </tr>
                    <tr>
                        <td class="signatory align-center" width="40%"></td>
                        <td width="20%"></td>
                        <td class="signatory align-center" width="40%">
                            <span class="conforme-label">Amount</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="signatory align-left" width="40%">Approved By:</td>
                        <td width="20%"></td>
                        <td class="signatory align-left v-align-middle" width="40%"></td>
                    </tr>
                    <tr>
                        <td class="signatory align-center v-align-bottom" width="40%" height="30px"></td>
                        <td width="20%"></td>
                        <td class="signatory align-center" width="40%"></td>
                    </tr>
                    <tr>
                        <td class="signatory align-center v-align-bottom" width="40%" height="10px">{{$data['coa_signatories']->name}} / {{$data['coa_approved_date']}}</td>
                        <td width="20%"></td>
                        <td class="signatory align-center" width="40%"></td>
                    </tr>
                    <tr>
                        <td class="signatory align-center" width="40%">
                            <span class="conforme-label">Commanding Officer</span>
                        </td>
                        <td width="20%"></td>
                        <td class="signatory align-center" width="40%"></td>
                    </tr>
                </table>
                </div>
                </div>
{{--
            <div class="printable-form__foot">
                <table class="printable-form__foot__table">
                    <tr>
                        <td colspan="2">
                            <p class="printable-form__foot__values">AFP Core Values: Honor, Service, Patriotism</p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="printable-form__foot__ref">{{$data['po_number']}} 111685 {{$data['purchase_date']}}</span>
                        </td>
                        <td>
                            <span class="printable-form__foot__code">
                                    <img src="{{base_path('public/img/barcode.png')}}" alt=""></span>
                        </td>
                    </tr>
                </table>
            </div>
 --}}

        </div>
    </body>
</html>