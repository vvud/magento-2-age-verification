<?php
/**
 * Copyright © Open Techiz. All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 */

namespace Magentiz\AgeVerification\Setup;

use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 * @codeCoverageIgnore
 */
class UpgradeData implements UpgradeDataInterface
{

    /**
     * @var \Magento\Cms\Model\BlockFactory
     */
    protected $blockFactory;

    /**
     * @var \Magento\Cms\Model\BlockRepository
     */
    protected $blockRepository;

    /**
     * Construct
     *
     * @param \Magento\Cms\Model\BlockFactory $blockFactory
     * @param \Magento\Cms\Model\BlockRepository $blockRepository
     */
    public function __construct(
        \Magento\Cms\Model\BlockFactory $blockFactory,
        \Magento\Cms\Model\BlockRepository $blockRepository
    ) {
        $this->blockFactory = $blockFactory;
        $this->blockRepository = $blockRepository;
    }

    /**
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        if (version_compare($context->getVersion(), '1.0.0') < 0) {
            $confirmBlock = array(
                'title' => 'Age Verification',
                'identifier' => 'magentiz-age-verification',
                'stores' => array(0),
                'is_active' => 1,
                'content' => 
                    '<fieldset class="fieldset age-verification-fieldset">
                        <div class="popup-logo">
                            <img src="" alt="Logo" />
                        </div>
                        <p class="small">You must be older than 18 years old to purchase our products!</p>
                        <div class="field choice">
                            <input type="checkbox" id="agree_term_condition" />
                            <label for="agree_term_condition" class="label">I agree with the Terms and Conditions</label>
                            <div class="agree_term_condition_error mage-error">Please confirm to continue</div>
                        </div>
                    </fieldset>'
            );

            $this->blockFactory->create()->setData($confirmBlock)->save();
        }

        $setup->endSetup();
    }
}
