<div class="modal" id="view-attachments-modal">
    <div class="modal__dialogue modal__dialogue--round-corner">
            <button type="button" class="modal__close-button">
                <i class="nc-icon-outline ui-1_simple-remove"></i>
            </button>

            <div class="moda__dialogue__head">
                <h1 class="modal__title">Attachments</h1>
            </div>

            <div class="modal__dialogue__body">
                <div class="row">
                    <div class="twelve columns">
                        <table class="table">
                             <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>File Name</th>
                                    <th>Uploaded By</th>
                                    <th>Upload Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data->attachments as  $attachment)
                                <tr>
                                    <td> <a target="_blank" href="{{route('procurements.unit-purchase-requests.attachments.download', $attachment->id)}}"> {{$attachment->name}} </a></td>
                                    <td>{{$attachment->file_name}}</td>
                                    <td>{{($attachment->users) ? $attachment->users->first_name ." ". $attachment->users->surname :""}}</td>
                                    <td>{{$attachment->upload_date}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
    </div>
</div>