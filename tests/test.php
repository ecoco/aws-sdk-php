<?php
require '../vendor/autoload.php';

use Aws\ProductAdvertising\ProductAdvertisingClient;

if (is_dir('/tmp/aws-cache')) {
    exec('rm -rf /tmp/aws-cache');
}

$client = new ProductAdvertisingClient([
    'version' => 'latest',
    'region'  => 'de',
    'credentials' => array(
        'key'    => 'AKIAIZCSSBVNGDFPEP4A',
        'secret' => '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',
    )
]);

$response = $client->ItemSearch([
    'Service' => 'AWSECommerceService',
    'Operation' => 'ItemSearch',
    'SearchIndex' => 'Grocery',
    'BrowseNode' => '340846031',
    'AssociateTag' => 'ecocode-21',
    'ResponseGroup' => 'Large,OfferFull',
    'Sort' => 'salesrank'
]);


print '<pre>' . print_r($response, true) . '</pre>';

