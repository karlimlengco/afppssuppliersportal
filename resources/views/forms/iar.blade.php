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
            <!-- form content -->
            <div class="printable-form__body boxed">
                <!-- letterhead -->
                <div class="printable-form__letterhead">
                    <span class="printable-form__letterhead__details">
                        {!! $data['unitHeader']!!}
                    </span>
                </div>
                <!-- title -->
                <span class="printable-form__body__title">Inspection And Acceptance Report</span>
                <!-- content -->
                <table class="printable-form__body__table">
                    <tr>
                        <td  class="align-center" width="10%"><strong>NR</strong></td>
                        <td  class="align-center" width="10%"><strong>UOM</strong></td>
                        <td  class="align-center" width="70%"><strong>DESCRIPTION</strong></td>
                        <td  class="align-center" width="10%"><strong>QTY</strong></td>
                    </tr>
                    @foreach($data['items'] as $key=>$value)
                    <tr>
                        <td class="align-center">{{$key + 1}}</td>
                        <td class="align-center">{{$value->unit}}</td>
                        <td class="align-left">{{$value->description}}</td>
                        <td class="align-center">{{$value->quantity}}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td class="align-center" colspan="4"><strong>x-x-x-x-x-x-x Nothing Follows x-x-x-x-x-x-x</strong></td>
                    </tr>
                </table>
                <table class="printable-form__body__table">
                    <tr>
                        <td class="align-center no-border-top" colspan="3" width="50%"><strong>INSPECTION</strong></td>
                        <td class="align-center no-border-top" colspan="3" width="50%"><strong>ACCEPTANCE</strong></td>
                    </tr>
                    <tr>
                        <td class="no-border-bottom no-border-right" width="1%" nowrap>Date Inspected</td>
                        <td class="no-border-right no-border-left" width="200px"></td>
                        <td class="border-top-only" width="20%"></td>
                        <td class="no-border-bottom no-border-right" width="1%" nowrap>Date Received</td>
                        <td class="no-border-right no-border-left" width="200px"></td>
                        <td class="no-border-bottom no-border-left" width="15%"></td>
                    </tr>
                    <tr>
                        <td class="align-middle no-border-top no-border-bottom" colspan="3" height="50px">Inspected, verified and found in order as to qunantity and specifications</td>
                        <td class="align-middle border-left-only"></td>
                        <td class="align-middle no-border" height="50px">
                            _________Complete<br>
                            _________Partial (Pls. specify quantity)
                        </td>
                        <td class="align-middle border-right-only"></td>
                    </tr>
                    <tr>
                        <td class="align-center align-bottom no-border-top no-border-bottom" colspan="3" height="60px">
                            <strong>{{$data['inspector'][1]}} {{$data['inspector'][0]}} {{$data['inspector'][2]}}</strong><br>
                            {{$data['inspector'][3]}}
                        </td>
                        <td class="align-center align-bottom no-border-top no-border-bottom" colspan="3" height="60px">
                            <strong>{{$data['sao'][1]}} {{$data['sao'][0]}} {{$data['sao'][2]}}</strong><br>
                            {{$data['sao'][3]}}
                        </td>
                    </tr>
                    <tr>
                        <td class="no-border-top" colspan="3"></td>
                        <td class="no-border-top" colspan="3"></td>
                    </tr>
                </table>
            </div>

        </div>
    </body>
</html>