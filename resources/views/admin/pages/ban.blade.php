@extends('layouts.admin')
@section('title') {{'Admin Ban Search | ProGram'}} @endsection
@section('content')
    <main class="px-8 flex flex-col gap-4">
        @include('admin.partials.search-field', ['route' => 'admin.ban.index'])

        <div class="overflow-x-auto">
            <table>
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Username</th>
                        <th>Admin</th>
                        <th>Start</th>
                        <th>Reason</th>
                        <th>Duration</th>
                        <th>Is Active?</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($bans as $ban)
                        <tr>
                            <td><strong>{{ $ban->id }}</strong></td>
                            <td>{{ $ban->user->handle }}</td>
                            <td>{{ $ban->administrator->name }}</td>
                            <td>{{ $ban->start }}</td>
                            <td>{{ $ban->reason }}</td>
                            <td>{{ $ban->duration != '00:00:00' ? $ban->duration : 'Permanent' }}</td>
                            <td>{{ $ban->isActive() ? 'Yes' : 'No' }}</td>
                            <td class="flex justify-end">
                                @if ($ban->isActive())
                                    <div class="dropdown">
                                        @include('partials.icon-button', ['iconName' => 'ellipsis', 'label' => 'Options', 'type' => 'transparent'])
                                        <div class="hidden">
                                            <div>
                                                <form action="{{ route('admin.ban.revoke', $ban->id) }}" method="POST" class="flex flex-col">
                                                    @csrf
                                                    @include('partials.dropdown-item', [
                                                        'icon' => 'user-round-x',
                                                        'text' => 'Revoke',
                                                        'type' => 'secondary',
                                                        'submit' => true,
                                                    ])
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">No bans found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $bans->onEachSide(0)->links() }}
    </main>
@endsection
