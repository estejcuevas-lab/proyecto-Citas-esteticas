@if ($errors->any())
    <div class="flash-error mb-4 grid gap-1" role="alert">
        @foreach ($errors->all() as $error)
            <p class="m-0">{{ $error }}</p>
        @endforeach
    </div>
@endif
