<?php
/**
 * Copyright © Open Techiz. All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 */

namespace Magentiz\AgeVerification\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Framework\UrlInterface;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magentiz\AgeVerification\Model\Attachment;

class AgeVerificationConfigProvider implements ConfigProviderInterface
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlBuilder;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $checkoutSession;

    /**
     * @var \Magentiz\AgeVerification\Model\ResourceModel\Attachment\Collection
     */
    protected $attachmentCollection;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magentiz\AgeVerification\Helper\Data
     */
    protected $dataHelper;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param UrlInterface $urlBuilder
     * @param CheckoutSession $checkoutSession
     * @param \Magentiz\AgeVerification\Model\ResourceModel\Attachment\Collection $attachmentCollection
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        UrlInterface $urlBuilder,
        CheckoutSession $checkoutSession,
        \Magentiz\AgeVerification\Model\ResourceModel\Attachment\Collection $attachmentCollection,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magentiz\AgeVerification\Helper\Data $dataHelper
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->urlBuilder = $urlBuilder;
        $this->checkoutSession = $checkoutSession;
        $this->attachmentCollection = $attachmentCollection;
        $this->storeManager = $storeManager;
        $this->dataHelper = $dataHelper;
    }

    public function getConfig()
    {
        $uploadedAttachments = $this->getUploadedAttachments();
        $attachSize = $this->getOrderAttachmentFileSize();
        return [
            'ageVerificationEnabled'    => $this->isAgeVerificationEnabled(),
            'ageVerificationTitle'      => $this->dataHelper->getTitle(),
            'additionalInformation'     => $this->scopeConfig->getValue( Attachment::AGE_VERIFICATION_ADDITIONAL_INFORMATION, ScopeInterface::SCOPE_STORE ),
            'attachments'               => $uploadedAttachments['result'],
            'attachmentLimit'           => $this->getOrderAttachmentFileLimit(),
            'attachmentSize'            => $this->getOrderAttachmentFileSize(),
            'attachmentExt'             => $this->getOrderAttachmentFileExt(),
            'bodUpdate'                 => $this->getBodUpdateUrl(),
            'attachmentUpload'          => $this->getAttachmentUploadUrl(),
            'attachmentRemove'          => $this->getAttachmentRemoveUrl(),
            'removeItem'                => __('Remove Item'),
            'attachmentInvalidExt'      => __('Invalid File Type'),
            'attachmentInvalidSize'     => __('Size of the file is greather than allowed') . '(' . $attachSize . ' KB)',
            'attachmentInvalidLimit'    => __('You have reached the limit of files'),
            'totalCount'                => $uploadedAttachments['totalCount']
        ];
    }

    private function getUploadedAttachments()
    {
        if ($quoteId = $this->checkoutSession->getQuote()->getId()) {
            $attachments = $this->attachmentCollection
                ->addFieldToFilter('quote_id', $quoteId)
                ->addFieldToFilter('order_id', ['is' => new \Zend_Db_Expr('null')]);

            $defaultStoreId = $this->storeManager->getDefaultStoreView()->getStoreId();
            foreach ($attachments as $attachment) {
                $url = $this->storeManager->getStore()->getBaseUrl(
                    \Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'ageverification' . $attachment['path'];
                $attachment->setUrl($url);
                $preview = $this->storeManager->getStore($defaultStoreId)->getUrl(
                    'ageverification/attachment/preview',
                    [
                        'attachment' => $attachment['attachment_id'],
                        'hash' => $attachment['hash']
                    ]
                );
                $attachment->setPreview($preview);
                $attachment->setPath(basename($attachment->getPath()));
            }

            $result = $attachments->toArray();
            
            foreach ($result['items'] as $key => $value) {
                $result['items'][$key]['attachment_class'] = 'sp-attachment-id'.$value['attachment_id'];
                $result['items'][$key]['hash_class'] = 'sp-attachment-hash'.$value['attachment_id'] ;
            }
            
            $result = $result['items'];

            return array('result' => $result,'totalCount'=> $attachments->getSize());
        }

        return false;
    }

    private function isAgeVerificationEnabled()
    {
        $moduleEnabled = $this->scopeConfig->getValue(
            Attachment::AGE_VERIFICATION_ENABLE,
            ScopeInterface::SCOPE_STORE
        );

        return ($moduleEnabled);
    }

    private function getOrderAttachmentFileLimit()
    {
        return $this->scopeConfig->getValue(
            Attachment::AGE_VERIFICATION_ATTACHMENT_FILE_LIMIT,
            ScopeInterface::SCOPE_STORE
        );
    }

    private function getOrderAttachmentFileSize()
    {
        return $this->scopeConfig->getValue(
            Attachment::AGE_VERIFICATION_ATTACHMENT_FILE_SIZE,
            ScopeInterface::SCOPE_STORE
        );
    }

    private function getOrderAttachmentFileExt()
    {
        return $this->scopeConfig->getValue(
            Attachment::AGE_VERIFICATION_ATTACHMENT_FILE_EXT,
            ScopeInterface::SCOPE_STORE
        );
    }

    public function getBodUpdateUrl()
    {
        return $this->urlBuilder->getUrl('ageverification/quote/save');
    }

    public function getAttachmentUploadUrl()
    {
        return $this->urlBuilder->getUrl('ageverification/attachment/upload');
    }

    public function getAttachmentRemoveUrl()
    {
        return $this->urlBuilder->getUrl('ageverification/attachment/delete');
    }
}
