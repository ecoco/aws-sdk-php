<?php
namespace Aws\Signature;

use Aws\Credentials\CredentialsInterface;
use Aws\Exception\CouldNotCreateChecksumException;
use GuzzleHttp\Psr7;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;

class SignatureV2PAA implements SignatureInterface
{
    const ISO8601_BASIC = 'Y-m-d\TH:i:s\Z';

    /** @var string */
    private $service;

    /** @var string */
    private $region;

    /**
     * @param string $service Service name to use when signing
     * @param string $region Region name to use when signing
     */
    public function __construct($service, $region)
    {
        $this->service = $service;
        $this->region = $region;
    }

    public function signRequest(
        RequestInterface $request,
        CredentialsInterface $credentials
    )
    {
        $params = Psr7\parse_query($request->getBody()->__toString());

        unset($params['Action']);
        $params['AWSAccessKeyId'] = $credentials->getAccessKeyId();
        $params['Timestamp'] = gmdate(self::ISO8601_BASIC);

        ksort($params);

        $canonicalizedQueryString = $this->getCanonicalizedQuery($params);

        $stringToSign = implode(
            "\n",
            [
                $request->getMethod(),
                $request->getUri()->getHost(),
                $request->getUri()->getPath(),
                $canonicalizedQueryString
            ]
        );

        // calculate HMAC with SHA256 and base64-encoding
        $signature = base64_encode(hash_hmac('sha256', $stringToSign, $credentials->getSecretKey(), TRUE));

        // encode the signature for the request
        $signature = str_replace('%7E', '~', rawurlencode($signature));
        $signature = str_replace('+', '%20', $signature);
        $signature = str_replace('*', '%2A', $signature);


        $queryString = $canonicalizedQueryString . "&Signature=" . $signature;
        if ($request->getMethod() === 'POST') {
            return new Request(
                'POST',
                $request->getUri(),
                [
                    'Content-Length' => strlen($queryString),
                    'Content-Type' => 'application/x-www-form-urlencoded'
                ],
                $queryString
            );
        } else {

            return new Request(
                'GET',
                $request->getUri()->withQuery($queryString)
            );
        }

    }

    public function presign(
        RequestInterface $request,
        CredentialsInterface $credentials,
        $expires
    )
    {
        throw new \RuntimeException('Not Yet Supported');
    }

    private function getCanonicalizedQuery(array $query)
    {
        unset($query['X-Amz-Signature']);

        if (!$query) {
            return '';
        }

        $qs = '';
        ksort($query);
        foreach ($query as $k => $v) {
            if (!is_array($v)) {
                $qs .= rawurlencode($k) . '=' . rawurlencode($v) . '&';
            } else {
                sort($v);
                foreach ($v as $value) {
                    $qs .= rawurlencode($k) . '=' . rawurlencode($value) . '&';
                }
            }
        }

        return substr($qs, 0, -1);
    }
}
