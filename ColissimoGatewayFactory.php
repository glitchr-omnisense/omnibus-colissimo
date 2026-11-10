<?php

namespace Omnibus\Colissimo;

use Omnibus\Config;
use Omnibus\GatewayFactory;

/**
 * Colissimo (La Poste). Its web services (labels, tracking, relay points)
 * are not wired yet: for now the gateway rates from configuration only
 * ("rates" option, Omnibus\Action\ConfiguredRatingAction).
 */
final class ColissimoGatewayFactory extends GatewayFactory
{
    protected function populateConfig(Config $config): void
    {
        $config->defaults([
            'omnibus.factory_name' => 'colissimo',
            'omnibus.factory_title' => 'Colissimo',
            'omnibus.policy' => __DIR__.'/policy.json',
        ]);
    }
}
