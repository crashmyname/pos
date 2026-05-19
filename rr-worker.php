<?php

require __DIR__ . '/vendor/autoload.php';

use Spiral\RoadRunner\Worker;
use Spiral\RoadRunner\Http\PSR7Worker;
use Nyholm\Psr7\Factory\Psr17Factory;

// bootstrap app
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Bpjs\Framework\Core\Kernel::class);

// PSR-7 setup
$worker = Worker::create();
$psr17Factory = new Psr17Factory();

$psr7 = new PSR7Worker(
    $worker,
    $psr17Factory,
    $psr17Factory,
    $psr17Factory
);

while ($request = $psr7->waitRequest()) {
    try {
        // convert PSR-7 ke Request kamu
        $bpjsRequest = \Bpjs\Framework\Core\Request::fromPsr($request);

        $response = $kernel->handle($bpjsRequest);

        $psr7->respond(
            $psr17Factory->createResponse($response->getStatusCode())
                ->withBody($psr17Factory->createStream($response->getContent()))
        );

        $kernel->terminate();
        $kernel->reset();

    } catch (\Throwable $e) {
        $psr7->getWorker()->error((string) $e);
    }
}