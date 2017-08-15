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
                        @foreach($signatory_info as $signee)
                            <tr>
                                <td>{{$signee->signatory->name}}</td>
                                <td>
                                    <input type="checkbox" name="attendance[]" @if($signee->is_present == 1) checked @endif value="{{$signee->id}}">
                                </td>
                                <td>
                                    <input type="radio" name="cop" @if($signee->cop == 1) checked @endif  value="{{$signee->id}}">
                                </td>
                                <td>
                                    <input type="radio" name="rop" @if($signee->rop == 1) checked @endif value="{{$signee->id}}">
                                </td>
                            </tr>
                        @endforeach
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