<?php

namespace App\Controller;

use App\Attribute\RequestBody;
use App\Attribute\RequestQueryBody;
use App\Document\UserData;
use App\DTO\GetUserDataDTO;
use App\DTO\TestVO;
use App\DTO\UserDataDTO;
use App\Factory\UserDataDocumentFactory;
use App\Repository\UserDataRepository;
use App\Service\LocationService;
use App\Service\UserDataService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

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
     * @throws TransportExceptionInterface
     * @throws ExceptionInterface
     */
    #[Route(path: '/v1/api/data', name: 'v1-add-data', methods: ['POST'])]
    public function addUserData(#[RequestBody] UserDataDTO $userDTO): JsonResponse
    {
        $ipAddress = $this->requestStack->getCurrentRequest()->getClientIp();
        $locationAndIp = $this->locationService->getLocation($ipAddress);
        $this->userDataService->addUserData($userDTO, $locationAndIp);
        return $this->json(["message" => "Success!"]);
    }

    #[Route(path: '/v1/api/data', name: 'v1-get-data', methods: ['GET'])]
    public function getUserData(#[RequestQueryBody] GetUserDataDTO $getUserDataDTO): JsonResponse
    {
        $result = $this->userDataService->getUserData($getUserDataDTO);
        return $this->json($getUserDataDTO);
    }
}