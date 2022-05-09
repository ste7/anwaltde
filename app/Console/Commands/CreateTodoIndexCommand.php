<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Elasticsearch\ClientBuilder;

class CreateTodoIndexCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elastic:create-todo-index';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * @var \Elasticsearch\ClientBuilder
     */
    private $elasticClient;

    public function __construct()
    {
        parent::__construct();

        $this->elasticClient = ClientBuilder::create()
            ->setHosts(config('services.search.hosts'))
            ->build();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->elasticClient->indices()->delete(['index' => 'todos']);

        $params = [
            'index' => 'todos',
            'body'  => [
                'mappings' => [
                    'properties' => [
                        'userId' => [
                            'type' => 'long',
                        ],
                        'title' => [
                            'type' => 'text',
                        ],
                        'status' => [
                            'type' => 'text',
                        ],
                        'dueOn' => [
                            'type' => 'date',
                            'format' => 'yyyy-MM-dd'
                        ],
                    ],
                ],
            ],
        ];

        $this->elasticClient->indices()->create($params);
    }
}
