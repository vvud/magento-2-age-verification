<?php
/**
 * Copyright © Open Techiz. All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 */

namespace Magentiz\AgeVerification\Model;

use Magentiz\AgeVerification\Api\Data\AttachmentInterface as AttachmentInt;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;

class Attachment extends AbstractModel implements AttachmentInt, IdentityInterface
{
    /**
     * XML configuration paths for "Allow file upload during checkout" property
     */
    const AGE_VERIFICATION_ADDITIONAL_INFORMATION = 'ageverification/checkout/additional_information';

    /**
     * XML configuration paths for "Allow file upload during checkout" property
     */
    const AGE_VERIFICATION_DISPLAY_POSITION = 'ageverification/checkout/display_position';

    /**
     * XML configuration paths for "File restrictions - limit" property
     */
    const AGE_VERIFICATION_ATTACHMENT_FILE_LIMIT = 'ageverification/checkout/count';
    /**
     * XML configuration paths for "File restrictions - size" property
     */
    const AGE_VERIFICATION_ATTACHMENT_FILE_SIZE = 'ageverification/checkout/size';

    /**
     * XML configuration paths for "File restrictions - Allowed extensions" property
     */
    const AGE_VERIFICATION_ATTACHMENT_FILE_EXT = 'ageverification/checkout/extension';
    /**
     * XML configuration paths for "Enabled orderattachment module" property
     */
    const AGE_VERIFICATION_ENABLE = 'ageverification/checkout/enabled';
    /**
     * XML configuration paths for "Allow file upload during checkout" property
     */
    const AGE_VERIFICATION_TITLE = 'ageverification/checkout/title';
    /**
     * XML configuration paths for "Allow file upload during checkout" property
     */
    const AGE_VERIFICATION_DEFAULT_TITLE = 'Age Verification';
    /**
     * cache tag
     */
    const CACHE_TAG = 'age_verification';

    /**
     * @var string
     */
    protected $_cacheTag = 'age_verification';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'age_verification';

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Magentiz\AgeVerification\Model\ResourceModel\Attachment');
    }

    public function getOrderAttachments($orderId)
    {
        return $this->_getResource()->getOrderAttachments($orderId);
    }

    public function getAttachmentsByQuote($quoteId)
    {
        return $this->_getResource()->getAttachmentsByQuote($quoteId);
    }

    /**
     * Return unique ID(s) for each object in system
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * Get attachment_id
     *
     * return int
     */
    public function getAttachmentId()
    {
        return $this->getData(self::ATTACHMENT_ID);
    }

    /**
     * Get quote_id
     *
     * return string
     */
    public function getQuoteId()
    {
        return $this->getData(self::QUOTE_ID);
    }

    /**
     * Get order_id
     *
     * return string
     */
    public function getOrderId()
    {
        return $this->getData(self::ORDER_ID);
    }

    /**
     * Get path
     *
     * return int
     */
    public function getPath()
    {
        return $this->getData(self::PATH);
    }

    /**
     * Get HASH
     *
     * return string
     */
    public function getHash()
    {
        return $this->getData(self::HASH);
    }

    /**
     * Get TYPE
     *
     * return string
     */
    public function getType()
    {
        return $this->getData(self::TYPE);
    }

    /**
     * Get Uploaded
     *
     * return string
     */
    public function getUploadedAt()
    {
        return $this->getData(self::UPLOADED_AT);
    }

    /**
     * Get Modified
     *
     * return string
     */
    public function getModifiedAt()
    {
        return $this->getData(self::MODIFIED_AT);
    }

    public function setAttachmentId($AttachmentId)
    {
        return $this->setData(self::ATTACHMENT_ID, $AttachmentId);
    }

    public function setQuoteId($QuoteId)
    {
        return $this->setData(self::QUOTE_ID, $QuoteId);
    }

    public function setOrderId($OrderId)
    {
        return $this->setData(self::ORDER_ID, $OrderId);
    }

    public function setPath($Path)
    {
        return $this->setData(self::PATH, $Path);
    }

    public function setHash($Hash)
    {
        return $this->setData(self::HASH, $Hash);
    }

    public function setType($Type)
    {
        return $this->setData(self::TYPE, $Type);
    }

    public function setUploadedAt($UploadedAt)
    {
        return $this->setData(self::UPLOADED_AT, $UploadedAt);
    }

    public function setModifiedAt($ModifiedAt)
    {
        return $this->setData(self::MODIFIED_AT, $ModifiedAt);
    }
}
