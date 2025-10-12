{{-- resources/views/livewire/language-switcher.blade.php --}}
<div class="fi-topbar-item">
    <label class="sr-only" for="locale">Language</label>
    <select id="locale" wire:model.live="locale" class="fi-input fi-select">
        @foreach($available as $code => $label)
            <option value="{{ $code }}">{{ $label }}</option>
        @endforeach
    </select>
</div>