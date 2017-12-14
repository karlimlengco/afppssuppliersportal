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
                            {!!$data['unitHeader']!!}
                        </span>
                    </div>
                    <!-- title -->
                    <span class="printable-form__body__title">Notice of Award</span>
                    <!-- content -->
                    <p>{{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$data['transaction_date'])->format('d F Y')}}</p>
                    <p>
                        {{$data['supplier']->owner}}<br>
                        {{$data['supplier']->name}}<br>
                        {{$data['supplier']->address}}<br>
                        Tel: {{$data['supplier']->phone_1}} Fax: {{$data['supplier']->fax_1}}
                    </p>
                    <p>Dear Sir/Madam,</p>

                    <p style="text-align: justify">We are pleased to notify you that your price offer/s for the procurement of {{translateToWords($data['items'][0]->quantity)}} ({{$data['items'][0]->quantity}}) {{$data['items'][0]->item_description}} @if(count($data['items']) > 1) and {{count($data['items']) - 1}} LI @endif only of RFQ No.{{$data['rfq_number']}} dated {{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$data['rfq_date'])->format('d F Y')}} for the use of {{$data['unit']}} in the amount of {{translateToWords($data['bid_amount'])}} pesos only (Php {{formatPrice($data['bid_amount'])}}) is hereby accepted. A contract for the procurement of this item/s is being awarded to your company/firm.</p>
                    <p  style="text-align: justify">You shall subsequently be informed (thru your contact number or email address specified below) accordingly to appear before this office for the requirements.</p>
                    <p class="indent">
                        - Signing of PURCHASE ORDER<br>
                        - Acknowledgement of the Notice to Proceed
                    </p>
                    <p  style="text-align: justify">Failure to comply with the above requirements within the prescribed period shall be grounds for the cancellation of this award.</p>
                    <p  style="text-align: justify">Meantime, you are requested to affix your conformity hereto and send this back to us not later than two (2) working days upon receipt of this notice. </p>
                    <p>Very truly yours;</p>

                    <table class="printable-form__body__table no-border no-padding">
                        <tr>
                            <td class="align-bottom align-left" width="45%" height="80px">
                                <strong>{{$data['signatory'][1]}} {{$data['signatory'][0]}} {{$data['signatory'][2]}}</strong><br>
                                {{$data['signatory'][3]}}
                            </td>
                            <td width="10%"></td>
                            <td class="align-bottom" width="45%"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td><strong>Conforme:</strong></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td class="align-bottom align-center" height="30px"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td class="align-bottom align-center border-top-only">Signature over Printed Name</td>
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
                        <tr>
                            <td></td>
                            <td></td>
                            <td class="align-bottom align-center" height="30px"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td class="align-bottom align-center border-top-only">Contact Number</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td class="align-bottom align-center" height="30px"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td class="align-bottom align-center border-top-only">Email Address</td>
                        </tr>
                    </table>
                </div>
            </div>

        </div>
    </body>
</html>