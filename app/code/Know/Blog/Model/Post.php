<?php

namespace Know\Blog\Model;

use \Magento\Framework\Model\AbstractModel;

class Post extends AbstractModel
{


    /**
     * Initialize resource model
     * @return void
     */
    public function _construct()
    {
        $this->_init('Know\Blog\Model\ResourceModel\Post');
    }


}

