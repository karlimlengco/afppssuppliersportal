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
                <br>
                <br>
                <!-- form content -->
                <div class="printable-form__body">
                    <span class="printable-form__body__title">Notice of Delivery / Request for Inspection</span>
                    <p>@if($data['transaction_date']){{\Carbon\Carbon::createFromFormat('Y-m-d',$data['transaction_date'])->format('d F Y')}}
                    @endif</p>
                    <p>To: All Concerned</p>
                    <ol>
                        <li>Reference: Approved PURCHASE ORDER No. {{$data['po_number']}}</li>
                        <li>Per reference above, {{$data['winner']}} will be delivering <span style="text-transform:uppercase">{{translateToWords($data['items'][0]->quantity)}}</span> ({{$data['items'][0]->quantity}}) {{$data['items'][0]->item_description}} @if(count($data['items']) > 1) and  <span style="text-transform:uppercase">{{translateToWords( count($data['items']) - 1 )}}</span> ({{ count($data['items']) - 1}}) LI @endif only at {{$data['center']}} on {{\Carbon\Carbon::createFromFormat('Y-m-d',$data['expected_date'])->format('d F Y')}} in the total amount of <strong>{{translateToWords($data['bid_amount'])}} (Php{{formatPrice($data['bid_amount'])}}) ONLY</strong>. </li>
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
                            <td class="signatory align-center v-align-middle" width="45%"></td>
                            <td width="10%"></td>
                            <td class="signatory align-left v-align-middle" width="45%">
                                <div class="signatory-name">
                                    <table>
                                        <tr>
                                            <td nowrap><strong>{{$data['signatory']->name}} {{$data['signatory']->ranks}}</strong></td>
                                            <td width="100%"></td>
                                        </tr>
                                        <tr>
                                            <td class="align-justify">
                                                <div class="signatory-rank-justify">
                                                    <strong>{{$data['signatory']->designation}}</strong>
                                                    <span></span>
                                                </div>
                                            </td>
                                            <td width="100%"></td>
                                        </tr>
                                        <tr>
                                            <td class="v-align-bottom" height="20px">Chief</td>
                                            <td width="100%" height="20px"></td>
                                        </tr>
                                    </table>
                                </div>
                            </td>
                        </tr>
                    </table>
                    <table class="printable-form__body__table
                                  printable-form__body__table--borderless">
                        <tr>
                            <td class="signatory align-left v-align-middle">Distributions:</td>
                        </tr>
                        <tr>
                            <td class="signatory v-align-bottom" width="1%" height="40px" nowrap>C, TIAC</td>
                            <td class="signatory align-center v-align-bottom" width="100px">
                                <span class="conforme-label move"></span>
                            </td>
                            <td width="65%"></td>
                        </tr>
                        <tr>
                            <td class="signatory v-align-bottom" width="1%" height="30px" nowrap>COA</td>
                            <td class="signatory align-center v-align-bottom" width="100px">
                                <span class="conforme-label move"></span>
                            </td>
                            <td width="65%"></td>
                        </tr>
                        <tr>
                            <td class="signatory v-align-bottom" width="1%" height="30px" nowrap>MFO</td>
                            <td class="signatory align-center v-align-bottom" width="100px">
                                <span class="conforme-label move"></span>
                            </td>
                            <td width="65%"></td>
                        </tr>
                        <tr>
                            <td class="signatory v-align-bottom" width="1%" height="30px" nowrap>SAO</td>
                            <td class="signatory align-center v-align-bottom" width="100px">
                                <span class="conforme-label move"></span>
                            </td>
                            <td width="65%"></td>
                        </tr>
                        <tr>
                            <td class="signatory v-align-bottom" width="1%" height="30px" nowrap>Recipient Unit</td>
                            <td class="signatory align-center v-align-bottom" width="100px">
                                <span class="conforme-label move"></span>
                            </td>
                            <td width="65%"></td>
                        </tr>
                    </table>
                    {{-- <span class="printable-form__body__title">Notice of Delivery / Request for Inspection</span>
                    <p>

                    {{\Carbon\Carbon::createFromFormat('Y-m-d',$data['transaction_date'])->format('d F Y')}}</p>
                    <p>To: All Concerned</p>
                    <ol>
                        <li>Reference: Approved PURCHASE ORDER No.  {{$data['po_number']}}</li>
                        <li>Per reference above, {{$data['winner']}} will be delivering {{$data['project_name']}} only at {{$data['center']}} on {{$data['expected_date']}} in the amount of {{translateToWords($data['bid_amount'])}} (Php{{formatPrice($data['bid_amount'])}}) ONLY. </li>
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
                    </table> --}}
                </div>
                <!-- form footer -->
            </div>

        </div>

    </body>
</html>