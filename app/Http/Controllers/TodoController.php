<?php

namespace App\Http\Controllers;

use Throwable;
 use App\Models\Todo;
 use App\TodoRepository;
 use Illuminate\Support\Arr;
use App\Http\Requests\TodoRequest;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

class TodoController extends Controller
{
    private $todoRepository;

    public function __construct(TodoRepository $todoRepository)
    {
        $this->todoRepository = $todoRepository;
    }

    public function index()
    {
        try {
            $perPage = request('limit', 10);
            $from = (request('page', 1) - 1) * $perPage;

            $access = $this->todoRepository->search([
                'userId' => request('userId'),
                'dueOnLte' => request('dueOnLte'),
                'dueOnGte' => request('dueOnGte'),
                'title' => request('title'),
                'status' => request('status')
            ], $perPage, $from);

            $ids = Arr::pluck($access['hits'], '_id');
            $todos = Todo::findMany($ids)->sortBy(fn($todo) => array_search($todo->getKey(), $ids));

            $response = new LengthAwarePaginator(
                $todos,
                $access['total']['value'],
                $perPage,
                Paginator::resolveCurrentPage(),
                ['path' => Paginator::resolveCurrentPath()]);
            return response()->json($response, 200);
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Internal server error',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    public function show(Todo $todo)
    {
        try {
            return response()->json([
                'todo' => $todo
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Internal server error',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    public function store(TodoRequest $request)
    {
        try {
            return response()->json([
                'todo' => Todo::create($request->validated())
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Internal server error',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Todo $todo, TodoRequest $request)
    {
        try {
            return response()->json([
                'todo' => tap($todo)->update($request->validated())
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Internal server error',
                'data' => $e->getMessage()
            ], 500);
        }
    }
}
