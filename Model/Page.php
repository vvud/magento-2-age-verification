<?php
/**
 * Copyright © Open Techiz. All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 */

namespace Magentiz\AgeVerification\Model;

class Page extends \Magento\Cms\Model\Config\Source\Page
{

    protected $options;

    /**
     * @return array
     */
    public function toOptionArray()
    {
        if (!$this->options) {
            $this->options = $this->collectionFactory->create()->toOptionIdArray();
        }
        $this->options[] = array('value' => 'catalog', 'label' => 'Catalog');
        $this->options[] = array('value' => 'checkout', 'label' => 'Checkout');
        return $this->options;
    }
}
