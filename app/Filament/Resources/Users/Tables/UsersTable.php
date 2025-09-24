<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Indicator;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('name')
            ->columns([
                TextColumn::make('name')
                    ->label('Nimi')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Sähköposti')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('phone')
                    ->label('Puhelin')
                    ->toggleable()
                    ->searchable(),
                TextColumn::make('badge_number')
                    ->label('Kulkulätkän numero')
                    ->toggleable()
                    ->searchable(),
                TextColumn::make('employment_started_at')
                    ->label('Työsuhteen alku')
                    ->date('d.m.Y')
                    ->sortable(),
            ])
            ->filters([
                Filter::make('employment_started_at')
                    ->label('Työsuhde aikavälillä')
                    ->form([
                        DatePicker::make('from')
                            ->label('Alkaen')
                            ->native(false)
                            ->closeOnDateSelection(),
                        DatePicker::make('until')
                            ->label('Asti')
                            ->native(false)
                            ->closeOnDateSelection(),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate('employment_started_at', '>=', $date),
                            )
                            ->when(
                                $data['until'] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate('employment_started_at', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['from'] ?? null) {
                            $indicators[] = Indicator::make('Alkaen ' . $data['from']);
                        }

                        if ($data['until'] ?? null) {
                            $indicators[] = Indicator::make('Asti ' . $data['until']);
                        }

                        return $indicators;
                    }),
            ])
            ->filtersFormColumns(2)
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
