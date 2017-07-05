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
                    <p>{{$data['transaction_date']}}</p>
                    <p>
                        Resident Auditor <br>
                        Commission on Audit
                    </p>
                    <p>Dear Sir / Madame,</p>
                    <p>This is in reference to COA Circular No. 2009-001 dated February 12, 2009 regarding submission of approved purchase order, work order and job order.</p>
                    <p>In compliance with the above reference, submitted herewith is the approved PO/WO/JO of 302nd on.</p>
                    <table class="printable-form__body__table">
                        <thead>
                            <tr>
                                <th class="head" width="20%">PO/WO/JO No.</th>
                                <th class="head" width="40%">Nomenclature</th>
                                <th class="head" width="10%">Amount</th>
                                <th class="head" width="30%">Supplier</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{$data['po_number']}}</td>
                                <td>{{$data['project_name']}}</td>
                                <td>{{formatPrice($data['bid_amount'])}}</td>
                                <td>{{$data['winner']}}</td>
                            </tr>
                        </tbody>
                    </table>
                    <p>Very truly yours;</p>
                    <!-- form signatories -->
                    <table class="printable-form__body__table
                                  printable-form__body__table--borderless">
                        <tr>
                            <td class="v-align-bottom align-center" width="40%" height="80px"></td>
                            <td class="v-align-bottom align-center" width="20%" height="80px"></td>
                            <td class="v-align-bottom align-center" width="40%" height="80px"></td>
                        </tr>
                        <tr>
                            <td class="signatory align-center v-align-middle" width="45%">
                                <div class="signatory-name">
                                    <table>
                                        <tr>
                                            <td width="50%"></td>
                                            <td nowrap>{{$data['coa_signatory']->name}}</td>
                                            <td width="50%"></td>
                                        </tr>
                                        <tr>
                                            <td width="50%"></td>
                                            <td class="align-justify" style="text-align-last: justify !important; text-align: justify;"> <pre style="border:none"> <?php echo $data['coa_signatory']->ranks; ?></pre> </td>
                                            <td width="50%"></td>
                                        </tr>
                                    </table>
                                </div>
                                {{$data['coa_signatory']->designation}}
                            </td>
                            <td width="10%"></td>
                            <td class="signatory align-left v-align-middle" width="45%"></td>
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