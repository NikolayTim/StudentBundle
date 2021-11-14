<?php

namespace StudentBundle\Service;

use App\Entity;
use Doctrine\ORM\EntityManagerInterface;

class TestDataService
{
    const SKILL_NAME = 'Навык_';
    const MODULE_NAME = 'Модуль_';
    const LESSON_NAME = 'Урок_';
    const TASK_NAME = 'Задание_';

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function generateSkills(): bool
    {
        for ($i = 1; $i <= 100; $i++)
        {
            $skills[$i] = new Entity\Skill();
            $skills[$i]->setName(self::SKILL_NAME . $i);
            $this->entityManager->persist($skills[$i]);
            $this->entityManager->flush();
        }

        return (bool)$skills;
    }

    public function generateStudents(): bool
    {
        $nameStudents = [
            'Иван Иванов',
            'Петр Петров',
            'Сидр Сидоров',
            'Маша Машина',
            'Катя Катина',
            'Клава Клавина',
            'Вася Васечкин',
            'Оля Олина',
            'Женя Женин',
            'Алексей Алексеев'
        ];

        foreach ($nameStudents as $keyStudent => $nameStudent)
        {
            $students[$keyStudent] = new Entity\Student();
            $students[$keyStudent]->setFullName($nameStudent);
            $this->entityManager->persist($students[$keyStudent]);
            $this->entityManager->flush();
        }

        return (bool)$students;
    }

    public function generateCourses(): bool
    {
        $nameCourses = [
            'Symfony',
            'PHP',
            'JavaScript',
            'Jquery',
            'HTML'
        ];

        foreach ($nameCourses as $keyCourse => $nameCourse)
        {
            $courses[$keyCourse] = new Entity\Course();
            $courses[$keyCourse]->setName($nameCourse);
            $this->entityManager->persist($courses[$keyCourse]);
            $this->entityManager->flush();

            $cntModules = rand(3, 7);
            for ($i = 1; $i <= $cntModules; $i++)
            {
                $module = new Entity\Course();
                $module->setName(self::MODULE_NAME . $i . ' (' . $courses[$keyCourse]->getName() . ')');
                $module->setParent($courses[$keyCourse]);
                $this->entityManager->persist($module);
                $this->entityManager->flush();
                $module = null;
            }
        }

        return (bool)$courses;
    }

    public function generateLessons(): bool
    {
        $lessons = [];

        $courseRepository = $this->entityManager->getRepository(Entity\Course::class);
        $modules = $courseRepository->findAll();

        foreach ($modules as $module)
        {
            if (($module->getParent() ?? null) === null)
                continue;

            $cntLesson = rand(5, 10);
            for ($i = 1; $i <= $cntLesson; $i++)
            {
                $lesson = new Entity\Lesson();
                $lesson->setName(self::LESSON_NAME . $i . ' (' . $module->getName() . ')');
                $lesson->setCourse($module);
                $this->entityManager->persist($lesson);
                $this->entityManager->flush();
                $lessons[] = $lesson;
                $lesson = null;
            }
        }

        return (bool)$lessons;
    }

    public function generateTasks(): bool
    {
        $tasks = [];
        $lessons = $this->entityManager->getRepository(Entity\Lesson::class)->findAll();

        foreach ($lessons as $lesson)
        {
            $cntTask = rand(5, 10);
            for ($i = 1; $i <= $cntTask; $i++)
            {
                $task = new Entity\Task();
                $task->setName(self::TASK_NAME . $i . ' (' . $lesson->getName() . ')');
                $task->setLesson($lesson);
                $this->entityManager->persist($task);
                $this->entityManager->flush();
                $tasks[] = $task;
                $task = null;
            }
        }

        return (bool)$tasks;
    }

    public function generateSpectrums(): bool
    {
        $spectrums = [];
        $skills = $this->entityManager->getRepository(Entity\Skill::class)->findAll();
        $tasks = $this->entityManager->getRepository(Entity\Task::class)->findAll();

        foreach ($tasks as $task)
        {
            $countSkillsInTask = rand(3, 6);
            $maxPercent = round(100/$countSkillsInTask, 0);
            $percents = 0;
            for ($i = 1; $i <= $countSkillsInTask; $i++)
            {
                if ($i === $countSkillsInTask)
                    $percent = 100 - $percents;
                else
                    $percent = rand(1, $maxPercent);

                $percents = $percents + $percent;
                $skillKey = rand(0, count($skills) - 1);

                $spectrum = new Entity\Spectrum();
                $spectrum->setPercent($percent);
                $spectrum->setTask($task);
                $spectrum->setSkill($skills[$skillKey]);

                $this->entityManager->persist($spectrum);
                $this->entityManager->flush();
                $spectrums[] = $spectrum;
                $spectrum = null;
            }
        }

        return (bool)$spectrums;
    }

    public function generateRatings(): bool
    {
        $ratings = [];
        $students = $this->entityManager->getRepository(Entity\Student::class)->findAll();
        $tasks = $this->entityManager->getRepository(Entity\Task::class)->findAll();

        foreach ($tasks as $task)
        {
            $studentsInTask = [];
            $countStudentsInTask = rand(4, 8);
            for ($i = 1; $i <= $countStudentsInTask; $i++)
            {
                $rate = rand(1, 10);

                $studentKey = rand(0, count($students) - 1);
                while (in_array($studentKey, $studentsInTask))
                    $studentKey = rand(0, count($students) - 1);

                $rating = new Entity\Rating();
                $rating->setRate($rate);
                $rating->setTask($task);
                $rating->setStudent($students[$studentKey]);

                $updatedAt = new \DateTime();
                $updatedAt->setTimestamp(mt_rand(1609448400, 1635714000));
                $rating->setUpdatedAt($updatedAt);

                $this->entityManager->persist($rating);
                $this->entityManager->flush();
                $ratings[] = $rating;
                $rating = null;

                $studentsInTask[] = $studentKey;
            }
            unset($studentsInTask);
        }

        return (bool)$ratings;
    }
}