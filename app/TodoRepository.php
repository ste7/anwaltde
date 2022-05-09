<?php

namespace App;

use Elasticsearch\ClientBuilder;

class TodoRepository implements SearchRepository
{
    private $params = [];

    public function search($search, $perPage, $from)
    {
        $elasticsearch = ClientBuilder::create()
            ->setHosts(config('services.search.hosts'))
            ->build();

        $this->params = [
            'index' => 'todos',
            'size' => $perPage,
            'from' => $from,
        ];

        $this->params['body'] = [
            'query' => [
                'bool' => [
                    'must' => [],
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

        if (isset($search['status'])) {
            $this->filter('status', $search['status']);
        }

        if (isset($search['dueOnLte']) && isset($search['dueOnGte'])) {
            array_push($this->params['body']['query']['bool']['filter'], [
                'range' => [
                    'dueOn' => [
                        'lte' => $search['dueOnLte'],
                        'gte' => $search['dueOnGte'],
                    ],
                ],
            ]);
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
