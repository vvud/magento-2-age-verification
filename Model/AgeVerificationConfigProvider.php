<?php
/**
 * Copyright © Open Techiz. All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 */

namespace Magentiz\AgeVerification\Model;

use Magento\Store\Model\ScopeInterface;
use Magento\Checkout\Model\ConfigProviderInterface;
use Magentiz\AgeVerification\Model\Attachment;
use Magentiz\AgeVerification\Helper\Data as AgeVerification;

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
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Framework\UrlInterface $urlBuilder
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magentiz\AgeVerification\Model\ResourceModel\Attachment\Collection $attachmentCollection
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magentiz\AgeVerification\Helper\Data $dataHelper
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\UrlInterface $urlBuilder,
        \Magento\Checkout\Model\Session $checkoutSession,
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

    /**
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getConfig()
    {
        $uploadedAttachments = $this->getUploadedAttachments();
        $attachSize = $this->getAttachmentFileSize();
        return [
            'ageVerificationEnabled'    => $this->dataHelper->isAgeVerificationEnabled(),
            'ageVerificationTitle'      => $this->dataHelper->getTitle(),
            'additionalInformation'     => $this->scopeConfig->getValue(AgeVerification::AGE_VERIFICATION_ADDITIONAL_INFORMATION, ScopeInterface::SCOPE_STORE),
            'verificationType'          => $this->dataHelper->getVerificationType(),
            'saveDob'                   => $this->getDobSaveUrl(),
            'saveIdCard'                => $this->getIdCardSaveUrl(),
            'attachments'               => $uploadedAttachments['result'],
            'attachmentLimit'           => $this->getAttachmentFileLimit(),
            'attachmentSize'            => $attachSize,
            'attachmentExt'             => $this->getAttachmentFileExt(),
            'attachmentUpload'          => $this->getAttachmentUploadUrl(),
            'attachmentRemove'          => $this->getAttachmentRemoveUrl(),
            'removeItem'                => __('Remove Item'),
            'attachmentInvalidExt'      => __('Invalid File Type'),
            'attachmentInvalidSize'     => __('Size of the file is greather than allowed') . '(' . $attachSize . ' KB)',
            'attachmentInvalidLimit'    => __('You have reached the limit of files'),
            'totalCount'                => $uploadedAttachments['totalCount']
        ];
    }

    /**
     * @return array|bool
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
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

    /**
     * @return string
     */
    private function getAttachmentFileLimit()
    {
        return $this->scopeConfig->getValue(
            Attachment::AGE_VERIFICATION_ATTACHMENT_FILE_LIMIT,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return string
     */
    private function getAttachmentFileSize()
    {
        return $this->scopeConfig->getValue(
            Attachment::AGE_VERIFICATION_ATTACHMENT_FILE_SIZE,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return string
     */
    private function getAttachmentFileExt()
    {
        return $this->scopeConfig->getValue(
            Attachment::AGE_VERIFICATION_ATTACHMENT_FILE_EXT,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return string
     */
    public function getDobSaveUrl()
    {
        return $this->urlBuilder->getUrl('ageverification/quote/saveDob');
    }

    /**
     * @return string
     */
    public function getIdCardSaveUrl()
    {
        return $this->urlBuilder->getUrl('ageverification/quote/saveIdCard');
    }

    /**
     * @return string
     */
    public function getAttachmentUploadUrl()
    {
        return $this->urlBuilder->getUrl('ageverification/attachment/upload');
    }

    /**
     * @return string
     */
    public function getAttachmentRemoveUrl()
    {
        return $this->urlBuilder->getUrl('ageverification/attachment/delete');
    }
}
