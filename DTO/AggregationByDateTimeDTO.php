<?php

namespace StudentBundle\DTO;

use StudentBundle\Controller\Api\v1\AggregationByDateTimeDTOController;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ApiResource(
 *     itemOperations={
 *         "get"={
 *             "method"="GET",
 *             "controller" = AggregationByDateTimeDTOController::class,
 *             "requirements" = {"id"="\d+"},
 *             "read"=false
 *         }
 *     },
 *     collectionOperations={},
 *     iri="/aggregationByDateTimeDTOs/{id}"
 * )
 */
class AggregationByDateTimeDTO
{
    public ?int $id = null;

    public array $datetime;

    public function __construct(array $data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->datetime = $data['datetime'] ?? [];
    }

    public function getId(): ?int
    {
        return $this->id;
    }
}