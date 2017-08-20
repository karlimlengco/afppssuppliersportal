<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="stylesheet" href="{{base_path('fonts/Nunito_Sans/css/nunitosans.css')}}">
        <link rel="stylesheet" href="{{base_path('public/css/main.css')}}">

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
                    <span class="printable-form__body__title">APPROVED PURCHASE ORDER/WORK ORDER/JOB ORDER</span>
                    <!-- content -->
                    <p>28 December 2015</p>
                    <p>
                        Resident Auditor <br>
                        Commission on Audit<br>
                        HEADER1<br>
                        HEADER2
                    </p>
                    <p>Dear Sir/Madame,</p>
                    <p>This is in reference to COA Circular No. 2009-001 dated February 12, 2009 regarding submission of approved purchase order, work order and job order.</p>
                    <p>In compliance with the above reference, submitted herewith is the approved PO/WO/JO of 302nd on.</p>
                    <table class="printable-form__body__table">
                        <tr>
                            <td class="align-center" width="25%"><strong>PO/WO/JO No.</strong></td>
                            <td class="align-center" width="30%"><strong>Nomenclature</strong></td>
                            <td class="align-center" width="20%"><strong>Amount</strong></td>
                            <td class="align-center" width="25%"><strong>Supplier</strong></td>
                        </tr>
                        <tr>
                            <td class="align-left">{{$data['po_number']}}</td>
                            <td class="align-left">{{translateToWords($data['items'][0]->quantity)}} ({{$data['items'][0]->quantity}}) {{$data['items'][0]->item_description}} @if(count($data['items']) > 1) and {{count($data['items']) - 1}} @endif</td>
                            <td class="align-right">{{formatPrice($data['bid_amount'])}}</td>
                            <td class="align-left">{{$data['winner']}}</td>
                        </tr>
                    </table>
                    <p>Very truly yours;</p>
                    <table class="printable-form__body__table no-border no-padding">
                        <tr>
                            <td width="45%" height="60px"></td>
                            <td width="10%" height="60px"></td>
                            <td width="45%" height="60px"></td>
                        </tr>
                        <tr>
                            <td>
                                <table class="signatory">
                                    <tr>
                                        <td nowrap>{{$data['coa_signatory']->name}}</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="signatory-rank-justify">
                                                <strong>{{$data['coa_signatory']->ranks}} {{$data['coa_signatory']->branch}}</strong>
                                                <span></span>
                                            </div>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>{{$data['coa_signatory']->designation}}</td>
                                        <td></td>
                                    </tr>
                                </table>
                            </td>
                            <td></td>
                            <td></td>
                        </tr>
                    </table>
                </div>
            </div>

        </div>

    </body>
</html>