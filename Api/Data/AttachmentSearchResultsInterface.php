<?php
/**
 * Copyright © Open Techiz. All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 */

namespace Magentiz\AgeVerification\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface AttachmentSearchResultsInterface extends SearchResultsInterface
{
    /**
     * @return \Magento\Framework\Api\ExtensibleDataInterface[]
     */
    public function getItems();

    /**
     * @param array $items
     * @return SearchResultsInterface
     */
    public function setItems(array $items);
}
