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
        <div class="printable-form">

            <!-- main page -->
            <div class="printable-form__head">
                <p class="printable-form__head__vision">AFP Vision 2028: A World-class Armed Forces, Source of National Pride</p>
            </div>

            <div class="printable-form__body no-letterhead boxed">
                <span class="printable-form__head__letterhead__details inside">
                    ARMED FORCES OF THE PHILIPPINES<br>
                    OFFICE OF THE ADJUTANT GENERAL, AFP<br>
                    Camp General Emilio Aguinaldo, Quezon City
                </span>
                <span class="printable-form__body__title">Requisition and Issue Slip</span>
                <table class="printable-form__body__table classic">
                    <tr>
                        <td width="10%"><strong>STOCK NO.</strong></td>
                        <td width="10%"><strong>UOM</strong></td>
                        <td  style="text-align:left" width="35%"><strong>DESCRIPTION</strong></td>
                        <td width="10%"><strong>QTY</strong></td>
                        <td width="15%"><strong>UNIT COST</strong></td>
                        <td width="20%"><strong>TOTAL COST</strong></td>
                    </tr>
                    @foreach($data['items'] as $key=>$item)
                    <tr>
                        <td>{{$key+1}}</td>
                        <td>{{$item->unit}}</td>
                        <td style="text-align:left">{{$item->description}}</td>
                        <td>{{$item->quantity}}</td>
                        <td>{{formatPrice($item->price_unit)}}</td>
                        <td>{{formatPrice($item->total_amount)}}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="5">One Hundred Pesos</td>
                        <td>100.00</td>
                    </tr>
                    <tr>
                        <td colspan="6">
                            <span class="label">Purpose</span>
                            {{$data['purpose']}}
                        </td>
                    </tr>
                    <tr>
                        <td class="v-align-bottom no-border-bottom" height="60px">Signature</td>
                        <td class="align-center no-border-bottom" colspan="2">REQUESTED BY:</td>
                        <td class="align-center no-border-bottom" colspan="3">APPROVED BY:</td>
                    </tr>
                    <tr>
                        <td class="no-border-top no-border-bottom">Name</td>
                        <td class="align-center no-border-top no-border-bottom" colspan="2">
                            <strong>{{$data['requestor']->name}}</strong>
                        </td>
                        <td class="align-center no-border-top no-border-bottom" colspan="3">
                            <strong>{{$data['approver']->name}}</strong>
                        </td>
                    </tr>
                    <tr>
                        <td class="no-border-top no-border-bottom">Designation</td>
                        <td class="align-center no-border-top no-border-bottom" colspan="2">{{$data['requestor']->designation}}</td>
                        <td class="align-center no-border-top no-border-bottom" colspan="3">{{$data['approver']->designation}}</td>
                    </tr>
                    <tr>
                        <td class="no-border-top">Date</td>
                        <td class="align-center" colspan="2"></td>
                        <td class="align-center" colspan="3"></td>
                    </tr>
                    <tr>
                        <td class="v-align-bottom no-border-top no-border-bottom" height="60px">Signature</td>
                        <td class="align-center no-border-top no-border-bottom" colspan="2">ISSUED BY:</td>
                        <td class="align-center no-border-top no-border-bottom" colspan="3">RECEIVED BY:</td>
                    </tr>
                    <tr>
                        <td class="no-border-top no-border-bottom">Name</td>
                        <td class="align-center no-border-top no-border-bottom" colspan="2">
                            <strong>{{$data['issuer']->name}}</strong>
                        </td>
                        <td class="align-center no-border-top no-border-bottom" colspan="3">
                            <strong>{{$data['receiver']->name}}</strong>
                        </td>
                    </tr>
                    <tr>
                        <td class="no-border-top no-border-bottom">Designation</td>
                        <td class="align-center no-border-top no-border-bottom" colspan="2">{{$data['issuer']->designation}}</td>
                        <td class="align-center no-border-top no-border-bottom" colspan="3">{{$data['receiver']->designation}}</td>
                    </tr>
                    <tr>
                        <td class="no-border-top">Date</td>
                        <td class="align-center" colspan="2"></td>
                        <td class="align-center" colspan="3"></td>
                    </tr>

                </table>
            </div>

        </div>
    </body>
</html>