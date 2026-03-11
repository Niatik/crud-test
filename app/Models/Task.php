<?php

namespace App\Models;

use App\Enums\TaskStatusEnum;
use Database\Factories\TaskFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    /** @use HasFactory<TaskFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'status',
    ];

    protected $casts = [
        'status' => TaskStatusEnum::class,
    ];

    /**
     * Scope: фильтрация по статусу.
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', TaskStatusEnum::from($status));
    }


}
