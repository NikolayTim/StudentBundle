<?php

namespace StudentBundle\Transformer;

use StudentBundle\Entity;
use StudentBundle\Service\AsyncService;
use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use ApiPlatform\Core\Validator\ValidatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\Exception\InvalidArgumentException;

class RatingInputTransformer implements DataTransformerInterface
{
    private ValidatorInterface $validator;
    private AsyncService $asyncService;
    private EntityManagerInterface $entityManager;

    public function __construct(ValidatorInterface $validator, EntityManagerInterface $entityManager,
                                AsyncService $asyncService)
    {
        $this->validator = $validator;
        $this->asyncService = $asyncService;
        $this->entityManager = $entityManager;
    }

    public function transform($data, string $to, array $context = [])
    {
        $ratingInputDto = $data;
        $this->validator->validate($ratingInputDto);

//        if (array_key_exists('object_to_populate', $context) && $context['object_to_populate'] instanceof Entity\Rating)
        if (($context['object_to_populate'] ?? null) instanceof Entity\Rating)
            $ratingInputDto->id = $context['object_to_populate']->getId();
        else
        {
            $rating = $this->entityManager->getRepository(Entity\Rating::class)->findRating(
                $ratingInputDto->taskId, $ratingInputDto->studentId
            );
            if ($rating)
                throw new InvalidArgumentException(
                    'Оценка за задание с Id: ' . $ratingInputDto->taskId .
                    ' по студенту с Id: ' . $ratingInputDto->studentId . ' уже существует!'
                );
        }

        $this->asyncService->publishToExchange(AsyncService::RATING, $ratingInputDto->toAMQPMessage());
        return new Entity\Rating();
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        if ($data instanceof Entity\Rating)
            return false;

        return Entity\Rating::class === $to && null !== ($context['input']['class'] ?? null);
    }
}