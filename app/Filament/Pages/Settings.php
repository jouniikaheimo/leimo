<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use App\Services\ThemeColorService;
use Filament\Actions\Action;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;

class Settings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected string $view = 'filament.pages.settings';
    protected static ?string $title = 'Asetukset';
    protected static ?string $navigationLabel = 'Asetukset';

    public ?array $data = [];

    public function mount(): void
    {
        $colors = ThemeColorService::getAllColors();
        $opacities = ThemeColorService::getOpacitySettings();
        $background = ThemeColorService::getBackgroundSettings();
        $workSettings = [
            'standard_shift_hours' => Setting::where('key', 'standard_shift_hours')->value('value') ?? config('timeclock.standard_shift_hours', 8)
        ];
        $allSettings = array_merge($colors, $opacities, $background, $workSettings);
        $this->form->fill($allSettings);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Tabs::make('Settings')
                    ->tabs([
                        // Teema-tabi: Hybrid-ratkaisu toimivilla komponenteilla
                        Tabs\Tab::make('Teema')
                            ->icon('heroicon-o-paint-brush')
                            ->schema([
                                Section::make('🎨 Päävärit')
                                    ->description('Sovelluksen päävärit - muutokset näkyvät välittömästi')
                                    ->schema([
                                        ColorPicker::make('primary')
                                            ->label('Pääväri')
                                            ->helperText('Käytetään napeissa, linkeissä ja korostuksissa')
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(fn () => $this->autoSave()),

                                        ColorPicker::make('secondary')
                                            ->label('Toissijainen väri')
                                            ->helperText('Toissijaiset toiminnot ja elementit')
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(fn () => $this->autoSave()),

                                        ColorPicker::make('accent')
                                            ->label('Korostusväri')
                                            ->helperText('Progress barit ja erikoiskorostukset')
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(fn () => $this->autoSave()),
                                    ])
                                    ->columns(3),

                                Section::make('🚦 Toimintavärit')
                                    ->description('Tilojen ja toimintojen värit')
                                    ->schema([
                                        ColorPicker::make('success')
                                            ->label('Onnistumisväri')
                                            ->helperText('Onnistuneet toiminnot ja positiiviset tilat')
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(fn () => $this->autoSave()),

                                        ColorPicker::make('warning')
                                            ->label('Varoitusväri')
                                            ->helperText('Varoitukset ja huomautukset')
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(fn () => $this->autoSave()),

                                        ColorPicker::make('danger')
                                            ->label('Vaaraväri')
                                            ->helperText('Virheet, poistot ja kriittiset toiminnot')
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(fn () => $this->autoSave()),
                                    ])
                                    ->columns(3),

                                Section::make('📑 Sivukohtaiset värit')
                                    ->description('Event Log -sivun tabit ja erikoisalueet')
                                    ->schema([
                                        ColorPicker::make('entries_tab_bg')
                                            ->label('Merkinnät-tabin tausta')
                                            ->helperText('Event Log -sivun "Merkinnät & leimaukset" -tabin taustaväri')
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(fn () => $this->autoSave()),

                                        ColorPicker::make('summary_tab_bg')
                                            ->label('Yhteenveto-tabin tausta')
                                            ->helperText('Event Log -sivun "Kuukausikertymä" -tabin taustaväri')
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(fn () => $this->autoSave()),
                                    ])
                                    ->columns(2),

                                // UUSI: Taustaväri & gradient -asetukset
                                Section::make('🌅 Sivun tausta')
                                    ->description('Määritä koko sovelluksen taustaväri tai gradient')
                                    ->schema([
                                        \Filament\Forms\Components\Select::make('background_mode')
                                            ->label('Tyyppi')
                                            ->options([
                                                'solid' => 'Yksivärinen',
                                                'gradient' => 'Gradient'
                                            ])
                                            ->default('solid')
                                            ->live()
                                            ->afterStateUpdated(fn () => $this->autoSave()),

                                        ColorPicker::make('background_color')
                                            ->label('Taustaväri')
                                            ->visible(fn (Get $get) => $get('background_mode') === 'solid')
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(fn () => $this->autoSave()),

                                        ColorPicker::make('gradient_start_color')
                                            ->label('Gradient alkuväri')
                                            ->visible(fn (Get $get) => $get('background_mode') === 'gradient')
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(fn () => $this->autoSave()),

                                        ColorPicker::make('gradient_end_color')
                                            ->label('Gradient loppuväri')
                                            ->visible(fn (Get $get) => $get('background_mode') === 'gradient')
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(fn () => $this->autoSave()),

                                        \Filament\Forms\Components\Select::make('gradient_type')
                                            ->label('Gradientin tyyppi')
                                            ->options([
                                                'linear' => 'Lineaarinen',
                                                'radial' => 'Radiaalinen'
                                            ])
                                            ->default('linear')
                                            ->visible(fn (Get $get) => $get('background_mode') === 'gradient')
                                            ->live()
                                            ->afterStateUpdated(fn () => $this->autoSave()),

                                        \Filament\Forms\Components\Select::make('gradient_direction')
                                            ->label('Suunta / kulma')
                                            ->helperText('Lineaariselle gradientille suunta tai kulma (esim. to right, 45deg)')
                                            ->options([
                                                'to right' => '→ Oikealle',
                                                'to left' => '← Vasemmalle',
                                                'to bottom' => '↓ Alas',
                                                'to top' => '↑ Ylös',
                                                'to bottom right' => '↘ Alas oikealle',
                                                'to top right' => '↗ Ylös oikealle',
                                                '45deg' => '45°',
                                                '90deg' => '90°',
                                                '135deg' => '135°',
                                            ])
                                            ->default('to right')
                                            ->visible(fn (Get $get) => $get('background_mode') === 'gradient' && $get('gradient_type') === 'linear')
                                            ->live()
                                            ->afterStateUpdated(fn () => $this->autoSave()),

                                        // Uusi erillinen esikatselukomponentti
                                        View::make('filament.components.background-preview')
                                    ])
                                    ->columns(2),

                                Section::make('💫 Läpinäkyvyys ja efektit - Sliderit')
                                    ->description('Käytä liukusäätimiä reaaliaikaiseen säätöön')
                                    ->schema([
                                        \Filament\Forms\Components\TextInput::make('tab_background_opacity')
                                            ->label('Tab-taustan läpinäkyvyys')
                                            ->helperText('Arvo 0.01-1.0 (esim. 0.08 = 8%)')
                                            ->numeric()
                                            ->step(0.01)
                                            ->minValue(0.01)
                                            ->maxValue(1.0)
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(fn () => $this->autoSave()),

                                        \Filament\Forms\Components\TextInput::make('main_background_opacity')
                                            ->label('Pääsivun taustan läpinäkyvyys')
                                            ->helperText('Arvo 0.01-1.0 (esim. 0.03 = 3%)')
                                            ->numeric()
                                            ->step(0.01)
                                            ->minValue(0.01)
                                            ->maxValue(1.0)
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(fn () => $this->autoSave()),

                                        \Filament\Forms\Components\TextInput::make('sidebar_opacity')
                                            ->label('Sivupalkin läpinäkyvyys')
                                            ->helperText('Arvo 0.1-1.0 (esim. 0.95 = 95%)')
                                            ->numeric()
                                            ->step(0.05)
                                            ->minValue(0.1)
                                            ->maxValue(1.0)
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(fn () => $this->autoSave()),
                                    ])
                                    ->columns(3),

                                // Interaktiivinen esikatselu ja sliderit
                                Section::make('✨ Interaktiivinen esikatselu ja sliderit')
                                    ->description('Reaaliaikainen esikatselu ja liukusäätimet')
                                    ->schema([
                                        View::make('filament.components.live-theme-preview')

                                    ]),
                            ]),

                        // Järjestelmä-tabi säilyy ennallaan
                        Tabs\Tab::make('Järjestelmä')
                            ->icon('heroicon-o-cog-6-tooth')
                            ->schema([
                                Section::make('Työaika-asetukset')
                                    ->description('Työaikaan liittyvät perusasetukset ja säännöt')
                                    ->schema([
                                        \Filament\Forms\Components\TextInput::make('standard_shift_hours')
                                            ->label('Työpäivän pituus')
                                            ->helperText('Normaalin työpäivän pituus tunneissa')
                                            ->numeric()
                                            ->minValue(1)
                                            ->maxValue(24)
                                            ->default(8)
                                            ->required()
                                            ->suffix('tuntia')
                                            ->live(),
                                    ])
                                    ->columns(1),

                                Section::make('Sovelluksen hallinta')
                                    ->description('Yleiset järjestelmäasetukset ja käyttöoikeudet')
                                    ->schema([
                                        \Filament\Forms\Components\Placeholder::make('admin_settings')
                                            ->label('Hallinta-asetukset')
                                            ->content('Tähän osioon tulee tulevaisuudessa käyttöoikeudet, varmuuskopiointi, tietokannan hallinta jne.')
                                    ])
                                    ->columns(1),

                                Section::make('Sovelluksen tiedot')
                                    ->description('Tekniset tiedot ja versiotiedot')
                                    ->schema([
                                        \Filament\Forms\Components\Placeholder::make('app_version')
                                            ->label('Sovelluksen versio')
                                            ->content('Laravel ' . app()->version() . ' | PHP ' . PHP_VERSION),

                                        \Filament\Forms\Components\Placeholder::make('last_updated')
                                            ->label('Viimeksi päivitetty')
                                            ->content('Syyskuu 2025'),
                                    ])
                                    ->columns(2),

                                Section::make('Tulevat järjestelmäasetukset')
                                    ->description('Kehityssuunnitelma ja tulevat ominaisuudet')
                                    ->schema([
                                        \Filament\Forms\Components\Placeholder::make('future_system_settings')
                                            ->label('Kehityssuunnitelma')
                                            ->content('Tänne lisätään tulevaisuudessa: taukojen hallinta, ylityörajat, lomapäivät, käyttäjäryhmät, raportointiasetus jne.')
                                    ])
                                    ->columns(1),
                            ]),
                    ])
            ])
            ->statePath('data')
            ->live(); // Lisää live-mode koko lomakkeelle
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->label('Tallenna asetukset')
                ->icon('heroicon-o-check')
                ->color('success')
                ->action('save'),

            Action::make('reset')
                ->label('Palauta oletusasetukset')
                ->icon('heroicon-o-arrow-path')
                ->color('gray')
                ->requiresConfirmation()
                ->modalHeading('Palauta oletusasetukset')
                ->modalDescription('Haluatko varmasti palauttaa kaikki asetukset oletusarvoihin? Tämä vaikuttaa väreihin, ulkoasuun ja työaika-asetuksiin.')
                ->action('resetToDefaults'),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $colors = array_intersect_key($data, array_flip(['primary', 'secondary', 'accent', 'success', 'warning', 'danger', 'entries_tab_bg', 'summary_tab_bg']));
        $opacities = array_intersect_key($data, array_flip(['tab_background_opacity', 'main_background_opacity', 'sidebar_opacity']));
        $background = array_intersect_key($data, array_flip(['background_mode','background_color','gradient_start_color','gradient_end_color','gradient_type','gradient_direction']));
        if (!empty($colors)) ThemeColorService::setColors($colors);
        if (!empty($opacities)) ThemeColorService::setOpacitySettings($opacities);
        if (!empty($background)) ThemeColorService::setBackgroundSettings($background);
        Setting::updateOrCreate(['key' => 'standard_shift_hours'], ['value' => $data['standard_shift_hours']]);
        Notification::make()->title('Asetukset tallennettu')->body('Teeman värit, tausta ja asetukset päivitetty.')->success()->send();
    }

    // Uusi automaattinen tallennusmetodi reaaliaikaisille muutoksille
    public function autoSave(): void
    {
        $data = $this->form->getState();
        $colors = array_intersect_key($data, array_flip(['primary', 'secondary', 'accent', 'success', 'warning', 'danger', 'entries_tab_bg', 'summary_tab_bg']));
        $opacities = array_intersect_key($data, array_flip(['tab_background_opacity', 'main_background_opacity', 'sidebar_opacity']));
        $background = array_intersect_key($data, array_flip(['background_mode','background_color','gradient_start_color','gradient_end_color','gradient_type','gradient_direction']));
        if (!empty($colors)) ThemeColorService::setColors($colors);
        if (!empty($opacities)) ThemeColorService::setOpacitySettings($opacities);
        if (!empty($background)) ThemeColorService::setBackgroundSettings($background);
    }

    public function resetToDefaults(): void
    {
        ThemeColorService::resetToDefaults();
        ThemeColorService::setOpacitySettings([
            'tab_background_opacity' => 0.08,
            'main_background_opacity' => 0.03,
            'sidebar_opacity' => 0.95,
        ]);
        ThemeColorService::setBackgroundSettings([
            'background_mode' => 'solid',
            'background_color' => '#f3f4f6',
            'gradient_start_color' => ThemeColorService::getAllColors()['primary'] ?? '#3b82f6',
            'gradient_end_color' => ThemeColorService::getAllColors()['accent'] ?? '#8b5cf6',
            'gradient_type' => 'linear',
            'gradient_direction' => 'to right',
        ]);
        Setting::updateOrCreate(['key' => 'standard_shift_hours'], ['value' => config('timeclock.standard_shift_hours', 8)]);
        $this->form->fill(array_merge(
            ThemeColorService::getAllColors(),
            ThemeColorService::getOpacitySettings(),
            ThemeColorService::getBackgroundSettings(),
            ['standard_shift_hours' => Setting::where('key','standard_shift_hours')->value('value')]
        ));
        Notification::make()->title('Asetukset palautettu')->success()->send();
    }

    public static function canAccess(): bool
    {
        $user = auth()->user();

        if (! $user) {
            return false;
        }

        if (config('timeclock.dev_bypass_admin')) {
            return true;
        }

        $emails = (array) config('timeclock.admin_emails', []);
        return in_array($user->email, $emails, true);
    }
}
