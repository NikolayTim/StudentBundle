<?php

namespace StudentBundle\Controller\Api\v1;

use StudentBundle\DTO\AggregationByDateTimeDTO;
use StudentBundle\Service\AggregationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AggregationByDateTimeDTOController extends AbstractController
{
    private AggregationService $aggregationService;

    public function __construct(AggregationService $aggregationService)
    {
        $this->aggregationService = $aggregationService;
    }

    public function __invoke(string $id): ?AggregationByDateTimeDTO
    {
        return new AggregationByDateTimeDTO(
            $this->aggregationService->getAggregationByDateTime($id)
        );
    }
}