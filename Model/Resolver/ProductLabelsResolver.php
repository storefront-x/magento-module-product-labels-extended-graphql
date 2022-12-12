<?php
/**
 * Copyright © StorefrontX, Inc. All rights reserved.
 */

declare(strict_types=1);

namespace StorefrontX\ProductLabelsExtension\Model\Resolver;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\Product;
use Amasty\Label\Model\Labels;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Query\Resolver\ContextInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Amasty\Label\Model\LabelViewer;
use Psr\Log\LoggerInterface;
use Exception;

/**
 * Class ProductLabelsResolver
 * @package Magento\CatalogInventoryGraphQl\Model\Resolver
 */
class ProductLabelsResolver implements ResolverInterface
{

    /**
     * @var LabelViewer
     */
    private $labelViewer;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Class construct
     *
     * @param LabelViewer $labelViewer
     * @param LoggerInterface $logger
     */
    public function __construct(
        LabelViewer $labelViewer,
        LoggerInterface $logger
    ) {
        $this->labelViewer = $labelViewer;
        $this->logger = $logger;
    }

    /**
     * Fetches the data from persistence models and format it according to the GraphQL schema.
     *
     * @param Field $field
     * @param ContextInterface $context
     * @param ResolveInfo $info
     * @param array|null $value
     * @param array|null $args
     * @return array
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null): array {

        if (!array_key_exists('model', $value) || !$value['model'] instanceof ProductInterface) {
            throw new LocalizedException(__('"model" value should be specified'));
        }

        $return['items'] = [];

        /* @var $product ProductInterface|Product */
        $product = $value['model'];

        $labelMode = $args['mode'] ?? 'product';

        try {
            $labelsForProduct = $this->labelViewer->getAppliedLabels($product, $labelMode);

            $baseUrl = $context->getExtensionAttributes()->getStore()->getBaseUrl();

            foreach ($labelsForProduct as $label) {
                /** @var Labels $label */
                $data = $label->getData();
                $data['product_id'] = $label->getAppliedProductId();
                $data['size'] = $label->getValue('image_size');
                $data['txt'] = $label->getText();
                $data['image'] = str_replace($baseUrl, '', $label->getImageSrc());
                $data['position'] = $label->getCssClass();
                $data['style'] = $label->getStyle();

                $return['items'][] = $data;
            }
        } catch (Exception $e) {
            $this->logger->debug($e->getMessage(), ['context' => $e]);
        }

        return $return;
    }

}

