@if(count($errors) > 0)
    @foreach($errors->all() as $error)
        <div class="alert alert-danger">
            {{ $error }}
        </div>
    @endforeach
@endif

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(isset($warning))
    <div class="alert alert-danger">
        {{ $warning }}
    </div>
@endif

@if(isset($info))
    <div class="alert alert-info">
        {{ $info }}
    </div>
@endif
