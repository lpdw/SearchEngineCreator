<?php

namespace lpdw\SearchEngineBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FeatureValue
 *
 * @ORM\Table(name="feature_value")
 * @ORM\Entity(repositoryClass="lpdw\SearchEngineBundle\Repository\FeatureValueRepository")
 */
class FeatureValue
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
     * One value has Many Features.
     * @ORM\OneToMany(targetEntity="Feature", mappedBy="featureValue")
     */
    private $features;

    /**
     * One value has Many Elements.
     * @ORM\OneToMany(targetEntity="Element", mappedBy="featureValue")
     */
    private $elements;

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
     * @return FeatureValue
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
     * Constructor
     */
    public function __construct()
    {
        $this->features = new \Doctrine\Common\Collections\ArrayCollection();
        $this->elements = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add feature
     *
     * @param \lpdw\SearchEngineBundle\Entity\Feature $feature
     *
     * @return FeatureValue
     */
    public function addFeature(\lpdw\SearchEngineBundle\Entity\Feature $feature)
    {
        $this->features[] = $feature;

        return $this;
    }

    /**
     * Remove feature
     *
     * @param \lpdw\SearchEngineBundle\Entity\Feature $feature
     */
    public function removeFeature(\lpdw\SearchEngineBundle\Entity\Feature $feature)
    {
        $this->features->removeElement($feature);
    }

    /**
     * Get features
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFeatures()
    {
        return $this->features;
    }

    /**
     * Add element
     *
     * @param \lpdw\SearchEngineBundle\Entity\Element $element
     *
     * @return FeatureValue
     */
    public function addElement(\lpdw\SearchEngineBundle\Entity\Element $element)
    {
        $this->elements[] = $element;

        return $this;
    }

    /**
     * Remove element
     *
     * @param \lpdw\SearchEngineBundle\Entity\Element $element
     */
    public function removeElement(\lpdw\SearchEngineBundle\Entity\Element $element)
    {
        $this->elements->removeElement($element);
    }

    /**
     * Get elements
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getElements()
    {
        return $this->elements;
    }
}
