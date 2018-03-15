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
        <div class="printable-form-wrapper" style="padding-top:50px">

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
                    <p  style="font-size:18px!important;">{{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$data['transaction_date'])->format('d F Y')}}</p>
                    <p  style="font-size:18px!important;">
                        {{$data['supplier']->owner}}<br>
                        {{$data['supplier']->name}}<br>
                        {{$data['supplier']->address}}<br>
                        Tel: {{$data['supplier']->phone_1}} Fax: {{$data['supplier']->fax_1}}
                    </p>
                    <p style="font-size:18px!important;">Dear Sir/Madam,</p>

                    <p style="text-align: justify;font-size:18px!important;">We are pleased to inform you that your bid dated {{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$data['rfq_date'])->format('d F Y')}} for the procurement of {{translateToWords($data['items'][0]->quantity)}} ({{$data['items'][0]->quantity}}) {{$data['items'][0]->item_description}} @if(count($data['items']) > 1) and {{translateToWords(count($data['items']) - 1)}} {{count($data['items']) - 1}} LI @endif under {{$data['rfq_number']}}, with the Contract Price of {{translateToWords( str_replace(',', '', $data['bid_amount']) )}} pesos only (Php {{formatPrice($data['bid_amount'])}}) in accordance with Instructions to Bidders is hereby accepted.

                    <p style="font-size:18px!important;">You are thus required to provide a performance security in the form and amount stipulated in the Instructions to Bidders within ten (10) calendar days. Failure to provide the said performance security shall constitute sufficient ground fo cancellation of the award and the forfeiture of the bid security.</p>
                 {{--    <p class="indent" style="font-size:18px!important;">
                        - Signing of {{$data['account_type']}}<br>
                        - Acknowledgement of the Notice to Proceed
                    </p>
                    <p  style="text-align: justify;font-size:18px!important;">Failure to comply with the above requirements within the prescribed period shall be grounds for the cancellation of this award.</p>
                    <p  style="text-align: justify;font-size:18px!important;">Meantime, you are requested to affix your conformity hereto and send this back to us not later than two (2) working days upon receipt of this notice. </p> --}}
                    {{-- <p style="font-size:18px!important; text-align:right">Very truly yours;</p> --}}

                    <table class="printable-form__body__table no-border no-padding" style="font-size:18px!important;">
                        <tr>
                            <td></td>
                            <td></td>
                            <td><strong>Very truly yours;</strong></td>
                        </tr>
                        <tr>
                            <td class="align-bottom align-left" width="15%" height="80px">

                            </td>
                            <td width="10%"></td>
                            <td class="align-bottom" width="75%" height="80px">
                              <strong>{{$data['signatory'][1]}} {{$data['signatory'][0]}} {{$data['signatory'][2]}}</strong><br>
                                {{$data['signatory'][3]}}
                            </td>
                        </tr>
                        <tr>
                          <td  height="80px"></td>
                          <td></td>
                          <td></td>
                        </tr>
                        <tr>
                            <td style="font-size:18px!important;"><strong>I acknowledge receipt of this Notice on:</strong></td>
                            <td width="30%" class="border-bottom-only"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td style="font-size:18px!important;"><strong>Name of the Representative of the Bidder:</strong></td>
                            <td width="30%" class="border-bottom-only"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td style="font-size:18px!important;"><strong>Authorized Signature:</strong></td>
                            <td width="30%" class="border-bottom-only"></td>
                            <td></td>
                        </tr>
                        {{-- <tr>
                            <td class="align-bottom align-center" height="30px"></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td  class="align-bottom align-center border-top-only">Signature over Printed Name</td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="align-bottom align-center" height="30px"></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="align-bottom align-center border-top-only">Date</td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="align-bottom align-center" height="30px"></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="align-bottom align-center border-top-only">Contact Number</td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="align-bottom align-center" height="30px"></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="align-bottom align-center border-top-only">Email Address</td>
                            <td></td>
                            <td></td>
                        </tr> --}}
                    </table>
                </div>
            </div>

        </div>
    </body>
</html>