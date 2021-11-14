<?php

namespace StudentBundle\Command;

use StudentBundle\Service\TestDataService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TestDataCommand extends Command
{
    private TestDataService $testDataService;

    public function __construct(TestDataService $testDataService)
    {
        parent::__construct();
        $this->testDataService = $testDataService;
    }

    protected function configure(): void
    {
        $this->setName('testdata:add')
            ->setDescription('Add test data');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->write("<info>Начало: " . (new \DateTime())->format('d.m.Y H:i:s') . "</info>\n");
        $result = $this->testDataService->generateSkills();
        if ($result)
            $output->write("<info>Навыки добавлены.</info>\n");
        else
        {
            $output->write("<info>Навыки не добавлены!</info>\n");
            return self::FAILURE;
        }

        $result = $this->testDataService->generateStudents();
        if ($result)
            $output->write("<info>Студенты добавлены.</info>\n");
        else
        {
            $output->write("<info>Студенты не добавлены!</info>\n");
            return self::FAILURE;
        }

        $result = $this->testDataService->generateCourses();
        if ($result)
            $output->write("<info>Курсы и модули добавлены.</info>\n");
        else
        {
            $output->write("<info>Курсы и модули не добавлены!</info>\n");
            return self::FAILURE;
        }

        $result = $this->testDataService->generateLessons();
        if ($result)
            $output->write("<info>Занятия добавлены.</info>\n");
        else
        {
            $output->write("<info>Занятия не добавлены!</info>\n");
            return self::FAILURE;
        }

        $result = $this->testDataService->generateTasks();
        if ($result)
            $output->write("<info>Задания добавлены.</info>\n");
        else
        {
            $output->write("<info>Задания не добавлены!</info>\n");
            return self::FAILURE;
        }

        $result = $this->testDataService->generateSpectrums();
        if ($result)
            $output->write("<info>Спектры навыков добавлены.</info>\n");
        else
        {
            $output->write("<info>Спектры навыков не добавлены!</info>\n");
            return self::FAILURE;
        }

        $result = $this->testDataService->generateRatings();
        if ($result)
            $output->write("<info>Оценки добавлены.</info>\n");
        else
        {
            $output->write("<info>Оценки не добавлены!</info>\n");
            return self::FAILURE;
        }
        $output->write("<info>Завершение: " . (new \DateTime())->format('d.m.Y H:i:s') . "</info>\n");

        return self::SUCCESS;
    }
}