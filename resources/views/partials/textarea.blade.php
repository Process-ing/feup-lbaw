@props(['name', 'label', 'value' => '', 'placeholder' => '', 'required' => false, 'rows' => 4, 'tooltip'])

<div class="relative group">
    <label for="{{ $name }}" class="font-medium">{{ $label }}</label>
    <textarea id="{{ $name }}" name="{{ $name }}" placeholder="{{ $placeholder }}" {{ $required ? 'required' : '' }} rows="{{ $rows }}"class="w-full px-5 py-3 rounded-lg bg-white dark:bg-slate-700 text-gray-600 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 border border-slate-300 dark:border-slate-600 focus:border-blue-600 outline-none resize-none">{{ old($name, $value) }}</textarea>
    @if ($errors->has($name))
        <p class="text-red-600 dark:text-red-400 text-sm font-medium">{{ $errors->first($name) }}</p>
    @endif
    @isset($tooltip)
        @include('partials.tooltip', ['text' => $tooltip])
    @endisset
</div>
