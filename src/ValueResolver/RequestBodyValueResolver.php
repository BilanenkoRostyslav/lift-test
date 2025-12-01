<?php

namespace App\ValueResolver;

use App\Attribute\RequestBody;
use App\Exception\RequestDecodeException;
use App\Exception\ValidationException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RequestBodyValueResolver implements ValueResolverInterface
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly LoggerInterface     $logger,
        private readonly ValidatorInterface  $validator,
    )
    {
    }

    private function supports(ArgumentMetadata $argument): bool
    {
        return count($argument->getAttributesOfType(RequestBody::class, ArgumentMetadata::IS_INSTANCEOF)) > 0;
    }

    /**
     * @return iterable<object>
     * @throws RequestDecodeException
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if (!$this->supports($argument)) {
            return [];
        }
        try {
            $data = $this->serializer->deserialize($request->getContent(), $argument->getType(), JsonEncoder::FORMAT);
        } catch (ExceptionInterface $e) {
            $this->logger->error($e->getMessage());
            throw new RequestDecodeException();
        }

        $errors = $this->validator->validate($data);
        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }
        return [$data];
    }
}