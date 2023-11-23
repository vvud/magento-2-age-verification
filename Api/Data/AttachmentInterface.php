<?php
/**
 * Copyright © Open Techiz. All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 */

namespace Magentiz\AgeVerification\Api\Data;

interface AttachmentInterface
{
    CONST ATTACHMENT_ID = 'attachment_id';
    CONST QUOTE_ID      = 'quote_id';
    CONST ORDER_ID      = 'order_id';
    CONST PATH          = 'path';
    CONST HASH          = 'hash';
    CONST TYPE          = 'type';
    CONST UPLOADED_AT   = 'uploaded_at';
    CONST MODIFIED_AT   = 'modified_at';

    /**
     * Get attachment id
     * @return string|null
     */
    public function getAttachmentId();

    /**
     * Get quote id
     * @return string|null
     */
    public function getQuoteId();

    /**
     * Get order id
     * @return string|null
     */
    public function getOrderId();

    /**
     * Get path
     * @return string|null
     */
    public function getPath();

    /**
     * Get hash
     * @return string|null
     */
    public function getHash();

    /**
     * Get type
     * @return string|null
     */
    public function getType();

    /**
     * Get uploaded at
     * @return datetime|null
     */
    public function getUploadedAt();

    /**
     * Get modified at
     * @return datetime|null
     */
    public function getModifiedAt();

    /**
     * @param $AttachmentId
     * @return mixed
     */
    public function setAttachmentId($AttachmentId);

    /**
     * @param $QuoteId
     * @return mixed
     */
    public function setQuoteId($QuoteId);

    /**
     * @param $OrderId
     * @return mixed
     */
    public function setOrderId($OrderId);

    /**
     * @param $Path
     * @return mixed
     */
    public function setPath($Path);

    /**
     * @param $Hash
     * @return mixed
     */
    public function setHash($Hash);

    /**
     * @param $Type
     * @return mixed
     */
    public function setType($Type);

    /**
     * @param $UploadedAt
     * @return mixed
     */
    public function setUploadedAt($UploadedAt);

    /**
     * @param $ModifiedAt
     * @return mixed
     */
    public function setModifiedAt($ModifiedAt);
}
