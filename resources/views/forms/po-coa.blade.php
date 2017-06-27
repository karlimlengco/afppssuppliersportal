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

        <!-- terms-and-condition-po.xps p1 -->
        <div class="printable-form">
            <!-- main page -->
            <div class="printable-form__head">
                <p class="printable-form__head__vision">AFP Vision 2028: A World-class Armed Forces, Source of National Pride</p>
                <div class="printable-form__head__letterhead">
                    <span class="printable-form__head__letterhead__logo">
                        <img src="{{base_path('public/img/form-logo.png')}}" alt="">
                    </span>
                    <span class="printable-form__head__letterhead__details">
                        <strong>302ND Contracting Office</strong><br>
                        ARMED FORCES OF THE PHILIPPINES PROCUREMENT SERVICE<br>
                        Naval Base Pascual Ledesma<br>
                        Fort San Felipe, Cavite City Philippines
                    </span>
                </div>
            </div>

            <div class="printable-form__body">
                <span class="printable-form__body__title">APPROVED PURCHASE ORDER/WORK ORDER/JOB ORDER</span>

                <p>{{$data['today']}}</p>
                <p>
                    Resident Auditor <br>
                    Commission on Audit<br>
                    HEADER1<br>
                    HEADER2
                </p>
                <p>Dear Sir/Madame,</p>
                <p>This is in reference to COA Circular No. 2009-001 dated February 12, 2009 regarding submission of approved purchase order, work order and job order.</p>
                <p>In compliance with the above reference, submitted herewith is the approved PO/WO/JO of 302ND on.</p>
                <table class="printable-form__body__table">
                    <tr>
                        <td>PO/WO/JO NO</td>
                        <td>NOMENCLATURE</td>
                        <td>AMOUNT</td>
                        <td>SUPPLIER</td>
                    </tr>
                    <tr>
                        <td>{{$data['rfq_number']}}</td>
                        <td>{{$data['project_name']}}</td>
                        <td>{{$data['bid_amount']}}</td>
                        <td>{{$data['winner']}}</td>
                    </tr>
                </table>
                <p>Very truly yours;</p>

                <div class="signatory">
                    <table class="signatory__table">
                        <tr>
                            <td>
                                <div class="signatory__details">
                                    <span class="signatory__details__name">{{$data['coa_signatory']->name}}</span>
                                    <span class="signatory__details__position">{{$data['coa_signatory']->designation}}</span>
                                </div>
                            </td>
                            <td></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="printable-form__foot">
                <table class="printable-form__foot__table">
                    <tr>
                        <td colspan="2">
                            <p class="printable-form__foot__values">AFP Core Values: Honor, Service, Patriotism</p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="printable-form__foot__ref">{{$data['rfq_number']}} 111685 {{$data['transaction_date']}}</span>
                        </td>
                        <td>
                            <span class="printable-form__foot__code">
                                    <img src="{{base_path('public/img/barcode.png')}}" alt="">
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

    </body>
</html>