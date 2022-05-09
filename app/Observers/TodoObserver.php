<?php

namespace App\Observers;

use App\Models\Todo;
use Elasticsearch\ClientBuilder;

class TodoObserver
{
    /**
     * @var \Elasticsearch\ClientBuilder
     */
    private $elasticClient;

    public function __construct()
    {
        $this->elasticClient = ClientBuilder::create()
            ->setHosts(config('services.search.hosts'))
            ->build();
    }

    /**
     * Handle the Todo "created" event.
     *
     * @param  \App\Models\Todo  $todo
     * @return void
     */
    public function created(Todo $todo)
    {
        $this->elasticClient->index([
            'index' => 'todos',
            'id' => $todo->id,
            'body' => [
                'userId' => $todo->userId,
                'dueOn' => $todo->dueOn,
                'title' => $todo->title,
                'status' => $todo->status
            ]
        ]);
    }

    /**
     * Handle the Todo "updated" event.
     *
     * @param  \App\Models\Todo  $todo
     * @return void
     */
    public function updated(Todo $todo)
    {
        $this->elasticClient->index([
            'index' => 'todos',
            'id' => $todo->id,
            'body' => [
                'userId' => $todo->userId,
                'dueOn' => $todo->dueOn,
                'title' => $todo->title,
                'status' => $todo->status
            ]
        ]);
    }
}
