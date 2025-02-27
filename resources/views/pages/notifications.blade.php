@extends('layouts.app')

@section('title', 'Notifications of ' . $user->name . ' | ProGram')

@section('content')
<main class="px-8">
    <section id="notifications" class="card space-y-3">
        <div class="flex items-center justify-between">
            <h1 class="text-xl font-bold">Notifications of {{$user->name}}</h1>
            @if($notifications->count() > 0)
                @include('partials.text-button', [
                    'text' => 'Mark all as read',
                    'id' => 'read-notifications-button',
                    'type' => 'primary',
                ])
            @endif
        </div>

        @if($notifications->count() > 0)
            @foreach($notifications as $notification)
                @include('partials.notification-card', ['notification' => $notification])
            @endforeach
            {{ $notifications->onEachSide(0)->links() }}
        @else
            <p>This user has no unread notifications</p>
        @endempty
    </section>
</main>
@endsection
