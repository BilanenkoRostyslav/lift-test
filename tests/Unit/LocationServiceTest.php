<?php

namespace App\Tests\Unit;

use App\DTO\IpLocationDTO;
use App\Exception\ApiIpLocateException;
use App\Service\LocationService;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class LocationServiceTest extends TestCase
{
    private HttpClientInterface $httpClient;
    private LoggerInterface $logger;
    private LocationService $service;

    protected function setUp(): void
    {
        $this->httpClient = $this->createMock(HttpClientInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->service = new LocationService($this->httpClient, $this->logger);
    }

    public function testGetLocationReturnsDtoSuccessfully(): void
    {
        $ip = '8.8.8.8';
        $responseData = ['country' => 'United States'];

        $responseMock = $this->createMock(ResponseInterface::class);
        $responseMock->method('getContent')->willReturn(json_encode($responseData));

        $this->httpClient
            ->method('request')
            ->with('GET', 'https://iplocate.io/api/lookup/' . $ip)
            ->willReturn($responseMock);

        $dto = $this->service->getLocation($ip);

        $this->assertInstanceOf(IpLocationDTO::class, $dto);
        $this->assertEquals('United States', $dto->getCountry());
        $this->assertEquals($ip, $dto->getIpAddress());
    }

    public function testGetLocationThrowsExceptionOnHttpError(): void
    {
        $ip = '8.8.8.8';

        $this->httpClient
            ->method('request')
            ->willThrowException(new \Exception('HTTP error'));

        $this->logger
            ->expects($this->once())
            ->method('error')
            ->with($this->stringContains('Error while getting location'));

        $this->expectException(ApiIpLocateException::class);

        $this->service->getLocation($ip);
    }

    public function testGetLocationThrowsExceptionIfCountryMissing(): void
    {
        $ip = '8.8.8.8';
        $responseData = ['city' => 'New York']; // country missing

        $responseMock = $this->createMock(ResponseInterface::class);
        $responseMock->method('getContent')->willReturn(json_encode($responseData));

        $this->httpClient
            ->method('request')
            ->willReturn($responseMock);

        $this->logger
            ->expects($this->once())
            ->method('error')
            ->with($this->stringContains('Response does not contain country key'));

        $this->expectException(ApiIpLocateException::class);

        $this->service->getLocation($ip);
    }
}