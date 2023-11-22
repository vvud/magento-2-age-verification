<?php
/**
 * Copyright © Open Techiz. All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 */

namespace Magentiz\AgeVerification\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class DisplayPositionOptions implements ArrayInterface
{
    /**
     * @inheritdoc
     */
    public function toOptionArray()
    {
        return [
            [
                'label' => __('Do Not Display'),
                'value' => ''
            ],
            [
                'label' => __('After Shipping Address'),
                'value' => 'after-shipping-address'
            ],
            [
                'label' => __('After Shipping Methods'),
                'value' => 'after-shipping-methods'
            ],
            [
                'label' => __('After Payment Method'),
                'value' => 'after-payment-methods'
            ]
        ];
    }
}
