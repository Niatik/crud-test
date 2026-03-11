<?php

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\postJson;

it('should create a task', function () {
    $task = postJson(
        route('tasks.store'), [
            'title' => 'Test task',
            'description' => 'Some description of test task',
            'status' => 'new',
        ]
    )->json('data');

    expect($task)
        ->title->toBe('Test task')
        ->description->toBe('Some description of test task')
        ->status->toBe('new');

    assertDatabaseHas('tasks',
        [
            'title' => 'Test task',
            'description' => 'Some description of test task',
            'status' => 'new',
        ]
    );
});

it('should create task with required fields', function () {
    $task = postJson(
        route('tasks.store'), [
            'title' => 'Test task',
        ]
    )->json('data');

    expect($task)
        ->title->toBe('Test task')
        ->description->toBeNull()
        ->status->toBe('new');

    assertDatabaseHas('tasks', ['title' => 'Test task']);
});

it('should not create task without title', function () {
    $this->postJson('/api/tasks', ['description' => 'Без названия'])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['title']);
});

it('should not create task with invalid status', function () {
    $this->postJson('/api/tasks', ['title' => 'Задача', 'status' => 'invalid_status'])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['status']);
});
