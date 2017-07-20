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
                <span class="printable-form__body__title">Inspection and Acceptance Report</span>
                <table class="printable-form__body__table
                              printable-form__body__table--custom">
                    <tbody>
                        <tr>
                            <td class="align-left" width="20%">
                                <span class="label">Date</span>
                                {{\Carbon\Carbon::createFromFormat('Y-m-d',$data['inspection_date'])->format('d F Y')}}
                            </td>
                            <td class="align-left" width="20%">
                                <span class="label">Supplier</span>
                                {{$data['winner']}}
                            </td>
                            <td class="align-left" width="30%">
                                <span class="label">Purchase Order</span>
                                {{$data['po_number']}} dated {{$data['purchase_date']}}
                            </td>
                            <td class="align-left" width="30%">
                                <span class="label">Requisitioning Office/Dept</span>
                                {{$data['center']}}
                            </td>
                        </tr>
                        <tr>
                            <td class="has-child" colspan="4">
                                <table class="child-table">
                                    <tr>
                                        <td style="text-align:center" class="head" width="15%">Item No</td>
                                        <td class="head" width="15%">UON</td>
                                        <td class="head" width="55%">Description</td>
                                        <td class="head" width="15%">Quantity</td>
                                    </tr>
                                    @foreach($data['items'] as $key=>$value)
                                    <tr>
                                        <td style="text-align:center">{{$key + 1}}</td>
                                        <td>{{$value->unit}}</td>
                                        <td style="text-align:left">{{$value->description}}</td>
                                        <td>{{$value->quantity}}</td>
                                    </tr>
                                    @endforeach
                                    <tr>
                                        <td class="align-center" colspan="4">*** Nothing Follows ***</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td class="has-child" colspan="4">
                                <table class="child-table">
                                    <tr>
                                        <td class="head align-center" width="50%" colspan="2">Inspection</td>
                                        <td class="head align-center" width="50%" colspan="2">Acceptance</td>
                                    </tr>
                                    <tr>
                                        <td class="v-align-middle" width="15%">
                                            <span class="label">Date Inspected</span>
                                            {{$data['inspection_date']}}
                                        </td>
                                        <td class="align-left v-align-middle">Inspected, verified and found in order as to quantity and specifications</td>
                                        <td class="v-align-middle" width="15%">
                                            <span class="label">Date Accepted</span>
                                            {{$data['accepted_date']}}
                                        </td>
                                        <td class="has-child">
                                            <table class="child-table">
                                                <tr>
                                                    <td width="20%"></td>
                                                    <td class="align-left" width="80%">Complete</td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td class="align-left">Partial (Please Specify Quantity)</td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" height="75px"></td>
                                        <td colspan="2" height="75px"></td>
                                    </tr>
                                    <tr>
                                        <td class="align-center" colspan="2">
                                            {{$data['inspector']->name}}
                                            <span class="label">{{$data['inspector']->designation}}</span>
                                        </td>
                                        <td class="align-center" colspan="2">
                                            {{$data['acceptor']->name}}
                                            <span class="label">{{$data['acceptor']->designation}}</span>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

  {{--           --}}
        </div>
    </body>
</html>