<?php

namespace StudentBundle\Resolver;

use StudentBundle\DTO\AggregationByLessonsDTO;
use StudentBundle\DTO\AggregationByCoursesDTO;
use StudentBundle\DTO\AggregationBySkillsDTO;
use StudentBundle\DTO\AggregationByDateTimeDTO;
use StudentBundle\Service\AggregationService;
use ApiPlatform\Core\GraphQl\Resolver\QueryItemResolverInterface;

class StudentResolver implements QueryItemResolverInterface
{
    private AggregationService $aggregationService;

    public function __construct(AggregationService $aggregationService)
    {
        $this->aggregationService = $aggregationService;
    }

    public function __invoke($item, array $context): ?object
    {
        if (array_key_exists('aggregationByLessonsDTO', $context['info']->getFieldSelection()))
            $item->aggregationByLessonsDTO = new AggregationByLessonsDTO(
                $this->aggregationService->getAggregationByLessons($item->getId())
            );

        if (array_key_exists('aggregationByCoursesDTO', $context['info']->getFieldSelection()))
            $item->aggregationByCoursesDTO = new AggregationByCoursesDTO(
                $this->aggregationService->getAggregationByCourses($item->getId())
            );

        if (array_key_exists('aggregationBySkillsDTO', $context['info']->getFieldSelection()))
            $item->aggregationBySkillsDTO = new AggregationBySkillsDTO(
                $this->aggregationService->getAggregationBySkills($item->getId())
            );

        if (array_key_exists('aggregationByDateTimeDTO', $context['info']->getFieldSelection()))
            $item->aggregationByDateTimeDTO = new AggregationByDateTimeDTO(
                $this->aggregationService->getAggregationByDateTime($item->getId())
            );

        return $item;
    }
}

/*
{
    aggregationsStudent(id: "/api-platform/students/1")
    {
    id
    _id
    fullName
    aggregationByLessonsDTO {
    lessons
    }
    aggregationByCoursesDTO {
    modules
    }
    aggregationBySkillsDTO {
    skills
    }
    aggregationByDateTimeDTO {
    datetime
    }
    ratings {
    edges {
        node {
            id
          _id
          rate
          createdAt
        }
      }
    }
  }
}
*/