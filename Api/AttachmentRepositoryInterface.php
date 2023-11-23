<?php
/**
 * Copyright © Open Techiz. All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 */

namespace Magentiz\AgeVerification\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface AttachmentRepositoryInterface
{

    /**
     * @param Data\AttachmentInterface $attachment
     * @return mixed
     */
    public function save(Data\AttachmentInterface $attachment);

    /**
     * @param $attachmentId
     * @return mixed
     */
    public function getById($attachmentId);

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return mixed
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * @param Data\AttachmentInterface $attachment
     * @return mixed
     */
    public function delete(Data\AttachmentInterface $attachment);

    /**
     * @param $attachmentId
     * @return mixed
     */
    public function deleteById($attachmentId);
}
