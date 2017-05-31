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
     * @ORM\Column(name="value", type="string", length=255, nullable=true)
     */
    private $value;

    /**
     * Many FeatureValue have One Element.
     * @ORM\ManyToOne(targetEntity="Element")
     * @ORM\JoinColumn(name="element_id", referencedColumnName="id")
     */
    private $element;
    /**
     * Many FeatureValue have One Element.
     * @ORM\ManyToOne(targetEntity="FeatureCategoryValue")
     * @ORM\JoinColumn(name="featureCV_id", referencedColumnName="id")
     */
    private $featureCV;

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
     * Constructor
     */
    public function __construct()
    {
        $this->features = new \Doctrine\Common\Collections\ArrayCollection();
        $this->elements = new \Doctrine\Common\Collections\ArrayCollection();
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

    /**
     * Add featuresCV
     *
     * @param \lpdw\SearchEngineBundle\Entity\FeatureCategoryValue $featuresCV
     *
     * @return FeatureValue
     */
    public function addFeaturesCV(\lpdw\SearchEngineBundle\Entity\FeatureCategoryValue $featuresCV)
    {
        $this->featuresCV[] = $featuresCV;

        return $this;
    }

    /**
     * Remove featuresCV
     *
     * @param \lpdw\SearchEngineBundle\Entity\FeatureCategoryValue $featuresCV
     */
    public function removeFeaturesCV(\lpdw\SearchEngineBundle\Entity\FeatureCategoryValue $featuresCV)
    {
        $this->featuresCV->removeElement($featuresCV);
    }

    /**
     * Get featuresCV
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFeaturesCV()
    {
        return $this->featuresCV;
    }

    /**
     * Set element
     *
     * @param \lpdw\SearchEngineBundle\Entity\Element $element
     *
     * @return FeatureValue
     */
    public function setElement(\lpdw\SearchEngineBundle\Entity\Element $element = null)
    {
        $this->element = $element;

        return $this;
    }

    /**
     * Get element
     *
     * @return \lpdw\SearchEngineBundle\Entity\Element
     */
    public function getElement()
    {
        return $this->element;
    }

    /**
     * Set featureCV
     *
     * @param \lpdw\SearchEngineBundle\Entity\FeatureCategoryValue $featureCV
     *
     * @return FeatureValue
     */
    public function setFeatureCV(\lpdw\SearchEngineBundle\Entity\FeatureCategoryValue $featureCV = null)
    {
        $this->featureCV = $featureCV;

        return $this;
    }

    /**
     * Get featureCV
     *
     * @return \lpdw\SearchEngineBundle\Entity\FeatureCategoryValue
     */
    public function getFeatureCV()
    {
        return $this->featureCV;
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
}
