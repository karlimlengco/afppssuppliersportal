<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="stylesheet" href="{{base_path('fonts/Nunito_Sans/css/nunitosans.css')}}">
        <link rel="stylesheet" href="{{base_path('public/css/main.css')}}">
        <style type="text/css">
            body{
                margin:0;
                padding:0;
            }
            @page{
                margin:0;
                padding:0;
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
                    <span class="printable-form__body__title">Notice to Proceed</span>
                    <p>{{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$data['transaction_date'])->format('d F Y')}}</p>
                    <p>
                        {{$data['supplier']->owner}}<br>
                        {{$data['supplier']->name}}<br>
                        {{$data['supplier']->address}}
                    </p>
                    <p>Dear Madam,</p>
                    <p>Please be informed that the PURCHASE ORDER No. {{$data['po_number']}} dated {{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$data['transaction_date'])->format('d F Y')}} prepared in your favor for the procurement of {{translateToWords($data['items'][0]->quantity)}} ({{$data['items'][0]->quantity}}) {{$data['items'][0]->item_description}} @if(count($data['items']) < 1) and {{count($data['items']) - 1}} @endif only amounting to <strong>{{translateToWords($data['total_amount'])}} (Php{{formatPrice($data['total_amount'])}})</strong> is hereby approved.</p>
                    <p>May we request you or your authorized representative to receive this notice and the approved PURCHASE ORDER not later than seven (7) calendar days reckoned from the date of this notice.</p>
                    <p>Furthermore, be reminded that failure on your part to receive document on the specified date is ground for cancellation of the aforesaid PURCHASE ORDER.</p>
                    <p>Very truly yours;</p>

                    <table class="printable-form__body__table
                                  printable-form__body__table--borderless">
                        <tr>
                            <td width="45%" height="80px"></td>
                            <td width="10%" height="80px"></td>
                            <td width="45%" height="80px"></td>
                        </tr>
                        <tr>
                            <td class="signatory align-center v-align-middle" width="45%">
                                <div class="signatory-name">
                                    <table>
                                        <tr>
                                            <td><strong>{{$data['signatory']->name}} {{$data['signatory']->ranks}}</strong></td>
                                        </tr>
                                        <tr>
                                            <td>{{$data['signatory']->designation}}</td>
                                        </tr>
                                    </table>
                                </div>
                            </td>
                            <td width="10%"></td>
                            <td class="signatory align-left v-align-middle" width="45%"></td>
                        </tr>
                    </table>
                    <table class="printable-form__body__table
                                  printable-form__body__table--borderless">
                        <tr>
                            <td class="signatory v-align-bottom" width="1%" height="40px" nowrap>I acknowledge receipt of this Notice on </td>
                            <td class="signatory align-center v-align-bottom" width="300px" height="40px">
                                <span class="conforme-label move">Date</span>
                            </td>
                            <td width="30%"></td>
                        </tr>
                        <tr>
                            <td class="signatory v-align-bottom" width="1%" height="60px" nowrap>Name of the Representative of the Bidder </td>
                            <td class="signatory align-center v-align-bottom" width="300px" height="40px">
                                <span class="conforme-label move">Authorized Signature Over Printed Name</span>
                            </td>
                            <td width="30%"></td>
                        </tr>
                    </table>
                </div>
{{--                 <div class="printable-form__body">
                    <span class="printable-form__body__title">Notice to Proceed</span>
                    <p>
                    {{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$data['transaction_date'])->format('d F Y')}}
                    <p>
                        {{$data['supplier']->owner}}<br>
                        {{$data['supplier']->name}}<br>
                        {{$data['supplier']->address}}
                    </p>
                    <p>Dear Madam,</p>
                    <p>Please be informed that the PURCHASE ORDER No. {{$data['po_number']}} dated {{$data['po_transaction_date']}} prepared in your favor for the procurement of {{$data['project_name']}} only amounting to {{translateToWords($data['total_amount'])}} (Php {{formatPrice($data['total_amount'])}}) ONLY is hereby approved.</p>
                    <p>May we request you or your authorized representative to receive this notice and the approved PURCHASE ORDER not later than seven (7) calendar days reckoned from the date of this notice.</p>
                    <p>Furthermore, be reminded that failure on your part to receive document on the specified date is ground for cancellation of the aforesaid PURCHASE ORDER.</p>
                    <p>Very truly yours;</p>

                    <table class="printable-form__body__table
                                  printable-form__body__table--borderless">
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
                                            <td nowrap>{{$data['signatory']->name}}</td>
                                            <td width="50%"></td>
                                        </tr>
                                        <tr>
                                            <td width="50%"></td>

                                            <td class="align-justify">
                                                <div class="signatory-rank-justify">
                                                    <strong>{{$data['signatory']->ranks}}</strong>
                                                    <span></span>
                                                </div>
                                            </td>
                                            <td width="50%"></td>
                                        </tr>
                                    </table>
                                </div>
                                {{$data['signatory']->designation}}
                            </td>
                            <td width="10%"></td>
                            <td class="signatory align-left v-align-middle" width="45%"></td>
                        </tr>
                        <tr>
                            <td class="signatory align-center v-align-bottom" width="45%" height="60px">I acknowledge receipt of this Notice on</td>
                            <td width="10%"></td>
                            <td class="signatory align-center v-align-bottom" width="45%" height="60px">Name of the Representative of the Bidder</td>
                        </tr>
                        <tr>
                            <td class="signatory align-center v-align-bottom" width="45%" height="40px"></td>
                            <td width="10%"></td>
                            <td class="signatory align-center v-align-bottom" width="45%" height="40px"></td>
                        </tr>
                        <tr>
                            <td class="signatory align-center v-align-bottom" width="45%" height="10px"></td>
                            <td width="10%"></td>
                            <td class="signatory align-center v-align-bottom" width="45%" height="10px"></td>
                        </tr>
                        <tr>
                            <td class="signatory align-center" width="45%">
                                <span class="conforme-label">Date</span>
                            </td>
                            <td width="10%"></td>
                            <td class="signatory align-center" width="45%">
                                <span class="conforme-label">Signature over Printed Name</span>
                            </td>
                        </tr>
                    </table>
                </div>
 --}}                <!-- form footer -->
            </div>

        </div>

    </body>
</html>