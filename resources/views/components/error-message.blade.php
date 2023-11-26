@if (session('success'))
    <div class="alert alert-success m-0">
        {{ session('success') }}
    </div>
@endif
@if (session('error'))
    <div class="alert alert-danger m-0">
        {{ session('error') }}
    </div>
@endif
@if (session('warning'))
    <div class="alert alert-warning m-0">
        {{ session('warning') }}
    </div>
@endif
@if ($errors->any())
    @if ($errors->count() === 1)
        <div class="alert alert-danger m-0">
            {{ $errors->first() }}
        </div>
    @else
        <div class="alert alert-danger m-0">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
@endif
