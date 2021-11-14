<?php

namespace StudentBundle\Entity;

use StudentBundle\DTO\RatingInputDTO;
use Doctrine\ORM\Mapping as ORM;
use DateTime;
use Gedmo\Mapping\Annotation as Gedmo;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ORM\Table(
 *     name="rating",
 *     indexes={
 *         @ORM\Index(name="rating__task_id__ind", columns={"task_id"}),
 *         @ORM\Index(name="rating__student_id__ind", columns={"student_id"}),
 *         @ORM\Index(name="rating__created_at__ind", columns={"created_at"})
 *     }
 * )
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="StudentBundle\Repository\RatingRepository")
 * @ApiResource(
 *    collectionOperations={
 *        "get",
 *        "post"={
 *            "method"="POST",
 *            "input"=RatingInputDTO::class,
 *            "status"=202,
 *            "output"=false
 *        }
 *    },
 *    itemOperations = {
 *        "get",
 *        "delete",
 *        "update" = {
 *            "method" = "PATCH",
 *            "input" = RatingInputDTO::class,
 *            "status"=202,
 *            "output"=false
 *         }
 *     }
 * )
 */
class Rating
{
    /**
     * @ORM\Column(name="id", type="bigint", unique=true)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="create")
     */
    private DateTime $createdAt;

    /**
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="update")
     */
    private DateTime $updatedAt;

    /**
     * @ORM\Column(type="integer")
     */
    private int $rate;

    /**
     * @ORM\ManyToOne(targetEntity="Task", inversedBy="ratings")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="task_id", referencedColumnName="id")
     * })
     */
    private Task $task;

    /**
     * @ORM\ManyToOne(targetEntity="Student", inversedBy="ratings")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="student_id", referencedColumnName="id")
     * })
     */
    private Student $student;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(): void
    {
        $this->createdAt = new DateTime();
    }

    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTime $dateTime): void
    {
        $this->updatedAt = $dateTime ?? new DateTime();
    }

    public function getRate(): int
    {
        return $this->rate;
    }

    public function setRate(int $rate): void
    {
        $this->rate = $rate;
    }

    public function getTask(): Task
    {
        return $this->task;
    }

    public function setTask(Task $task): void
    {
        $this->task = $task;
    }

    public function getStudent(): Student
    {
        return $this->student;
    }

    public function setStudent(Student $student): void
    {
        $this->student = $student;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
            'rating' => $this->rate,
            'task' => $this->task->getName(),
            'student' => $this->student->getFullName(),
        ];
    }
}