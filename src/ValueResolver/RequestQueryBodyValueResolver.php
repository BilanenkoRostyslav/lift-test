<?php

namespace App\ValueResolver;

use App\Attribute\RequestQueryBody;
use App\Exception\BadRequestException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class RequestQueryBodyValueResolver implements ValueResolverInterface
{
    public function __construct(
        private readonly DenormalizerInterface $denormalizer,
        private readonly LoggerInterface       $logger,
    )
    {
    }

    private function supports(ArgumentMetadata $argument): bool
    {
        return count($argument->getAttributesOfType(RequestQueryBody::class, ArgumentMetadata::IS_INSTANCEOF)) > 0;
    }

    /**
     * @param Request $request
     * @param ArgumentMetadata $argument
     * @return iterable<object>
     * @throws BadRequestException
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if (!$this->supports($argument)) {
            return [];
        }
        try {
            $data = $this->denormalizer->denormalize($request->query->all(), $argument->getType());
        } catch (ExceptionInterface $e) {
            $this->logger->error($e->getMessage());
            throw new BadRequestException();
        }
        return [$data];
    }
}