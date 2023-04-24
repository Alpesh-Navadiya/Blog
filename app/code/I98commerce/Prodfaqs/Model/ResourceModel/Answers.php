<?php

namespace I98commerce\Prodfaqs\Model\ResourceModel;

class Answers extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Desc
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('i98commerce_faq_answers', 'answer_id');
    }
}
