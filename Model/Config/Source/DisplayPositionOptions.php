<?php
/**
 * Copyright © Open Techiz. All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 */

namespace Magentiz\AgeVerification\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class DisplayPositionOptions implements ArrayInterface
{
    const NOT_DISPLAY = '';
    const AFTER_SHIPPING_ADDRESS = 0;
    const AFTER_SHIPPING_METHOD = 1;
    const BEFORE_PAYMENT_METHOD = 2;
    const AFTER_PAYMENT_METHOD = 3;

    /**
     * @inheritdoc
     */
    public function toOptionArray()
    {
        return [
            [
                'label' => __('Do Not Display'),
                'value' => self::NOT_DISPLAY
            ],
            [
                'label' => __('After Shipping Address'),
                'value' => self::AFTER_SHIPPING_ADDRESS
            ],
            [
                'label' => __('After Shipping Methods'),
                'value' => self::AFTER_SHIPPING_METHOD
            ],
            [
                'label' => __('Before Payment Method'),
                'value' => self::BEFORE_PAYMENT_METHOD
            ],
            [
                'label' => __('After Payment Method'),
                'value' => self::AFTER_PAYMENT_METHOD
            ]
        ];
    }
}
