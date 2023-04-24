<?php

namespace Know\Grid\Model\ResourceModel;

use \Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Post extends AbstractDb
{


    /**
     * Initialize resource
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('know_grid', 'g_id');
    }


}

