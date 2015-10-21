<?php
namespace Aws\ProductAdvertising;

use Aws\AwsClient;

class ProductAdvertisingClient extends AwsClient
{
    private $associateTag;

    public function __construct(array $args)
    {
        if (isset($args['AssociateTag'])) {
            $this->setAssociateTag($args['AssociateTag']);
        }

        parent::__construct($args);

        //$api = $this->getApi();
    }
    /**
     * {@inheritdoc}
     */
    public function getCommand($name, array $args = [])
    {
        $args = $args + [
            'Service' => 'AWSECommerceService',
            'Operation' => $name
        ];

        if (!isset($args['AssociateTag'])) {
            $args['AssociateTag'] = $this->getAssociateTag();
        }

        return parent::getCommand($name, $args);
    }

    public function setAssociateTag($tag)
    {
        $this->associateTag = $tag;

        return $this;
    }

    public function getAssociateTag()
    {
        return $this->associateTag;
    }
}
