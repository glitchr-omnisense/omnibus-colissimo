# omnibus/colissimo

Colissimo (La Poste) for [omnisense/omnibus](https://github.com/glitchr-omnisense/omnibus).

For now the gateway prices parcels from configuration only; labels, tracking and relay points (Colissimo's web services) are still to be wired.

## Install and a first call

```sh
composer require omnibus/colissimo
```

No account and no network: this package calls nothing.

```php
<?php

require __DIR__.'/vendor/autoload.php';

use Omnibus\Colissimo\ColissimoGatewayFactory;
use Omnibus\Model\{Address, Parcel, Shipment};

// Prices from configuration (grams => cents): no account, no network.
$gateway = (new ColissimoGatewayFactory())->create([
    'rates' => [['service' => 'DOM', 'label' => 'Colissimo, home delivery', 'bands' => [250 => 549, 500 => 749, 1000 => 899]]],
]);
$shipment = new Shipment(
    new Address('The shop', ['1 rue de Rivoli'], '75001', 'Paris', 'FR'),
    new Address('Camille Durand', ['12 rue des Juifs'], '67000', 'Strasbourg', 'FR'),
    [new Parcel(500)],
);
$rate = $gateway->rate($shipment)[0];
echo $rate->label, ': ', $rate->amount, ' ', $rate->currency, "\n";   // Colissimo, home delivery: 749 EUR
```

Colissimo's web services (labels, tracking, relay points) are not wired yet: only configured prices.

## Configuration

```php
$gateway = (new ColissimoGatewayFactory())->create($options);   // the options below
```

No framework needed, and no HTTP client: the package requires `omnisense/omnibus` alone and calls
nothing. In a Symfony application, the same through the bundle's configuration:

```yaml
omnibus:
    gateways:
        colissimo:
            factory: colissimo
            options:
                rates: [...]   # Omnibus\Action\ConfiguredRatingAction
```

## License

MIT since 2026-10-10; earlier versions remain published under LGPL-3.0-or-later.
