<?php
/**
 * Copyright © Open Techiz. All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 */

namespace Magentiz\AgeVerification\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

/**
 * @codeCoverageIgnore
 */
class UpgradeSchema implements UpgradeSchemaInterface
{

    /**
     * Upgrades DB schema for a module
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $quote      = 'quote';
        $orderTable = 'sales_order';

        if (version_compare($context->getVersion(), '1.0.0') < 0) {
            // Dob
            $setup->getConnection()->addColumn(
                $setup->getTable($quote),
                'dob',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DATE,
                    'nullable' => false,
                    'comment' => 'Date of Birth'
                ]
            );
            $setup->getConnection()->addColumn(
                $setup->getTable($orderTable),
                'dob',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DATE,
                    'nullable' => false,
                    'comment' => 'Date of Birth'
                ]
            );
        }

        if (version_compare($context->getVersion(), '1.0.1') < 0) {
            // Quote
            $setup->getConnection()->addColumn(
                $setup->getTable($quote),
                'av_type',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'nullable' => true,
                    'comment' => 'Age Verification Type'
                ]
            );
            $setup->getConnection()->addColumn(
                $setup->getTable($quote),
                'av_number',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'nullable' => true,
                    'comment' => 'Age Verification Number'
                ]
            );
            // Sales order
            $setup->getConnection()->addColumn(
                $setup->getTable($orderTable),
                'av_type',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'nullable' => true,
                    'comment' => 'Age Verification Type'
                ]
            );
            $setup->getConnection()->addColumn(
                $setup->getTable($orderTable),
                'av_number',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'nullable' => true,
                    'comment' => 'Age Verification Number'
                ]
            );
        }

        $setup->endSetup();
    }
}
