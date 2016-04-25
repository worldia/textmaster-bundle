<?php

namespace Worldia\Bundle\ProductTestBundle\Entity;

class ProductTranslation
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var Product
     */
    protected $translatable;

    /**
     * @var string
     */
    protected $locale;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $description;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Product
     */
    public function getTranslatable()
    {
        return $this->translatable;
    }

    /**
     * @param Product $translatable
     */
    public function setTranslatable(Product $translatable = null)
    {
        $this->translatable = $translatable;
    }

    /**
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @param string $locale
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return Product
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return Product
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }
}
