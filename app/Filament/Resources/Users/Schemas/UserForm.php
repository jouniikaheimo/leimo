<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Section;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Perustiedot')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Nimi')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->label('Sähköposti')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true),
                        TextInput::make('phone')
                            ->label('Puhelin')
                            ->tel()
                            ->maxLength(255),
                        TextInput::make('badge_number')
                            ->label('Kulkulätkän numero')
                            ->maxLength(255),
                        TextInput::make('personal_identity_number')
                            ->label('Hetu')
                            ->maxLength(255),
                        DatePicker::make('employment_started_at')
                            ->label('Työsuhteen alku')
                            ->native(false)
                            ->closeOnDateSelection()
                            ->maxDate(now()),
                    ]),
                Section::make('Salasana')
                    ->schema([
                        TextInput::make('password')
                            ->label('Salasana')
                            ->password()
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->dehydrated(fn (?string $state): bool => filled($state))
                            ->revealable()
                            ->maxLength(255),
                        TextInput::make('password_confirmation')
                            ->label('Salasanan vahvistus')
                            ->password()
                            ->same('password')
                            ->revealable()
                            ->dehydrated(false),
                    ]),
            ]);
    }
}
