<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="stylesheet" href="{{base_path('fonts/Nunito_Sans/css/nunitosans.css')}}">
        <style type="text/css"  media="all">
            body{
                margin:0;
                padding:0;
            }
            /*
            printable-form.less
            ------------------------------------------------------------------------- */
            .printable-form-wrapper {
              /*display: flex;*/
              /*flex-direction: column;*/
              align-items: center;
            }
            .printable-form {
              position: relative;
              /*display: flex;*/
              /*position:absolute;*/
              /*flex-direction: column;*/
              width: 8.3in;
              height: 11.7in;
              max-height: 11.7in;
              min-height: 11.7in;
              border: solid 1px #cccccc;
            }

            .printable-form * {
              font-size: 12px;
              font-weight: 600;
              color: #545454;
            }
            .printable-form * strong {
              font-weight: 800;
            }
            .printable-form__filename {
              position: absolute;
              bottom: 100%;
              padding: 10px 0;
              left: 0;
            }
            .printable-form__head {
              /*display: flex;*/
              /*align-self: flex-start;*/
              /*flex-direction: column;*/
              width: 100%;
            }
            .printable-form__head__vision {
              display: block;
              width: 100%;
              text-align: center;
              padding: 0;
              margin: 0 0 30px 0;
            }
            .printable-form__head__letterhead {
              display: block;
              width: 100%;
              text-align: center;
            }
            .printable-form__head__letterhead__logo {
              display: inline-block;
              height: 75px;
              width: 75px;
              margin-bottom: 10px;
            }
            .printable-form__head__letterhead__logo img {
              width: 100%;
            }
            .printable-form__head__letterhead__details {
              display: block;
            }
            .printable-form__body {
              /*display: flex;*/
              /*flex-direction: column;*/
              /*flex-grow: 1;*/
              width: 100%;
              padding: 0;
              margin: 50px 0;
            }
            .printable-form__body__title {
              font-weight: 800;
              text-align: center;
              margin-bottom: 20px;
              display: block;
              width: 100%;
            }
            .printable-form__body table,
            .printable-form__body p {
              margin-bottom: 20px;
            }
            .printable-form__body table {
              width: 100%;
              table-layout: auto;
              /* auto or fixed */
              border-collapse: collapse;
            }
            .printable-form__body table tr td {
              font-size: 10px;
              border: solid 1px #adadad;
              padding: 4px 6px;
            }
            .printable-form__foot {
              /*display: flex;*/
              /*flex-direction: column;*/
              /*align-self: flex-end;*/
              width: 100%;
            }
            .printable-form__foot__values {
              display: block;
              width: 100%;
              text-align: center;
              padding: 0;
              margin: 0 0 20px 0;
            }
            .printable-form__foot__details {
              /*display: flex;*/
              /*flex-direction: row;*/
              align-items: center;
            }
            .printable-form__foot__details__left {
              font-size: 8px;
              font-weight: 800;
              text-transform: uppercase;
              width: 50%;
              text-align: left;
            }
            .printable-form__foot__details__right {
              width: 50%;
              height: 20px;
              text-align: right;
            }
            .printable-form__foot__details__right img {
              height: 100%;
            }
            .signatory {
              /*display: flex;*/
              /*flex-direction: column;*/
              position:absolute;
              bottom:0;
            }
            .signatory__box {
              /*display: flex;*/
              /*flex-direction: row;*/
              width: 100%;
              height: 60px;
              margin-top: 10px;
            }
            .signatory__box.conforme {
              height: auto;
            }
            .signatory__box.conforme .signatory__box__left,
            .signatory__box.conforme .signatory__box__right {
              /*flex-direction: column;*/
            }
            .signatory__box.conforme .signatory__box__left .signatory__details,
            .signatory__box.conforme .signatory__box__right .signatory__details {
              /*align-self: flex-start;*/
              margin-top: 30px;
              border-top: solid 1px #adadad;
            }
            .signatory__box.custom-a {
              margin-top: 30px;
              height: auto;
            }
            .signatory__box.custom-a .signatory__box__left,
            .signatory__box.custom-a .signatory__box__right {
              /*flex-direction: row;*/
            }
            .signatory__box.custom-a .signatory__box__left .signatory__details,
            .signatory__box.custom-a .signatory__box__right .signatory__details {
              /*align-self: flex-start;*/
              margin-top: 35px;
              border-top: solid 1px #adadad;
            }
            .signatory__box.extend-left .signatory__box__left {
              width: 80%;
            }
            .signatory__box.extend-left .signatory__box__right {
              width: 20%;
            }
            .signatory__box.extend-left .signatory__top-title {
              white-space: nowrap;
              margin-right: 10px;
            }
            .signatory__box__left {
              /*display: flex;*/
              width: 50%;
              margin-right: 50px;
            }
            .signatory__box__right {
              /*display: flex;*/
              width: 50%;
              margin-left: 50px;
            }
            .signatory__top-title {
              margin-bottom: 15px;
              margin-top: 25px;
            }
            .signatory__details {
              /*display: flex;*/
              /*align-self: flex-end;*/
              /*flex-direction: column;*/
              /*align-items: flex-start;*/
              padding-top: 5px;
              width: 100%;
            }
            .signatory__details.align-center {
              align-items: center;
            }
            .signatory__details.align-right {
              /*align-items: flex-end;*/
            }
            .signatory__details__name {
              text-transform: uppercase;
              font-weight: 700;
            }
            .signatory__details__label {
              font-size: 10px;
            }
        </style>
    </head>

    <body>

        <div class="printable-form-wrapper">
            {{--  --}}
            <div class="printable-form">
                <!-- page info -->
                {{-- <span class="printable-form__filename">rfq.xps p 1/1</span> --}}

                <!-- main page -->
                <div class="printable-form__head">
                    <p class="printable-form__head__vision">AFP Vision 2028: A World-class Armed Forces, Source of National Pride</p>
                    <div class="printable-form__head__letterhead">
                        <span class="printable-form__head__letterhead__logo">
                            <img src="{{public_path('img/logo.png')}}" alt="">
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
                    <span class="printable-form__body__title">REQUEST FOR QUOTATION</span>

                    <div class="row">
                        <div class="six columns"><p>28 December 2015</p></div>
                        <div class="six columns align-right"><p>RFQ No: 302ND-NLC-SPOF-016-15</p></div>
                    </div>

                    <p>SIR/MADAME,</p>
                    <p>Please quote your lowest price on the items listed below, subject to the following conditions and submit your quotation duly signed by you or your representative not later than 040900H January 2016</p>

                    <ol>
                        <li>DELIVERY PERIOD IS ATLEAST SEVEN (7) CALENDAR DAYS AT GHQ HEADQUARTERS</li>
                        <li>WARRANTY SHALL BE FOR THE PERIOD OF THREE (3) MONTHS FOR SUPPLIES &amp; MATERIALS, ONE (1) YEAR FOR EQUIPMENT, FROM DATE OF ACCEPTANCE BY THE PROCURING ENTITY OR PRODUCT WARRANTY WHICHEVER IS LONGER.</li>
                        <li>PRICE VALIDITY SHALL BE FIXED DURING THE BIDDERS PERFORMANCE OF THE CONTRACT AND NOT SUBJECT TO VARIATION OR PRICE ESCALATION ON ANY ACCOUNT.</li>
                        <li>PHILGEPS REGISTRATION SHALL BE ATTACHED UPON SUBMISSION OF THE QUOTATION.</li>
                        <li>BIDDERS SHALL SUBMIT ORIGINAL DOCUMENTS SHOWING CERTIFICATIONS OF THE PROJECT BEING OFFERED OR ITS EQUIVALENT, IF NECESSARY.</li>
                        <li>APPROVED BUDGET FOR THE CONTRACT IS:&nbsp; TWENTY FIVE THOUSAND PESOS ONLY. ( Php &nbsp; 25,000.00)</li>
                    </ol>
                    <table>
                        <tbody><tr>
                            <td>ITEM NO</td>
                            <td>QTY</td>
                            <td>UOM</td>
                            <td>DESCRIPTION</td>
                            <td>UNIT PRICE</td>
                            <td>TOTAL PRICE</td>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td>100</td>
                            <td>RM</td>
                            <td>BOND PAPER A4</td>
                            <td>UNIT PRICE</td>
                            <td>TOTAL PRICE</td>
                        </tr>
                        <tr>
                            <td colspan="5">x-x-x-x-x Nothing Follows x-x-x-x-x</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="5">TOTAL PhP</td>
                            <td></td>
                        </tr>
                    </tbody></table>
                    <p>After having carefully read and accepted your General Conditions, I/We hereby certify the item/s quoted is of the quality/standard at the price/s noted above.</p>
                    <div class="signatory">
                        <div class="signatory__box conforme">
                            <div class="signatory__box__left"></div>
                            <div class="signatory__box__right">
                                <div class="signatory__details align-center">
                                    <span class="signatory__details__label">Signature Over Printed Name</span>
                                </div>
                                <div class="signatory__details align-center">
                                    <span class="signatory__details__label">Business Nam</span>
                                </div>
                                <div class="signatory__details align-center">
                                    <span class="signatory__details__label">Business Address</span>
                                </div>
                                <div class="signatory__details align-center">
                                    <span class="signatory__details__label">Tel No / Cell No</span>
                                </div>
                                <div class="signatory__details align-center">
                                    <span class="signatory__details__label">Date</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="printable-form__foot">
                    <p class="printable-form__foot__values">AFP Core Values: Honor, Service, Patriotism</p>
                    <div class="printable-form__foot__details">
                        <span class="printable-form__foot__details__left">302ND-NLC-SPOF-016-15 111685 281035H December 2015</span>
                        <span class="printable-form__foot__details__right">
                            <img src="{{public_path('img/barcode.png')}}" alt="">
                        </span>
                    </div>
                </div>
            </div>

            {{--  --}}
        </div>
    </body>
</html>