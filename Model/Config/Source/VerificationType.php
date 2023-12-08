<?php
/**
 * Copyright © Open Techiz. All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 */

namespace Magentiz\AgeVerification\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class VerificationType implements ArrayInterface
{
    const ALL = 0;
    const ATTACHMENT_UPLOAD = 1;
    const ID_CARD = 2;
    
    /**
     * @inheritdoc
     */
    public function toOptionArray()
    {
        return [
            [
                'label' => __('All'),
                'value' => self::ALL
            ],
            [
                'label' => __('Attachment Upload'),
                'value' => self::ATTACHMENT_UPLOAD
            ],
            [
                'label' => __('ID Card'),
                'value' => self::ID_CARD
            ]
        ];
    }
}
