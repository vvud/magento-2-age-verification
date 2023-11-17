<?php
/**
 * Copyright © Open Techiz. All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 */

namespace Magentiz\AgeVerification\Helper;

use Magento\Store\Model\ScopeInterface;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    const AGE_VERIFICATION_ENABLED = 'ageverification/general/enabled';

    /**
     * @var \Magento\Cms\Model\Template\FilterProvider
     */
    protected $_filterProvider;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;
    /**
     * @var \Magento\Cms\Model\BlockFactory
     */
    protected $_blockFactory;

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Cms\Model\Template\FilterProvider $filterProvider
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Cms\Model\BlockFactory $blockFactory
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Cms\Model\Template\FilterProvider $filterProvider,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Cms\Model\BlockFactory $blockFactory
    ) {
        $this->_filterProvider = $filterProvider;
        $this->_storeManager = $storeManager;
        $this->_blockFactory = $blockFactory;
        parent::__construct($context);
    }

    /**
     * Check if enabled
     *
     * @return string|null
     */
    public function isEnabled()
    {
        return $this->scopeConfig->getValue(
            self::AGE_VERIFICATION_ENABLED, ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get Delay time
     *
     * @return string|null
     */
    public function getDelayTime()
    {
        $delay = $this->scopeConfig->getValue('ageverification/general/cookie_interval',
            ScopeInterface::SCOPE_STORE);

        $delayHours = $delay/1440;
        return $delayHours;
    }

    /**
     * Get Cookie Expire
     *
     * @return string|null
     */
    public function getCookieExpire()
    {
        $Cookie = $this->scopeConfig->getValue('ageverification/general/cookie_interval',
            ScopeInterface::SCOPE_STORE);

       $cookieHours = $Cookie/24;
       return $cookieHours;
    }

    /**
     * Get Block Id
     *
     * @return string|null
     */
    public function getBlockId()
    {
        $blockId =  $this->scopeConfig->getValue('ageverification/general/popup_block',
            ScopeInterface::SCOPE_STORE);

        return $blockId;
    }

    /**
     * Get Block Title
     *
     * @return string|null
     */
    public function getBlockTitle()
    {
        $blockId = $this->getBlockId();
        $blockTitle =  '';
        if ($blockId) {
            $storeId = $this->_storeManager->getStore()->getId();
            /** @var \Magento\Cms\Model\Block $block */
            $block = $this->_blockFactory->create();
            $block->setStoreId($storeId)->load($blockId);
            $blockTitle = $this->_filterProvider->getBlockFilter()->setStoreId($storeId)->filter($block->getTitle());
        }

        return $blockTitle;
    }


    /**
     * showPopUp
     *
     * @return string|null
     */
    public function showPopUp()
    {
        $allowedPages = $this->scopeConfig->getValue('ageverification/general/pages', ScopeInterface::SCOPE_STORE);
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $pageModel   = $objectManager->get('Magento\Cms\Model\Page');
        $currCmsPage = $pageModel->getIdentifier();
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $requestInterface = $objectManager->get('Magento\Framework\App\RequestInterface');
        $moduleName = $requestInterface->getModuleName();
        $pageArr = explode(',',$allowedPages);

        foreach ($pageArr as $value) {
            if ($moduleName == $value || $currCmsPage == $value) {
                return true;
            }
        }
    }

    /**
     * Get Height
     *
     * @return string|null
     */
    public function getHeight()
    {
        $height = $this->scopeConfig->getValue('ageverification/general/popup_height',
            ScopeInterface::SCOPE_STORE);
        if ($height == 'auto') {
            return $height;
        } else {
            return $height.'px';
        }
    }

    /**
     * Get Width
     *
     * @return string|null
     */
    public function getWidth()
    {
        $width = $this->scopeConfig->getValue('ageverification/general/popup_width',
            ScopeInterface::SCOPE_STORE);
        if ($width == 'auto') {
            return $width;
        } else {
            return $width.'px';
        }
    }

    /**
     * Get Agree
     *
     * @return string|null
     */
    public function getAgree()
    {
        $agree = $this->scopeConfig->getValue('ageverification/general/agree',
            ScopeInterface::SCOPE_STORE);

        return $agree;
    }

    /**
     * Get Disagree
     *
     * @return string|null
     */
    public function getDisagree()
    {
        $disagree = $this->scopeConfig->getValue('ageverification/general/disagree',
            ScopeInterface::SCOPE_STORE);

        return $disagree;
    }
}
