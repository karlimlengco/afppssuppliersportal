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
                                <strong>{{$data['header']->name}}</strong><br>
                                Armed Forces of the Philippines Procurement Service<br>
                                {{$data['header']->address}}
                            </span>
                        </div>
                        <!-- title -->
                        <span class="printable-form__body__title">Request for Quotation</span>
                        <!-- content -->
                        <table class="printable-form__body__table no-border no-padding">
                            <tr>
                                <td class="align-left" width="50%">{{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$data['transaction_date'])->format('d F Y')}}</td>
                                <td class="align-right" width="50%">RFQ No: {{$data['rfq_number']}}</td>
                            </tr>
                        </table>
                        <p>SIR/MADAME,</p>
                        <p>Please quote your lowest price on the items listed below, subject to the following conditions and submit your quotation duly signed by you or your representative not later than {{\Carbon\Carbon::createFromFormat( 'Y-m-d H:i', $data['deadline'])->format('dHi F y') }}</p>
                        <table class="printable-form__body__table no-border no-padding">
                            <tr>
                                <td class="align-bottom" width="45%"></td>
                                <td width="10%"></td>
                                <td class="align-bottom align-left" width="45%" height="60px">
                                    <strong> @if($data['chief'] != null)  {{$data['chief'][1]}} {{$data['chief'][0]}} {{$data['chief'][2]}} </strong><br>
                                    {{$data['chief'][3]}}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" height="30px"></td>
                            </tr>
                        </table>
                        <ol>
                            <li>DELIVERY PERIOD IS ATLEAST SEVEN (7) CALENDAR DAYS AT GHQ HEADQUARTERS</li>
                            <li>WARRANTY SHALL BE FOR THE PERIOD OF THREE (3) MONTHS FOR SUPPLIES & MATERIALS, ONE (1) YEAR FOR EQUIPMENT, FROM DATE OF ACCEPTANCE BY THE PROCURING ENTITY OR PRODUCT WARRANTY WHICHEVER IS LONGER.</li>
                            <li>PRICE VALIDITY SHALL BE FIXED DURING THE BIDDERS PERFORMANCE OF THE CONTRACT AND NOT SUBJECT TO VARIATION OR PRICE ESCALATION ON ANY ACCOUNT.</li>
                            <li>PHILGEPS REGISTRATION SHALL BE ATTACHED UPON SUBMISSION OF THE QUOTATION.</li>
                            <li>BIDDERS SHALL SUBMIT ORIGINAL DOCUMENTS SHOWING CERTIFICATIONS OF THE PROJECT BEING OFFERED OR ITS EQUIVALENT, IF NECESSARY.</li>
                            <li>FOR INFRASTRUCTURE PROJECT, INTERESTED PROPONENTS SHOULD SUBMIT CERTIFICATE OF SITE INSPECTION ISSUED BY THE END USER.</li>
                            <li>APPROVED BUDGET FOR THE CONTRACT IS: <strong><strong style="text-transform:uppercase">{{translateToWords($data['total_amount'])}} PESOS ONLY. ( Php{{formatPrice($data['total_amount'])}})</strong></strong></li>
                        </ol>
                        <table class="printable-form__body__table">
                            <tr>
                                <td class="align-center" width="10%"><strong>Item No.</strong></td>
                                <td class="align-center" width="10%"><strong>Qunatity</strong></td>
                                <td class="align-center" width="10%"><strong>UOM</strong></td>
                                <td class="align-center" width="30%"><strong>Description</strong></td>
                                <td class="align-center" width="10%"><strong>Unit Price</strong></td>
                                <td class="align-center" width="20%"><strong>Total Price</strong></td>
                            </tr>
                            <tr>
                                <td  class="align-center" >1</td>
                                <td  class="align-center" >100</td>
                                <td  class="align-center" >RM</td>
                                <td  class="align-left" >BOND PAPER A4</td>
                                <td  class="align-center" ></td>
                                <td  class="align-center" ></td>
                            </tr>
                            <tr>
                                <td class="align-center" colspan="5">x-x-x-x-x Nothing Follows x-x-x-x-x</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="5" class="align-center"><strong>TOTAL PhP</strong></td>
                                <td></td>
                            </tr>
                        </table>
                        <p>After having carefully read and accepted your General Conditions, I/We hereby certify the item/s quoted is of the quality/standard at the price/s noted above.</p>
                        <!-- form signatories -->
                        <table class="printable-form__body__table no-border no-padding">
                            <tr>
                                <td width="45%"></td>
                                <td width="10%"></td>
                                <td width="45%" class="align-bottom align-center" height="40px">{{$data['supplier']->owner}}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td class="align-bottom align-center border-top-only">Signature over Printed Name</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td class="align-bottom align-center" height="30px">{{$data['supplier']->name}}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td class="align-bottom align-center border-top-only">Business Name</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td class="align-bottom align-center" height="30px">{{$data['supplier']->address}}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td class="align-bottom align-center border-top-only">Business Address</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td class="align-bottom align-center" height="30px">{{$data['supplier']->tell_1}} {{$data['supplier']->cell_1}}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td class="align-bottom align-center border-top-only">Tel No. / Cell No.</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td class="align-bottom align-center" height="30px"></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td class="align-bottom align-center border-top-only">Date</td>
                            </tr>
                        </table>
                    </div>
            </div>

    </body>
</html>