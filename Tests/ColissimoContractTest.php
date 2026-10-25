<?php

namespace Omnibus\Colissimo\Tests;

use Omnibus\Colissimo\ColissimoGatewayFactory;
use Omnibus\GatewayFactoryInterface;
use Omnibus\Testing\GatewayContractTests;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/** The conformance suite: configured rates only for now, every other request refused as unsupported. */
final class ColissimoContractTest extends TestCase
{
    use GatewayContractTests;

    protected function gatewayFactory(HttpClientInterface $http): GatewayFactoryInterface
    {
        return new ColissimoGatewayFactory($http);
    }

    protected function gatewayOptions(): array
    {
        return [];
    }

    protected function recordedAnswers(): array
    {
        return [];
    }
}
