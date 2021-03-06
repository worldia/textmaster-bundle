<?php

namespace Worldia\Bundle\ProductTestBundle\Service;

use Textmaster\Client;
use Textmaster\Model\Project;
use Worldia\Bundle\ProductTestBundle\Service\Project\Author as ProjectAuthors;

class ProjectApi
{
    protected $projects;
    protected $client;
    protected $api;

    public function __construct(Client $client, DocumentApi $api)
    {
        $this->client = $client;
        $this->api = $api;
    }

    public function create(array $params)
    {
        $params['id'] = $params['name'];
        $this->projects[$params['id']] = $params;

        return $params;
    }

    public function show($id)
    {
        return $this->projects[$id];
    }

    public function documents()
    {
        return $this->api;
    }

    public function getProject($projectId)
    {
        return new Project($this->client, $this->projects[$projectId]);
    }

    public function getDocument($documentId)
    {
        return $this->api->getDocument($documentId);
    }

    public function launch($id)
    {
        return $this->projects[$id];
    }

    public function updateProject(array $params)
    {
        $this->projects[$params['id']] = array_merge($this->projects[$params['id']], $params);
    }

    public function update($id, array $params)
    {
        $this->projects[$id] = array_merge($this->projects[$id], $params);

        return $this->projects[$id];
    }

    public function authors($projectId)
    {
        return new ProjectAuthors($this->client, $projectId);
    }
}
