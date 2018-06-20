<!DOCTYPE html>
<html lang="en">
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
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

        <div class="printable-form-wrapper" style="padding-top:50px">

            <div class="printable-form">
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
                            {!! $data['unitHeader']!!}
                        </span>
                    </div>
                    <!-- title -->
                    <span class="printable-form__body__title">Notice to Proceed</span>
                    <!-- content -->
                    <p>{{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$data['transaction_date'])->format('d F Y')}}</p>
                    <p>

                        {{$data['supplier']->owner}}<br>
                        {{$data['supplier']->name}}<br>
                        {{$data['supplier']->address}}
                    </p>
                    <p>Dear Sir/Madam,</p>
                    <p>This is to inform you of the approval of the @if($data['po_type'] == 'purchase_order')PURCHASE ORDER No.@elseif($data['po_type'] == 'work_order')WORK ORDER No.@elseif($data['po_type'] == 'job_order')JOB ORDER No.@else CONTRACT ORDER No.@endif {{$data['po_number']}} for the procurement of {{translateToWordsPoints($data['items'][0]->quantity)}} ({{$data['items'][0]->quantity}}) {{$data['items'][0]->item_description}} @if(count($data['items']) > 1) and {{count($data['items']) - 1}} Other LI @endif in the amount of {{translateToWords($data['total_amount'])}} Pesos (Php {{formatPrice($data['total_amount'])}}) ONLY</p>
                    <p>In connection with this, you are responsible for performing the stipulated terms and conditions of the Agreement and must abide with the implementation schedule.</p>
                    <p>Please acknowledge receipt and acceptance of this notice by signing both copies in the spaces provided below. Keep a copy for you reference and return the other to the {{$data['center']}}.</p>

                  {{--   <p>Please be informed that the @if($data['po_type'] == 'purchase_order')PURCHASE ORDER No.@elseif($data['po_type'] == 'work_order')WORK ORDER No.@elseif($data['po_type'] == 'job_order')JOB ORDER No.@else CONTRACT ORDER No.@endif {{$data['po_number']}} dated {{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$data['transaction_date'])->format('d F Y')}} prepared in your favor for the procurement of {{translateToWords($data['items'][0]->quantity)}} ({{$data['items'][0]->quantity}}) {{$data['items'][0]->item_description}} @if(count($data['items']) > 1) and {{count($data['items']) - 1}} Other LI @endif only amounting to <strong>{{translateToWords($data['total_amount'])}} Pesos (Php {{formatPrice($data['total_amount'])}}) ONLY</strong> is hereby approved.</p>
                    <p>May we request you or your authorized representative to receive this notice and the approved PURCHASE ORDER not later than {{translateToWords($data['delivery_terms'])}} ({{$data['delivery_terms']}}) calendar days reckoned from the date of this notice.</p>
                    <p>Furthermore, be reminded that failure on your part to receive document on the specified date is ground for cancellation of the aforesaid @if($data['po_type'] == 'purchase_order')PURCHASE ORDER No.@elseif($data['po_type'] == 'work_order')WORK ORDER No.@elseif($data['po_type'] == 'job_order')JOB ORDER No.@else CONTRACT ORDER No.@endif.</p> --}}

                    <p></p>
                    <table class="printable-form__body__table no-border no-padding">
                        <tr>
                            <td></td>
                            <td></td>
                            <td>Very truly yours;</td>
                        </tr>
                        <tr>
                            <td class="align-bottom" width="45%"></td>
                            <td width="10%"></td>
                            <td class="align-bottom align-left" width="45%" height="80px">
                                <strong>{{$data['signatory'][1]}} {{$data['signatory'][0]}} {{$data['signatory'][2]}}</strong><br>
                                {{$data['signatory'][3]}}
                            </td>
                        </tr>
                    </table>
                    <table class="printable-form__body__table no-border no-padding">
                        <tr>
                            <td class="align-bottom" width="1%" nowrap>I acknowledge receipt of this Notice on </td>
                            <td class="align-center align-bottom" width="300px"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="align-bottom" width="1%" nowrap></td>
                            <td class="align-center align-bottom border-top-only" width="300px"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td height="20px"></td>
                            <td height="20px"></td>
                            <td height="20px"></td>
                        </tr>
                        <tr>
                            <td class="align-bottom" width="1%" nowrap>Name of the Representative of the Bidder</td>
                            <td class="align-center align-bottom" width="300px"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="align-bottom" width="1%" nowrap></td>
                            <td class="align-center align-bottom border-top-only" width="300px"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td height="20px"></td>
                            <td height="20px"></td>
                            <td height="20px"></td>
                        </tr>
                        <tr>
                            <td class="align-bottom" width="1%" nowrap>Authorized Signature </td>
                            <td class="align-center align-bottom" width="300px"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="align-bottom" width="1%" nowrap></td>
                            <td class="align-center align-bottom border-top-only" width="300px"></td>
                            <td></td>
                        </tr>
                    </table>
                </div>
            </div>

        </div>

    </body>
</html>