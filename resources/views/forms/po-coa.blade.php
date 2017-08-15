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
                    <span class="printable-form__body__title">APPROVED PURCHASE ORDER/WORK ORDER/JOB ORDER</span>
                    <p>28 December 2015</p>
                    <p>
                        Resident Auditor <br>
                        Commission on Audit<br>
                    </p>
                    <p>Dear Sir/Madame,</p>
                    <p>This is in reference to COA Circular No. 2009-001 dated February 12, 2009 regarding submission of approved purchase order, work order and job order.</p>
                    <p>In compliance with the above reference, submitted herewith is the approved PO/WO/JO of 302nd on.</p>
                    <table class="printable-form__body__table classic">
                        <tr>
                            <td width="25%">PO/WO/JO No.</td>
                            <td width="30%">Nomenclature</td>
                            <td width="20%">Amount</td>
                            <td width="25%">Supplier</td>
                        </tr>
                        <tr>
                            <tr>
                                <td>{{$data['po_number']}}</td>
                                <td>{{translateToWords($data['items'][0]->quantity)}} ({{$data['items'][0]->quantity}}) {{$data['items'][0]->item_description}} @if(count($data['items']) < 1) and {{count($data['items']) - 1}} @endif</td>
                                <td>{{formatPrice($data['bid_amount'])}}</td>
                                <td>{{$data['winner']}}</td>
                            </tr>
                        </tr>
                    </table>
                    <p>Very truly yours;</p>
                    <!-- form signatories -->
                    <table class="printable-form__body__table
                                  printable-form__body__table--borderless">
                        <tr>
                            <td width="40%" height="80px"></td>
                            <td width="20%" height="80px"></td>
                            <td width="40%" height="80px"></td>
                        </tr>
                        <tr>
                            <td class="signatory align-center v-align-middle" width="45%">
                                <div class="signatory-name">
                                    <table>
                                        <tr>
                                            <td nowrap><strong>{{$data['coa_signatory']->name}}</strong></td>
                                            <td width="100%"></td>
                                        </tr>
                                        <tr>
                                            <td class="align-justify">
                                                <div class="signatory-rank-justify">
                                                    <strong>{{$data['coa_signatory']->ranks}}</strong>
                                                    <span></span>
                                                </div>
                                            </td>
                                            <td width="100%"></td>
                                        </tr>
                                        <tr>
                                            <td class="v-align-bottom" height="20px">{{$data['coa_signatory']->designation}}</td>
                                            <td width="100%" height="20px"></td>
                                        </tr>
                                    </table>
                                </div>
                            </td>
                            <td width="10%"></td>
                            <td class="signatory align-left v-align-middle" width="45%"></td>
                        </tr>
                    </table>
                </div>
                <!-- form footer -->
            </div>

        </div>

    </body>
</html>