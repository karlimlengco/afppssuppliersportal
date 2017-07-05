<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="stylesheet" href="{{base_path('fonts/Nunito_Sans/css/nunitosans.css')}}">
        <link rel="stylesheet" href="{{base_path('public/css/main.css')}}">

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
                    <span class="printable-form__body__title">Notice of Delivery / Request for Inspection</span>
                    <p>{{$data['transaction_date']}}</p>
                    <p>To: All Concerned</p>
                    <ol>
                        <li>Reference: Approved PURCHASE ORDER No.  {{$data['po_number']}}</li>
                        <li>Per reference above, {{$data['winner']}} will be delivering {{$data['project_name']}} only at {{$data['center']}} on {{$data['expected_date']}} in the {{translateToWords($data['bid_amount'])}} (Php{{formatPrice($data['bid_amount'])}}) ONLY. </li>
                        <li>Request acknowledge receipt. </li>
                    </ol>
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
                                            <td class="align-justify" style="text-align-last: justify !important; text-align: justify;"> <pre style="border:none"> <?php echo $data['signatory']->ranks; ?></pre> </td>
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
                            <td class="signatory align-center v-align-middle" width="45%"></td>
                            <td width="10%"></td>
                            <td class="signatory align-left v-align-middle" width="45%">DISTRIBUTIONS</td>
                        </tr>
                        <tr>
                            <td class="signatory align-center v-align-bottom" width="45%" height="50px"></td>
                            <td width="10%"></td>
                            <td class="signatory align-center v-align-bottom" width="45%" height="50px">    </td>
                        </tr>
                        <tr>
                            <td class="signatory align-center" width="45%"></td>
                            <td width="10%"></td>
                            <td class="signatory align-center" width="45%">
                                <span class="conforme-label">C, TIAC</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="signatory align-center v-align-bottom" width="45%" height="30px"></td>
                            <td width="10%"></td>
                            <td class="signatory align-center v-align-bottom" width="45%" height="30px">    </td>
                        </tr>
                        <tr>
                            <td class="signatory align-center" width="45%"></td>
                            <td width="10%"></td>
                            <td class="signatory align-center" width="45%">
                                <span class="conforme-label">COA</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="signatory align-center v-align-bottom" width="45%" height="30px"></td>
                            <td width="10%"></td>
                            <td class="signatory align-center v-align-bottom" width="45%" height="30px">    </td>
                        </tr>
                        <tr>
                            <td class="signatory align-center" width="45%"></td>
                            <td width="10%"></td>
                            <td class="signatory align-center" width="45%">
                                <span class="conforme-label">MFO</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="signatory align-center v-align-bottom" width="45%" height="30px"></td>
                            <td width="10%"></td>
                            <td class="signatory align-center v-align-bottom" width="45%" height="30px">    </td>
                        </tr>
                        <tr>
                            <td class="signatory align-center" width="45%"></td>
                            <td width="10%"></td>
                            <td class="signatory align-center" width="45%">
                                <span class="conforme-label">SAO</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="signatory align-center v-align-bottom" width="45%" height="30px"></td>
                            <td width="10%"></td>
                            <td class="signatory align-center v-align-bottom" width="45%" height="30px">    </td>
                        </tr>
                        <tr>
                            <td class="signatory align-center" width="45%"></td>
                            <td width="10%"></td>
                            <td class="signatory align-center" width="45%">
                                <span class="conforme-label">Recipient Unit</span>
                            </td>
                        </tr>
                    </table>
                </div>
                <!-- form footer -->
                <div class="printable-form__foot">
                    <table class="printable-form__foot__table">
                        <tr>
                            <td colspan="2">
                                <p class="printable-form__foot__values">AFP Core Values: Honor, Service, Patriotism</p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="printable-form__foot__ref">{{$data['po_number']}} {{$data['transaction_date']}}</span>
                            </td>
                            <td>
                                <span class="printable-form__foot__code"><img src="{{base_path('public/img/barcode.png')}}" alt=""></span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

        </div>

    </body>
</html>