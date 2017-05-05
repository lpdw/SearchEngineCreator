<?php

namespace lpdw\SearchEngineBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * FeatureCategoryValue
 *
 * @ORM\Table(name="feature_category_value")
 * @ORM\Entity(repositoryClass="lpdw\SearchEngineBundle\Repository\FeatureCategoryValueRepository")
 */
class FeatureCategoryValue
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="string", length=255)
     */
    private $value;


    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     * @Assert\File(mimeTypes={"image/png", "image/jpeg"},
     *             mimeTypesMessage="L'extension du fichier est invalide {{ type }}). Les extensions valides sont {{ types }}",
     *             maxSize="1M",
     *             maxSizeMessage="Le fichier ({{ size }} {{ suffix }}) dépasse la taille maximum autorisée ({{ limit }} {{ suffix }})")
     */
    private $image;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="text", nullable=true)
     */
    private $comment;

    /**
     * Many Values have One Feature.
     * @ORM\ManyToOne(targetEntity="Feature")
     * @ORM\JoinColumn(name="feature_id", referencedColumnName="id")
     */
    private $feature;




    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set value
     *
     * @param string $value
     *
     * @return FeatureCategoryValue
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set category
     *
     * @param \lpdw\SearchEngineBundle\Entity\Category $category
     *
     * @return FeatureCategoryValue
     */
    public function setCategory(\lpdw\SearchEngineBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \lpdw\SearchEngineBundle\Entity\Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set feature
     *
     * @param \lpdw\SearchEngineBundle\Entity\Feature $feature
     *
     * @return FeatureCategoryValue
     */
    public function setFeature(\lpdw\SearchEngineBundle\Entity\Feature $feature = null)
    {
        $this->feature = $feature;

        return $this;
    }

    /**
     * Get feature
     *
     * @return \lpdw\SearchEngineBundle\Entity\Feature
     */
    public function getFeature()
    {
        return $this->feature;
    }

    /**
     * Set comment
     *
     * @param string $comment
     *
     * @return FeatureCategoryValue
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }


    /**
     * Set image
     *
     * @param string $image
     *
     * @return FeatureCategoryValue
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }
}
