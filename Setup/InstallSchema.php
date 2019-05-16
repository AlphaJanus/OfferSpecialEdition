<?php

namespace Netzexpert\OfferSpecialEdition\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
    /**
     * Function install
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        /** create the table offer_quote */
        $table = $installer->getConnection()
            ->newTable($installer->getTable('offer_quote'))
            ->addColumn(
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true,
                    'nullable' => false,
                    'primary' => true
                ],
                'Entity Id'
            )
            ->addColumn(
                'quote_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [
                    'nullable' => true
                ],
                'Name'
            )
            ->addColumn(
                'date',
                \Magento\Framework\DB\Ddl\Table::TYPE_DATE,
                255,
                [
                    'nullable' =>true
                ],
                'Date and Time'
            )
            ->addColumn(
                'email',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '255',
                [
                    'nullable' => true
                ],
                'Email'
            )
            ->addColumn(
                'customer_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '255',
                [
                    'nullable' => true
                ],
                'Customer ID'
            )
            ->addColumn(
                'approved_by_user_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                '255',
                [
                    'nullable' => true
                ],
                'id of the user responsible for approving or declining of the order'
            )
            ->setComment('Offer Table');
        $installer->getConnection()->createTable($table);
        $table = $installer->getConnection()
            ->newTable($installer->getTable('offer_quote_item'))
            ->addColumn(
                'id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true,
                    'nullable' => false,
                    'primary' => true
                ],
                'ID'
            )
            ->addColumn(
                'offer_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                [
                    'nullable' => false
                ],
                'Offer ID'
            )
            ->addColumn(
                'quote_item_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '255',
                [
                    'nullable' => true
                ],
                'Quote item ID'
            )
            ->setComment('Offer Quote Item');
        $installer->getConnection()->createTable($table);


        $installer->getConnection()->addForeignKey(
            $installer->getFkName(
                'offer_quote_item',
                'offer_id',
                'offer_quote',
                'entity_id'
            ),
            $installer->getTable('offer_quote_item'),
            'offer_id',
            $installer->getTable('offer_quote'),
            'entity_id'
        );
        $installer->endSetup();
    }
}
