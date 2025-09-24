<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LockAccessResource\Pages\ListLockAccesses;
use App\Models\LockAccess;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Table;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;

class LockAccessResource extends Resource
{
    protected static ?string $model = LockAccess::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedLockClosed;

    protected static ?string $modelLabel = 'Lukon käyttö';

    protected static ?string $pluralModelLabel = 'Lukon käytöt';



    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('opened_at', 'desc')
            ->columns([
                TextColumn::make('opened_at')->label('Aika')->dateTime('d.m.Y H:i'),
                TextColumn::make('lock_number')->label('Lukko')->searchable(),
                TextColumn::make('location')->label('Sijainti')->toggleable(),
                TextColumn::make('user.name')->label('Käyttäjä')->searchable(),
                TextColumn::make('badge_number')->label('Lätkän numero')->searchable(),
                TextColumn::make('result')->label('Tulos')->badge()->color(fn ($state) => $state === 'granted' ? 'success' : 'danger'),
            ])
            ->filters([
                Filter::make('badge')
                    ->label('Lätkä')
                    ->form([
                        TextInput::make('badge')->label('Lätkän numero'),
                    ])
                    ->query(fn (Builder $query, array $data) => $query->when($data['badge'] ?? null, fn ($q, $v) => $q->where('badge_number', 'like', "%{$v}%"))),
                Filter::make('lock')
                    ->label('Lukko')
                    ->form([
                        TextInput::make('lock')->label('Lukon numero'),
                    ])
                    ->query(fn (Builder $query, array $data) => $query->when($data['lock'] ?? null, fn ($q, $v) => $q->where('lock_number', 'like', "%{$v}%"))),
                Filter::make('date')
                    ->label('Aikaväli')
                    ->form([
                        DatePicker::make('from')->label('Alkaen')->native(false),
                        DatePicker::make('until')->label('Asti')->native(false),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['from'] ?? null, fn ($q, $d) => $q->whereDate('opened_at', '>=', $d))
                            ->when($data['until'] ?? null, fn ($q, $d) => $q->whereDate('opened_at', '<=', $d));
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLockAccesses::route('/'),
        ];
    }
}

