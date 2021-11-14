<?php

namespace StudentBundle\Consumer\Rating;

use StudentBundle\DTO\RatingInputDTO;
use StudentBundle\Entity;
use Exception;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Doctrine\ORM\EntityManagerInterface;

class Consumer implements ConsumerInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function execute(AMQPMessage $msg): int
    {
        try
        {
            $ratingInputDTO = RatingInputDTO::createFromQueue($msg->getBody());

            if ($ratingInputDTO->id === null)
                $rating = new Entity\Rating();
            else
                $rating = $this->entityManager->getRepository(Entity\Rating::class)->find($ratingInputDTO->id);

            $rating->setRate($ratingInputDTO->rate);
            $rating->setTask(
                $this->entityManager->getRepository(Entity\Task::class)->find($ratingInputDTO->taskId)
            );
            $rating->setStudent(
                $this->entityManager->getRepository(Entity\Student::class)->find($ratingInputDTO->studentId)
            );

            $this->entityManager->persist($rating);
            $this->entityManager->flush();
        }
        catch (Exception $e)
        {
            return $this->reject($e->getMessage());
        }

        $this->entityManager->clear();
        $this->entityManager->getConnection()->close();

        return self::MSG_ACK;
    }

    private function reject(string $error): int
    {
        echo "Incorrect message: $error";
        return self::MSG_REJECT;
    }
}