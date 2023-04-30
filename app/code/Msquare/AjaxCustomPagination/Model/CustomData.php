<?php
/**
 * @category Msquare AjaxCustomPagination
 * @package Msquare_AjaxCustomPagination
 * @author Mahesh Makwana <maheshmakwana588@gmail.com>
 */

namespace Msquare\AjaxCustomPagination\Model;

use Magento\Framework\Model\AbstractModel;
use Msquare\AjaxCustomPagination\Model\ResourceModel\CustomData as CustomDataResourceModel;

class CustomData extends AbstractModel
{
    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init(CustomDataResourceModel::class);
    }
}
