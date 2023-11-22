<?php
/**
 * Copyright © Open Techiz. All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 */

namespace Magentiz\AgeVerification\Observer;

use Magento\Framework\Event\ObserverInterface;

class SaveOrderObserver implements ObserverInterface
{
    const DOB = 'dob';

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();
        $quote = $observer->getEvent()->getQuote();

        $order->setData(
            self::DOB,
            $quote->getData(self::DOB)
        );

        return $this;
    }
}