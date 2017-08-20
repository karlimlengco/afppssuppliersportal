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
                <br>
                <br>
                <!-- form header -->

                <div class="printable-form__body">
                    <span class="printable-form__body__title">Notice of Award</span>

                    <p{{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$data['transaction_date'])->format('d F Y')}}</p>
                    <p>
                        {{$data['supplier']->owner}}<br>
                        {{$data['supplier']->name}}<br>
                        {{$data['supplier']->address}}<br>
                        Tel: {{$data['supplier']->phone_1}} Fax: {{$data['supplier']->fax_1}}
                    </p>
                    <p>Dear Madam,</p>
                    <p style="text-align: justify">We are pleased to notify you that your price offer/s for the {{translateToWords($data['items'][0]->quantity)}} ({{$data['items'][0]->quantity}}) {{$data['items'][0]->item_description}} @if(count($data['items']) < 1) and {{count($data['items']) - 1}} @endif only of RFQ No.{{$data['rfq_number']}} dated {{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$data['transaction_date'])->format('d F Y')}} for the use of {{$data['center']}} in the amount of {{translateToWords($data['bid_amount'])}} PESOS ONLY (Php {{formatPrice($data['bid_amount'])}}) is hereby accepted. A contract for the procurement of this item/s is being awarded to your company/firm.</p>
                    <p  style="text-align: justify">You shall subsequently be informed (thru your contact number or email address specified below) accordingly to appear before this office for the requirements.</p>
                    <p class="indent">
                        - Signing of PURCHASE ORDER<br>
                        - Acknowledgement of the Notice to Proceed
                    </p>
                    <p  style="text-align: justify">Failure to comply with the above requirements within the prescribed period shall be grounds for the cancellation of this award.</p>
                    <p  style="text-align: justify">Meantime, you are requested to affix your conformity hereto and send this back to us not later than two (2) working days upon receipt of this notice. </p>
                    <p>Very truly yours;</p>
                    <!-- form signatories -->
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
                        <tr>
                            <td class="signatory align-center v-align-middle" width="45%" height="50px"></td>
                            <td width="10%"></td>
                            <td class="signatory align-left v-align-middle" width="45%" height="50px">Conforme:</td>
                        </tr>
                        <tr>
                            <td class="signatory align-center v-align-bottom" width="45%" height="10px"></td>
                            <td width="10%"></td>
                            <td class="signatory align-center v-align-bottom" width="45%" height="10px"></td>
                        </tr>
                        <tr>
                            <td class="signatory align-center" width="45%"></td>
                            <td width="10%"></td>
                            <td class="signatory align-center" width="45%">
                                <span class="conforme-label">Signature over Printed Name</span>
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
                            <td class="signatory align-center v-align-bottom" width="45%" height="30px"></td>
                            <td width="10%"></td>
                            <td class="signatory align-center v-align-bottom" width="45%" height="30px"></td>
                        </tr>
                        <tr>
                            <td class="signatory align-center" width="45%"></td>
                            <td width="10%"></td>
                            <td class="signatory align-center" width="45%">
                                <span class="conforme-label">Contact Number</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="signatory align-center v-align-bottom" width="45%" height="30px"></td>
                            <td width="10%"></td>
                            <td class="signatory align-center v-align-bottom" width="45%" height="30px">example@email.com</td>
                        </tr>
                        <tr>
                            <td class="signatory align-center" width="45%"></td>
                            <td width="10%"></td>
                            <td class="signatory align-center" width="45%">
                                <span class="conforme-label">Email Address</span>
                            </td>
                        </tr>
                    </table>
                </div>
{{--                 <div class="printable-form__body">
                    <span class="printable-form__body__title">Notice of Award</span>

                    <p>
                    {{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$data['transaction_date'])->format('d F Y')}}</p>
                    <p>
                        {{$data['supplier']->owner}}<br>
                        {{$data['supplier']->name}}<br>
                        {{$data['supplier']->address}}<br>
                        Tel: {{$data['supplier']->phone_1}} Fax: {{$data['supplier']->fax_1}}
                    </p>
                    <p>Dear Madam,</p>
                    <p>We are pleased to notify you that your price offer/s for the procurement of {{$data['project_name']}} @if(isset($data['rfq_number']) ) only of RFQ No. {{$data['rfq_number']}} dated {{$data['rfq_date']}} @endif for the use of {{$data['unit']}} in the amount of {{translateToWords($data['bid_amount'])}} PESOS ONLY (Php {{formatPrice($data['bid_amount'])}}) is hereby accepted. A contract for the procurement of this item/s is being awarded to your company/firm.</p>
                    <p>You shall subsequently be informed (thru your contact number or email address specified below) accordingly to appear before this office for the requirements.</p>
                    <ul>
                        <li>Signing of PURCHASE ORDER</li>
                        <li>Acknowledgement of the Notice to Proceed</li>
                    </ul>
                    <p>Failure to comply with the above requirements within the prescribed period shall be grounds for the cancellation of this award.</p>
                    <p>Meantime, you are requested to affix your conformity hereto and send this back to us not later than two (2) working days upon receipt of this notice. </p>
                    <p>Very truly yours;</p>
                    <!-- form signatories -->
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
                            <td width="10%"></td>
                            <td class="signatory align-left v-align-middle" width="45%"></td>
                        </tr>
                        <tr>
                            <td class="signatory align-center v-align-middle" width="45%" height="50px"></td>
                            <td width="10%"></td>
                            <td class="signatory align-left v-align-middle" width="45%" height="50px">CONFORME:</td>
                        </tr>
                        <tr>
                            <td class="signatory align-center v-align-bottom" width="45%" height="20px"></td>
                            <td width="10%"></td>
                            <td class="signatory align-center v-align-bottom" width="45%" height="20px"></td>
                        </tr>
                        <tr>
                            <td class="signatory align-center v-align-bottom" width="45%" height="10px"></td>
                            <td width="10%"></td>
                            <td class="signatory align-center v-align-bottom" width="45%" height="10px"></td>
                        </tr>
                        <tr>
                            <td class="signatory align-center" width="45%"></td>
                            <td width="10%"></td>
                            <td class="signatory align-center" width="45%">
                                <span class="conforme-label">Signature over Printed Name</span>
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
                            <td class="signatory align-center v-align-bottom" width="45%" height="30px"></td>
                            <td width="10%"></td>
                            <td class="signatory align-center v-align-bottom" width="45%" height="30px"></td>
                        </tr>
                        <tr>
                            <td class="signatory align-center" width="45%"></td>
                            <td width="10%"></td>
                            <td class="signatory align-center" width="45%">
                                <span class="conforme-label">Contact Number</span>
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
                                <span class="conforme-label">Email Address</span>
                            </td>
                        </tr>
                    </table>
                </div> --}}
            </div>

        </div>
    </body>
</html>