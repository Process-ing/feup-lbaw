@extends('layouts.admin')
@section('title')
    {{ 'Admin Language Search | ProGram' }}
@endsection
@section('content')
    <main class="px-4 flex flex-col gap-4">
        <div class="grid grid-cols-[1fr_auto] gap-4 items-center">
            @include('admin.partials.search-field', ['route' => 'admin.language.index'])


            <div class="modal ban-modal">
                @include('partials.text-button', [
                    'text' => 'Add Language',
                    'class' => 'open-button',
                    'type' => 'primary',
                ])
                <div>
                    <div>
                        <div class="mb-4 flex justify-between items-center">
                            <h1 class="text-2xl font-bold">Add Language</h1>
                            @include('partials.icon-button', [
                                'iconName' => 'x',
                                'class' => 'close-button',
                                'label' => 'Close',
                                'type' => 'transparent',
                            ])
                        </div>
                        <form method="post" action="{{ route('admin.language.store') }}" class="grid gap-4">
                            @csrf
                            @include('partials.input-field', [
                                'name' => 'language',
                                'label' => '',
                                'type' => 'text',
                                'value' => old('language'),
                                'placeholder' => 'JavaScript',
                                'required' => true,
                            ])
                            @include('partials.text-button', [
                                'text' => 'Submit',
                                'type' => 'primary',
                                'submit' => true,
                            ])
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <table>
            <thead class="text-center">
                <tr>
                    <th>ID</th>
                    <th>Language</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($languages as $language)
                    <tr class="border-t border-white">
                        <td>{{ $language->id }}</td>
                        <td>{{ $language->name }}</td>
                        <td class="pe-8 flex justify-end gap-2">
                            <form method="post" action="{{ route('admin.language.destroy', $language->id) }}">
                                @csrf
                                @method('DELETE')
                                @include('partials.text-button', [
                                    'text' => 'Delete',
                                    'type' => 'secondary',
                                    'submit' => true,
                                ])
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">No languages found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        {{ $languages->links() }}
    </main>
@endsection
