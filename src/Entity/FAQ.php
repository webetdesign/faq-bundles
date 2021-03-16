<?php

namespace WebEtDesign\FaqBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface;
use Knp\DoctrineBehaviors\Model\Translatable\TranslatableTrait;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * @ORM\Entity(repositoryClass="WebEtDesign\FaqBundle\Repository\FaqRepository")
 * @ORM\Table(name="faq__faq")
 *
 * @method string getQuestion()
 * @method string getAnswer()
 * @method string getSlug()
 * @method self setQuestion() setQuestion(string $label)
 * @method self setAnswer() setAnswer(string $slug)
 * @method self setSlug() setSlug(string $slug)
 */
class Faq implements TranslatableInterface
{
    use TimestampableEntity;
    use TranslatableTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="integer", options={"default": 0})
     * @Gedmo\SortablePosition
     */
    private int $position = 0;

    /**
     * @var null|Category
     * @ORM\ManyToOne(targetEntity="WebEtDesign\FaqBundle\Entity\Category", inversedBy="faqs")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     * @Gedmo\SortableGroup
     */
    private ?Category $category = null;

    /**
     * @ORM\Column(type="boolean", nullable=false, options={"default": 0})
     */
    private bool $visible = false;

    public function __toString()
    {
        return (string) $this->getQuestion();
    }

    public function __call($method, $arguments)
    {
        if ($method == '_action') {
            return null;
        }

        return PropertyAccess::createPropertyAccessor()->getValue($this->translate(), $method);
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getVisible()
    {
        return $this->visible;
    }

    /**
     * @param mixed $visible
     */
    public function setVisible($visible): void
    {
        $this->visible = $visible;
    }


}
