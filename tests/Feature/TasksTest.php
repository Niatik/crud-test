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
