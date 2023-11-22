<?php
/**
 * Copyright © Open Techiz. All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 */

namespace Magentiz\AgeVerification\Helper;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Store\Model\ScopeInterface;
use Magentiz\AgeVerification\Model\Attachment;

class Upload
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Magento\MediaStorage\Model\File\UploaderFactory
     */
    protected $uploaderFactory;

    /**
     * @var \Magento\Framework\Filesystem
     */
    protected $fileSystem;

    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory
     * @param \Magento\Framework\Filesystem $fileSystem
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory,
        \Magento\Framework\Filesystem $fileSystem
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->uploaderFactory = $uploaderFactory;
        $this->fileSystem = $fileSystem;
    }

    /**
     * @param  array $uploadData
     * @return array
     */
    public function uploadFileAndGetInfo($uploadData)
    {
        $allowedExtensions = $this->scopeConfig->getValue(
            Attachment::AGE_VERIFICATION_ATTACHMENT_FILE_EXT,
            ScopeInterface::SCOPE_STORE
        );
        $varDirectoryPath = $this->fileSystem
            ->getDirectoryRead(DirectoryList::MEDIA)
            ->getAbsolutePath("ageverification");

        $result = $this->uploaderFactory
            ->create(['fileId' => $uploadData])
            ->setAllowedExtensions(explode(',', $allowedExtensions))
            ->setAllowRenameFiles(true)
            ->setFilesDispersion(true)
            ->save($varDirectoryPath);

        return $result;
    }
}
