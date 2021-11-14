<?php

namespace StudentBundle\Entity;

use StudentBundle\DTO\AggregationByLessonsDTO;
use StudentBundle\DTO\AggregationByCoursesDTO;
use StudentBundle\DTO\AggregationBySkillsDTO;
use StudentBundle\DTO\AggregationByDateTimeDTO;
use StudentBundle\Resolver\StudentResolver;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;

/**
 * @ORM\Table(name="student")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="StudentBundle\Repository\StudentRepository")
 * @ApiResource(
 *     graphql={
 *         "aggregations"={
 *             "item_query"=StudentResolver::class
 *         },
 *         "collection_query",
 *         "create",
 *         "update",
 *         "delete"
 *     }
 * )
 */
class Student
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
    private string $fullName;

    /**
     * @ORM\OneToMany(targetEntity="Rating", mappedBy="student")
     */
    private Collection $ratings;

    public ?AggregationByLessonsDTO $aggregationByLessonsDTO = null;
    public ?AggregationByCoursesDTO $aggregationByCoursesDTO = null;
    public ?AggregationBySkillsDTO $aggregationBySkillsDTO = null;
    public ?AggregationByDateTimeDTO $aggregationByDateTimeDTO = null;

    public function __construct()
    {
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

    public function getFullName(): string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): void
    {
        $this->fullName = $fullName;
    }

    public function getRatings(): Collection
    {
        return $this->ratings;
    }

    public function addRating(Rating $rating): void
    {
        if (!$this->ratings->contains($rating)) {
            $this->ratings->add($rating);
            $rating->setStudent($this);
        }
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'fullName' => $this->fullName,
            'ratings' => array_map(static fn(Rating $rating) => $rating->toArray(), $this->ratings->toArray()),
        ];
    }
}
