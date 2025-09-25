<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use App\Services\ThemeColorService;
use Filament\Actions\Action;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

use Filament\Schemas\Components\Grid;
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

        // Lataa käyttäjävärit ja muunna ne repeater-muotoon
        $userColors = ThemeColorService::getUserColors();
        $userColorsRepeater = array_map(fn($color) => ['color' => $color], $userColors);

        // Varmista että gradient-asetuksilla on oikeat oletusarvot
        if (!isset($background['gradient_start_color']) || empty($background['gradient_start_color'])) {
            $background['gradient_start_color'] = '#3b82f6';
        }
        if (!isset($background['gradient_end_color']) || empty($background['gradient_end_color'])) {
            $background['gradient_end_color'] = '#8b5cf6';
        }

        $allSettings = array_merge($colors, $opacities, $background, $workSettings, [
            'user_colors' => $userColorsRepeater
        ]);
        $this->form->fill($allSettings);
    }

    public function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Tabs::make('Settings')
                    ->tabs([
                        // Teema-tabi: Optimoitu layout maksimaalisen tilankäytön vuoksi
                        Tabs\Tab::make('Teema')
                            ->icon('heroicon-o-paint-brush')
                            ->schema([
                                // YLÄRIVI: Päävärit, toimintavärit ja sivukohtaiset värit vierekkäin
                                Grid::make([
                                    'default' => 1,
                                    'md' => 2,
                                    'lg' => 3
                                ])->schema([
                                    Section::make('🎨 Päävärit')
                                        ->description('Sovelluksen päävärit')
                                        ->schema([
                                            ColorPicker::make('primary')
                                                ->label('Pääväri')
                                                ->helperText('Napit, linkit, korostukset')
                                                ->live(onBlur: true)
                                                ->afterStateUpdated(fn () => $this->autoSave()),

                                            ColorPicker::make('secondary')
                                                ->label('Toissijainen')
                                                ->helperText('Toissijaiset toiminnot')
                                                ->live(onBlur: true)
                                                ->afterStateUpdated(fn () => $this->autoSave()),

                                            ColorPicker::make('accent')
                                                ->label('Korostus')
                                                ->helperText('Progress barit, erikoiskorostukset')
                                                ->live(onBlur: true)
                                                ->afterStateUpdated(fn () => $this->autoSave()),
                                        ])
                                        ->columns(1)
                                        ->compact(),

                                    Section::make('🚦 Toimintavärit')
                                        ->description('Tilojen ja toimintojen värit')
                                        ->schema([
                                            ColorPicker::make('success')
                                                ->label('Onnistuminen')
                                                ->helperText('Onnistuneet toiminnot')
                                                ->live(onBlur: true)
                                                ->afterStateUpdated(fn () => $this->autoSave()),

                                            ColorPicker::make('warning')
                                                ->label('Varoitus')
                                                ->helperText('Varoitukset ja huomautukset')
                                                ->live(onBlur: true)
                                                ->afterStateUpdated(fn () => $this->autoSave()),

                                            ColorPicker::make('danger')
                                                ->label('Vaara')
                                                ->helperText('Virheet ja poistot')
                                                ->live(onBlur: true)
                                                ->afterStateUpdated(fn () => $this->autoSave()),
                                        ])
                                        ->columns(1)
                                        ->compact(),

                                    Section::make('📑 Sivukohtaiset')
                                        ->description('Event Log -sivun värit')
                                        ->schema([
                                            ColorPicker::make('entries_tab_bg')
                                                ->label('Merkinnät-tabi')
                                                ->helperText('Merkinnät & leimaukset -tabin tausta')
                                                ->live(onBlur: true)
                                                ->afterStateUpdated(fn () => $this->autoSave()),

                                            ColorPicker::make('summary_tab_bg')
                                                ->label('Yhteenveto-tabi')
                                                ->helperText('Kuukausikertymä -tabin tausta')
                                                ->live(onBlur: true)
                                                ->afterStateUpdated(fn () => $this->autoSave()),
                                        ])
                                        ->columns(1)
                                        ->compact(),
                                ]),

                                // KESKIRIVI: Taustavärit ja läpinäkyvyys vierekkäin (YHDISTETTY - ei duplikaatteja)
                                Grid::make([
                                    'default' => 1,
                                    'lg' => 2
                                ])->schema([
                                    Section::make('🌅 Sivun tausta')
                                        ->description('Sovelluksen taustaväri tai gradient')
                                        ->schema([
                                           Select::make('background_mode')
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

                                            Grid::make(2)->schema([
                                                ColorPicker::make('gradient_start_color')
                                                    ->label('Alkuväri')
                                                    ->visible(fn (Get $get) => $get('background_mode') === 'gradient')
                                                    ->live(onBlur: true)
                                                    ->afterStateUpdated(fn () => $this->autoSave()),

                                                ColorPicker::make('gradient_end_color')
                                                    ->label('Loppuväri')
                                                    ->visible(fn (Get $get) => $get('background_mode') === 'gradient')
                                                    ->live(onBlur: true)
                                                    ->afterStateUpdated(fn () => $this->autoSave()),
                                            ]),

                                            Grid::make(2)->schema([
                                               Select::make('gradient_type')
                                                    ->label('Tyyppi')
                                                    ->options([
                                                        'linear' => 'Lineaarinen',
                                                        'radial' => 'Radiaalinen'
                                                    ])
                                                    ->default('linear')
                                                    ->visible(fn (Get $get) => $get('background_mode') === 'gradient')
                                                    ->live()
                                                    ->afterStateUpdated(fn () => $this->autoSave()),

                                               Select::make('gradient_direction')
                                                    ->label('Suunta')
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
                                            ]),

                                            // Gradient esikatselu
                                            View::make('filament.components.background-preview')
                                                ->visible(fn (Get $get) => $get('background_mode') === 'gradient')
                                        ])
                                        ->compact(),

                                    Section::make('💫 Läpinäkyvyys ja efektit')
                                        ->description('Läpinäkyvyys ja visuaaliset efektit - KAIKKI YHDESSÄ PAIKASSA')
                                        ->schema([
                                           TextInput::make('tab_background_opacity')
                                                ->label('Tab-taustan läpinäkyvyys')
                                                ->helperText('0.01-1.0 (esim. 0.08)')
                                                ->numeric()
                                                ->step(0.01)
                                                ->minValue(0.01)
                                                ->maxValue(1.0)
                                                ->live(onBlur: true)
                                                ->afterStateUpdated(fn () => $this->autoSave()),

                                           TextInput::make('main_background_opacity')
                                                ->label('Pääsivun läpinäkyvyys')
                                                ->helperText('0.01-1.0 (esim. 0.03)')
                                                ->numeric()
                                                ->step(0.01)
                                                ->minValue(0.01)
                                                ->maxValue(1.0)
                                                ->live(onBlur: true)
                                                ->afterStateUpdated(fn () => $this->autoSave()),

                                           TextInput::make('sidebar_opacity')
                                                ->label('Sivupalkin läpinäkyvyys')
                                                ->helperText('0.1-1.0 (esim. 0.95)')
                                                ->numeric()
                                                ->step(0.05)
                                                ->minValue(0.1)
                                                ->maxValue(1.0)
                                                ->live(onBlur: true)
                                                ->afterStateUpdated(fn () => $this->autoSave()),
                                        ])
                                        ->columns(1)
                                        ->compact(),
                                ]),

                                Grid::make(2)->schema([


                                // KÄYTTÄJÄVÄRIT: Kompakti layout - ei turhaa tilaa
                                Section::make('👥 Event Log -käyttäjävärit')
                                    ->description('Värit käyttäjien tunnistamiseen event log -taulukossa. Värit kiertävät käyttäjien määrän mukaan.')
                                    ->schema([
                                        Grid::make([
                                            'default' => 1,
                                            'lg' => 2
                                        ])->schema([
                                            // Vasemman puolen kompakti repeater
                                           Repeater::make('user_colors')
                                                ->label('Käyttäjävärit')
                                                ->helperText('Lisää värejä. Värit kiertävät käyttäjien kesken.')
                                                ->schema([
                                                    ColorPicker::make('color')
                                                        ->label('')
                                                        ->hiddenLabel()
                                                        ->live(onBlur: true)
                                                        ->afterStateUpdated(fn () => $this->autoSaveUserColors()),
                                                ])
                                                ->defaultItems(8)
                                                ->minItems(1)
                                                ->maxItems(20)
                                                ->addActionLabel('+ Lisää väri')
                                                ->deleteAction(
                                                    fn ($action) => $action->size('sm')
                                                )
                                                ->reorderable()
                                                ->collapsible(false) // Ei collapsible - vie vähemmän tilaa
                                                ->itemLabel(fn (array $state): ?string =>
                                                    isset($state['color']) ? $state['color'] : 'Uusi väri'
                                                )
                                                ->live()
                                                ->afterStateUpdated(fn () => $this->autoSaveUserColors()),


                                            // Oikean puolen esikatselu
                                           Section::make('Esikatselu')
                                                ->description('Näin värit näkyvät Event Log -sivulla')
                                                ->schema([
                                                    View::make('filament.components.user-colors-preview')
                                                        ->viewData([
                                                            'formUserColors' => fn ($get) => $get('user_colors') ?? []
                                                        ])
                                                ])
                                                ->compact()
                                                ->columnSpan([
                                                    'default' => 1,
                                                    'lg' => 1
                                                ])
                                        ])
                                    ])
                                    ->compact() // Kompakti section
                                    ->collapsible()
                                    ->collapsed(false),

                                // ESIKATSELUT: Kaikki yhteen paikkaan sivun loppuun
                                Section::make('✨ Reaaliaikaiset esikatselut ja työkalut')
                                    ->description('Näe kaikki muutokset reaaliajassa ja käytä interaktiivisia työkaluja')
                                    ->schema([
                                        View::make('filament.components.live-theme-preview'),
                                        View::make('filament.components.background-preview'), // Myös tämä tänne, ei vain gradient-moodissa
                                    ])
                                    ->collapsible()
                                    ->collapsed(true), // Alkuun pienennettynä säästää tilaa
                                 ])
                            ]),


                        // Järjestelmä-tabi säilyy ennallaan
                        Tabs\Tab::make('Järjestelmä')
                            ->icon('heroicon-o-cog-6-tooth')
                            ->schema([
                                Section::make('Työaika-asetukset')
                                    ->description('Työaikaan liittyvät perusasetukset ja säännöt')
                                    ->schema([
                                       TextInput::make('standard_shift_hours')
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
                                       Placeholder::make('admin_settings')
                                            ->label('Hallinta-asetukset')
                                            ->content('Tähän osioon tulee tulevaisuudessa käyttöoikeudet, varmuuskopiointi, tietokannan hallinta jne.')
                                    ])
                                    ->columns(1),

                                Section::make('Sovelluksen tiedot')
                                    ->description('Tekniset tiedot ja versiotiedot')
                                    ->schema([
                                       Placeholder::make('app_version')
                                            ->label('Sovelluksen versio')
                                            ->content('Laravel ' . app()->version() . ' | PHP ' . PHP_VERSION),

                                       Placeholder::make('last_updated')
                                            ->label('Viimeksi päivitetty')
                                            ->content('Syyskuu 2025'),
                                    ])
                                    ->columns(2),

                                Section::make('Tulevat järjestelmäasetukset')
                                    ->description('Kehityssuunnitelma ja tulevat ominaisuudet')
                                    ->schema([
                                       Placeholder::make('future_system_settings')
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

    // Automaattinen tallennusmetodi käyttäjäväreille
    public function autoSaveUserColors(): void
    {
        $data = $this->form->getState();
        if (isset($data['user_colors']) && is_array($data['user_colors'])) {
            $colors = array_map(fn($item) => $item['color'] ?? '#3b82f6', $data['user_colors']);
            $colors = array_filter($colors); // Poista tyhjät arvot
            if (!empty($colors)) {
                ThemeColorService::setUserColors($colors);
            }
        }
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
            'gradient_start_color' => '#3b82f6', // Käytä kiinteitä gradient-värejä
            'gradient_end_color' => '#8b5cf6',   // eikä primary/secondary värejä
            'gradient_type' => 'linear',
            'gradient_direction' => 'to right',
        ]);
        Setting::updateOrCreate(['key' => 'standard_shift_hours'], ['value' => config('timeclock.standard_shift_hours', 8)]);

        // Lataa käyttäjävärit uudelleen mount-metodin tapaan
        $userColors = ThemeColorService::getUserColors();
        $userColorsRepeater = array_map(fn($color) => ['color' => $color], $userColors);

        $this->form->fill(array_merge(
            ThemeColorService::getAllColors(),
            ThemeColorService::getOpacitySettings(),
            ThemeColorService::getBackgroundSettings(),
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
