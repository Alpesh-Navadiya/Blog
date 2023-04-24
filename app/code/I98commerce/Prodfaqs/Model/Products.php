<?php
/**
 * Copyright © 2016 AionNext Ltd. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace I98commerce\Prodfaqs\Model;

use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use Magento\Store\Model\StoreManagerInterface;

class Products extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Statuses
     */
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;

    /**
     * Aion Test cache tag
     */
    const CACHE_TAG = 'prodfaqs_answer';

    /**
     * @var string
     */
    protected $_cacheTag = 'prodfaqs_products';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'prodfaqs_products';

    /**
     * Desc
     *
     * @var void
     */

    protected $_resource;

    /**
     * Des
     *
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * Des
     *
     * @var ResourceConnection
     */
    protected $_resourceConnection;

    /**
     * Desc
     *
     * @param Context $context
     * @param Registry $registry
     * @param StoreManagerInterface $storeManager
     * @param ResourceConnection $resourceConnection
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {

        $this->storeManager = $storeManager;
        $this->_resource = $resource;
        $this->_resourceConnection = $resourceConnection;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Desc
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('I98commerce\Prodfaqs\Model\ResourceModel\Products');
    }

    /**
     * Des
     *
     * @param integer $product_id
     * @return array
     */
    public function CountProductFaq($product_id)
    {

        $conn = $this->_resourceConnection->getConnection('core_read');
        $productsTable = $conn->getTableName('i98commerce_faqs_product');
        $select = $conn->select()->from(['f' => $productsTable]) ->where('f.product_id ='.$product_id);
        $result = $conn->fetchAll($select);
        return $result;
    }

    /**
     * Desc
     *
     * @param integer $product_id
     * @return array
     */
    public function CountVisibleProductFaq($product_id)
    {

        $conn = $this->_resourceConnection->getConnection('core_read');
        $productsTable = $conn->getTableName('i98commerce_faqs_product');
        $faqsTable = $conn->getTableName('i98commerce_faq');
        $select = $conn->select()->from(['f' => $productsTable])->where('f.product_id ='.$product_id);
        $select->join(['fs' => $faqsTable], 'f.faq_id = fs.faq_id AND fs.status=1', []);
        $result = $conn->fetchAll($select);
        return $result;
    }

    /**
     * Get identities
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId(), self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * Prepare item's statuses
     *
     * @return array
     */
    public function getAvailableStatuses()
    {
        return [self::STATUS_ENABLED => __('Enabled'), self::STATUS_DISABLED => __('Disabled')];
    }
}
