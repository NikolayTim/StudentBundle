<?php

namespace StudentBundle\DTO;

use StudentBundle\Controller\Api\v1\AggregationByCoursesDTOController;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ApiResource(
 *     itemOperations={
 *         "get"={
 *             "method"="GET",
 *             "controller" = AggregationByCoursesDTOController::class,
 *             "requirements" = {"id"="\d+"},
 *             "read"=false
 *         }
 *     },
 *     collectionOperations={},
 *     iri="/aggregationByCoursesDTOs/{id}"
 * )
 */
class AggregationByCoursesDTO
{
    public ?int $id = null;

    public array $modules;

    public function __construct(array $data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->modules = $data['modules'] ?? [];
    }

    public function getId(): ?int
    {
        return $this->id;
    }
}