<?php

namespace StudentBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ORM\Table(name="skill")
 * @ORM\Entity(repositoryClass="StudentBundle\Repository\SkillRepository")
 * @ApiResource()
 */
class Skill
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
     * @ORM\OneToMany(targetEntity="Spectrum", mappedBy="skill")
     */
    private Collection $spectrums;

    public function __construct()
    {
        $this->spectrums = new ArrayCollection();
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

    public function getSpectrums(): Collection
    {
        return $this->spectrums;
    }

    public function addSpectrum(Spectrum $spectrum): void
    {
        if (!$this->spectrums->contains($spectrum)) {
            $this->spectrums->add($spectrum);
            $spectrum->setSkill($this);
        }
    }

    public function removeSpectrum(Spectrum $spectrum): self
    {
        if ($this->spectrums->contains($spectrum)) {
            $this->spectrums->removeElement($spectrum);
        }
        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'spectrums' => array_map(static fn(Spectrum $spectrum) => $spectrum->toArray(), $this->spectrums->toArray()),
        ];
    }
}