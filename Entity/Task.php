<?php

namespace StudentBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ORM\Table(
 *     name="task",
 *     indexes={
 *         @ORM\Index(name="task__lesson_id__ind", columns={"lesson_id"}),
 *     }
 * )
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="StudentBundle\Repository\TaskRepository")
 * @ApiResource()
 */

class Task
{
    /**
     * @ORM\Column(name="id", type="bigint", unique=true)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private string $name;

    /**
     * @ORM\ManyToOne(targetEntity="Lesson", inversedBy="tasks")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="lesson_id", referencedColumnName="id")
     * })
     */
    private ?Lesson $lesson;

    /**
     * @ORM\OneToMany(targetEntity="Spectrum", mappedBy="task")
     */
    private Collection $spectrums;

    /**
     * @ORM\OneToMany(targetEntity="Rating", mappedBy="task")
     */
    private Collection $ratings;

    public function __construct()
    {
        $this->spectrums = new ArrayCollection();
        $this->ratings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getLesson(): ?Lesson
    {
        return $this->lesson;
    }

    public function setLesson(Lesson $lesson): void
    {
        $this->lesson = $lesson;
    }

    public function getSpectrums(): Collection
    {
        return $this->spectrums;
    }

    public function addSpectrum(Spectrum $spectrum): void
    {
        if (!$this->spectrums->contains($spectrum)) {
            $this->spectrums->add($spectrum);
            $spectrum->setTask($this);
        }
    }

    public function removeSpectrum(Spectrum $spectrum): self
    {
        if ($this->spectrums->contains($spectrum)) {
            $this->spectrums->removeElement($spectrum);
        }
        return $this;
    }

    public function getRatings(): Collection
    {
        return $this->ratings;
    }

    public function addRating(Rating $rating): void
    {
        if (!$this->ratings->contains($rating)) {
            $this->ratings->add($rating);
            $rating->setTask($this);
        }
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'lesson' => isset($this->lesson) ? $this->lesson->getName() : null,
            'spectrums' => array_map(static fn(Spectrum $spectrum) => $spectrum->toArray(), $this->spectrums->toArray()),
            'ratings' => array_map(static fn(Rating $rating) => $rating->toArray(), $this->ratings->toArray()),
        ];
    }
}
