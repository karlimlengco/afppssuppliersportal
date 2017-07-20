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
                    <!-- for dev reference -->
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
                        <span class="printable-form__body__title">Request for Quotation</span>
                        <table class="printable-form__body__table
                                      printable-form__body__table--layout">
                            <tr>
                                <td class="align-left" width="50%">{{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$data['transaction_date'])->format('d F Y')}}</td>
                                <td class="align-right" width="50%">RFQ No: {{$data['rfq_number']}}</td>
                            </tr>
                        </table>
                        <p>Sir / Madame,</p>
                        <p>Please quote your lowest price on the items listed below, subject to the following conditions and submit your quotation duly signed by you or your representative not later than {{$data['deadline']}}</p>
                        <ol>
                            <li>DELIVERY PERIOD IS ATLEAST SEVEN (7) CALENDAR DAYS AT GHQ HEADQUARTERS</li>
                            <li>WARRANTY SHALL BE FOR THE PERIOD OF THREE (3) MONTHS FOR SUPPLIES & MATERIALS, ONE (1) YEAR FOR EQUIPMENT, FROM DATE OF ACCEPTANCE BY THE PROCURING ENTITY OR PRODUCT WARRANTY WHICHEVER IS LONGER.</li>
                            <li>PRICE VALIDITY SHALL BE FIXED DURING THE BIDDERS PERFORMANCE OF THE CONTRACT AND NOT SUBJECT TO VARIATION OR PRICE ESCALATION ON ANY ACCOUNT.</li>
                            <li>PHILGEPS REGISTRATION SHALL BE ATTACHED UPON SUBMISSION OF THE QUOTATION.</li>
                            <li>BIDDERS SHALL SUBMIT ORIGINAL DOCUMENTS SHOWING CERTIFICATIONS OF THE PROJECT BEING OFFERED OR ITS EQUIVALENT, IF NECESSARY.</li>
                            <li>APPROVED BUDGET FOR THE CONTRACT IS:  {{translateToWords($data['total_amount'])}} PESOS ONLY. ( Php {{formatPrice($data['total_amount'])}})</li>
                        </ol>
                        <table class="printable-form__body__table">
                            <thead>
                                <tr>
                                    <th class="head" width="10%">Item No.</th>
                                    <th class="head" width="10%">Qunatity</th>
                                    <th class="head" width="10%">UOM</th>
                                    <th class="head" width="30%">Description</th>
                                    <th class="head" width="10%">Unit Price</th>
                                    <th class="head" width="20%">Total Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data['items'] as $key => $item)
                                    <tr>
                                        <td style="text-align:center">{{$key+1}}</td>
                                        <td>{{$item->quantity}}</td>
                                        <td>{{$item->unit_measurement}}</td>
                                        <td style="text-align:left">{{$item->item_description}}</td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:center">{{$key+1}}</td>
                                        <td>{{$item->quantity}}</td>
                                        <td>{{$item->unit_measurement}}</td>
                                        <td style="text-align:left">{{$item->item_description}}</td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:center">{{$key+1}}</td>
                                        <td>{{$item->quantity}}</td>
                                        <td>{{$item->unit_measurement}}</td>
                                        <td style="text-align:left">{{$item->item_description}}</td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:center">{{$key+1}}</td>
                                        <td>{{$item->quantity}}</td>
                                        <td>{{$item->unit_measurement}}</td>
                                        <td style="text-align:left">{{$item->item_description}}</td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:center">{{$key+1}}</td>
                                        <td>{{$item->quantity}}</td>
                                        <td>{{$item->unit_measurement}}</td>
                                        <td style="text-align:left">{{$item->item_description}}</td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td class="align-center" colspan="6">*** Nothing Follows ***</td>
                                </tr>
                                <tr>
                                    <td colspan="5">Total</td>
                                    <td>{{formatPrice($data['total_amount'])}}</td>
                                </tr>
                            </tbody>
                        </table>
                        <p>After having carefully read and accepted your General Conditions, I/We hereby certify the item/s quoted is of the quality/standard at the price/s noted above.</p>
                        <!-- form signatories -->
                        <table class="printable-form__body__table
                                      printable-form__body__table--borderless">
                            <tr>
                                <td class="signatory align-center v-align-bottom" width="45%" height="80px"></td>
                                <td class="signatory align-center" width="10%"></td>
                                <td class="signatory align-center v-align-bottom" width="45%" height="80px"></td>
                            </tr>
                            <tr>
                                <td class="signatory align-center v-align-bottom" width="45%" height="10px"></td>
                                <td class="signatory align-center" width="10%"></td>
                                <td class="signatory align-center v-align-bottom" width="45%" height="10px"></td>
                            </tr>
                            <tr>
                                <td class="signatory align-center" width="45%"></td>
                                <td class="signatory align-center" width="10%"></td>
                                <td class="signatory align-center" width="45%">
                                    <span class="conforme-label">Signature over Printed Name</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="signatory align-center v-align-bottom" width="45%" height="30px"></td>
                                <td class="signatory align-center" width="10%"></td>
                                <td class="signatory align-center v-align-bottom" width="45%" height="30px"></td>
                            </tr>
                            <tr>
                                <td class="signatory align-center" width="45%"></td>
                                <td class="signatory align-center" width="10%"></td>
                                <td class="signatory align-center" width="45%">
                                    <span class="conforme-label">Business Name</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="signatory align-center v-align-bottom" width="45%" height="30px"></td>
                                <td class="signatory align-center" width="10%"></td>
                                <td class="signatory align-center v-align-bottom" width="45%" height="30px"></td>
                            </tr>
                            <tr>
                                <td class="signatory align-center" width="45%"></td>
                                <td class="signatory align-center" width="10%"></td>
                                <td class="signatory align-center" width="45%">
                                    <span class="conforme-label">Business Address</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="signatory align-center v-align-bottom" width="45%" height="30px"></td>
                                <td class="signatory align-center" width="10%"></td>
                                <td class="signatory align-center v-align-bottom" width="45%" height="30px"></td>
                            </tr>
                            <tr>
                                <td class="signatory align-center" width="45%"></td>
                                <td class="signatory align-center" width="10%"></td>
                                <td class="signatory align-center" width="45%">
                                    <span class="conforme-label">Contact Number</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="signatory align-center v-align-bottom" width="45%" height="30px"></td>
                                <td class="signatory align-center" width="10%"></td>
                                <td class="signatory align-center v-align-bottom" width="45%" height="30px"></td>
                            </tr>
                            <tr>
                                <td class="signatory align-center" width="45%"></td>
                                <td class="signatory align-center" width="10%"></td>
                                <td class="signatory align-center" width="45%">
                                    <span class="conforme-label">Date</span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <!-- form footer -->
                 {{--    <div class="printable-form__foot">
                        <table class="printable-form__foot__table">
                            <tr>
                                <td colspan="2">
                                    <p class="printable-form__foot__values">AFP Core Values: Honor, Service, Patriotism</p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="printable-form__foot__ref">{{$data['rfq_number']}} {{$data['transaction_date']}}</span>
                                </td>
                                <td>
                                    <span class="printable-form__foot__code">
                                        <img src="{{base_path('public/img/barcode.png')}}" alt="">
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div> --}}
                </div>

            </div>

    </body>
</html>