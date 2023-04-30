<?php
/**
 * @category VendoreName ModuleName
 * @package VendoreName_ModuleName
 * @author Mahesh Makwana <maheshmakwana588@gmail.com>
 */

namespace Know\Blog\Block;

use Magento\Framework\View\Element\Template;

class Pager extends Template
{
    /**
     * @var \Know\Blog\Model\CustomData
     */
    protected $customFactory;

    /**
     * @var \Know\Blog\Model\ResourceModel\CustomData\CollectionFactory
     */
    protected $customdataCollection;

    /**
     * @param Template\Context $context
     * @param \Know\Blog\Model\Post $customFactory
     * @param \Know\Blog\Model\ResourceModel\Post\CollectionFactory $customdataCollection
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        \Know\Blog\Model\Post $customFactory,
        \Know\Blog\Model\ResourceModel\Post\CollectionFactory $customdataCollection,
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
                \Know\Blog\Block\CustomPager::class,
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
     * Get pager HTML
     *
     * @return \Know\Blog\Block\CustomPager
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    /**
     * Get custom data collection
     *
     * @return \Know\Blog\Model\CustomData
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

    public function getDeleteUrl()
    {
        return $this->getUrl('blog/index/delete');
    }

    public function getViewUrl()
    {

        return $this->getUrl('blog/index/view');
    }
}
