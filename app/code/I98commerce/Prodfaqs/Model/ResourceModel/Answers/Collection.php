<?php

namespace I98commerce\Prodfaqs\Model\ResourceModel\Answers;

use \I98commerce\Prodfaqs\Model\ResourceModel\AbstractCollection;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Desc
     *
     * @var string
     */
    protected $_idFieldName = 'answer_id';

    /**
     * Desc
     *
     * @var $_previewFlag
     */
    protected $_previewFlag;

    /**
     * Desc
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('I98commerce\Prodfaqs\Model\Answers', 'I98commerce\Prodfaqs\Model\ResourceModel\Answers');

        $this->_map['fields']['answer_id'] ='main_table.answer_id';
    }
}
