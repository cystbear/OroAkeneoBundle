<?php

namespace Oro\Bundle\AkeneoBundle\Integration\Iterator;

use Akeneo\Pim\ApiClient\AkeneoPimClientInterface;
use Akeneo\Pim\ApiClient\Pagination\ResourceCursorInterface;
use Psr\Log\LoggerInterface;

class ConfigurableProductIterator extends AbstractIterator
{
    private $attributeMapping = [];

    public function __construct(
        ResourceCursorInterface $resourceCursor,
        AkeneoPimClientInterface $client,
        LoggerInterface $logger,
        array $attributeMapping = []
    ) {
        parent::__construct($resourceCursor, $client, $logger);

        $this->attributeMapping = $attributeMapping;
    }

    public function doCurrent()
    {
        $item = $this->resourceCursor->current();

        $sku = $item['identifier'] ?? $item['code'];

        if (array_key_exists('sku', $this->attributeMapping)) {
            if (!empty($item['values'][$this->attributeMapping['sku']][0]['data'])) {
                $sku = $item['values'][$this->attributeMapping['sku']][0]['data'];
            }
        }

        return ['sku' => (string)$sku, 'parent' => $item['parent'] ?? null, 'family_variant' => $item['family_variant'] ?? null];
    }
}
