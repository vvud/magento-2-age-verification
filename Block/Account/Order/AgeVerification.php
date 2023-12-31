<?php
/**
 * Copyright © Open Techiz. All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 */

namespace Magentiz\AgeVerification\Block\Account\Order;

class AgeVerification extends \Magento\Framework\View\Element\Template
{
    protected $_template = 'account/order/age_verification.phtml';

    /**
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry;

    /**
     * @var \Magentiz\AgeVerification\Helper\Attachment
     */
    protected $attachmentHelper;

    /**
     * @var \Magentiz\AgeVerification\Helper\Data
     */
    protected $dataHelper;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magentiz\AgeVerification\Helper\Attachment $attachmentHelper
     * @param \Magentiz\AgeVerification\Helper\Data $dataHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magentiz\AgeVerification\Helper\Attachment $attachmentHelper,
        \Magentiz\AgeVerification\Helper\Data $dataHelper,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->coreRegistry = $registry;
        $this->attachmentHelper = $attachmentHelper;
        $this->dataHelper = $dataHelper;
    }

    /**
     * @return order
     */
    public function getOrder()
    {
        return $this->coreRegistry->registry('current_order');
    }

    /**
     * @return bool
     */
    public function isAgeVerificationEnabled()
    {
        return $this->dataHelper->isAgeVerificationEnabled();
    }

    /**
     * @return string
     */
    public function getAgeVerificationConfig()
    {
        $config = $this->dataHelper->getAgeVerficationConfig($this);

        return $config;
    }

    /**
     * @return date
     */
    public function getDob()
    {
        return $this->getOrder()->getDob();
    }

    /**
     * @return array
     */
    public function getOrderAttachments()
    {
        $orderId = $this->getOrder()->getId();

        return $this->attachmentHelper->getOrderAttachments($orderId);
    }
}
