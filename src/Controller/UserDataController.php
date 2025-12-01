<?php

namespace App\Controller;

use App\Attribute\RequestBody;
use App\Attribute\RequestQueryBody;
use App\DTO\GetUserDataDTO;
use App\DTO\UserDataDTO;
use App\Service\LocationService;
use App\Service\UserDataService;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Routing\Attribute\Route;

class UserDataController extends AbstractController
{
    public function __construct(
        private readonly UserDataService $userDataService,
        private readonly LocationService $locationService,
        private readonly RequestStack    $requestStack,
    )
    {
    }

    /**
     * @param UserDataDTO $userDTO
     * @throws ExceptionInterface
     */
    #[OA\RequestBody(
        description: 'Дані користувача',
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(
                    property: 'firstName',
                    description: "Ім'я",
                    type: 'string',
                    example: 'John'
                ),
                new OA\Property(
                    property: 'lastName',
                    description: 'Фамілія',
                    type: 'string',
                    example: 'Doe'
                ),
                new OA\Property(
                    property: 'phoneNumbers',
                    description: 'Список телефонних номерів',
                    type: 'array',
                    items: new OA\Items(
                        type: 'string',
                        example: '+380501234567'
                    )
                ),
            ],
            type: 'object'
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Успіх',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(
                    property: 'message',
                    type: 'string',
                    example: 'Success!'
                )
            ],
            type: 'object'
        )
    )]
    #[OA\Response(
        response: 422,
        description: 'Помилка валідації',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(
                    property: 'message',
                    type: 'string',
                    example: 'Validation failed'
                ),
                new OA\Property(
                    property: 'errors',
                    type: 'object',
                    example: [
                        'firstName' => [
                            'First name must be at least 3 characters long'
                        ],
                        'lastName' => [
                            'First name must be at least 3 characters long'
                        ],
                        'phoneNumbers[0].phoneNumber' => [
                            'This value is too short. It should have 10 characters or more.',
                            'Phone number must be a valid phone number. Pattern: /^\\+380\\d{9}$/'
                        ],
                        'phoneNumbers[1].phoneNumber' => [
                            'This value is too short. It should have 10 characters or more.',
                            'Phone number must be a valid phone number. Pattern: /^\\+380\\d{9}$/'
                        ],
                    ]
                )
            ],
            type: 'object'
        )
    )]
    #[Route(path: '/api/v1/data', name: 'v1-add-data', methods: ['POST'])]
    public function addUserData(#[RequestBody] UserDataDTO $userDTO): JsonResponse
    {
        $ipAddress = $this->requestStack->getCurrentRequest()->getClientIp();
        $locationAndIp = $this->locationService->getLocation($ipAddress);
        $this->userDataService->addUserData($userDTO, $locationAndIp);
        return $this->json(["message" => "Success!"]);
    }

    #[OA\Parameter(
        name: 'orderBy',
        description: 'Поле для сортування',
        in: 'query',
        required: true,
        schema: new OA\Schema(
            type: 'string',
            enum: ['firstName', 'lastName', 'country']
        )
    )]
    #[OA\Parameter(
        name: 'orderDirection',
        description: 'Напрямок сортування',
        in: 'query',
        required: true,
        schema: new OA\Schema(
            type: 'string',
            enum: ['ASC', 'DESC']
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Список користувачів',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(
                properties: [
                    new OA\Property(property: 'firstName', type: 'string', example: 'John'),
                    new OA\Property(property: 'lastName', type: 'string', example: 'Doe'),
                    new OA\Property(
                        property: 'phoneNumbers',
                        type: 'array',
                        items: new OA\Items(type: 'string', example: '+380501234567')
                    )
                ],
                type: 'object'
            )
        )
    )]
    #[OA\Response(
        response: 422,
        description: 'Помилка валідації запита',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(
                    property: 'message',
                    type: 'string',
                    example: 'Validation failed'
                ),
                new OA\Property(
                    property: 'errors',
                    type: 'object',
                    example: [
                        'orderBy' => ['The value "abc" is invalid. Allowed values are: id, firstName, lastName'],
                        'orderDirection' => ['The value "UP" is invalid. Allowed values are: ASC, DESC']
                    ]
                )
            ],
            type: 'object'
        )
    )]
    #[Route(path: '/api/v1/data', name: 'v1-get-data', methods: ['GET'])]
    public function getUserData(#[RequestQueryBody] GetUserDataDTO $getUserDataDTO): JsonResponse
    {
        $result = $this->userDataService->getUserData($getUserDataDTO);

        return $this->json($result);
    }
}