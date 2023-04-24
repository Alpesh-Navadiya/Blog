<?php

namespace Know\Grid\Model;

use \Magento\Framework\Model\AbstractModel;

class Post extends AbstractModel
{


    /**
     * Initialize resource model
     * @return void
     */
    public function _construct()
    {
        $this->_init('Know\Grid\Model\ResourceModel\Post');
    }


}

