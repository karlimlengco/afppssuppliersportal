<table class="printable-form__body__table classic">
    <tr>
        <td class="align-center v-align-middle" colspan="11" height="60px">
            {{$data["unitHeader"]}}
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
        <td class="v-align-middle" colspan="9" rowspan="2"><strong>{{$data["payee_name"]}}</strong></td>
        <td class="no-border-bottom">TIN/Employee No.: {{$data["payee_tin"]}}</td>
        <td rowspan="2">ORS/BURS No.:</td>
    </tr>
    <tr>
        <td class="align-center no-border-top"><strong></strong></td>
    </tr>
    <tr>
        <td>Address</td>
        <td colspan="11"><strong>{{$data["payee_address"]}}</strong></td>
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
        <td class="no-border-top no-border-bottom" style="text-indent: justify" colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;To payment for the ten ({{$data["itemCount"]}}) Line Items(s) spicified and delivered under {{$data["poType"]}} {{$data["po_number"]}} in the amount of <strong style="text-transform: uppercase">{{$data["bid_amount_word"]}} PESOS ONLY (Php {{$data["bid_amount"]}})</strong></td>
        <td class="no-border-top no-border-bottom"></td>
        <td class="no-border-top no-border-bottom"></td>
        <td class="no-border-top no-border-bottom align-right"><strong>Php {{$data["bid_amount"]}}</strong></td>
    </tr>
    <tr>
        <td class="border-left-only" width="5%" height="30px"></td>
        <td class="no-padding no-border v-align-bottom">Less</td>
        <td class="no-border"></td>
        <td class="no-border-top no-border-bottom"></td>
        <td class="no-border-top no-border-bottom"></td>
        <td class="no-border-top no-border-bottom"></td>
    </tr>
    <tr>
        <td class="border-left-only"></td>
        <td class="no-padding no-border">{{$data["final_tax"]}}% Final Tax</td>
        <td class="no-padding no-border">{{$data["final_tax_amount"]}}</td>
        <td class="no-border-top no-border-bottom"></td>
        <td class="no-border-top no-border-bottom"></td>
        <td class="no-border-top no-border-bottom"></td>
    </tr>
    <tr>
        <td class="border-left-only"></td>
        <td class="no-padding no-border">{{$data["expanded_witholding_tax"]}}% Expanded Withholding Tax</td>
        <td class="no-padding no-border">{{$data["ewt_amount"]}}</td>
        <td class="no-border-top no-border-bottom"></td>
        <td class="no-border-top no-border-bottom"></td>
        <td class="no-border-top no-border-bottom"></td>
    </tr>
    <tr>
        <td class="v-align-top border-left-only" height="30px"></td>
        <td class="no-padding no-border">Penalty Deduction</td>
        <td class="no-padding no-border">{{$data["penalty"]}}</td>
        <td class="no-border-top no-border-bottom"></td>
        <td class="no-border-top no-border-bottom"></td>
        <td class="no-border-top no-border-bottom"></td>
    </tr>
    <tr>
        <td class="align-center" colspan="5"><strong>Amount Due</strong></td>
        <td class="v-align-middle align-right"><strong> </strong></td>
    </tr>
</table>

<table class="printable-form__body__table classic">
    <tr>
        <td width="15px"><strong>A.Certified: Expenses/Cash Advance necessary lawful and incurred under my direct supervision.</strong></td>
    </tr>
    <tr>
        <td class="v-align-bottom align-center" height="80px">
            <strong>{{$data["certifier_ranks"]}} {{$data["certifier_name"]}} {{$data["certifier_branch"]}}</strong><br>
            {{$data["certifier_designation"]}}<br>
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
        <td class="align-center"  width="15%"><strong>UACS Code</strong></td>
        <td class="align-center"  width="15%"><strong>Debit</strong></td>
        <td class="align-center" width="15%"><strong>Credit</strong></td>
    </tr>
    <tr>
        <td height="80px"></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
</table>
<table class="printable-form__body__table classic">
    <tr>
        <td colspan="3" width="50%"><strong>C. Certified:</strong></td>
        <td width="50%"><strong>D. Approved for Payment:</strong></td>
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
        <td class="align-center"><strong>
        {{$data["receiver_ranks"]}} {{$data["receiver_name"]}} {{$data["receiver_branch"]}}
        </strong></td>
        <td class="align-center">Printed Name</td>
        <td class="align-center"><strong>

        {{$data["approver_ranks"]}} {{$data["approver_name"]}} {{$data["approver_branch"]}}
        </strong></td>
    </tr>
    <tr>
        <td class="align-center v-align-middle" rowspan="2">Position</td>
        <td class="align-center">
             {{$data["receiver_designation"]}}
        </td>
        <td class="align-center v-align-middle" rowspan="2">Position</td>
        <td class="align-center">
             {{$data["approver_designation"]}}
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