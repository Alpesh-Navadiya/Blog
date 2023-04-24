<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace I98commerce\Prodfaqs\Model\ResourceModel\Faqs;

use \I98commerce\Prodfaqs\Model\ResourceModel\AbstractCollection;

/**
 *  collection
 */
class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'faq_id';

    /**
     * Load data for preview flag
     *
     * @var bool
     */
    protected $_previewFlag;

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(\I98commerce\Prodfaqs\Model\Faqs::class, \I98commerce\Prodfaqs\Model\ResourceModel\Faqs::class);
        $this->_map['fields']['faq_id'] = 'main_table.faq_id';
    }

    /**
     * Desc
     *
     * @return Collection
     */
    protected function _afterLoad()
    {
        $items = $this->getColumnValues('faq_id');
        if (count($items)) {
            $connection = $this->getConnection();
            $select = $connection->select()->from(['cps' => $this->getTable('i98commerce_faq_answers')])
                    ->where('cps.faq_id IN (?)', $items);
            $result = $connection->fetchall($select);
            if ($result) {
            //  $cms_idd = implode(',', $result);
                foreach ($this as $item) {
                    $item->setData('answers_dynamic', $result);
                }
            }
        }

        $this->_previewFlag = false;
        return parent::_afterLoad();
    }
    /**
     * Add filter by store
     *
     * @param int|array|\Magento\Store\Model\Store $store
     * @param bool $withAdmin
     * @return $this
     */
    public function addStoreFilter($store, $withAdmin = true)
    {
        if (!$this->getFlag('store_filter_added')) {
            $this->performAddStoreFilter($store, $withAdmin);
        }
        return $this;
    }

    /**
     * Desc
     *
     * @return $this
     */
    public function joinTopicTable()
    {
        $this->_select->joinLeft(
            ['topic_table' => $this->getTable('i98commerce_faqs_topic')],
            'main_table.faqs_topic_id = topic_table.faqs_topic_id',
            ['t_title' => 'title',
                't_identifier' => 'identifier',
                't_status' => 'status',
                't_show_on_main' => 'show_on_main',
                't_topic_order' => 'topic_order']
        );
        return $this;
    }

    /**
     * Desc
     *
     * @return $this
     */
    public function joinStoreTable()
    {
        $this->_select->joinLeft(
            ['store_table' => $this->getTable('i98commerce_faqs_topic_store')],
            'main_table.faqs_topic_id = store_table.faqs_topic_id',
            ['store_id' => 'store_id']
        );
        return $this;
    }

    /**
     * Desc
     *
     * @return $this
     */
    public function joinAnswerTable()
    {
        $this->_select->joinLeft(
            ['answer_table' => $this->getTable('i98commerce_faq_answers')],
            'main_table.faq_id = answer_table.faq_id',
            ['a_answer' => 'answer']
        );
        return $this;
    }

    /**
     * Desc
     *
     * @return $this
     */
    public function joinProductAttachmentTable()
    {
        $this->_select->joinLeft(
            ['fp_table' => $this->getTable('i98commerce_faqs_product')],
            'main_table.faq_id = fp_table.faq_id',
            ['product_id' => 'product_id']
        );
        return $this;
    }
}
