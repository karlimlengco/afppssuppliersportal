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
                <!-- form content -->
                <div class="printable-form__body no-letterhead">
                    <span class="printable-form__body__title">Disbursement Voucher</span>
                    <table class="printable-form__body__table
                                  printable-form__body__table--layout">
                        <tr>
                            <td class="align-right">No. 000-000-000</td>
                        </tr>
                    </table>
                    <table class="printable-form__body__table">
                        <tbody>
                            <tr>
                                <td class="align-left" colspan="2">
                                    <span class="label">Payment Mode</span>
                                    <span class="checkbox-item">&#9744; MDS</span>
                                    <span class="checkbox-item">&#9744; Commercial Check</span>
                                    <span class="checkbox-item">&#9744; ADA</span>
                                    <span class="checkbox-item">&#9744; Others</span>
                                </td>
                                <td class="align-left">
                                    <span class="label">Date</span>

                                {{\Carbon\Carbon::createFromFormat('Y-m-d',$data['transaction_date'])->format('d F Y')}}
                                </td>
                            </tr>
                            <tr>
                                <td class="align-left" width="40%">
                                    <span class="label">Payee</span>
                                    {{$data['payee']->name}}
                                </td>
                                <td class="align-left" width="30%">
                                    <span class="label">TIN No.</span>
                                    {{$data['payee']->tin}}
                                </td>
                                <td class="align-left" width="30%">
                                    <span class="label">OR/BUR No.</span>

                                </td>
                            </tr>
                            <tr>
                                <td class="align-left">
                                    <span class="label">Address</span>
                                    {{$data['payee']->address}}
                                </td>
                                <td class="align-left">
                                    <span class="label">Office / Unit / Project</span>
                                    {{$data['upr']->centers->name}} /  {{$data['upr']->unit->short_code}} / {{$data['upr']->project_name}}
                                </td>
                                <td class="align-left">
                                    <span class="label">Code</span>
                                    {{$data['upr']->accounts->new_account_code}}
                                </td>
                            </tr>
                            <tr>
                                <td class="head" colspan="2">Explanation</td>
                                <td class="head align-center">Amount</td>
                            </tr>
                            <tr>
                                <td colspan="2"> &nbsp; To remit the following TAX(s) of {{$data['payee']->name}} under Purchase Order number {{$data['po']->po_number}} </td>
                                <td class="align-center v-align-middle">{{ formatPrice($data['amount'])}}</td>
                            </tr>
                            <tr>
                                <td class="has-child" colspan="2">
                                    <table class="child-table">
                                        <tr rowspan="3">
                                            <td class="head align-center v-align-middle fix-border" height="90px" rowspan="3" width="10%">&nbsp; </td>
                                        </tr>
                                    </table>
                                </td>
                                <td class="align-center"></td>
                            </tr>
                            <tr>
                                <td colspan="2">Amount Due</td>
                                <td class="align-center"></td>
                            </tr>
                            <tr>
                                <td class="has-child" colspan="3">
                                    <table class="child-table">
                                        <tr>
                                            <td class="head align-left" colspan="2">A. Certified</td>
                                            <td class="head align-left" colspan="2">B. Approved for Payment</td>
                                        </tr>
                                        <tr>
                                            <td class="align-left" colspan="2">
                                                <span class="checkbox-item">&#9744; Cash Available</span>
                                            </td>
                                            <td class="align-center v-align-middle" colspan="2" rowspan="3"></td>
                                        </tr>
                                        <tr>
                                            <td class="align-left" colspan="2">
                                                <span class="checkbox-item">&#9744; Subject to Authority to Debit Account (When applicable)</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="align-left" colspan="2">
                                                <span class="checkbox-item">&#9744; Supporting documents complete</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="align-left fix-border" width="20%" rowspan="3">
                                                <span class="label"></span>
                                            </td>
                                            <td class="align-left" width="30%">
                                                <span class="label">Name</span>
                                            </td>
                                            <td class="align-left fix-border" width="20%" rowspan="3">
                                                <span class="label"></span>
                                            </td>
                                            <td class="align-left" width="30%">
                                                <span class="label">Name</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="align-left" width="30%">
                                                <span class="label">Designation</span>
                                            </td>
                                            <td class="align-left" width="30%">
                                                <span class="label">Designation</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="align-left" width="30%">
                                                <span class="label">Date</span>
                                            </td>
                                            <td class="align-left" width="30%">
                                                <span class="label">Date</span>
                                                </td>
                                        </tr>
                                        <tr>
                                            <td class="head align-left" colspan="2">C. Received Payment</td>
                                            <td class="head align-left" colspan="2">D. Journal Entry Voucher</td>
                                        </tr>
                                        <tr>
                                            <td class="has-child" colspan="2">
                                                <table class="child-table">
                                                    <tr>
                                                        <td class="align-left" width="40%">
                                                            <span class="label">Check / ADA No.</span>
                                                        </td>
                                                        <td class="align-left" width="35%">
                                                            <span class="label">Bank Name</span>
                                                        </td>
                                                        <td class="align-left" width="25%">
                                                            <span class="label">Date</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="align-left fix-border" width="35%" rowspan="2">
                                                            <span class="label"></span>
                                                        </td>
                                                        <td class="align-left" width="30%" colspan="2">
                                                            <span class="label">Printed Name</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="align-left" width="30%" colspan="2">
                                                            <span class="label">Date</span>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                            <td class="has-child" colspan="2">
                                                <table class="child-table">
                                                    <tr>
                                                        <td class="align-left" width="30%" colspan="2">
                                                            <span class="label">Number</span>
                                                        </td>
                                                        <td class="align-left" width="30%" colspan="2">
                                                            <span class="label">Date</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="head" colspan="4">
                                                            <span class="label">Official Receipt / Other Documents</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                </div>
                <!-- form footer -->
            </div>

        </div>
    </body>
</html>