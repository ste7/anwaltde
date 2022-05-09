<?php

namespace App\Observers;

use App\Models\Post;
use Elasticsearch\ClientBuilder;

class PostObserver
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
     * Handle the Post "created" event.
     *
     * @param  \App\Models\Post  $post
     * @return void
     */
    public function created(Post $post)
    {
        $this->elasticClient->index([
            'index' => 'posts',
            'id' => $post->id,
            'body' => [
                'userId' => $post->userId,
                'title' => $post->title,
                'body' => $post->body
            ]
        ]);
    }

    /**
     * Handle the Post "updated" event.
     *
     * @param  \App\Models\Post  $post
     * @return void
     */
    public function updated(Post $post)
    {
        $this->elasticClient->index([
            'index' => 'posts',
            'id' => $post->id,
            'body' => [
                'userId' => $post->userId,
                'title' => $post->title,
                'body' => $post->body
            ]
        ]);
    }
}
