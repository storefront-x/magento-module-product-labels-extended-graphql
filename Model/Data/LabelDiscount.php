<?php

declare(strict_types=1);

namespace StorefrontX\ProductLabelsExtension\Model\Data;

use Magento\SalesRule\Api\Data\RuleDiscountInterface;

class LabelDiscount
{
    /**
     * afterGetRuleLabel
     * @SuppressWarnings("unused")
     * @param RuleDiscountInterface $subject
     * @param string $result
     * @return string
     */
    public function afterGetRuleLabel($subject, string $result):string
    {
        $result = $result == "Discount"?" ":$result;
        return $result;
    }
}
