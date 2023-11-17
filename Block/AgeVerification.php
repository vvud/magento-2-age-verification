<?php
/**
 * Copyright © Open Techiz. All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 */

namespace Magentiz\AgeVerification\Block;

class AgeVerification extends \Magento\Framework\View\Element\Template
{
    
    /**
     * @var \Magentiz\AgeVerification\Helper\Data
     */
    protected $helper;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magentiz\AgeVerification\Helper\Data $helper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magentiz\AgeVerification\Helper\Data $helper,
        array $data = []
    ) {
        $this->helper = $helper;
        parent::__construct($context, $data);
    }

    /**
     * Check if enabled
     *
     * @return string|null
     */
    public function isEnabled()
    {
        return $this->helper->isEnabled();
    }

    /**
     * Get Delay time
     *
     * @return string|null
     */
    public function getDelayTime()
    {
        return $this->helper->getDelayTime();
    }

    /**
     * Get Cookie Expire
     *
     * @return string|null
     */
    public function getCookieExpire()
    {
        return $this->helper->getCookieExpire();
    }

    /**
     * Get Block Id
     *
     * @return string|null
     */
    public function getBlockId()
    {
        return $this->helper->getBlockId();
    }

    /**
     * Get Block Title
     *
     * @return string|null
     */
    public function getBlockTitle()
    {
        return $this->helper->getBlockTitle();
    }

    /**
     * showPopUp
     *
     * @return string|null
     */
    public function showPopUp()
    {
        return $this->helper->showPopUp();
    }

    /**
     * Get Height
     *
     * @return string|null
     */
    public function getHeight()
    {
        return $this->helper->getHeight();
    }

    /**
     * Get Width
     *
     * @return string|null
     */
    public function getWidth()
    {
        return $this->helper->getWidth();
    }

    /**
     * Get Agree
     *
     * @return string|null
     */
    public function getAgree()
    {
        return $this->helper->getAgree();
    }

    /**
     * Get Disagree
     *
     * @return string|null
     */
    public function getDisagree()
    {
        return $this->helper->getDisagree();
    }
}
