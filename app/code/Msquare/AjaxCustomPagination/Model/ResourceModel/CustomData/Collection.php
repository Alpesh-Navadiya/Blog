<?php
/**
 * @category Msquare AjaxCustomPagination
 * @package Msquare_AjaxCustomPagination
 * @author Mahesh Makwana <maheshmakwana588@gmail.com>
 */

namespace Msquare\AjaxCustomPagination\Model\ResourceModel\CustomData;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Msquare\AjaxCustomPagination\Model\CustomData as CustomDataModel;
use Msquare\AjaxCustomPagination\Model\ResourceModel\CustomData as CustomDataResourceModel;

class Collection extends AbstractCollection
{
    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init(
            CustomDataModel::class,
            CustomDataResourceModel::class
        );
    }
}
