<?php

namespace StudentBundle\Controller\Api\v1;

use StudentBundle\DTO\AggregationBySkillsDTO;
use StudentBundle\Service\AggregationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AggregationBySkillsDTOController extends AbstractController
{
    private AggregationService $aggregationService;

    public function __construct(AggregationService $aggregationService)
    {
        $this->aggregationService = $aggregationService;
    }

    public function __invoke(string $id): ?AggregationBySkillsDTO
    {
        return new AggregationBySkillsDTO(
            $this->aggregationService->getAggregationBySkills($id)
        );
    }
}