<?php

namespace App;

use Elasticsearch\ClientBuilder;

class PostRepository implements SearchRepository
{
    private $params = [];

    public function search($search, $perPage, $from)
    {
        $elasticsearch = ClientBuilder::create()
            ->setHosts(config('services.search.hosts'))
            ->build();

        $this->params = [
            'index' => 'posts',
            'size' => $perPage,
            'from' => $from,
        ];

        $this->params['body'] = [
            'query' => [
                'bool' => [
                    'filter' => []
                ]
            ]
        ];

        if (isset($search['userId'])) {
            $this->filter('userId', $search['userId']);
        }

        if (isset($search['title'])) {
            $this->filter('title', $search['title']);
        }

        if (isset($search['body'])) {
            $this->filter('body', $search['body']);
        }

        $response = $elasticsearch->search($this->params);

        return $response['hits'];
    }

    public function filter($field, $value)
    {
        array_push($this->params['body']['query']['bool']['filter'], [
            'match' => [
                $field => $value,
            ],
        ]);
    }
}
