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
                </div>
                <!-- form content -->
                <div class="printable-form__body no-letterhead">
                    <span class="printable-form__body__title">Delivered Item(s) Inspected Report</span>
                    <p><strong>Section A: Request for Inspection</strong></p>
                    <p><strong>To:<br>
                    Chief Management & Fiscal, PNFC<br>
                    Bonifacio Naval Station<br>
                    Fort Bonifacio, Taguig City</strong>
                    </p>
                    <p>Request conduct inspection of delivered items described as follows:</p>
                    <table class="printable-form__body__table">
                        <tbody>
                            <tr>
                                <td class="align-left">
                                    <span class="label">Unit</span>
                                    {{$data['units']}}
                                </td>
                                <td class="align-left" colspan="3">
                                    <span class="label">Name of Dealer/Supplier</span>
                                    {{$data['supplier']}}
                                </td>
                            </tr>
                            <tr>
                                <td class="align-left" width="25%">
                                    <span class="label">Date of Delivery</span>
                                    {{$data['date']}}
                                </td>
                                <td class="align-left" width="25%">
                                    <span class="label">Place of Delivery</span>
                                    {{$data['place']}}
                                </td>
                                <td class="align-left" width="30%">
                                    <span class="label">Sales Invoice/Delivery Receipt Nr</span>
                                    @foreach($data['invoice'] as $invoice)
                                        {{$invoice->invoice_number}} /
                                    @endforeach
                                </td>
                                <td class="align-left" width="20%">
                                    <span class="label">Date of Invoice Receipt</span>
                                    @foreach($data['invoice'] as $invoice)
                                        {{$invoice->invoice_date}} /
                                    @endforeach
                                </td>
                            </tr>
                            <tr>
                                <td class="has-child" colspan="4">
                                    <table class="child-table">
                                        <tr>
                                            <td class="head" width="10%">Qty</td>
                                            <td class="head" width="10%">U/I</td>
                                            <td class="head" width="40%">Article/Nomenclature</td>
                                            <td class="head" width="20%">Amount Unit Item</td>
                                            <td class="head" width="20%">Total Amount</td>
                                        </tr>
                                        <?php  $total = 0; ?>
                                        @foreach($data['items'] as $key=>$item)
                                            <tr>
                                                <td style="text-align:center">{{$item->quantity}}</td>
                                                <td>{{$item->unit}}</td>
                                                <td style="text-align:left">{{$item->description}}</td>
                                                <td>{{formatPrice($item->price_unit)}}</td>
                                                <td>{{formatPrice($item->total_amount)}}</td>
                                            </tr>
                                            <?php  $total = $total + $item->total_amount; ?>
                                        @endforeach
                                        <tr>
                                            <td colspan="4">Total</td>
                                            <td>{{formatPrice($total)}}</td>
                                        </tr>
                                        <tr>
                                            <td class="align-center" colspan="5">*** Nothing Follows ***</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td class="align-left" colspan="2">
                                    <span class="label">Purchase Order/Contract Nr</span>
                                    {{$data['po_number']}}
                                </td>
                                <td class="align-left" colspan="2">
                                    <span class="label">Date of Serving of PO</span>
                                    {{$data['po_date']}}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <!-- form signatories -->
                    <table class="printable-form__body__table
                                  printable-form__body__table--borderless">
                        <tr>
                            <td class="v-align-bottom align-center" width="45%">Requested By:</td>
                            <td class="v-align-bottom align-center" width="10%"></td>
                            <td class="v-align-bottom align-center" width="45%">Note By:</td>
                        </tr>
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
                                            <td nowrap>{{$data['requestor']->name}}</td>
                                            <td width="50%"></td>
                                        </tr>
                                        <tr>
                                            <td width="50%"></td>
                                            <td class="align-justify">
                                                <div class="signatory-rank-justify">
                                                    <strong>{{$data['requestor']->ranks}}</strong>
                                                    <span></span>
                                                </div>
                                            </td>
                                            <td width="50%"></td>
                                        </tr>
                                    </table>
                                </div>
                                {{$data['requestor']->designation}}<br>
                            </td>
                            <td width="10%"></td>
                            <td class="signatory align-center v-align-middle" width="45%">
                                <div class="signatory-name">
                                    <table>
                                        <tr>
                                            <td width="50%"></td>
                                            <td nowrap>{{$data['issuer']->name}}</td>
                                            <td width="50%"></td>
                                        </tr>
                                        <tr>
                                            <td width="50%"></td>
                                            <td class="align-justify">
                                                <div class="signatory-rank-justify">
                                                    <strong>{{$data['issuer']->ranks}}</strong>
                                                    <span></span>
                                                </div>
                                            </td>
                                            <td width="50%"></td>
                                        </tr>
                                    </table>
                                </div>
                                {{$data['issuer']->designation}}<br>
                            </td>
                        </tr>
                    </table>

                    <p><strong>Section B: Report of Inspection and Action Taken</strong></p>
                    <p><strong>Findings/Observation:</strong><br>
                    @foreach($data['issues'] as $issue)
                        <p>{{$issue->issue}} / {{$issue->remarks}}</p>
                    @endforeach
                    </p>
                    <!-- form signatories -->
                    <table class="printable-form__body__table
                                  printable-form__body__table--borderless" style="page-break-inside:avoid">
                        <tr>
                            <td class="v-align-bottom align-center" width="30%">Inspected By:</td>
                            <td width="5%"></td>
                            <td class="v-align-bottom align-center" width="30%">Witnessed By:</td>
                            <td width="5%"></td>
                            <td class="v-align-bottom align-center" width="30%">Certified By:</td>
                        </tr>
                        <tr>
                            <td class="v-align-bottom align-center" height="80px"></td>
                            <td></td>
                            <td class="v-align-bottom align-center" height="80px"></td>
                            <td></td>
                            <td class="v-align-bottom align-center" height="80px"></td>
                        </tr>
                        <tr>
                            <td class="signatory align-center v-align-middle" width="30%">
                                <div class="signatory-name">
                                    <table>
                                        <tr>
                                            <td width="50%"></td>
                                            <td nowrap>{{$data['inspector']->name}}</td>
                                            <td width="50%"></td>
                                        </tr>
                                        <tr>
                                            <td width="50%"></td>
                                            <td class="align-justify">
                                                <div class="signatory-rank-justify">
                                                    <strong>{{$data['inspector']->ranks}}</strong>
                                                    <span></span>
                                                </div>
                                            </td>
                                            <td width="50%"></td>
                                        </tr>
                                    </table>
                                </div>
                                {{$data['inspector']->designation}}<br>
                            </td>
                            <td width="5%"></td>
                            <td class="signatory align-center v-align-middle" width="30%">
                                <div class="signatory-name">
                                    <table>
                                        <tr>
                                            <td width="50%"></td>
                                            <td nowrap>{{$data['receiver']->name}}</td>
                                            <td width="50%"></td>
                                        </tr>
                                        <tr>
                                            <td width="50%"></td>
                                            <td class="align-justify">
                                                <div class="signatory-rank-justify">
                                                    <strong>{{$data['receiver']->ranks}}</strong>
                                                    <span></span>
                                                </div>
                                            </td>
                                            <td width="50%"></td>
                                        </tr>
                                    </table>
                                </div>
                                {{$data['receiver']->designation}}<br>
                            </td>
                            <td width="5%"></td>
                            <td class="signatory align-center v-align-middle" width="30%">
                                <div class="signatory-name">
                                    <table>
                                        <tr>
                                            <td width="50%"></td>
                                            <td nowrap>{{$data['approver']->name}}</td>
                                            <td width="50%"></td>
                                        </tr>
                                        <tr>
                                            <td width="50%"></td>
                                            <td class="align-justify">
                                                <div class="signatory-rank-justify">
                                                    <strong>{{$data['approver']->ranks}}</strong>
                                                    <span></span>
                                                </div>
                                            </td>
                                            <td width="50%"></td>
                                        </tr>
                                    </table>
                                </div>
                                {{$data['approver']->designation}}<br>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

        </div>

    </body>
</html>