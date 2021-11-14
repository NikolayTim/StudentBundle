<?php

namespace StudentBundle\Controller\Api\v1;

use StudentBundle\DTO\AggregationByCoursesDTO;
use StudentBundle\Service\AggregationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AggregationByCoursesDTOController extends AbstractController
{
    private AggregationService $aggregationService;

    public function __construct(AggregationService $aggregationService)
    {
        $this->aggregationService = $aggregationService;
    }

    public function __invoke(string $id): ?AggregationByCoursesDTO
    {
        return new AggregationByCoursesDTO(
            $this->aggregationService->getAggregationByCourses($id)
        );
    }
}