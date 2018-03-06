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
                </div>
                <!-- form body -->
                <div class="printable-form__body">
                    <!-- letterhead -->
                    <div class="printable-form__letterhead">
                        <span class="printable-form__letterhead__details">
                            {!!$data['unitHeader']!!}
                        </span>
                    </div>
                    <!-- title -->
                    <span class="printable-form__body__title">Certificate of Inspection</span>
                    <!-- content -->
                    <p>THIS IS TO CERTIFY that we, the undersigned had conducted actual physical inventory of delivered goods under the following information.</p>
                    <table class="printable-form__body__table no-border">
                        <tr>
                            <td width="30%">1. SUPPLIER:</td>
                            <td class="align-left" width="70%"><strong>{{$data['supplier']->name}}</strong></td>
                        </tr>
                        <tr>
                            <td>2. ADDRESS:</td>
                            <td class="align-left"><strong>{{$data['supplier']->address}}</strong></td>
                        </tr>
                        <tr>
                            <td>3. PURCHASE ORDER NO:</td>
                            <td class="align-left"><strong>{{$data['po_number']}}</strong></td>
                        </tr>
                        <tr>
                            <td>4. DELIVERY RECEIPT NO:</td>
                            <td class="align-left"><strong>{{$data['delivery_number']}} ({{\Carbon\Carbon::createFromFormat('!Y-m-d',$data['delivery_date'])->format('d F Y')}})</strong></td>
                        </tr>
                        <tr>
                            <td>5. INVOICE NO:</td>
                            <td class="align-left">
                            <strong>
                            @foreach($data['invoice'] as $invoice)
                                {{$invoice->invoice_number}}
                            @endforeach
                            </strong></td>
                        </tr>
                        <tr>
                            <td>6. PLACE OF DELIVERY:</td>
                            <td class="align-left"><strong>{{$data['place']}}</strong></td>
                        </tr>
                        <tr>
                            <td>7. NATURE OF DELIVERY:</td>
                            <td class="align-left"><strong>{{$data['nature_of_delivery']}}</strong></td>
                        </tr>
                        <tr>
                            <td>8. DATE OF INSPECTION:</td>
                            <td class="align-left"><strong>{{\Carbon\Carbon::createFromFormat('!Y-m-d',$data['inspection_date'])->format('d F Y')}}</strong></td>
                        </tr>
                        <tr>
                            <td>9. FINDINGS:</td>
                            <td class="align-left"><strong>{{$data['findings']}}</strong></td>
                        </tr>
                        <tr>
                            <td>10. REMARKS/RECOMMENDATIONS:</td>
                            <td class="align-left"><strong>{{$data['recommendation']}}</strong></td>
                        </tr>
                    </table>
                    <table class="printable-form__body__table no-border no-padding">
                        <tr>
                            <td width="30%"></td>
                            <td class="align-bottom align-left" width="40%" height="80px">
                                <strong>{{$data['chairman'][1]}} {{$data['chairman'][0]}} {{$data['chairman'][2]}}</strong><br>
                                {{$data['chairman'][3]}}
                            </td>
                            <td width="30%"></td>
                        </tr>
                        <tr><td height="80px"></td></tr>
                        <tr>
                            <td width="30%"><strong>{{$data['one'][1]}} {{$data['one'][0]}} {{$data['one'][2]}}</strong><br>
                                {{$data['one'][3]}}</td>
                            <td class="align-bottom align-left" width="40%" height="80px">

                            </td>
                            <td width="30%"><strong>{{$data['two'][1]}} {{$data['two'][0]}} {{$data['two'][2]}}</strong><br>
                                {{$data['two'][3]}}</td>
                        </tr>
                        <tr><td height="80px"></td></tr>
                        <tr>
                            <td width="30%"><strong>
                              @if(count($data['three']) != 1)
                            {{$data['three'][1]}} {{$data['three'][0]}} {{$data['three'][2]}}</strong><br>
                                {{$data['three'][3]}}</td>
                                @endif
                            <td class="align-bottom align-left" width="40%" height="80px">

                            </td>
                            <td width="30%"><strong>

                              @if(count($data['four']) != 1)
                                 {{$data['four'][1]}} {{$data['four'][0]}} {{$data['four'][2]}}</strong><br>
                                {{$data['four'][3]}}
                                @endif
                                </td>
                        </tr>
                        <tr><td height="80px"></td></tr>
                        @if(count($data['five']) != 1)
                        <tr>
                            <td width="30%"><strong>{{$data['five'][1]}} {{$data['five'][0]}} {{$data['five'][2]}}</strong><br>
                                {{$data['five'][3]}}</td>
                            <td class="align-bottom align-left" width="40%" height="80px">

                            </td>
                            <td width="30%"></td>
                        </tr>
                        @endif
                    </table>
                </div>


            </div>

        </div>

    </body>
</html>