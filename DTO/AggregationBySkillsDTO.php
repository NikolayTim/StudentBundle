<?php

namespace StudentBundle\DTO;

use StudentBundle\Controller\Api\v1\AggregationBySkillsDTOController;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ApiResource(
 *     itemOperations={
 *         "get"={
 *             "method"="GET",
 *             "controller" = AggregationBySkillsDTOController::class,
 *             "requirements" = {"id"="\d+"},
 *             "read"=false
 *         }
 *     },
 *     collectionOperations={},
 *     iri="/aggregationBySkillsDTOs/{id}"
 * )
 */
class AggregationBySkillsDTO
{
    public ?int $id = null;

    public array $skills;

    public function __construct(array $data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->skills = $data['skills'] ?? [];
    }

    public function getId(): ?int
    {
        return $this->id;
    }
}