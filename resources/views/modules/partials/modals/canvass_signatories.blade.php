<div class="modal" id="signatory-modal">
    <div class="modal__dialogue modal__dialogue--round-corner">
        <form method="POST" id="delete-form"  action="{{route('procurements.canvassing.signatories', $data->id)}}" accept-charset="UTF-8">
            <button type="button" class="modal__close-button">
                <i class="nc-icon-outline ui-1_simple-remove"></i>
            </button>

            <div class="moda__dialogue__head">
                <h1 class="modal__title">Signatory</h1>
            </div>

            <div class="modal__dialogue__body">
                <table class="table">

                    <thead>
                        <tr>
                            <th></th>
                            <th>Attendance</th>
                            <th>COP Signee</th>
                            <th>ROP Signee</th>
                        </tr>
                    </thead>
                    <tbody>
                     {{--    <tr>
                            <td>{{$data->chieftain->name}}</td>
                            <td>
                                signatory of noa
                            </td>
                            <td>
                                <input type="radio" name="cop" @if($data->cop == 6) checked @endif  value="6">
                            </td>
                            <td>
                                <input type="radio" name="rop" @if($data->rop == 6) checked @endif value="6">
                            </td>
                        </tr> --}}
                        <tr>
                            <td>{{$data->officer->name}}</td>
                            <td>
                                <input type="checkbox" name="attendance[]" @if($data->chief_attendance == 1) checked @endif value="1">
                            </td>
                            <td>
                                <input type="radio" name="cop" @if($data->cop == 1) checked @endif  value="1">
                            </td>
                            <td>
                                <input type="radio" name="rop" @if($data->rop == 1) checked @endif value="1">
                            </td>
                        </tr>
                        <tr>
                            <td>{{$data->unit_head_name->name}}</td>
                            <td>
                                <input type="checkbox" name="attendance[]" @if($data->unit_head_attendance == 1) checked @endif value="2">
                            </td>
                            <td>
                                <input type="radio" name="cop" @if($data->cop == 2) checked @endif  value="2">
                            </td>
                            <td>
                                <input type="radio" name="rop" @if($data->rop == 2) checked @endif value="2">
                            </td>
                        </tr>
                        <tr>
                            <td>{{$data->mfo_name->name}}</td>
                            <td>
                                <input type="checkbox" name="attendance[]" @if($data->mfo_attendance == 1) checked @endif value="3">
                            </td>
                            <td>
                                <input type="radio" name="cop" @if($data->cop == 3) checked @endif  value="3">
                            </td>
                            <td>
                                <input type="radio" name="rop" @if($data->rop == 3) checked @endif value="3">
                            </td>
                        </tr>
                        <tr>
                            <td>{{$data->legal_name->name}}</td>
                            <td>
                                <input type="checkbox" name="attendance[]" @if($data->legal_attendance == 1) checked @endif value="4">
                            </td>
                            <td>
                                <input type="radio" name="cop" @if($data->cop == 4) checked @endif  value="4">
                            </td>
                            <td>
                                <input type="radio" name="rop" @if($data->rop == 4) checked @endif value="4">
                            </td>
                        </tr>
                        <tr>
                            <td>{{$data->secretary_name->name}}</td>
                            <td>
                                <input type="checkbox" name="attendance[]" @if($data->secretary_attendance == 1) checked @endif value="5">
                            </td>
                            <td>
                                <input type="radio" name="cop" @if($data->cop == 5) checked @endif  value="5">
                            </td>
                            <td>
                                <input type="radio" name="rop" @if($data->rop == 5) checked @endif value="5">
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>

            <input name="_token" type="hidden" value="{{ csrf_token() }}">
            <input name="_method" type="hidden" value="POST">

            <div class="modal__dialogue__foot">
                <button class="button">Proceed</button>
            </div>

        </form>
    </div>
</div>