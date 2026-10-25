# omnibus/colissimo

Colissimo (La Poste) for [omniscient/omnibus](https://github.com/glitchr-studio/omnibus). For now
the gateway prices parcels from configuration only: Colissimo's web services (labels, tracking,
relay points) are not wired yet, and the gateway calls nothing.

## Installation

```sh
composer require omnibus/colissimo
```

Requires PHP 8.2 and `omniscient/omnibus` (2.x), nothing else: no HTTP client, no framework. The
factory takes an HTTP client in its constructor like every carrier, and does not use it. In a
Symfony application with `Omnibus\Bridge\Symfony\OmnibusBundle`, the factory is registered as soon
as the package is installed.

## Credentials

None yet: no Colissimo service is called.

## Options

No option is required: `(new ColissimoGatewayFactory())->create()` builds a gateway, which then
supports nothing.

| Option | Type | Default | Required | |
|---|---|---|---|---|
| `rates` | list | - | no | prices from configuration, the only capability ([the rates option](https://github.com/glitchr-studio/omnibus/blob/2.x/docs/configuration.md#the-rates-option)) |

No shipment option is read.

## Capabilities

| Request | Supported |
|---|---|
| `Rating` | only with `rates` (configured prices) |
| `Shipping` | no |
| `Tracking` | no |
| `Pickup` | no |
| `GetSlip` | no |
| `Cancel` | no |
| `Paperless` | no |

## Behaviour

**Rate.** The core's `ConfiguredRatingAction`: each configured rate whose `countries` include the
recipient's (or that lists none), and whose `service` is the shipment's when one is set, is
priced by adding up each parcel's weight band; a parcel heavier than the last band removes that
rate. Rates come cheapest first.

Every other request is refused before anything happens.

## Example

```php
<?php

require __DIR__.'/vendor/autoload.php';

use Omnibus\Colissimo\ColissimoGatewayFactory;
use Omnibus\Model\Address;
use Omnibus\Model\Parcel;
use Omnibus\Model\Shipment;

$colissimo = (new ColissimoGatewayFactory())->create([
    'rates' => [
        ['service' => 'DOM', 'label' => 'Colissimo Domicile', 'days' => 2, 'countries' => ['FR'],
            'bands' => [250 => 549, 500 => 749, 1000 => 899, 2000 => 999, 5000 => 1499]],
        ['service' => 'BPR', 'label' => 'Colissimo Point Retrait', 'days' => 3, 'to_pickup_point' => true,
            'countries' => ['FR'], 'bands' => [1000 => 629, 5000 => 1099]],
    ],
]);

$shipment = new Shipment(
    new Address('Glitch Art', ['12 rue de la Paix'], '75002', 'Paris', 'FR'),
    new Address('Ada Lovelace', ['3 place Bellecour'], '69002', 'Lyon', 'FR'),
    [new Parcel(800)],
);

foreach ($colissimo->rate($shipment) as $rate) {
    printf("%s %-24s %5.2f %s, %d days\n", $rate->service, $rate->label, $rate->amount / 100, $rate->currency, $rate->days);
}
```

Output:

```text
BPR Colissimo Point Retrait   6.29 EUR, 3 days
DOM Colissimo Domicile        8.99 EUR, 2 days
```

In Symfony:

```yaml
omnibus:
    gateways:
        colissimo:
            factory: colissimo
            options:
                rates:
                    - { service: DOM, label: 'Colissimo Domicile', days: 2, countries: [FR],
                        bands: { 250: 549, 500: 749, 1000: 899, 2000: 999, 5000: 1499 } }
```

## Errors

No carrier is called, so there is no `CarrierException`. Every request but `Rating` (and `Rating`
without `rates`) raises `RequestNotSupportedException`.

## Verified, and not

Tested: `Tests/ColissimoGatewayTest.php` (the gateway's name) and
`Tests/ColissimoContractTest.php`, the family's conformance suite (configured rates replace any
rating, every other request refused before any call; the failure checks are skipped, there is no
carrier to fail). The example above was run in the core's harness. Colissimo's web services are
not implemented, so nothing was run against them.

## Responsibility

No Colissimo service is called by this version; when one is, La Poste's terms will apply to
whoever runs it. The prices configured are the shop's own.
