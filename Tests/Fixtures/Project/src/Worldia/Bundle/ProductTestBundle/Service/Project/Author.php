<?php

namespace Worldia\Bundle\ProductTestBundle\Service\Project;

use Textmaster\Client;

class Author
{
    protected $client;
    protected $page;
    protected $projectId;

    public function __construct(Client $client, $projectId)
    {
        $this->client = $client;
        $this->projectId = $projectId;
    }

    public function all($status = null)
    {
        return [
            'total_pages' => 1,
            'count' => 1,
            'page' => 1,
            'per_page' => 20,
            'previous_page' => null,
            'next_page' => null,
            'my_authors' => [
                [
                    'status' => 'my_textmaster',
                    'id' => '58f9febce27d6b5dd2b0b4ec',
                    'author_id' => '55c3763e656462000b000027',
                ]
            ]
        ];
    }

    public function setPage($page)
    {
        $this->page = (null === $page ? $page : (int)$page);

        return $this;
    }
}
