<?php
/**
 * Copyright © Open Techiz. All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 */

namespace Magentiz\AgeVerification\Observer;

use Magento\Framework\Event\ObserverInterface;

class SaveAvObserver implements ObserverInterface
{
    const AV_DOB    = 'dob';
    const AV_TYPE   = 'av_type';
    const AV_NUMBER = 'av_number';

    /**
     * @var \Magentiz\AgeVerification\Helper\Data
     */
    protected $dataHelper;
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * SaveAvAttachment constructor.
     * @param \Magentiz\AgeVerification\Helper\Data $dataHelper
     */
    public function __construct(
        \Magentiz\AgeVerification\Helper\Data $dataHelper
    ) {
        $this->dataHelper = $dataHelper;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this|void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();
        $quote = $observer->getEvent()->getQuote();

        if ($this->dataHelper->isAgeVerificationEnabled()) {
            $order->setData(
                self::AV_DOB,
                $quote->getData(self::AV_DOB)
            );
        }

        if ($this->dataHelper->allowIdCard()) {
            $order->setData(
                self::AV_TYPE,
                $quote->getData(self::AV_TYPE)
            );
    
            $order->setData(
                self::AV_NUMBER,
                $quote->getData(self::AV_NUMBER)
            );
        }

        return $this;
    }
}
