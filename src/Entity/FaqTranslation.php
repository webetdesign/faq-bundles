<?php


namespace WebEtDesign\FaqBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Knp\DoctrineBehaviors\Contract\Entity\TranslationInterface;
use Knp\DoctrineBehaviors\Model\Translatable\TranslationTrait;

/**
 * @ORM\Entity()
 * @ORM\Table(name="faq__translation")
 */
class FaqTranslation implements TranslationInterface
{
    use TranslationTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     * @Gedmo\Slug(fields={"question"}, updatable=true)
     */
    private string $slug = '';

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $question = '';

    /**
     * @ORM\Column(type="text")
     */
    private string $answer = '';

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return FaqTranslation
     */
    public function setId(?int $id): FaqTranslation
    {
        $this->id = $id;
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
     * @return FaqTranslation
     */
    public function setSlug(string $slug): FaqTranslation
    {
        $this->slug = $slug;
        return $this;
    }

    /**
     * @return string
     */
    public function getQuestion(): string
    {
        return $this->question;
    }

    /**
     * @param string $question
     * @return FaqTranslation
     */
    public function setQuestion(string $question): FaqTranslation
    {
        $this->question = $question;
        return $this;
    }

    /**
     * @return string
     */
    public function getAnswer(): string
    {
        return $this->answer;
    }

    /**
     * @param string $answer
     * @return FaqTranslation
     */
    public function setAnswer(string $answer): FaqTranslation
    {
        $this->answer = $answer;
        return $this;
    }
}
