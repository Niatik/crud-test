<?php

namespace App\Enums;

enum TaskStatusEnum: string
{
    case New        = 'new';
    case InProgress = 'in_progress';
    case Done       = 'done';

    public function label(): string
    {
        return match($this) {
            self::New        => 'Новая',
            self::InProgress => 'В работе',
            self::Done       => 'Выполнена',
        };
    }

    /**
     * Для select-опций в Blade/JS: ['new' => 'Новая', ...]
     */
    public static function options(): array
    {
        return array_column(
            array_map(fn($case) => ['value' => $case->value, 'label' => $case->label()], self::cases()),
            'label',
            'value'
        );
    }
}
