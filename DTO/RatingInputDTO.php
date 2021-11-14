<?php
namespace StudentBundle\DTO;

use StudentBundle\Entity\Rating;
use StudentBundle\Validator\Constraints\IsValidTaskId as IsValidTaskId;
use StudentBundle\Validator\Constraints\IsValidStudentId as IsValidStudentId;
use Symfony\Component\Validator\Constraints as Assert;

class RatingInputDTO
{
    public ?int $id = null;

    /**
     * @Assert\NotBlank()
     * @Assert\Type("integer")
     * @Assert\Range(
     *      min = 1,
     *      max = 10,
     *      minMessage = "Минимальная оценка за задание - 1",
     *      maxMessage = "Максимальная оценка за задание - 10"
     * )
     */
    public int $rate;

    /**
     * @Assert\NotBlank()
     * @Assert\Type("integer")
     * @IsValidTaskId()
     */
    public int $taskId;

    /**
     * @Assert\NotBlank()
     * @Assert\Type("integer")
     * @IsValidStudentId()
     */
    public int $studentId;

    public function __construct(array $data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->rate = $data['rate'] ?? 0;
        $this->taskId = $data['taskId'] ?? 0;
        $this->studentId = $data['studentId'] ?? 0;
    }

    public static function createFromQueue(string $messageBody): self
    {
        $message = json_decode($messageBody, true, 512, JSON_THROW_ON_ERROR);
        $result = new self();
        $result->id = $message['id'];
        $result->rate = $message['rate'];
        $result->taskId = $message['taskId'];
        $result->studentId = $message['studentId'];

        return $result;
    }

    public function toAMQPMessage(): string
    {
        return json_encode($this, JSON_FORCE_OBJECT);
    }
}