<?php

namespace App\EventListener;

use App\DTO\ErrorResponseDTO;
use App\Exception\ValidationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;

class ValidationExceptionListener
{
    public function __construct(
        private SerializerInterface $serializer,
    )
    {
    }

    /**
     * @throws ExceptionInterface
     */
    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        if (!($exception instanceof ValidationException)) {
            return;
        }
        $violations = [];
        foreach ($exception->getViolations() as $violation) {
            $violations[$violation->getPropertyPath()][] = $violation->getMessage();
        }
        $data = $this->serializer->serialize(
            new ErrorResponseDTO($exception->getMessage(), $violations),
            JsonEncoder::FORMAT
        );
        $response = new JsonResponse($data, Response::HTTP_UNPROCESSABLE_ENTITY, [], true);
        $event->setResponse($response);
    }
}