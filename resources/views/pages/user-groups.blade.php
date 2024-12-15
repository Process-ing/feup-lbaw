@extends('layouts.app')

@section('title') {{'Followed by ' . $user->name . ' | ProGram'}} @endsection

@section('content')
<article id="users" class="mx-12 card space-y-3 min-h-[50rem]">
    <h3 class="text-xl font-bold">Groups of {{$user->name}}</h3>
    <div class="grid gap-x-4 gap-y-2 sm:grid-cols-2 md:grid-cols-3">
        @forelse ($user->groups as $group)
            @include('partials.group-card', ['group' => $group])
        @empty
            <p>This user does not follow any other user</p>
        @endforelse
    </div>
</article>
@endsection
