<?php


namespace WebEtDesign\FaqBundle\Entity;


use Knp\DoctrineBehaviors\Contract\Entity\TranslationInterface;
use Knp\DoctrineBehaviors\Model\Translatable\TranslationTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity()
 * @ORM\Table(name="faq__category_translation")
 */
class CategoryTranslation implements TranslationInterface
{
    use TranslationTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $title = '';

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     * @Gedmo\Slug(fields={"title"}, updatable=true)
     */
    private string $slug = '';

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return CategoryTranslation
     */
    public function setId(?int $id): CategoryTranslation
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return CategoryTranslation
     */
    public function setTitle(string $title): CategoryTranslation
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     * @return CategoryTranslation
     */
    public function setSlug(string $slug): CategoryTranslation
    {
        $this->slug = $slug;
        return $this;
    }
}
