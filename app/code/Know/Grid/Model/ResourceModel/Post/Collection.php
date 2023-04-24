<?php

namespace Know\Grid\Model\ResourceModel\Post;

use \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * Initialize resource collection
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('Know\Grid\Model\Post', 'Know\Grid\Model\ResourceModel\Post');
    }
}
