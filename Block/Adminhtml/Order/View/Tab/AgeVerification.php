<?php
/**
 * Copyright © Open Techiz. All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 */

namespace Magentiz\AgeVerification\Block\Adminhtml\Order\View\Tab;

use Magento\Sales\Block\Adminhtml\Order\AbstractOrder;
use Magento\Backend\Block\Widget\Tab\TabInterface;

class AgeVerification extends AbstractOrder implements TabInterface
{
    /**
     * @var \Magentiz\AgeVerification\Helper\Attachment
     */
    protected $attachmentHelper;

    /**
     * @var \Magentiz\AgeVerification\Helper\Attachment
     */
    protected $dataHelper;
   
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Sales\Helper\Admin $adminHelper
     * @param \Magentiz\AgeVerification\Helper\Attachment $attachmentHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Sales\Helper\Admin $adminHelper,
        \Magentiz\AgeVerification\Helper\Attachment $attachmentHelper,
        \Magentiz\AgeVerification\Helper\Data $dataHelper,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        array $data = []
    ) {
        parent::__construct($context, $registry, $adminHelper, $data);
        $this->attachmentHelper = $attachmentHelper;
        $this->dataHelper = $dataHelper;
        $this->scopeConfig = $scopeConfig;
    }

    public function getOrder()
    {
        return $this->_coreRegistry->registry('current_order');
    }

    public function getAgeVerficationConfig()
    {
        $config = $this->dataHelper->getAgeVerficationConfig($this);

        return $config;
    }

    public function getDob()
    {
        return $this->getOrder()->getDob();
    }

    public function getOrderAttachments()
    {
        $orderId = $this->getOrder()->getId();

        return $this->attachmentHelper->getOrderAttachments($orderId);
    }

    public function getUploadUrl()
    {
        return $this->getUrl(
            'ageverification/attachment/upload',
            ['order_id' => $this->getOrder()->getId()]
        );
    }

    public function getRemoveUrl()
    {
        return $this->getUrl(
            'ageverification/attachment/delete',
            ['order_id' => $this->getOrder()->getId()]
        );
    }

    public function getTabLabel()
    {
        return __($this->dataHelper->getTitle());
    }

    public function getTabTitle()
    {
        return __($this->dataHelper->getTitle());
    }

    public function canShowTab()
    {
        return $this->dataHelper->isAgeVerificationEnabled();
    }

    public function isHidden()
    {
        return false;
    }
}
