<!DOCTYPE html>
<html lang="en">
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
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

        <div class="printable-form-wrapper" style="padding-top:30px">

            <div class="printable-form">

                <div class="printable-form__head">
                    <p class="printable-form__head__vision">AFP Vision 2028: A World-class Armed Forces, Source of National Pride</p>
                </div>
                <!-- form content -->
                <div class="printable-form__body no-letterhead boxed boxed__no-padding">
                    <table class="printable-form__body__table classic">
                        <tr>
                            <td class="align-center v-align-middle" colspan="11" height="60px">

                                {!!$data['unitHeader']!!}
                            </td>
                            <td>Fund Cluster:</td>
                        </tr>
                        <tr>
                            <td class="align-center v-align-middle" colspan="11" height="40px"><strong>DISBURSEMENT VOUCHER</strong></td>
                            <td>Date:</td>
                        </tr>
                        <tr>
                            <td class="v-align-middle" width="10%" rowspan="3">Mode of Payment</td>
                            <td class="border-top-only" width="70%" colspan="10"></td>
                            <td width="20%" rowspan="3">DV No.</td>
                        </tr>
                        <tr>
                            <td class="no-border" width="3%"></td>
                            <td width="15px"></td>
                            <td class="no-border" width="1%" nowrap>MDS Check</td>
                            <td width="15px"></td>
                            <td class="no-border" width="1%" nowrap>Commercial Check</td>
                            <td width="15px"></td>
                            <td class="no-border" width="1%" nowrap>ADA</td>
                            <td width="15px"></td>
                            <td class="no-border" width="20%" nowrap>Others (Please specify)</td>
                            <td class="no-border"></td>
                        </tr>
                        <tr>
                            <td class="border-bottom-only" colspan="10"></td>
                        </tr>
                        <tr>
                            <td class="v-align-middle" rowspan="2">Payee</td>
                            <td class="v-align-middle" colspan="9" rowspan="2"><strong>{{$data['payee']->name}}</strong></td>
                            <td class="no-border-bottom">TIN/Employee No.: {{$data['payee']->tin}}</td>
                            <td rowspan="2">ORS/BURS No.:</td>
                        </tr>
                        <tr>
                            <td class="align-center no-border-top"><strong></strong></td>
                        </tr>
                        <tr>
                            <td>Address</td>
                            <td colspan="11"><strong>

                            @if($data['suppliers_address'])
                            {{$data['suppliers_address']}}
                            @else
                            {{$data['payee']->address}}
                            @endif
                            </strong></td>
                        </tr>
                    </table>
                    <table class="printable-form__body__table classic">
                        <tr>
                            <td class="v-align-middle no-border-top align-center" width="50%" colspan="3"><strong>Particulars</strong></td>
                            <td class="v-align-middle no-border-top align-center" width="15%"><strong>Responsibility Center</strong></td>
                            <td class="v-align-middle no-border-top align-center" width="15%"><strong>MFO/PAP</strong></td>
                            <td class="v-align-middle no-border-top align-center" width="15%"><strong>Amount</strong></td>
                        </tr>
                        <tr>
                            <td class="no-border-top no-border-bottom" style="text-indent: justify" colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;To payment for the ten ({{count($data['items'])}}) Line Items(s) spicified and delivered under @if($data['po_type'] == 'purchase_order')PURCHASE ORDER No.@elseif($data['po_type'] == 'work_order')WORK ORDER No.@elseif($data['po_type'] == 'job_order')JOB ORDER No.@else CONTRACT ORDER No.@endif {{$data['po_number']}} in the amount of <strong style="text-transform: uppercase">{{translateToWords($data['bid_amount'])}} (Php {{ formatPrice($data['bid_amount'])}})</strong></td>
                            <td class="no-border-top no-border-bottom"></td>
                            <td class="no-border-top no-border-bottom"></td>
                            <td class="no-border-top no-border-bottom align-right"><strong>Php {{ formatPrice($data['bid_amount'])}}</strong></td>
                        </tr>
                        <tr>
                            <td class="border-left-only" width="5%" height="30px"></td>
                            <td class="no-padding no-border v-align-bottom"></td>
                            <td class="no-border"></td>
                            <td class="no-border-top no-border-bottom"></td>
                            <td class="no-border-top no-border-bottom"></td>
                            <td class="no-border-top no-border-bottom"></td>
                        </tr>
                        <tr>
                            <td class="border-left-only"></td>
                            <td class="no-padding no-border"></td>
                            <td class="no-padding no-border"></td>
                            <td class="no-border-top no-border-bottom"></td>
                            <td class="no-border-top no-border-bottom"></td>
                            <td class="no-border-top no-border-bottom"></td>
                        </tr>
                        <tr>
                            <td class="border-left-only"></td>
                            <td class="no-padding no-border"></td>
                            <td class="no-padding no-border"></td>
                            <td class="no-border-top no-border-bottom"></td>
                            <td class="no-border-top no-border-bottom"></td>
                            <td class="no-border-top no-border-bottom"></td>
                        </tr>
                        <tr>
                            <td class="v-align-top border-left-only" height="30px"></td>
                            <td class="no-padding no-border"></td>
                            <td class="no-padding no-border"></td>
                            <td class="no-border-top no-border-bottom"></td>
                            <td class="no-border-top no-border-bottom"></td>
                            <td class="no-border-top no-border-bottom"></td>
                        </tr>
                        <tr>
                            <td class="align-center" colspan="5"><strong>Amount Due</strong></td>
                            <td class="v-align-middle align-right"><strong></strong></td>
                        </tr>
                    </table>
                    <table class="printable-form__body__table classic">
                        <tr>
                            <td width="15px"><strong>A. Certified: Expenses/Cash Advance necessary lawful and incurred under my direct supervision.</strong></td>
                        </tr>
                        <tr>
                            <td class="v-align-bottom align-center" height="80px">
                                <br>
                                <br>
                                <br>
                                @if(count($data['certifier']) > 1)
                                <strong>{{$data['certifier'][1]}} {{$data['certifier'][0]}} {{$data['certifier'][2]}}</strong><br>
                                {{$data['certifier'][3]}}<br>
                                @endif
                                Printed Name, Designation and Signature of Supervisor
                            </td>
                        </tr>
                    </table>
                    <table class="printable-form__body__table classic">
                        <tr>
                            <td colspan="4" width="15px"><strong>B. Accounting Entry:</strong></td>
                        </tr>
                        <tr>
                            <td class="align-center"><strong>Account Title</strong></td>
                            <td class="align-center" width="15%"><strong>UACS Code</strong></td>
                            <td class="align-center" width="15%"><strong>Debit</strong></td>
                            <td class="align-center" width="15%"><strong>Credit</strong></td>
                        </tr>
                        <tr>
                            <td height="50px"></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </table>
                    <table class="printable-form__body__table classic">
                        <tr>
                            <td colspan="3" width="50%"><strong>C. Certified:</strong></td>
                            <td width="50%"><strong>D. Approved Payment:</strong></td>
                        </tr>
                        <tr>
                            <td class="no-border-top no-border-bottom" colspan="3"></td>
                            <td class="no-border-top no-border-bottom"></td>
                        </tr>
                        <tr>
                            <td class="border-left-only" width="15px"></td>
                            <td width="15px"></td>
                            <td class="no-border-top no-border-bottom" width="45%">Cash Available</td>
                            <td class="border-right-only" width="45%"></td>
                        </tr>
                        <tr>
                            <td class="no-border-top no-border-bottom" colspan="3"></td>
                            <td class="no-border-top no-border-bottom"></td>
                        </tr>
                        <tr>
                            <td class="border-left-only" width="15px"></td>
                            <td width="15px"></td>
                            <td class="no-border-top no-border-bottom" width="45%">Subject to Authority to Debit Account (when applicable)</td>
                            <td class="border-right-only" width="45%"></td>
                        </tr>
                        <tr>
                            <td class="no-border-top no-border-bottom" colspan="3"></td>
                            <td class="no-border-top no-border-bottom"></td>
                        </tr>
                        <tr>
                            <td class="no-border-top no-border-bottom" width="15px"></td>
                            <td width="15px"></td>
                            <td class="no-border-top no-border-bottom" width="45%">Supporting documents complete and amount claimed proper</td>
                            <td class="no-border-top no-border-bottom" width="45%"></td>
                        </tr>
                        <tr>
                            <td class="no-border-top" colspan="3"></td>
                            <td class="no-border-top"></td>
                        </tr>
                    </table>
                    <table class="printable-form__body__table classic">
                        <tr>
                            <td class="align-center v-align-middle no-border-top" width="15%" height="50px">Signature</td>
                            <td class="align-center no-border-top" width="35%" height="50px"></td>
                            <td class="align-center v-align-middle no-border-top" width="15%" height="50px">Signature</td>
                            <td class="align-center no-border-top" width="35%" height="50px"></td>
                        </tr>
                        <tr>
                            <td class="align-center">Printed Name</td>
                            <td class="align-center"><strong style="font-size:11px!important;>

                            @if(count($data['receiver']) > 1)
                            {{$data['receiver'][1]}} {{$data['receiver'][0]}} {{$data['receiver'][2]}}
                            @endif
                            </strong></td>
                            <td class="align-center">Printed Name</td>
                            <td class="align-center"><strong style="font-size:11px!important;>

                            @if(count($data['approver']) > 1)
                            {{$data['approver'][1]}} {{$data['approver'][0]}} {{$data['approver'][2]}}
                            @endif
                            </strong></td>
                        </tr>
                        <tr>
                            <td class="align-center v-align-middle" rowspan="2">Position</td>
                            <td class="align-center">
                              @if(count($data['receiver']) > 1)
                                 {{$data['receiver'][3]}}
                              @endif
                            </td>
                            <td class="align-center v-align-middle" rowspan="2">Position</td>
                            <td class="align-center">

                              @if(count($data['approver']) > 1)
                                 {{$data['approver'][3]}}
                              @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="align-center">Head, Accounting Unit/Authorized Representative</td>
                            <td class="align-center">Agency Head/Authorized Representative</td>
                        </tr>
                        <tr>
                            <td class="align-center">Date</td>
                            <td class="align-center"></td>
                            <td class="align-center">Date</td>
                            <td class="align-center"></td>
                        </tr>
                    </table>
                    <table class="printable-form__body__table classic">
                        <tr>
                            <td class="no-border-top" colspan="5"><strong>E. Receipt of Payment</strong></td>
                        </tr>
                        <tr>
                            <td class="v-align-middle" width="15%" height="40px">Check/ADA No.:</td>
                            <td width="20%"></td>
                            <td width="20%">Date:</td>
                            <td width="30%">Bank Name & Account Number:</td>
                            <td width="15%">JEV No.</td>
                        </tr>
                        <tr>
                            <td class="v-align-middle" height="40px">Signature:</td>
                            <td></td>
                            <td>Date:</td>
                            <td>Printed Name:</td>
                            <td>Date:</td>
                        </tr>
                        <tr>
                            <td colspan="5">Official Receipt No. & Date/Other Documents</td>
                        </tr>
                    </table>
                </div>
                {{-- <!-- form header -->
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

                                {{\Carbon\Carbon::createFromFormat('!Y-m-d',$data['transaction_date'])->format('d F Y')}}
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

                </div> --}}
                <!-- form footer -->
            </div>

        </div>
    </body>
</html>