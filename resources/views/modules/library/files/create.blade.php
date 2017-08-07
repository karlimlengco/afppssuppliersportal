@section('title')
Files
@stop


@section('breadcrumbs')

    @if(isset($breadcrumbs))
      @foreach($breadcrumbs as $route => $crumb)
        @if($crumb->hasLink())
        <a href="{{ $crumb->link() }}" class="topbar__breadcrumbs__item">{{ $crumb->title() }}</a>
        @else
        <a href="#" class="topbar__breadcrumbs__item">{{ $crumb->title() }}</a>
        @endif
      @endforeach
    @else
    <li><a href="#">Application</a></li>
    @endif

@stop


@section('contents')

{!! Form::open($modelConfig['store']) !!}

<div class="row">
    <div class="twelve columns align-left utility utility--align-right">
        <a href="{{route($indexRoute)}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>
        <button type="submit" class="button" tooltip="Save"><i class="nc-icon-mini ui-2_disk"></i></button>

    </div>
</div>

<div class="row">
    <div class="twelve columns">
        {!! Form::fileField('file_name', 'File') !!}
    </div>
</div>

<div class="row">
    <div class="six columns">
        {!! Form::textField('name', 'Name') !!}
    </div>
    <div class="six columns">
        {!! Form::selectField('catalog_id', 'Catalog', $catalogs) !!}
    </div>
</div>
<div class="row">
    <div class="twelve columns">
        {!! Form::textField('tags', 'Tags/ Keywords') !!}
    </div>
</div>

{!!Form::close()!!}

@stop
