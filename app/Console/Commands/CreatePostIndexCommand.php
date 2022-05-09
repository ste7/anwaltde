<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Elasticsearch\ClientBuilder;

class CreatePostIndexCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elastic:create-post-index';

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
        $this->elasticClient->indices()->delete(['index' => 'posts']);

        $params = [
            'index' => 'posts',
            'body'  => [
                'mappings' => [
                    'properties' => [
                        'userId' => [
                            'type' => 'long',
                        ],
                        'title' => [
                            'type' => 'text',
                        ],
                        'body' => [
                            'type' => 'text',
                        ],
                    ],
                ],
            ],
        ];

        $this->elasticClient->indices()->create($params);
    }
}
