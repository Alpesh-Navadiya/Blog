<?php
/**
 * @category Msquare AjaxCustomPagination
 * @package Msquare_AjaxCustomPagination
 * @author Mahesh Makwana <maheshmakwana588@gmail.com>
 */

namespace Msquare\AjaxCustomPagination\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class CustomData extends AbstractDb
{
    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init('custom_data', 'id');
    }
}
