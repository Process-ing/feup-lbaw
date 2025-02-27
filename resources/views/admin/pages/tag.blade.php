@extends('layouts.admin')
@section('title', 'Admin Tag Search | ProGram')
@section('content')
    <main class="px-8 flex flex-col gap-4">
        <div class="grid grid-cols-[1fr_auto] gap-4 items-center">
            @include('admin.partials.search-field', ['route' => 'admin.tag.index'])


            <div class="modal ban-modal">
                @include('partials.text-button', [
                    'text' => 'Add Tag',
                    'class' => 'open-button',
                    'type' => 'primary',
                ])
                <div>
                    <article>
                        <div class="mb-4 flex justify-between items-center">
                            <h2 class="text-2xl font-bold">Add Tag</h2>
                            @include('partials.icon-button', [
                                'iconName' => 'x',
                                'class' => 'close-button',
                                'label' => 'Close',
                                'type' => 'transparent',
                            ])
                        </div>
                        <form method="post" action="{{ route('admin.tag.store') }}" class="grid gap-4">
                            @csrf
                            @include('partials.input-field', [
                                'name' => 'tag',
                                'label' => '',
                                'type' => 'text',
                                'value' => old('tag'),
                                'placeholder' => '#tag',
                                'required' => true,
                            ])
                            @include('partials.text-button', [
                                'text' => 'Submit',
                                'type' => 'primary',
                                'submit' => true,
                            ])
                        </form>
                    </article>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto flex flex-col gap-4 pb-2">
            <table>
                <thead class="text-center">
                    <tr>
                        <th>ID</th>
                        <th>Tag</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tags as $tag)
                        <tr class="border-t border-white">
                            <td>{{ $tag->id }}</td>
                            <td>#{{ $tag->name }}</td>
                            <td class="flex justify-end">
                                <div class="dropdown">
                                    @include('partials.icon-button', ['iconName' => 'ellipsis', 'label' => 'Options', 'type' => 'transparent'])
                                    <div class="hidden">
                                        <div>
                                            @include('partials.confirmation-modal', [
                                                'label' => 'Delete Tag',
                                                'icon' => 'trash',
                                                'message' => 'Are you sure you want to delete this tag? This action cannot be undone!',
                                                'type' => 'dropdown',
                                                'action' => route('admin.tag.destroy', $tag->id),
                                                'method' => 'DELETE',
                                            ])
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">No tags found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $tags->onEachSide(0)->links() }}
        </div>
    </main>
@endsection
