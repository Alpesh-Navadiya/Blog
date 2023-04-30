<?php
/**
 * @category Msquare AjaxCustomPagination
 * @package Msquare_AjaxCustomPagination
 * @author Mahesh Makwana <maheshmakwana588@gmail.com>
 */

namespace Msquare\AjaxCustomPagination\Block;

use Magento\Framework\View\Element\Template;

class Pager extends Template
{
    /**
     * @var \Msquare\AjaxCustomPagination\Model\CustomData
     */
    protected $customFactory;

    /**
     * @var \Msquare\AjaxCustomPagination\Model\ResourceModel\CustomData\CollectionFactory
     */
    protected $customdataCollection;

    /**
     * @param Template\Context $context
     * @param \Msquare\AjaxCustomPagination\Model\CustomData $customFactory
     * @param \Msquare\AjaxCustomPagination\Model\ResourceModel\CustomData\CollectionFactory $customdataCollection
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        \Msquare\AjaxCustomPagination\Model\CustomData $customFactory,
        \Msquare\AjaxCustomPagination\Model\ResourceModel\CustomData\CollectionFactory $customdataCollection,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->customFactory = $customFactory;
        $this->customdataCollection = $customdataCollection;
    }

    /**
     * @inheritdoc
     */
    protected function _prepareLayout()
    {
        $this->pageConfig->getTitle()->set(__('My Custom Pagination'));
        parent::_prepareLayout();
        $page_size = $this->getPagerCount();
        $page_data = $this->getCustomData();
        if ($this->getCustomData()) {
            $pager = $this->getLayout()->createBlock(
                \Msquare\AjaxCustomPagination\Block\CustomPager::class,
                'custom.pager'
            )
                ->setAvailableLimit($page_size)
                ->setShowPerPage(true)
                ->setCollection($page_data);
            $this->setChild('pager', $pager);
            $this->getCustomData()->load();
        }
        return $this;
    }

    /**
     * Get pager html
     *
     * @return \Msquare\AjaxCustomPagination\Block\CustomPager
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    /**
     * Get custom data collection
     *
     * @return \Msquare\AjaxCustomPagination\Model\CustomData
     */
    public function getCustomData()
    {
        $page = ($this->getRequest()->getParam('p')) ? $this->getRequest()->getParam('p') : 1;
        $pageSize = ($this->getRequest()->getParam('limit')) ? $this->getRequest()->getParam('limit') : 5;
        $collection = $this->customFactory->getCollection();
        $collection->setPageSize($pageSize);
        $collection->setCurPage($page);
        return $collection;
    }

    /**
     * Get custom data collection
     *
     * @return array
     */
    public function getPagerCount()
    {
        $minimum_show = 5; // set minimum records
        $page_array = [];
        $list_data = $this->customdataCollection->create();
        $list_count = ceil(count($list_data->getData()));
        $show_count = $minimum_show + 1;
        if (count($list_data->getData()) >= $show_count) {
            $list_count = $list_count / $minimum_show;
            $page_nu = $total = $minimum_show;
            $page_array[$minimum_show] = $minimum_show;
            for ($x = 0; $x <= $list_count; $x++) {
                $total = $total + $page_nu;
                $page_array[$total] = $total;
            }
        } else {
            $page_array[$minimum_show] = $minimum_show;
            $minimum_show = $minimum_show + $minimum_show;
            $page_array[$minimum_show] = $minimum_show;
        }
        return $page_array;
    }
}
