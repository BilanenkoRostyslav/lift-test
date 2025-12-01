<?php

namespace App\EventListener;

use App\DTO\ErrorResponseDTO;
use App\Enum\OrderDirection;
use App\ExceptionResolver\ExceptionMapping;
use App\ExceptionResolver\ExceptionResolver;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;

class ApiExceptionListener
{
    public function __construct(
        private readonly ExceptionResolver   $exceptionResolver,
        private readonly LoggerInterface     $logger,
        private readonly SerializerInterface $serializer,
        private readonly bool                $isDebug,
    )
    {
    }

    /**
     * @throws ExceptionInterface
     */
    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $mapping = $this->exceptionResolver->resolve(get_class($exception));
        if (!$mapping) {
            $mapping = ExceptionMapping::fromCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        if ($mapping->getCode() >= Response::HTTP_INTERNAL_SERVER_ERROR || $mapping->isLoggable()) {
            $this->logger->error(
                $exception->getMessage(),
                [
                    'trace' => $exception->getTraceAsString(),
                ]
            );
        }
        $message = $mapping->isHidden() ? Response::$statusTexts[$mapping->getCode()] : $exception->getMessage();
        $trace = $this->isDebug ? ['trace' => $exception->getTrace()] : null;
        $response = new ErrorResponseDTO($message, $trace);
        $data = $this->serializer->serialize($response, JsonEncoder::FORMAT);
        $response = new JsonResponse($data, $mapping->getCode(), [], true);
        $event->setResponse($response);
    }
}