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
                    <span class="printable-form__body__title">Certificate of Reasonableness of Price</span>
                    <!-- content -->
                    <p>{{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$data['date'])->format('d F Y')}}</p>
                    <p class="indent-first-line">THIS IS TO CERTIFY that the price indicated in the PURCHASE ORDER No. {{$data['ref_number']}} is the lowest obtainable price as a result of the open market canvass made by this unit and found that the prices are in favor of the government.</p>
                    <p class="indent-first-line">THIS CERTIFICATION is made to support the procurement of {{translateToWords($data['items'][0]->quantity)}} ({{$data['items'][0]->quantity}}) {{$data['items'][0]->item_description}} @if(count($data['items']) > 1) and {{count($data['items']) - 1}} @endif only FOR THE USE OF {{$data['center']}}</p>
                    <table class="printable-form__body__table no-border no-padding">
                        <tr>
                            <td colspan="3" height="60px"></td>
                        </tr>
                        <tr>
                            <td width="45%"></td>
                            <td width="10%"></td>
                            <td width="45%">
                                @if($data['signatory'] != null)
                                    <table class="signatory">
                                        <tr>
                                            <td nowrap><strong>{{$data['signatory'][0]}}</strong></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="signatory-rank-justify">
                                                    <strong>{{$data['signatory'][1]}} {{$data['signatory'][2]}}</strong>
                                                    <span></span>
                                                </div>
                                            </td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>{{$data['signatory'][3]}}</td>
                                            <td></td>
                                        </tr>
                                    </table>
                                </td>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>

            </div>
        </div>

    </body>
</html>