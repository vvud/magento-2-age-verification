<?php

namespace Magentiz\AgeVerification\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Attachment extends AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('age_verification_attachment', 'attachment_id');
    }

    public function getOrderAttachments($orderId)
    {
        $connection = $this->getConnection();
        $select = $connection->select()->from($this->getMainTable())
            ->where('order_id = ?', $orderId);

        return $connection->fetchAll($select);
    }

    public function getAttachmentsByQuote($quoteId)
    {
        $connection = $this->getConnection();
        $select = $connection->select()->from($this->getMainTable())
            ->where('quote_id = ?', $quoteId);

        return $connection->fetchAll($select);
    }
}
