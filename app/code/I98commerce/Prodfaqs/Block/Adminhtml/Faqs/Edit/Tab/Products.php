<?php
/**
 * @author I98commerce Team
 * @copyright Copyright (c) 2020 I98commerce (https://www.i98commerce.com)
 * @package I98commerce_Prodfaqs
 */

namespace I98commerce\Prodfaqs\Block\Adminhtml\Faqs\Edit\Tab;

use I98commerce\Prodfaqs\Model\Faqs;
use I98commerce\Prodfaqs\Model\FaqsFactory;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Helper\Data;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\Registry;

class Products extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $productCollectionFactory;
    /**
     * Contact factory
     *
     * @var ContactFactory
     */
    protected $faqsFactory;
    /**
     * @var  \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @param Context $context
     * @param Data $backendHelper
     * @param Registry $registry
     * @param FaqsFactory $faqsFactory
     * @param CollectionFactory $productCollectionFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magento\Framework\Registry $registry,
        FaqsFactory $faqsFactory,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        array $data = []
    ) {
        $this->faqsFactory = $faqsFactory;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->registry = $registry;
        parent::__construct($context, $backendHelper, $data);
    }
    /**
     * _construct
     *
     * @return void
     */
    protected function _construct()
    {

        parent::_construct();
        $this->setId('productsGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
        if ($this->getRequest()->getParam('faq_id')) {
            $this->setDefaultFilter(['in_product' => 1]);
        }
    }
    /**
     * Short desc
     *
     * @param int|string|array|null $column
     * Add Column Filter To Collection
     */
    protected function _addColumnFilterToCollection($column)
    {
        if ($column->getId() == 'in_product') {
            $productIds = $this->_getSelectedProducts();
            if (empty($productIds)) {
                $productIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', ['in' => $productIds]);
            } else {
                if ($productIds) {
                    $this->getCollection()->addFieldToFilter('entity_id', ['nin' => $productIds]);
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }
    /**
     * Prepare collection
     */
    protected function _prepareCollection()
    {
        $collection = $this->productCollectionFactory->create();
        $collection->addAttributeToSelect('name');
        $collection->addAttributeToSelect('sku');
        $collection->addAttributeToSelect('price');
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
    /**
     * Short desc
     *
     * @return $this
     */
    protected function _prepareColumns()
    {

        //model = $this->_objectManager->get('\I98commerce\Prodfaqs\Model\Faqs');
        $this->addColumn(
            'in_product',
            [
                'header_css_class' => 'a-center',
                'type' => 'checkbox',
                'name' => 'in_product',
                'align' => 'center',
                'index' => 'entity_id',
                'values' => $this->_getSelectedProducts(),
            ]
        );
        $this->addColumn(
            'entity_id',
            [
                'header' => __('Product ID'),
                'type' => 'number',
                'index' => 'entity_id',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id',
            ]
        );
        $this->addColumn(
            'name',
            [
                'header' => __('Name'),
                'index' => 'name',
                'class' => 'xxx',
                'width' => '50px',
            ]
        );
        $this->addColumn(
            'sku',
            [
                'header' => __('Sku'),
                'index' => 'sku',
                'class' => 'xxx',
                'width' => '50px',
            ]
        );
        $this->addColumn(
            'price',
            [
                'header' => __('Price'),
                'type' => 'currency',
                'index' => 'price',
                'width' => '50px',
            ]
        );
        return parent::_prepareColumns();
    }
    /**
     * Short desc
     *
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/productsgrid', ['_current' => true]);
    }
    /**
     * Short desc
     *
     * @param  object $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return '';
    }

    /**
     * Short desc
     *
     * @return mixed
     */
    protected function _getSelectedProducts()
    {
        $faq = $this->getFaqObj();
        return $faq->getProducts($faq);
    }
    /**
     * Retrieve selected products
     *
     * @return array
     */
    public function getSelectedProducts()
    {
        $faq = $this->getFaqObj();
        $selected = $faq->getProducts($faq);
        if (!is_array($selected)) {
            $selected = [];
        }
        return $selected;
    }

    /**
     * Short desc
     *
     * @return Faqs
     */
    protected function getFaqObj()
    {
        $faqId = $this->getRequest()->getParam('faq_id');
        $faq   = $this->faqsFactory->create();
        if ($faqId) {
            $faq->load($faqId);
        }
        return $faq;
    }

    /**
     * Short desc
     *
     * @return true
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Short desc
     *
     * @return true
     */
    public function isHidden()
    {
        return true;
    }
}
