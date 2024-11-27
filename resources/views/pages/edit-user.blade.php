@extends('layouts.app')
@section('title') {{'Edit ' . $user->name . ' | ProGram'}} @endsection
@section('content')
    <main class="card h-min p-10 pt-16 grid gap-12 justify-items-center col-start-2 m-6 md:m-16 lg:m-32">
        <h1 class="text-4xl font-bold">Edit Profile</h1>
        <form method="post" action="{{ route('user.update', $user->id) }}" class="grid gap-4 justify-self-stretch" id="profile-form">
            {{ csrf_field() }}

            @include('partials.input-field', ['name' => 'name', 'label' => 'Name', 'type' => 'text', 'value' => $user->name, 'placeholder' => 'John Doe', 'required' => false])
            @include('partials.textarea', ['name' => 'description', 'label' => 'Description', 'type' => 'text', 'value' => $user->description, 'placeholder' => 'I am just a chill dev', 'required' => false])
            @include('partials.input-field', ['name' => 'handle', 'label' => 'Handle', 'type' => 'text', 'value' => $user->handle, 'placeholder' => 'john_doe', 'required' => false])

            <section id="visibility" class="flex items-center mt-4">
                <input type="checkbox" id="is_public" name="is_public" value="1" class="w-5 h-5 mr-2 text-blue-600 border-gray-300 rounded focus:ring-blue-500 focus:ring-2" {{ $user->is_public ? 'checked' : '' }}>
                <label for="is_public" class="font-medium text-gray-700 dark:text-gray-200">
                    Make profile public
                </label>
            </section>

            <section id="languages-section" class="flex flex-col">
                <label for="languages" class="font-medium">Languages</label>
                <select name="languages[]" id="languages" multiple class="card overflow-auto">
                    @foreach ($languages as $language)
                        <option class="w-full text-gray-600 dark:text-white px-4 py-2" value="{{ $language->id }}" {{ $user->stats->languages->contains('id', $language->id) ? 'selected' : '' }}> {{ $language->name }}</option>
                    @endforeach
                </select>
            </section>
            
            <section id="technologies-section" class="flex flex-col">
                <label for="technologies" class="font-medium">Technologies</label>
                <select name="technologies[]" id="technologies" multiple class="card overflow-auto">
                    @foreach ($technologies as $technology)
                        <option class="w-full text-gray-600 dark:text-white px-4 py-2" value="{{ $technology->id }}" {{ $user->stats->technologies->contains('id', $technology->id) ? 'selected' : '' }}> {{ $technology->name }} </option>
                    @endforeach
                </select>
            </section>

            <section id="projects-section" class="flex flex-col">
                <label for="projects" class="font-medium">Projects</label>
                <div id="projects" >
                    @foreach ($user->stats->projects as $project)
                        <div class="grid grid-cols-12 gap-2 mb-4" data-project-id="{{ $project->id }}">
                            <input type="text" name="projects[{{ $project->id }}][name]" value="{{ $project->name }}" placeholder="Project Name" class="col-span-5 w-full card m-2" readonly>
                            <input type="url" name="projects[{{ $project->id }}][url]" value="{{ $project->url }}" placeholder="Project URL" class="lg:col-span-6 col-span-5 w-full card m-2" readonly>
                            <button type="button" class="btn btn-danger remove-project-btn">Remove</button>
                        </div>
                    @endforeach
                </div>

                <div id = "new_projects"></div>

                <div class="grid grid-cols-12 gap-2 mb-4">
                    <input type="text" id="new_project_name" placeholder="Project Name" class="col-span-5 w-full card m-2">
                    <input type="url" id="new_project_url" placeholder="Project URL" class="lg:col-span-6 col-span-5 w-full card m-2">
                    <button type="button" id="add_project" class="btn btn-primary">Add Project</button>
                </div>
            </section>

            @include('partials.text-button', ['text' => 'Update', 'label' => 'Update', 'type' => 'primary', 'submit' => true, 'class' => 'w-full'])
        </form>
    </main>
@endsection
