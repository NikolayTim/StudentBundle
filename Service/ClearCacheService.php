<?php

namespace StudentBundle\Service;

use StudentBundle\Entity;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class ClearCacheService
{
    public const CACHE_TAG_BY_LESSONS = 'lessons';
    public const CACHE_TAG_BY_COURSES = 'courses';
    public const CACHE_TAG_BY_SKILLS = 'skills';
    public const CACHE_TAG_BY_DATETIME = 'datetime';

    private TagAwareCacheInterface $cache;

    public function __construct(TagAwareCacheInterface $cache)
    {
        $this->cache = $cache;
    }

    public function clearCache(object $entity): void
    {
//        dump($entity);
        if ($entity instanceof Entity\Skill || $entity instanceof Entity\Spectrum)
            $this->clearCacheByTags([self::CACHE_TAG_BY_SKILLS]);
        elseif ($entity instanceof Entity\Course || $entity instanceof Entity\Lesson)
            $this->clearCacheByTags([self::CACHE_TAG_BY_COURSES, self::CACHE_TAG_BY_LESSONS]);
        elseif ($entity instanceof Entity\Task)
            $this->clearCacheByTags([
                self::CACHE_TAG_BY_COURSES,
                self::CACHE_TAG_BY_LESSONS,
                self::CACHE_TAG_BY_SKILLS
            ]);
        elseif ($entity instanceof Entity\Rating)
        {
            $this->clearCacheByTags([
                self::CACHE_TAG_BY_COURSES,
                self::CACHE_TAG_BY_LESSONS,
                self::CACHE_TAG_BY_SKILLS,
                self::CACHE_TAG_BY_DATETIME . '_' . $entity->getStudent()->getId()
            ]);
        }
    }

    public function clearCacheByTags(array $tags): void
    {
        $this->cache->invalidateTags($tags);
    }
}