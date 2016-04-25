<?php

namespace Worldia\Bundle\TextmasterBundle\Entity;

class Job implements JobInterface
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var object
     */
    protected $translatable;

    /**
     * @var string
     */
    protected $translatableClass;

    /**
     * @var int
     */
    protected $translatableId;

    /**
     * @var string
     */
    protected $projectId;

    /**
     * @var string
     */
    protected $documentId;

    /**
     * @var string
     */
    protected $status;

    /**
     * Job constructor.
     *
     * @param object $translatable
     * @param string $projectId
     * @param string $documentId
     */
    public function __construct($translatable, $projectId, $documentId)
    {
        $this->setTranslatable($translatable);
        $this->projectId = $projectId;
        $this->documentId = $documentId;
        $this->status = self::STATUS_CREATED;
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getTranslatable()
    {
        return $this->translatable;
    }

    /**
     * {@inheritdoc}
     */
    public function setTranslatable($translatable)
    {
        $this->translatable = $translatable;
        $this->translatableClass = get_class($translatable);
        $this->translatableId = $translatable->getId();

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTranslatableClass()
    {
        return $this->translatableClass;
    }

    /**
     * {@inheritdoc}
     */
    public function getTranslatableId()
    {
        return $this->translatableId;
    }

    /**
     * {@inheritdoc}
     */
    public function getProjectId()
    {
        return $this->projectId;
    }

    /**
     * {@inheritdoc}
     */
    public function getDocumentId()
    {
        return $this->documentId;
    }

    /**
     * {@inheritdoc}
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * {@inheritdoc}
     */
    public function setStatus($status)
    {
        $allowed = self::getAllowedStatus();
        if (!in_array($status, $allowed)) {
            throw new \InvalidArgumentException(sprintf(
                'Status must be one of "%s".',
                implode(',', $allowed)
            ));
        }

        $this->status = $status;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public static function getAllowedStatus()
    {
        return [
            self::STATUS_CREATED,
            self::STATUS_STARTED,
            self::STATUS_FINISHED,
            self::STATUS_VALIDATED,
        ];
    }
}
