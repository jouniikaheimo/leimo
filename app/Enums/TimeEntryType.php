<?php

namespace App\Enums;

enum TimeEntryType: string
{
    case WorkStart = 'work_start';
    case WorkEnd = 'work_end';
    case CoffeeBreak = 'coffee_break';
    case MealBreak = 'meal_break';
    case Break = 'break';

    public function translationKey(): string
    {
        return 'timeclock.entries.' . $this->value;
    }

    public function label(): string
    {
        return __($this->translationKey());
    }

    public function requiresEndTime(): bool
    {
        return match ($this) {
            self::WorkStart, self::WorkEnd => false,
            self::CoffeeBreak, self::MealBreak, self::Break => true,
        };
    }

    public function badgeColor(): string
    {
        return match ($this) {
            self::WorkStart => 'success',
            self::WorkEnd => 'gray',
            self::CoffeeBreak => 'warning',
            self::MealBreak => 'info',
            self::Break => 'secondary',
        };
    }

    public function filterGroup(): string
    {
        return match ($this) {
            self::WorkStart, self::WorkEnd => 'work',
            self::CoffeeBreak, self::MealBreak, self::Break => 'breaks',
        };
    }

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $type) => [$type->value => $type->label()])
            ->all();
    }
}
