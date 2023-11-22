<?php
/**
 * Copyright © Open Techiz. All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 */

namespace Magentiz\AgeVerification\Plugin\Checkout\Model;

class ShippingInformationManagement
{
    public $quoteRepository;

    public function __construct(
        \Magento\Quote\Model\QuoteRepository $quoteRepository
    ) {
        $this->quoteRepository = $quoteRepository;
    }

    public function beforeSaveAddressInformation(
        \Magento\Checkout\Model\ShippingInformationManagement $subject,
        $cartId,
        \Magento\Checkout\Api\Data\ShippingInformationInterface $addressInformation
    )
    {
        if(!$extensionAttributes = $addressInformation->getExtensionAttributes())
        {
            return;
        }
		
        $quote = $this->quoteRepository->getActive($cartId);
        $quote->setDob($extensionAttributes->getDob());
        // $quote->setIdFile($extensionAttributes->getIdFile());
    }
}
