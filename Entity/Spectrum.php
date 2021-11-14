<?php

namespace StudentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ORM\Table(
 *     name="spectrum",
 *     indexes={
 *         @ORM\Index(name="spectrum__skill_id__ind", columns={"skill_id"}),
 *         @ORM\Index(name="spectrum__task_id__ind", columns={"task_id"})
 *     }
 * )
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="StudentBundle\Repository\SpectrumRepository")
 * @ApiResource()
 */
class Spectrum
{
    /**
     * @ORM\Column(name="id", type="bigint", unique=true)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private int $percent;

    /**
     * @ORM\ManyToOne(targetEntity="Skill", inversedBy="spectrums")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="skill_id", referencedColumnName="id")
     * })
     */
    private Skill $skill;

    /**
     * @ORM\ManyToOne(targetEntity="Task", inversedBy="spectrums")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="task_id", referencedColumnName="id")
     * })
     */
    private Task $task;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getPercent(): int
    {
        return $this->percent;
    }

    public function setPercent(int $percent): void
    {
        $this->percent = $percent;
    }

    public function getSkill(): Skill
    {
        return $this->skill;
    }

    public function setSkill(Skill $skill): void
    {
        $this->skill = $skill;
    }

    public function getTask(): Task
    {
        return $this->task;
    }

    public function setTask(Task $task): void
    {
        $this->task = $task;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'skill' => $this->skill->getName(),
            'task' => $this->task->getName(),
            'percent' => $this->percent,
        ];
    }
}