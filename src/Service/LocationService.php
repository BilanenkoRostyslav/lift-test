<?php

namespace App\Service;

use App\DTO\IpLocationDTO;
use App\Exception\ApiIpLocateException;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Throwable;

class LocationService
{
    private const string IPLOCATE_API_URL = 'https://iplocate.io/api/lookup/';

    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly LoggerInterface     $logger,
    )
    {
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function getLocation(string $ipAddress): IpLocationDTO
    {
        $response = $this->httpClient->request(
            'GET',
            self::IPLOCATE_API_URL . $ipAddress,
        );
        if ($response->getStatusCode() !== 200) {
            $this->logger->error("Error while getting location: {$ipAddress}. Response: {$response->getStatusCode()}");
            throw new ApiIpLocateException();
        }

        try {
            $data = json_decode($response->getContent(), true);
        } catch (Throwable $exception) {
            $this->logger->error("Error while getting location: {$ipAddress}. Exception message: {$exception->getMessage()}");
            throw new ApiIpLocateException();
        }

        if (empty($data['country'])) {
            $this->logger->error("Error while getting location: {$ipAddress}. Response does not contain country key. Response: {$data}");
            throw new ApiIpLocateException();
        }

        return new IpLocationDTO(
            $data['country'],
            $ipAddress,
        );
    }
}