<?php

namespace StudentBundle\Controller\Api\v1;

use StudentBundle\DTO\AggregationByLessonsDTO;
use StudentBundle\Service\AggregationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AggregationByLessonsDTOController extends AbstractController
{
    private AggregationService $aggregationService;

    public function __construct(AggregationService $aggregationService)
    {
        $this->aggregationService = $aggregationService;
    }

    public function __invoke(string $id): ?AggregationByLessonsDTO
    {
        return new AggregationByLessonsDTO(
            $this->aggregationService->getAggregationByLessons($id)
        );
    }
}