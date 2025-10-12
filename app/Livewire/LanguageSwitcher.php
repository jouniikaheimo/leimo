<?php

// app/Livewire/LanguageSwitcher.php
namespace App\Livewire;

use Livewire\Component;

class LanguageSwitcher extends Component
{
    public string $locale;
    public array $available = [
        'fi' => 'Suomi',
        'en' => 'English',
        'sv' => 'Svenska',
    ];

    public function mount(): void
    {
        $this->locale = app()->getLocale();
    }

    public function updatedLocale(string $value): void
    {
        if (! array_key_exists($value, $this->available)) {
            return;
        }

        session(['locale' => $value]);
        app()->setLocale($value);

        // Päivitä näkymä nopeasti
        $this->redirect(request()->header('Referer') ?: url()->current(), navigate: true);
    }

    public function render()
    {
        return view('livewire.language-switcher');
    }
}
