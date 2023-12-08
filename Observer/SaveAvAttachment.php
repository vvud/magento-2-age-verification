<?php
/**
 * Copyright © Open Techiz. All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 */

namespace Magentiz\AgeVerification\Observer;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;

class SaveAvAttachment implements ObserverInterface
{

    /**
     * @var \Magentiz\AgeVerification\Model\ResourceModel\Attachment\Collection
     */
    protected $attachmentCollection;
    /**
     * @var \Magentiz\AgeVerification\Helper\Data
     */
    protected $dataHelper;

    /**
     * SaveAvAttachment constructor.
     * @param \Magentiz\AgeVerification\Model\ResourceModel\Attachment\Collection $attachmentCollection
     * @param \Magentiz\AgeVerification\Helper\Data $dataHelper
     */
    public function __construct(
        \Magentiz\AgeVerification\Model\ResourceModel\Attachment\Collection $attachmentCollection,
        \Magentiz\AgeVerification\Helper\Data $dataHelper
    ) {
        $this->attachmentCollection = $attachmentCollection;
        $this->dataHelper = $dataHelper;
    }

    /**
     * @param EventObserver $observer
     * @return $this|void
     */
    public function execute(EventObserver $observer)
    {
        if ($this->dataHelper->allowAttachment()) {
            $order = $observer->getEvent()->getOrder();
            if (!$order) {
                return $this;
            }
    
            $attachments = $this->attachmentCollection
                ->addFieldToFilter('quote_id', $order->getQuoteId())
                ->addFieldToFilter('order_id', ['is' => new \Zend_Db_Expr('null')]);
    
            foreach ($attachments as $attachment) {
                try {
                    $attachment->setOrderId($order->getId())->save();
                } catch (\Exception $e) {
                    continue;
                }
            }
        }

        return $this;
    }
}
