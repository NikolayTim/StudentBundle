<?php

namespace StudentBundle\Controller\Api\Aggregation;

use StudentBundle\Service\AggregationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route as Route;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/api/aggregation")
 */
class Controller extends AbstractController
{
    private AggregationService $aggregationService;

    public function __construct(AggregationService $aggregationService)
    {
        $this->aggregationService = $aggregationService;
    }

    /**
     * @Route("/{studentId}/lessons", methods={"GET"}, requirements={"studentId":"\d+"})
     */
    public function getAggregationByLessonsAction(Request $request, $studentId): Response
    {
//        dump($this->container);
        $result = $this->aggregationService->getAggregationByLessons($studentId);
        dump($result);
        $code = empty($result) ? Response::HTTP_NO_CONTENT : Response::HTTP_OK;
        return new Response('<html><body>' . json_encode($result, JSON_UNESCAPED_UNICODE) . '</body></html>', $code);
    }

    /**
     * @Route("/{studentId}/datetime", methods={"GET"}, requirements={"studentId":"\d+"})
     */
    public function getAggregationByDateTimeAction(Request $request, $studentId): Response
    {
        $result = $this->aggregationService->getAggregationByDateTime($studentId);
//        dump($result);
        $code = empty($result) ? Response::HTTP_NO_CONTENT : Response::HTTP_OK;
        return new Response('<html><body>' . json_encode($result, JSON_UNESCAPED_UNICODE) . '</body></html>', $code);
    }

    /**
     * @Route("/{studentId}/skills", methods={"GET"}, requirements={"studentId":"\d+"})
     */
    public function getAggregationBySkillsAction(Request $request, $studentId): Response
    {
        $result = $this->aggregationService->getAggregationBySkills($studentId);
//        dump($result);
        $code = empty($result) ? Response::HTTP_NO_CONTENT : Response::HTTP_OK;
        return new Response('<html><body>' . json_encode($result, JSON_UNESCAPED_UNICODE) . '</body></html>', $code);
    }

    /**
     * @Route("/{studentId}/courses", methods={"GET"}, requirements={"studentId":"\d+"})
     */
    public function getAggregationByCoursesAction(Request $request, $studentId): Response
    {
        $result = $this->aggregationService->getAggregationByCourses($studentId);
//        dump($result);
        $code = empty($result) ? Response::HTTP_NO_CONTENT : Response::HTTP_OK;
        return new Response('<html><body>' . json_encode($result, JSON_UNESCAPED_UNICODE) . '</body></html>', $code);
    }
}