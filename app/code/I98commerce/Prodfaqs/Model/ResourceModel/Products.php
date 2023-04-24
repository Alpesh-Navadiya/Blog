<?php

namespace I98commerce\Prodfaqs\Model\ResourceModel;

class Products extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Desc
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('i98commerce_faqs_product', 'product_id');
    }
}
