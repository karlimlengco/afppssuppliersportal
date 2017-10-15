@section('title')
RIS Form2
@stop
@section('styles')
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/css/bootstrap.min.css" />

<link rel="stylesheet" href="/css/summernote.min.css">
<style>

.container {
    position: relative;
    width: 100%;
    max-width: 100%;
    font-family: "Nunito Sans";
    font-size: 16px;
}
</style>
@stop
@section('contents')

{!! Form::open($modelConfig['store']) !!}

<div class="row">
    <div class="twelve columns align-left utility utility--align-right">
        <a href="{{route($indexRoute)}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>
        <button type="submit" id="submit-form" class="button" tooltip="Save"><i class="nc-icon-mini ui-2_disk"></i></button>

    </div>
</div>
<div class="row">
    <div class="twelve columns">
        {!! Form::selectField('unit_id', 'Unit', $unit_lists) !!}
        {!! Form::textareaField('content', 'Content') !!}

    </div>
</div>

{!!Form::close()!!}
@stop

@section('scripts')
<script type="text/javascript">

$("#submit-form").click(function(e){
  // e.preventDefault();
  if ($('#id-field-content').summernote('codeview.isActivated')) {
    $('#id-field-content').summernote('codeview.deactivate');
  }
})
$(document).ready(function() {
  $('#id-field-content').summernote({
    toolbar: [
      // [groupName, [list of button]]
      ['Misc', ['codeview']]
    ]
  });
});

$('#id-field-content').val(JSON.parse(rawContents) )
</script>

@stop