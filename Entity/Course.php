<?php

namespace StudentBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ORM\Table(
 *     name="course",
 *     indexes={
 *         @ORM\Index(name="course__parent_id__ind", columns={"parent_id"}),
 *     }
 * )
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="StudentBundle\Repository\CourseRepository")
 * @ApiResource()
 */
class Course
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
     * @ORM\ManyToOne(targetEntity="Course", inversedBy="children")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     * })
     */
    private ?Course $parent = null;

    /**
     * @ORM\OneToMany(targetEntity="Course", mappedBy="parent")
     */
    private Collection $children;

    /**
     * @ORM\OneToMany(targetEntity="Lesson", mappedBy="course")
     */
    private Collection $lessons;

    public function __construct()
    {
        $this->lessons = new ArrayCollection();
        $this->children = new ArrayCollection();
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

    public function getParent(): ?Course
    {
        return $this->parent;
    }

    public function setParent(Course $parent): void
    {
        $this->parent = $parent;
    }

    public function getLessons(): Collection
    {
        return $this->lessons;
    }

    public function addLesson(Lesson $lesson): void
    {
        if (!$this->lessons->contains($lesson)) {
            $this->lessons->add($lesson);
            $lesson->setCourse($this);
        }
    }

    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function addChildren(Course $child): void
    {
        if (!$this->children->contains($child)) {
            $this->children->add($child);
            $child->setParent($this);
        }
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'parent' => (empty($this->parent) ? '0' : $this->parent->getId()),
            'children' => array_map(static fn(Course $course) => $course->toArray(), $this->children->toArray()),
            'lessons' => array_map(static fn(Lesson $lesson) => $lesson->toArray(), $this->lessons->toArray()),
        ];
    }
}