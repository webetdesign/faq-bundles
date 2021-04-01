<?php

namespace WebEtDesign\FaqBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface;
use Knp\DoctrineBehaviors\Model\Translatable\TranslatableTrait;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * @ORM\Entity(repositoryClass="WebEtDesign\FaqBundle\Repository\CategoryRepository")
 * @ORM\Table(name="faq__category")
 *
 * @method string getTitle()
 * @method string getSlug()
 * @method self setTitle() setTitle(string $label)
 * @method self setSlug() setSlug(string $slug)
 */
class Category implements TranslatableInterface
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
     * @var Collection
     * @ORM\OneToMany(targetEntity="Faq", mappedBy="category")
     * @ORM\OrderBy({"position" = "ASC"})
     */
    private $faqs;

    public function __construct()
    {
        $this->faqs = new ArrayCollection();
    }

    public function __call($method, $arguments)
    {
        if ($method == '_action') {
            return null;
        }

        return PropertyAccess::createPropertyAccessor()->getValue($this->translate(), $method);
    }

    /**
     * @inheritDoc
     */
    public function __toString()
    {
        return (string) $this->getTitle();
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

    /**
     * @return Collection|Faq[]
     */
    public function getFaqs(): Collection
    {
        return $this->faqs;
    }

    public function getVisibleFaqs()
    {
        return $this->getFaqs()->filter(function(Faq $faq) {
            return $faq->getVisible() === true;
        });
    }

    public function addFaq(Faq $faq): self
    {
        if (!$this->faqs->contains($faq)) {
            $this->faqs[] = $faq;
            $faq->setCategory($this);
        }

        return $this;
    }

    public function removeFaq(Faq $faq): self
    {
        if ($this->faqs->contains($faq)) {
            $this->faqs->removeElement($faq);
            // set the owning side to null (unless already changed)
            if ($faq->getCategory() === $this) {
                $faq->setCategory(null);
            }
        }

        return $this;
    }

}
