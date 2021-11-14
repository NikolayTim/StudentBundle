<?php

namespace StudentBundle\Service;

use StudentBundle\Entity;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\Exception\InvalidArgumentException;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class AggregationService
{
    private EntityManagerInterface $entityManager;
    private TagAwareCacheInterface $cache;

    public function __construct(EntityManagerInterface $entityManager, TagAwareCacheInterface $cache)
    {
        $this->entityManager = $entityManager;
        $this->cache = $cache;
    }

    /**
     * Агрегация баллов по студенту $studentId по занятиям
     *
     * @param int $studentId
     * @return array
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function getAggregationByLessons(int $studentId): array
    {
        return $this->cache->get(
            ClearCacheService::CACHE_TAG_BY_LESSONS . "_{$studentId}",
            function(ItemInterface $item) use ($studentId) {
                $result['id'] = $studentId;
                $student = $this->entityManager->getRepository(Entity\Student::class)->find($studentId);
                if (!$student)
                    throw new InvalidArgumentException('Id-student: ' . $studentId . ' not found!');

                $aggregations = $this->entityManager->getRepository(Entity\Lesson::class)->getRatesByLessons($studentId);
                $lessonIds = array_column($aggregations, 'lessonId');

                $lessons = [];
                if ($lessonIds)
                    $lessons = $this->entityManager->getRepository(Entity\Lesson::class)->findBy(['id' => $lessonIds]);

                array_walk($lessons, function (&$lesson, $key) use ($aggregations, &$result) {
                    $result['lessons'][] = [
                        'sum' => $aggregations[$key]['sum'],
                        'lessonId' => $lesson->getId(),
                        'lessonName' => $lesson->getName()
                    ];
                });

                $item->set($result);
                $item->tag(ClearCacheService::CACHE_TAG_BY_LESSONS);

                return $result;
            });
    }

    /**
     * Агрегация баллов по студенту $studentId по курсам (модулям)
     *
     * @param int $studentId
     * @return array
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function getAggregationByCourses(int $studentId): array
    {
        return $this->cache->get(
            ClearCacheService::CACHE_TAG_BY_COURSES . "_{$studentId}",
            function(ItemInterface $item) use ($studentId) {
                $result['id'] = $studentId;
                $student = $this->entityManager->getRepository(Entity\Student::class)->find($studentId);
                if (!$student)
                    throw new InvalidArgumentException('Id-student: ' . $studentId . ' not found!');

                $aggregations = $this->entityManager->getRepository(Entity\Course::class)->getRatesByCourses($studentId);
                $moduleIds = array_column($aggregations, 'moduleId');

                $modules = [];
                if ($moduleIds)
                    $modules = $this->entityManager->getRepository(Entity\Course::class)->findBy(['id' => $moduleIds]);

                array_walk($modules, function (&$item, $key) use ($aggregations, &$result) {
                    $result['modules'][] = [
                        'sum' => $aggregations[$key]['sum'],
                        'moduleId' => $item->getId(),
                        'moduleName' => $item->getName()
                    ];
                });

                $item->set($result);
                $item->tag(ClearCacheService::CACHE_TAG_BY_COURSES);

                return $result;
            });
    }

    /**
     * Агрегация баллов по студенту $studentId по навыкам
     *
     * @param int $studentId
     * @return array
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function getAggregationBySkills(int $studentId): array
    {
        return $this->cache->get(
            ClearCacheService::CACHE_TAG_BY_SKILLS . "_{$studentId}",
            function(ItemInterface $item) use ($studentId) {
                $result['id'] = $studentId;
                $student = $this->entityManager->getRepository(Entity\Student::class)->find($studentId);
                if (!$student)
                    throw new InvalidArgumentException('Id-student: ' . $studentId . ' not found!');

                $aggregations = $this->entityManager->getRepository(Entity\Skill::class)->getRatesBySkills($studentId);
                $skillIds = array_column($aggregations, 'skillId');

                $skills = [];
                if ($skillIds)
                    $skills = $this->entityManager->getRepository(Entity\Skill::class)->findBy(['id' => $skillIds]);

                array_walk($skills, function (&$item, $key) use ($aggregations, &$result) {
                    $result['skills'][] = [
                        'sum' => round($aggregations[$key]['sum'] / 100, 2),
                        'skillId' => $item->getId(),
                        'skillName' => $item->getName()
                    ];
                });

                $item->set($result);
                $item->tag(ClearCacheService::CACHE_TAG_BY_SKILLS);

                return $result;
            });
    }

    /**
     * Агрегация баллов по студенту $studentId по дате и времени
     *
     * @param int $studentId
     * @return array
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function getAggregationByDateTime(int $studentId): array
    {
        return $this->cache->get(
            ClearCacheService::CACHE_TAG_BY_DATETIME . "_{$studentId}",
            function(ItemInterface $item) use ($studentId) {
                $result['id'] = $studentId;
                $student = $this->entityManager->getRepository(Entity\Student::class)->find($studentId);
                if (!$student)
                    throw new InvalidArgumentException('Id-student: ' . $studentId . ' not found!');

                $result['datetime'] = $this->entityManager->getRepository(Entity\Rating::class)->getRatesByDateTime($studentId);

                $item->set($result);
                $item->tag(ClearCacheService::CACHE_TAG_BY_DATETIME . '_' . $studentId);

                return $result;
            });
    }
}