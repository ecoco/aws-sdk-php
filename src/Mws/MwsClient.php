<?php
namespace Aws\Mws;

use Aws\AwsClient;

class MwsClient extends AwsClient
{
    /**
     * {@inheritdoc}
     */
    public function getCommand($name, array $args = [])
    {
        return parent::getCommand($name, $args);
    }

}
