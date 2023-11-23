<?php
/**
 * Copyright © Open Techiz. All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 */

namespace Magentiz\AgeVerification\Block\Checkout;

use Magento\Store\Model\ScopeInterface;
use Magentiz\AgeVerification\Model\Attachment as AgeVerification;


class LayoutProcessor implements \Magento\Checkout\Block\Checkout\LayoutProcessorInterface
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var CustomerSession
     */
    private $customerSession;

    /**
     * @var \Magentiz\AgeVerification\Helper\Data
     */
    protected $dataHelper;

    /**
     * LayoutProcessor constructor.
     *
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magentiz\AgeVerification\Helper\Data $dataHelper
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Customer\Model\Session $customerSession,
        \Magentiz\AgeVerification\Helper\Data $dataHelper

    ) {
        $this->scopeConfig = $scopeConfig;
        $this->customerSession = $customerSession;
        $this->dataHelper = $dataHelper;
    }

    /**
     * Process js Layout of block
     *
     * @param array $jsLayout
     *
     * @return array
     * @throws Exception
     */
    public function process($jsLayout)
    {
        if ($this->dataHelper->isAgeVerificationEnabled()) {
            switch ($this->scopeConfig->getValue(AgeVerification::AGE_VERIFICATION_DISPLAY_POSITION, ScopeInterface::SCOPE_STORE)){
                case 'after-shipping-address':
                    $this->addToAfterShippingAddress($jsLayout);
                    break;
                case 'after-shipping-methods':
                    $this->addToAfterShippingMethods($jsLayout);
                    break;
                case 'after-payment-methods':
                    $this->addToAfterPaymentMethods($jsLayout);
                        break;
            }

            $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']
                ['payment']['children']['additional-payment-validators']['children']['av-validator'] =
                [
                    'component' => 'Magentiz_AgeVerification/js/view/payment/validator'
                ];
        }

        return $jsLayout;
    }

    /**
     * @param $jsLayout
     * @return $jsLayout
     */
    private function addValidator(&$jsLayout)
    {
        $validator = &$jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']
            ['children']['shippingAddress']['children']['before-form']['children'];

        $validator['validate_av'] =
        [
            'component' => 'Magento_Ui/js/form/element/abstract',
            'config' => [
                'customScope' => 'shippingAddress',
                'template' => 'ui/form/field',
                'options' => [],
                'id' => 'validate_av'
            ],
            'dataScope' => 'shippingAddress.validate_av',
            'label' => __('Validate Age Verification'),
            'value' => '0',
            'provider' => 'checkoutProvider',
            'visible' => true,
            'validation' => [
                'required-entry' => true,
                'validate-av' => true
            ],
            'sortOrder' => 200,
            'id' => 'validate_av'
        ];        

        return $jsLayout;
    }

    /**
     * @param $jsLayout
     * @return $jsLayout
     */
    protected function addToAfterShippingAddress(&$jsLayout)
    {
        $shippingAddressId = $this->customerSession->getCustomer()->getDefaultShipping();

        if ($shippingAddressId &&
            isset($jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']
                ['children']['shippingAddress']['children']['before-form']['children'])
        ) {
            $fields = &$jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']
                    ['children']['shippingAddress']['children']['before-form']['children'];
            $this->addValidator($jsLayout);
        }

        if (!$shippingAddressId &&
            isset($jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']
                ['children']['shippingAddress']['children']['shipping-address-fieldset']['children'])
        ){
            $fields = &$jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']
                    ['children']['shippingAddress']['children']['shipping-address-fieldset']['children'];
            $this->addValidator($jsLayout);
        }

        $fields['age-verification-after-shipment-address'] =
        [
            'component' => 'Magentiz_AgeVerification/js/view/shipment/shipment-age-verification'
        ];

        return $jsLayout;
    }

    /**
     * @param $jsLayout
     * @return $jsLayout
     */
    protected function addToAfterShippingMethods(&$jsLayout)
    {
        if (isset($jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']
                ['children']['shippingAddress']['children'])
        ) {
            $fields = &$jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']
                        ['children']['shippingAddress']['children'];

            $fields['age-verification-after-shipment-methods'] =
            [
                'component'     => 'uiComponent',
                'displayArea'   => 'shippingAdditional',
                'children'      =>
                [
                    'age-verification'=> ['component' => 'Magentiz_AgeVerification/js/view/shipment/shipment-age-verification']
                ]
            ];

            $this->addValidator($jsLayout);
        }

        return $jsLayout;
    }

    /**
     * @param $jsLayout
     * @return $jsLayout
     */
    protected function addToAfterPaymentMethods(&$jsLayout)
    {
        if (isset($jsLayout['components']['checkout']['children']['steps']['children']['billing-step']
                ['children']['payment']['children']['afterMethods']['children'])
        ) {
            $fields = &$jsLayout['components']['checkout']['children']['steps']['children']['billing-step']
                    ['children']['payment']['children']['afterMethods']['children'];

            $fields['age-verification-after-payment-methods'] =
            [
                'component' => 'Magentiz_AgeVerification/js/view/payment/payment-age-verification'
            ];
        }

        return $jsLayout;
    }
}
