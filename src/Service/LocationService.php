<?php

namespace App\Service;

use App\DTO\IpLocationDTO;
use App\Exception\ApiIpLocateException;
use Psr\Log\LoggerInterface;
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

    public function getLocation(string $ipAddress): IpLocationDTO
    {
        try {
            $response = $this->httpClient->request(
                'GET',
                self::IPLOCATE_API_URL . $ipAddress,
            );
            $data = json_decode($response->getContent(), true);
        } catch (Throwable $exception) {
            $this->logger->error("Error while getting location. Exception message: {$exception->getMessage()}");
            throw new ApiIpLocateException();
        }

        if (empty($data['country'])) {
            $this->logger->error("Error while getting location: {$ipAddress}. Response does not contain country key.", [
                'data' => $data,
            ]);
            throw new ApiIpLocateException();
        }

        return new IpLocationDTO(
            $data['country'],
            $ipAddress,
        );
    }
}