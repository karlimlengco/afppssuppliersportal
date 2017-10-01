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

            <!-- form header -->
            <div class="printable-form__head">
                <p class="printable-form__head__vision">AFP Vision 2028: A World-class Armed Forces, Source of National Pride</p>
            </div>
            <!-- form body -->
            <div class="printable-form__body boxed">
                <!-- letterhead -->
                <div class="printable-form__letterhead">
                    <span class="printable-form__letterhead__details">
                        {!!$data['unitHeader']!!}
                    </span>
                </div>
                <!-- title -->
                <span class="printable-form__body__title">Requisition and Issue Slip</span>
                <!-- content -->
                <table class="printable-form__body__table" width="100%">
                    <tr>
                        <td class="align-center" width="10%"><strong>STOCK NO.</strong></td>
                        <td class="align-center" width="5%"><strong>UOM</strong></td>
                        <td class="align-center" width="30%"><strong>DESCRIPTION</strong></td>
                        <td class="align-center" width="10%"><strong>QTY</strong></td>
                        <td class="align-center" width="15%"><strong>UNIT COST</strong></td>
                        <td class="align-center" width="15%"><strong>TOTAL COST</strong></td>
                    </tr>
                    @foreach($data['items'] as $key=>$item)
                    <tr>
                        <td class="align-center" >{{$key+1}}</td>
                        <td class="align-center" >{{$item->unit}}</td>
                        <td class="align-left">{{$item->description}}</td>
                        <td class="align-center">{{$item->quantity}}</td>
                        <td class="align-right" >{{formatPrice($item->price_unit)}}</td>
                        <td class="align-right" >{{formatPrice($item->total_amount)}}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td class="align-center"  colspan="5" style="text-transform:uppercase">{{translateToWords($data['bid_amount'])}} Pesos Only</td>
                        <td  class="align-right" >Php {{formatPrice($data['bid_amount'])}}</td>
                    </tr>
                    <tr>
                        <td class="align-center" colspan="6"><strong>***********************************************************************************************************************</strong></td>
                    </tr>
                    <tr>
                        <td class="align-center" colspan="6">DEBIT PROPERTY ACCOUNTABILITY OF {{$data['receiver'][1]}} {{$data['receiver'][0]}}{{$data['receiver'][2]}}</td>
                    </tr>
                    <tr>
                        <td class="align-center" colspan="6">{{$data['receiver'][3]}}</td>
                    </tr>
                    <tr>
                        <td colspan="6">PURPOSE: <strong>{{$data['purpose']}}</strong></td>
                    </tr>
                    <tr>
                        <td class="align-bottom no-border-bottom" height="60px">Signature</td>
                        <td class="align-left no-border-bottom" colspan="5">APPROVED BY:</td>
                    </tr>
                    <tr>
                        <td class="no-border-top no-border-bottom">Name</td>
                        <td class="align-center no-border-top no-border-bottom" colspan="5">
                            <strong>{{$data['approver'][1]}} {{$data['approver'][0]}}{{$data['approver'][2]}}</strong>
                        </td>
                    </tr>
                    <tr>
                        <td class="no-border-top no-border-bottom">Designation</td>
                        <td class="align-center no-border-top no-border-bottom" colspan="5">{{$data['approver'][3]}}</td>
                    </tr>
                    <tr>
                        <td class="no-border-top">Date</td>
                        <td class="align-center" colspan="5"></td>
                    </tr>
                    <tr>
                        <td class="align-bottom no-border-top no-border-bottom" height="60px">Signature</td>
                        <td class="align-left no-border-top no-border-bottom" colspan="2">REQUESTED BY:</td>
                        <td class="align-left no-border-top no-border-bottom" colspan="3">RECEIVED BY:</td>
                    </tr>
                    <tr>
                        <td class="no-border-top no-border-bottom">Name</td>
                        <td class="align-center no-border-top no-border-bottom" colspan="2">
                            @if($data['requestor'] != null)
                            <strong>{{$data['requestor'][1]}} {{$data['requestor'][0]}}{{$data['requestor'][2]}}</strong>
                            @endif
                        </td>
                        <td class="align-center no-border-top no-border-bottom" colspan="3">
                            @if($data['receiver'] != null)
                            <strong>{{$data['receiver'][1]}} {{$data['receiver'][0]}}{{$data['receiver'][2]}}</strong>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="no-border-top no-border-bottom">Designation</td>
                        <td class="align-center no-border-top no-border-bottom" colspan="2">
                            @if($data['requestor'] != null)
                            {{$data['requestor'][3]}}
                            @endif
                        </td>
                        <td class="align-center no-border-top no-border-bottom" colspan="3">
                            @if($data['receiver'] != null)
                            {{$data['receiver'][3]}}
                            @endif
                        </td>
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