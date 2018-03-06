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
                    <span class="printable-form__body__title">MFO Inspection</span>
                    <!-- content -->
                    <p>Management FiscalOffice (MFO)</p>
                    <p>Sir/Madam:</p>
                    <p>May I respectfully request the availability of a representative from your office to conduct inspection of goods delivered to this office under PURCHASE ORDER No.{{$data['po_number']}}</p>
                    <table class="printable-form__body__table no-border">
                        <tr>
                            <td width="20%">ACCOUNT OF:</td>
                            <td class="align-left" width="40%"><strong>{{$data['venue']}}</strong></td>
                            <td width="40%"></td>
                        </tr>
                        <tr>
                            <td>DELIVERED AT:</td>
                            <td class="align-left"><strong>{{$data['venue']}}</strong></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>DATE DELIVERED:</td>
                            <td class="align-left"><strong>{{\Carbon\Carbon::createFromFormat('!Y-m-d',$data['delivery_date'])->format('d F Y')}}</strong></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>DEALER/CONTRACTOR:</td>
                            <td class="align-left"><strong>{{$data['winner']}}</strong></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>COMMERCIAL INVOICE:</td>
                            <td class="align-left"><strong>
                            @foreach($data['invoices'] as $invoice)
                                {{$invoice->invoice_number}} ({{\Carbon\Carbon::createFromFormat('!Y-m-d',$invoice->invoice_date)->format('d F Y')}})<br>
                            @endforeach
                            </strong></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>DELIVERY RECEIPT NO.:</td>
                            <td class="align-left"><strong>{{$data['delivery_number']}} ({{\Carbon\Carbon::createFromFormat('!Y-m-d',$data['delivery_date'])->format('d F Y')}})</strong></td>
                            <td></td>
                        </tr>
                    </table>
                    <table class="printable-form__body__table no-border no-padding">
                        <tr>
                            <td class="align-bottom align-left" width="45%" height="60px">
                                @if($data['sao'])
                                <strong>{{$data['sao'][1]}} {{$data['sao'][0]}} {{$data['sao'][2]}}</strong><br>
                                {{$data['sao'][3]}}
                                @endif
                            </td>
                            <td width="10%"></td>
                            <td class="align-bottom" width="45%"></td>
                        </tr>
                        <tr>
                            <td class="border-bottom-only" colspan="3" height="30px"></td>
                        </tr>
                    </table>
                    <p class="align-center">
                        <strong>MFO INSPECTION REPORT</strong><br>
                        (For MFO Portion Only)
                    </p>
                    <p class="align-right">Date:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                    <p>TO:</p>
                    <p>Please conduct inspection on the above-cited request for inspection.</p>
                    <p>FINDINGS/REMARKS:</p>
                    <p>________________________________________________________________________________________________________________</p>
                    <p>________________________________________________________________________________________________________________</p>
                    <p class="align-center"></p>
                    <table class="printable-form__body__table no-border no-padding">
                        <tr>
                            <td colspan="4" height="40px"></td>
                        </tr>
                        <tr>
                            <td width="45%"></td>
                            <td width="10%"></td>
                            <td width="1%" nowrap>INSPECTED BY:</td>
                            <td class="align-bottom" width="45%" height="80px">
                                <strong></strong>
                                <p>____________________________</p>
                                <p>____________________________</p>
                                <p>____________________________</p>
                            </td>
                        </tr>
                        <tr>
                            <td class="align-top" width="45%" height="50px">NOTED BY:

                              <p style="text-indent: 100px">____________________________</p>
                              <p style="text-indent: 100px">____________________________</p>
                              <p style="text-indent: 100px">____________________________</p>
                              </td>
                            <td width="10%" >

                            </td>
                            <td width="1%"></td>
                            <td width="45%"></td>
                        </tr>
                        <tr>
                            <td width="45%">
                                <strong></strong><br>

                            </td>
                            <td width="10%"></td>
                            <td width="1%"></td>
                            <td width="45%"></td>
                        </tr>
                        <tr>
                            <td width="45%"></td>
                            <td width="10%"></td>
                            <td width="1%" nowrap>WITNESSED BY:</td>
                            <td class="align-bottom" width="45%" height="80px">
                                <strong></strong>

                                <p>____________________________</p>
                                <p>____________________________</p>
                                <p>____________________________</p>

                            </td>
                        </tr>
                    </table>
                </div>
            </div>

        </div>

    </body>
</html>