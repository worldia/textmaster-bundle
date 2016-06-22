<?php

namespace Worldia\Bundle\ProductTestBundle\Service;

use Textmaster\Client;
use Textmaster\Model\Document;
use Textmaster\Model\DocumentInterface;

class DocumentApi
{
    protected $documents;
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function create(array $params)
    {
        $params['id'] = $params['title'];
        $this->documents[$params['id']] = $params;

        return $params;
    }

    public function update($id, array $params)
    {
        $this->documents[$id] = $params;

        return $params;
    }

    public function complete($id)
    {
        $document = $this->documents[$id];
        $document['status'] = DocumentInterface::STATUS_COMPLETED;

        return $document;
    }

    public function show($id)
    {
        return $this->documents[$id];
    }

    public function getDocument($documentId)
    {
        return new Document($this->client, $this->documents[$documentId]);
    }

    public function updateDocument(array $params)
    {
        $this->documents[$params['id']] = array_merge($this->documents[$params['id']], $params);
    }

    public function batchCreate(array $documents)
    {
        foreach ($documents as &$document) {
            $document['id'] = $document['title'];
            $this->documents[$document['id']] = $document;
        }

        return $documents;
    }
}
