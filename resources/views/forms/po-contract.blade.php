<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="stylesheet" href="{{base_path('fonts/Nunito_Sans/css/nunitosans.css')}}">
        <link rel="stylesheet" href="{{base_path('public/css/main.css')}}">
        <style type="text/css">
            body{
                margin:0;
            }
            @page{margin:0;padding:0;}
        </style>
    </head>

    <body>
        <div class="printable-form-wrapper">
            <!-- third-upr.xps -->
            <div class="printable-form">
                <div class="printable-form__head">
                    <p class="printable-form__head__vision">AFP Vision 2028: A World-class Armed Forces, Source of National Pride</p>
                </div>
                <!-- form body -->
                <div class="printable-form__body">
                    <!-- letterhead -->
                    <div class="printable-form__letterhead">
                        <span class="printable-form__letterhead__details">
                            <strong>{{$data['header']->name}}</strong><br>
                            Armed Forces of the Philippines Procurement Service<br>
                            {{$data['header']->address}}
                        </span>
                    </div>
                    <!-- title -->
                    <span class="printable-form__body__title">
                        Contract Agreement<br>
                        GHQPC-OTCDS-REPE-600-17
                    </span>
                    <!-- content -->
                    <p>KNOW ALL MEN BY THESE PRESENTS:</p>
                    <p class="indent-first-line">THIS CONTRACT is being made and entered in this ______ day of _________________, of between <strong>{{$data['supplier']->name}}</strong> with business address at <strong>{{$data['supplier']->address}}</strong> reprensented by the Proprietress <strong> {{$data['supplier']->owner}} </strong> herein after referred to as the "First Party".</p>
                    <p class="align-center">and</p>
                    <p class="indent-first-line"><strong>AFPPS</strong> at Camp General Emilio Aguinaldo, Quezon City represented by CO, GHQ Procurement Center in behalf of End-user and its duty authorized representative, herein after referred to as the "Second Party:.</p>
                    <p class="align-center">WITNESSETH</p>
                    <p class="align-center">That the parties hereto, agree and stipulate:</p>
                    <p class="indent-first-line">That for and in consideration of the payment to be made by <strong>CPT ANNE MICHELLE R JOMENTO DS</strong> the First Party willfully and faithfully agrees to perform the following services:</p>
                    <p class="indent-first-line">To provide catering service used by OTCDS, AFP (see attached training/event schedule), 2017:</p>
                    <table class="printable-form__body__table">
                        <tr>
                            <td class="align-center" width="5%"><strong>L/I</strong></td>
                            <td class="align-center" width="5%"><strong>Qty</strong></td>
                            <td class="align-center" width="5%"><strong>UOM</strong></td>
                            <td class="align-center" width="60%"><strong>Description</strong></td>
                            <td class="align-center" width="10%"><strong>U/P</strong></td>
                            <td class="align-center" width="15%"><strong>T/P</strong></td>
                        </tr>

                        <?php $total = 0; ?>
                        @foreach($data['items'] as $key=>$item)
                            @if($data['type'] == 'contract')
                                <tr>
                                    <td class="align-center">{{$key+1}}</td>
                                    <td class="align-center">{{$item->quantity}}</td>
                                    <td class="align-center">{{$item->unit}}</td>
                                    <td class="align-left">{{$item->description}}</td>
                                    <td class="align-right">{{formatPrice($item->price_unit)}}</td>
                                    <td class="align-right">{{formatPrice($item->total_amount)}}</td>
                                        <?php $total += $item->total_amount; ?>
                                </tr>
                            @elseif($data['type'] == 'contract_and_po' && $item->type == 'contract')
                                <tr>
                                    <td class="align-center">{{$key+1}}</td>
                                    <td class="align-center">{{$item->quantity}}</td>
                                    <td class="align-center">{{$item->unit}}</td>
                                    <td class="align-left">{{$item->description}}</td>
                                    <td class="align-right">{{formatPrice($item->price_unit)}}</td>
                                    <td class="align-right">{{formatPrice($item->total_amount)}}</td>
                                    <?php $total += $item->total_amount; ?>
                                </tr>
                            @endif
                        @endforeach
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="align-right"><strong>TOTAL</strong></td>
                            <td class="align-right"><strong>{{formatPrice($total)}}</strong></td>
                        </tr>
                    </table>
                    <p class="indent-first-line">That the Second Party, after fulfillment of all the terms and conditions of this contract will pay the First Party sum of <strong style="text-transform:uppercase">{{translateToWords($total)}}PESOS ONLY.</strong>.</p>
                    <p class="indent-first-line">IN WITNESS HEREOF, the Parties aforementioned have hereunto placed their hands on the date herein above.</p>
                    <table class="printable-form__body__table no-border no-padding">
                        <tr>
                            <td colspan="3" height="60px"></td>
                        </tr>
                        <tr>
                            <td width="10%"></td>
                            <td width="35%">
                                <table class="signatory">
                                    <tr>
                                        <td nowrap><strong>ANNE MICHELLE R JOMENTO</strong></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="signatory-rank-justify">
                                                <strong>CPT DS</strong>
                                                <span></span>
                                            </div>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>Chief, Program & Budget Branch</td>
                                        <td></td>
                                    </tr>
                                </table>
                            </td>
                            <td width="10%"></td>
                            <td width="35%">
                                <table class="signatory">
                                    <tr>
                                        <td nowrap><strong>{{$data['supplier']->owner}}</strong></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="signatory-rank-justify">
                                                <strong>Proprietress</strong>
                                                <span></span>
                                            </div>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>{{$data['supplier']->name}}</td>
                                        <td></td>
                                    </tr>
                                </table>
                            </td>
                            <td width="10%"></td>
                        </tr>
                    </table>
                    <table class="printable-form__body__table no-border no-padding">
                        <tr>
                            <td colspan="3" height="60px"></td>
                        </tr>
                        <tr>
                            <td width="35%"></td>
                            <td width="35%">
                                <table class="signatory">
                                    <tr>
                                        <td nowrap><strong>FRANKLIN B BORTONI</strong></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="signatory-rank-justify">
                                                <strong>CDR PN</strong>
                                                <span></span>
                                            </div>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>CO, GHQPC, AFPPS</td>
                                        <td></td>
                                    </tr>
                                </table>
                            </td>
                            <td width="30%"></td>
                        </tr>
                    </table>
                    <table class="signatory">
                        <tr>
                            <td width="18%"></td>
                            <td width="35%" height="50px">Funds Available:</td>
                            <td width="10%"></td>
                            <td width="35%"></td>
                            <td width="10%"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="align-bottom align-left" height="60px">
                                <strong>MR NILO B ABAIGAR, DPA</strong><br>
                                <strong>Chief Accountant</strong><br>
                                AFP Accounting Center
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