<?php

namespace StudentBundle\DTO;

use StudentBundle\Controller\Api\v1\AggregationByLessonsDTOController;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ApiResource(
 *     itemOperations={
 *         "get"={
 *             "method"="GET",
 *             "controller" = AggregationByLessonsDTOController::class,
 *             "requirements" = {"id"="\d+"},
 *             "read"=false
 *         }
 *     },
 *     collectionOperations={},
 *     iri="/aggregationByLessonsDTOs/{id}"
 * )
 */
class AggregationByLessonsDTO
{
    public ?int $id = null;

    public array $lessons;

    public function __construct(array $data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->lessons = $data['lessons'] ?? [];
    }

    public function getId(): ?int
    {
        return $this->id;
    }
}