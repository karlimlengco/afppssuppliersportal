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

            <div class="printable-form__body no-letterhead">
                <span class="printable-form__body__title">Requisition and Issue Slip</span>
                <table class="printable-form__body__table
                              printable-form__body__table--custom">
                    <thead>
                        <tr>
                            <th width="10%">Stock No.</th>
                            <th width="10%">UOM</th>
                            <th width="35%">Description</th>
                            <th width="10%">Quantity</th>
                            <th width="15%">Unit Cost</th>
                            <th width="20%">Total Cost</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data['items'] as $key=>$item)
                        <tr>
                            <td>{{$key+1}}</td>
                            <td>{{$item->unit}}</td>
                            <td>{{$item->description}}</td>
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
                            <td class="has-child" colspan="6">
                                <table class="child-table">
                                    <tr>
                                        <td class="head align-center" width="50%">Requested By</td>
                                        <td class="head align-center" width="50%">Approved By</td>
                                    </tr>
                                    <tr>
                                        <td class="align-left" height="75px">
                                            <span class="label"> </span>
                                        </td>
                                        <td class="align-left">
                                            <span class="label"> </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="align-left">
                                            <span class="label">Name</span>
                                            {{$data['requestor']->name}}
                                        </td>
                                        <td class="align-left">
                                            <span class="label">Name</span>
                                            {{$data['approver']->name}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="align-left">
                                            <span class="label">Designation</span>
                                            {{$data['requestor']->designation}}
                                        </td>
                                        <td class="align-left">
                                            <span class="label">Designation</span>
                                            {{$data['approver']->designation}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="align-left">
                                            <span class="label">Date</span>

                                        </td>
                                        <td class="align-left">
                                            <span class="label">Date</span>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="head align-center" width="50%">Issued By</td>
                                        <td class="head align-center" width="50%">Received By</td>
                                    </tr>
                                    <tr>
                                        <td class="align-left" height="75px">
                                            <span class="label"></span>
                                        </td>
                                        <td class="align-left">
                                            <span class="label"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="align-left">
                                            <span class="label">Name</span>
                                            {{$data['issuer']->name}}
                                        </td>
                                        <td class="align-left">
                                            <span class="label">Name</span>
                                            {{$data['receiver']->name}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="align-left">
                                            <span class="label">Designation</span>
                                            {{$data['issuer']->designation}}
                                        </td>
                                        <td class="align-left">
                                            <span class="label">Designation</span>
                                            {{$data['receiver']->designation}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="align-left">
                                            <span class="label">Date</span>

                                        </td>
                                        <td class="align-left">
                                            <span class="label">Date</span>

                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="printable-form__foot">
                <table class="printable-form__foot__table">
                    <tr>
                        <td colspan="2">
                            <p class="printable-form__foot__values">AFP Core Values: Honor, Service, Patriotism</p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="printable-form__foot__ref">{{$data['ref_number']}}</span>
                        </td>
                        <td>
                            <span class="printable-form__foot__code">
                            <img src="{{base_path('public/img/barcode.png')}}" alt=""></span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </body>
</html>