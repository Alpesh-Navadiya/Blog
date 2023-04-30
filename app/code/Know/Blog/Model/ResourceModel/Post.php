<?php

namespace Know\Blog\Model\ResourceModel;

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
        $this->_init('magemastery_blog_post', 'post_id');
    }


}

