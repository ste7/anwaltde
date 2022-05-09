<?php

namespace Tests\Feature;

use App\Models\Todo;
use App\Observers\TestModelObserver;
use Tests\TestCase;

class TodoTest extends TestCase
{
    public function test_get_todos_is_return_http_200_status_when_no_params_are_set()
    {
        $this->get('api/todos')
            ->assertStatus(200);
    }

    public function test_get_todos_is_return_http_200_status_when_params_are_set()
    {
        $this->get('api/todos?limit=10&page=1&title=Qui&status=completed&dueOnLte=2000-01-01&dueOnGte=1990-01-06')
            ->assertStatus(200);
    }

    public function test_get_todos()
    {
        $id = 1;
        $this->get('api/todos/' . $id, [
            'todo' => Todo::find($id)
        ])
            ->assertStatus(200);
    }

    public function test_get_todo_is_return_http_status_404_when_todo_does_not_exists()
    {
        $id = Todo::count() + 1;
        $this->get('api/todos/' . $id, [
            'todo' => Todo::find($id)
        ])
            ->assertStatus(404);
    }

    public function test_create_todo()
    {
        $todo = new Todo();
        $todo->observe(TestModelObserver::class);

        $factory = Todo::factory()->create();

        $this->post('api/todos', $factory->toArray())
            ->assertStatus(200);
    }

    public function test_create_todo_required_fields()
    {
        $this->json('POST', 'api/todos', ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The user id field is required. (and 3 more errors)',
                'errors' => [
                    'userId' => ['The user id field is required.'],
                    'title' => ['The title field is required.'],
                    'dueOn' => ['The due on field is required.'],
                    'status' => ['The status field is required.'],
                ]
            ]);
    }

    public function test_create_todo_wrong_status()
    {
        $todo = new Todo();
        $todo->observe(TestModelObserver::class);

        $factory = Todo::factory()->create();
        $factory->status = 'done';

        $this->json('POST', 'api/todos', $factory->toArray(), ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The selected status is invalid.',
                'errors' => [
                    'status' => ['The selected status is invalid.'],
                ]
            ]);
    }

    public function test_update_todo()
    {
        $id = 1;
        $todo = new Todo();
        $todo->observe(TestModelObserver::class);

        $factory = Todo::factory()->create();

        $this->put('api/todos/' . $id, $factory->toArray())
            ->assertStatus(200);
    }

    public function test_update_todo_required_fields()
    {
        $id = 1;
        $this->json('PUT', 'api/todos/' . $id, ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The user id field is required. (and 3 more errors)',
                'errors' => [
                    'userId' => ['The user id field is required.'],
                    'title' => ['The title field is required.'],
                    'dueOn' => ['The due on field is required.'],
                    'status' => ['The status field is required.'],
                ]
            ]);
    }
}
