<div class="row">
    <div class="col-md-12">

        @if(Session::has('error'))
        <div class="alert alert-danger alert-dismissible fade in" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
            <strong>Oh No!!</strong> {{Session::get('error')}}
        </div>
        @endif

        @if(Session::has('errors'))
        <div class="alert alert-danger alert-dismissible fade in" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
            <strong>Oh No!! Something went wrong.</strong> {{Session::get('error-msg')}}
        </div>
        @endif

        @if(Session::has('success'))
        <div class="alert alert-success alert-dismissible fade in" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
            <strong>Success!</strong> {{Session::get('success')}}
        </div>

        @endif

    </div>
</div>