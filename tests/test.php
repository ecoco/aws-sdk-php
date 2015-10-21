<?php
if (is_dir('/tmp/aws-cache')) {
    exec('rm -rf /tmp/aws-cache');
}

require '../vendor/autoload.php';

use Aws\ProductAdvertising\ProductAdvertisingClient;
use Aws\Mws\MwsClient;


$client = new MwsClient([
    'version' => 'latest',
    'region' => 'de',
    'credentials' => array(
        'key' => 'AKIAJMANQVTARK46ITKA',
        'secret' => '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',
    )
]);
$i = 0;
try {


while ($i < 100) {
    $i++;
    $result = $client->ListMatchingProducts(
        array(
            'Query' => 'manuka',
            'SellerId' => 'A38ICKGISRQYVD',
            'MarketplaceId' => 'A1PA6795UKMFR9'
        )
    );
    var_dump($result);
}
} catch (\Exception $e) {
    var_dump($e);
}
die();

$client = new MwsClient([
    'version' => 'latest',
    'region' => 'de',
    'AssociateTag' => 'ecocode-21',
    'credentials' => array(
        'key' => 'AKIAJMANQVTARK46ITKA',
        'secret' => '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',
    )
]);

$client = new ProductAdvertisingClient([
    'version' => 'latest',
    'region' => 'de',
    'AssociateTag' => 'ecocode-21'
]);

/*$response = $client->ItemSearch([
    'SearchIndex' => 'Grocery',
    'BrowseNode' => '340846031',
    'AssociateTag' => 'ecocode-21',
    'ResponseGroup' => 'Large,OfferFull',
    'Sort' => 'salesrank'
]);*/

$iterator = $client->getIterator(
    'ItemSearch',
    array(
        'SearchIndex' => 'Grocery',
        'ItemPage' => '1',
        //'BrowseNode' => '340846031',
        'BrowseNode' => '364781031',
        'AssociateTag' => 'ecocode-21',
        'ResponseGroup' => 'Large,OfferFull',
        'Sort' => 'salesrank'));


/*$response = $client->ItemLookup([
    'ItemId' => 'B000ZM34MO'
]);*/
foreach ($iterator as $object) {
    $offers = isset($object['OfferList']['Offers']) ? $object['OfferList']['Offers'] : false;
    $price = null;
    if ($offers) {
        $offer = reset($offers);
        $price = $offer['OfferListing']['Price']['Amount'] / 100;
    }
    $data = [
        $object['ASIN'],
        $object['ItemAttributes']['Title'],
        isset($object['SalesRank']) ? $object['SalesRank'] : 'n/a',
        $price,
        $object['OfferSummary']['TotalNew']
    ];
    echo "\"" . implode('";"', $data) . "\"\n";
}


