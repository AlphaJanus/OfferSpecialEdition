<?php
/**
 * Created by PhpStorm.
 * User: hosein
 * Date: 2019-05-10
 * Time: 11:22
 */

namespace Netzexpert\OfferSpecialEdition\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->getConnection()->addColumn(
            $setup->getTable('offer_quote'),
            'is_order',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
                'nullable' => true,
                'comment'  => 'Check if order is approved'
            ]
        );
        $setup->getConnection()->addColumn(
            $setup->getTable('quote'),
            'offer_id',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                'nullable' => true,
                'comment'  => 'entity_id of the table "offer_quote"'
            ]
        );

        $setup->endSetup();
    }
}
