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

            <!-- form header -->
            <div class="printable-form__head">
                <p class="printable-form__head__vision">AFP Vision 2028: A World-class Armed Forces, Source of National Pride</p>
            </div>
            <!-- form body -->
            <div class="printable-form__body boxed">
                <!-- letterhead -->
                <div class="printable-form__letterhead">
                    <span class="printable-form__letterhead__details">
                        <strong>{{$data['header']->name}}</strong><br>
                        Armed Forces of the Philippines Procurement Service<br>
                        {{$data['header']->address}}
                    </span>
                </div>
                <!-- title -->
                <span class="printable-form__body__title">PURCHASE ORDER/WORK ORDER/JOB ORDER</span>
                <!-- content -->
                <table class="printable-form__body__table">
                    <tr>
                        <td class="no-border-bottom" colspan="4"></td>
                    </tr>
                    <tr>
                        <td class="border-left-only" width="80px" nowrap>Supplier</td>
                        <td class="no-border" width="50%"><strong>{{$data['winner']->name}}</strong></td>
                        <td class="no-border" width="80px" nowrap>P.O. No.</td>
                        <td class="border-right-only" width="30%"><strong>{{$data['po_number']}}</strong></td>
                    </tr>
                    <tr>
                        <td class="border-left-only" nowrap>Address</td>
                        <td class="no-border"><strong>{{$data['winner']->address}}</strong></td>
                        <td class="no-border" nowrap>Date</td>
                        <td class="border-right-only"><strong>{{\Carbon\Carbon::createFromFormat('Y-m-d',$data['purchase_date'])->format('d F Y')}}</strong></td>
                    </tr>
                    <tr>
                        <td class="border-left-only" nowrap>Email Address</td>
                        <td class="no-border"><strong>{{$data['winner']->email_1}}</strong></td>
                        <td class="no-border" nowrap>Proc Mode</td>
                        <td class="border-right-only"><strong>{{$data['mode']}}</strong></td>
                    </tr>
                    <tr>
                        <td class="border-left-only" nowrap>Telephone No</td>
                        <td class="no-border"><strong>{{$data['winner']->phone_1}}</strong></td>
                        <td class="no-border" nowrap>Account Code</td>
                        <td class="border-right-only"><strong>{{$data['accounts']}}</strong></td>
                    </tr>
                    <tr>
                        <td class="border-left-only" nowrap>TIN</td>
                        <td class="border-right-only" colspan="3"><strong>{{$data['winner']->tin}}</strong></td>
                    </tr>
                    <tr>
                        <td class="no-border-top no-border-bottom" colspan="4"></td>
                    </tr>
                    <tr>
                        <td colspan="4">
                            Sir/Madam,<br>Please furnish this office the following articles subject to the terms and conditions contained herein:
                        </td>
                    </tr>
                    <tr>
                        <td class="no-border-top no-border-bottom" colspan="4"></td>
                    </tr>
                    <tr>
                        <td class="border-left-only" nowrap>Place of Delivery</td>
                        <td class="no-border"><strong>{{$data['centers']}}</strong></td>
                        <td class="no-border" nowrap>Delivery Schedule</td>
                        <td class="border-right-only"><strong>{{($data['delivery']) ? $data['delivery']->expected_date : ""}}</strong></td>
                    </tr>
                    <tr>
                        <td class="border-left-only" nowrap>Delivery Term</td>
                        <td class="no-border"><strong>{{$data['delivery_term']}} Calendar Days upon conformed of this PURCHASE ORDER</strong></td>
                        <td class="no-border" nowrap>Payment Term</td>
                        <td class="border-right-only"><strong>{{$data['term']}}</strong></td>
                    </tr>
                    <tr>
                        <td class="no-border-top no-border-bottom" colspan="4"></td>
                    </tr>
                </table>
                <table class="printable-form__body__table">
                    <tr>
                        <td class="align-center" width="10%"><strong>ITEM NO</strong></td>
                        <td class="align-center" width="10%"><strong>UOM</strong></td>
                        <td class="align-center" width="40%"><strong>DESCRPTION</strong></td>
                        <td class="align-center" width="10%"><strong>QTY</strong></td>
                        <td class="align-center" width="15%"><strong>UNIT COST</strong></td>
                        <td class="align-center" width="15%"><strong>AMOUNT</strong></td>
                    </tr>
                    @foreach($data['items'] as $key=>$item)
                    <tr>
                        <td class="align-center">{{$key+1}}</td>
                        <td class="align-center">{{$item->unit}}</td>
                        <td class="align-left">{{$item->description}}</td>
                        <td class="align-center">{{$item->quantity}}</td>
                        <td class="align-right">{{formatPrice($item->price_unit)}}</td>
                        <td class="align-right">{{formatPrice($item->total_amount)}}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td class="align-center" colspan="4"><strong style="text-transform:uppercase">{{translateToWords($data['bid_amount'])}}PESOS ONLY.</strong></td>
                        <td colspan="2" class="align-right"><strong>{{formatPrice($data['bid_amount'])}}</strong></td>
                    </tr>
                    <tr>
                        <td colspan="6">
                            BASIS: {{$data['purpose']}}<br>
                            REFERENCES: UPR No. THIRD-UPR-2016 dtd 28 December 2015
                        </td>
                    </tr>
                    <tr>
                        <td class="align-top no-border-bottom" colspan="6" height="40px">In case of failure to make the full delivery within the time specified above, a penalty of one-tenth (1/10) of one percent (1%) for every day of delay shall be imposed.</td>
                    </tr>
                </table>
                <table class="printable-form__body__table">
                    <tr>
                        <td class="border-left-only" width="45%"></td>
                        <td class="no-border" width="10%"></td>
                        <td class="border-right-only" width="45%">
                            @if($data['approver'] != null)
                            Very truly yours,
                            <strong class="margin-top">{{$data['approver']->ranks}} {{$data['approver']->name}} {{$data['approver']->branch}}</strong>
                            {{$data['approver']->designation}}
                            @endif
                        </td>
                    </tr>
                </table>
                <table class="printable-form__body__table">
                    <tr>
                        <td class="border-left-only" width="9%" height="30px"></td>
                        <td class="no-border" width="1%" height="40px">Conforme:</td>
                        <td class="border-bottom-only" width="40%" height="30px"></td>
                        <td class="border-right-only" width="50%" height="30px"></td>
                    </tr>
                    <tr>
                        <td class="border-left-only" colspan="2" height="30px"></td>
                        <td class="align-center no-padding border-bottom-only" height="30px">Signature over printed name of Supplier</td>
                        <td class="border-right-only" height="30px"></td>
                    </tr>
                    <tr>
                        <td class="border-left-only" colspan="2"></td>
                        <td class="align-center no-padding no-border">Date</td>
                        <td class="border-right-only"></td>
                    </tr>
                    <tr>
                        <td class="no-border-top" colspan="4"></td>
                    </tr>
                </table>
                <table class="printable-form__body__table">
                    <tr>
                        <td class="border-left-only" width="13%" height="30px">Funds Available:</td>
                        <td class="no-border" width="1%"></td>
                        <td class="no-border" width="30%"></td>
                        <td class="no-border" width="15%"></td>
                        <td class="no-border" width="1%"></td>
                        <td class="no-border" width="30%"></td>
                        <td class="border-right-only" width="10%"></td>
                    </tr>
                    <tr>
                        <td class="no-padding border-left-only"></td>
                        <td class="no-padding no-border" colspan="2"><strong>{{$data['accounting']->ranks}} {{$data['accounting']->name}}, {{$data['accounting']->branch}}</strong></td>
                        <td class="no-padding no-border"></td>
                        <td class="no-padding no-border" nowrap>OBR/BUR No</td>
                        <td class="no-padding border-bottom-only"></td>
                        <td class="no-padding border-right-only"></td>
                    </tr>
                    <tr>
                        <td class="no-padding border-left-only"></td>
                        <td class="no-padding no-border" colspan="2">{{$data['accounting']->designation}}</td>
                        <td class="no-padding no-border"></td>
                        <td class="no-padding no-border"></td>
                        <td class="no-padding no-border"></td>
                        <td class="no-padding border-right-only"></td>
                    </tr>
                    <tr>
                        <td class="no-padding border-left-only"></td>
                        <td class="no-padding no-border">Date</td>
                        <td class="no-padding border-bottom-only"></td>
                        <td class="no-padding no-border"></td>
                        <td class="no-padding no-border" nowrap>Amount</td>
                        <td class="no-padding border-bottom-only"></td>
                        <td class="no-padding border-right-only"></td>
                    </tr>
                    <tr>
                        <td class="no-border-top" colspan="7" height="20px"></td>
                    </tr>
                </table>
                <table class="printable-form__body__table">
                    <tr>
                        <td class="border-left-only" width="12%" height="30px">Approved By:</td>
                        <td class="no-border" width="30%"></td>
                        <td class="no-border" width="19%"></td>
                        <td class="no-border" width="1%"></td>
                        <td class="no-border" width="30%"></td>
                        <td class="border-right-only" width="8%"></td>
                    </tr>
                    <tr>
                        <td class="no-padding border-left-only"></td>
                        <td class="no-padding no-border"><strong>{{$data['coa_signatories']->ranks}} {{$data['coa_signatories']->name}}, {{$data['coa_signatories']->branch}}</strong></td>
                        <td class="no-padding no-border"></td>
                        <td class="no-padding no-border" nowrap>Date</td>
                        <td class="no-padding border-bottom-only"></td>
                        <td class="no-padding border-right-only"></td>
                    </tr>
                    <tr>
                        <td class="no-padding border-left-only"></td>
                        <td class="no-padding no-border">{{$data['coa_signatories']->designation}}</td>
                        <td class="no-padding no-border"></td>
                        <td class="no-padding no-border"></td>
                        <td class="no-padding no-border"></td>
                        <td class="no-padding border-right-only"></td>
                    </tr>
                    <tr>
                        <td class="no-border-top no-border-bottom" colspan="6" height="15px"></td>
                    </tr>
                </table>
            </div>


            {{-- <div class="printable-form__body">
                <span class="printable-form__body__title">Purchase Order / Job Order / Work Order</span>
                <table class="printable-form__body__table">
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
                                {{$data['winner']->tin}}
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
                                {{$data['remarks']}}
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
                                {{$data['delivery_term']}} Days Calendar Days upon conformed of this Purchase Order
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
                                        <td class="head" width="8%">Item No</td>
                                        <td class="head" width="5%">UOM</td>
                                        <td class="head" width="42%">Description</td>
                                        <td class="head" width="5%">Quantity</td>
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
                                        <td colspan="2">{{formatPrice($data['bid_amount'])}}</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <p>Purpose: {{$data['purpose']}}</p>
                <p>In case of failure to make the full delivery within the time specified above, a penalty of one-tenth (1/10) of one percent (1%) for every day of delay shall be imposed. </p>
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
                                {{$data['remarks']}}
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

            </div>


            <div class="printable-form">


                <div class="printable-form__body" style="page-break-inside:avoid">
                @if($data['approver'])

                <p>Very truly yours,</p>

                @endif
                <table class="printable-form__body__table
                              printable-form__body__table--borderless">
                    <tr>
                        <td class="v-align-bottom align-center" width="45%" height="50px"></td>
                        <td class="v-align-bottom align-center" width="10%" height="50px"></td>
                        <td class="v-align-bottom align-center" width="45%" height="50px"></td>
                    </tr>
                    <tr>
                        <td class="signatory align-center v-align-middle" width="45%">
                            <div class="signatory-name">
                                <table>
                                    <tr>
                                        <td width="50%"></td>
                                        <td nowrap>{{($data['approver']) ? $data['approver']->name :""}}</td>
                                        <td width="50%"></td>
                                    </tr>
                                    <tr>
                                        <td width="50%"></td>
                                        <td class="align-justify">
                                            <div class="signatory-rank-justify">
                                                <strong>{{($data['approver']) ? $data['approver']->ranks :""}}</strong>
                                                <span></span>
                                            </div>
                                        </td>
                                        <td width="50%"></td>
                                    </tr>
                                </table>
                            </div>
                            {{($data['approver']) ? $data['approver']->designation :""}}</td>
                        <td width="10%"></td>
                        <td class="signatory align-left v-align-middle" width="45%">CONFORME</td>
                    </tr>
                    <tr>
                        <td class="signatory align-center v-align-bottom" width="45%" height="20px"></td>
                        <td width="10%"></td>
                        <td class="signatory align-center v-align-bottom" width="45%" height="20px"></td>
                    </tr>
                    <tr>
                        <td class="signatory align-center" width="45%" height="10px"></td>
                        <td width="10%"></td>
                        <td class="signatory align-center v-align-bottom" width="45%" height="10px"></td>
                    </tr>
                    <tr>
                        <td class="signatory align-center" width="45%"></td>
                        <td width="10%"></td>
                        <td class="signatory align-center" width="45%">
                            <span class="conforme-label">Signature over Printed Name of Supplier</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="signatory align-center v-align-bottom" width="45%" height="30px"></td>
                        <td width="10%"></td>
                        <td class="signatory align-center v-align-bottom" width="45%" height="30px"></td>
                    </tr>
                    <tr>
                        <td class="signatory align-center" width="45%"></td>
                        <td width="10%"></td>
                        <td class="signatory align-center" width="45%">
                            <span class="conforme-label">Date</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="signatory align-left v-align-middle" width="45%">FUND  LE</td>
                        <td width="10%"></td>
                        <td class="signatory align-left v-align-middle" width="45%"></td>
                    </tr>
                    <tr>
                        <td class="signatory align-center v-align-bottom" width="45%" height="30px">Signature</td>
                        <td width="10%"></td>
                        <td class="signatory align-center v-align-bottom" width="45%" height="30px"></td>
                    </tr>
                    <tr>
                        <td class="signatory align-center v-align-bottom" width="45%" height="10px">{{$data['accounting']->name}} / {{\Carbon\Carbon::createFromFormat('Y-m-d',$data['mfo_release_date'])->format('M/d/Y')}}</td>
                        <td width="10%"></td>
                        <td class="signatory align-center v-align-bottom" width="45%" height="10px"></td>
                    </tr>
                    <tr>
                        <td class="signatory align-center" width="45%">
                            <span class="conforme-label">{{$data['accounting']->designation}}</span>
                        </td>
                        <td width="10%"></td>
                        <td class="signatory align-center" width="45%">
                            <span class="conforme-label">OBR/BUR Number</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="signatory align-center v-align-bottom" width="45%" height="30px"></td>
                        <td width="10%"></td>
                        <td class="signatory align-center v-align-bottom" width="45%" height="30px">Data</td>
                    </tr>
                    <tr>
                        <td class="signatory align-center" width="45%"></td>
                        <td width="10%"></td>
                        <td class="signatory align-center" width="45%">
                            <span class="conforme-label">Amount</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="signatory align-left v-align-middle" width="45%">APPROVED BY</td>
                        <td width="10%"></td>
                        <td class="signatory align-left v-align-middle" width="45%"></td>
                    </tr>
                    <tr>
                        <td class="signatory align-center v-align-bottom" width="45%" height="30px">Signature</td>
                        <td width="10%"></td>
                        <td class="signatory align-center v-align-bottom" width="45%" height="30px"></td>
                    </tr>
                    <tr>
                        <td class="signatory align-center v-align-bottom" width="45%" height="10px">{{$data['coa_signatories']->name}} / {{\Carbon\Carbon::createFromFormat('Y-m-d',$data['coa_approved_date'])->format('M/d/Y')}}</td>
                        <td width="10%"></td>
                        <td class="signatory align-center v-align-bottom" width="45%" height="10px"></td>
                    </tr>
                    <tr>
                        <td class="signatory align-center" width="45%">
                            <span class="conforme-label">{{$data['coa_signatories']->designation}}</span>
                        </td>
                        <td width="10%"></td>
                        <td class="signatory align-center" width="45%"></td>
                    </tr>
                </table>
                <t --}}{{-- able class="printable-form__body__table
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
                </table>--}}
                {{-- </div> --}}
                {{-- </div> --}}
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