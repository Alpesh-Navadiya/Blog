<?php

declare(strict_types=1);

namespace I98commerce\Prodfaqs\Model;

use I98commerce\Prodfaqs\Helper\Data;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magento\Framework\Registry;
use Magento\Store\Model\StoreManagerInterface;

class Faqs extends \Magento\Framework\Model\AbstractModel
{
    /**
     * @var AbstractResource|null
     */
    protected $_resource;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var ResourceConnection
     */
    protected $_resourceConnection;

    /**
     * @var Data
     */
    protected $_helper;

    /**
     * Desc
     *
     * @param Context $context
     * @param Registry $registry
     * @param StoreManagerInterface $storeManager
     * @param ResourceConnection $resourceConnection
     * @param Data $helper
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        \I98commerce\Prodfaqs\Helper\Data $helper,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->storeManager = $storeManager;
        $this->_resource = $resource;
        $this->_resourceConnection = $resourceConnection;
        $this->_helper = $helper;
    }

    /**
     * Desc
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init('I98commerce\Prodfaqs\Model\ResourceModel\Faqs');
    }

    /**
     * Desc
     *
     * @return string[]
     */
    public function getAvailableStatuses(): array
    {
        $availableOptions = ['1' => 'Enable', '0' => 'Disable'];
        return $availableOptions;
    }

    /**
     * Desc
     *
     * @param integer $topic_id
     * @param $show_on_main
     * @return AbstractDb|AbstractCollection|null
     */
    public function loadFaqsOfTopic($topic_id, $show_on_main = false)
    {
        $faqsCollection = $this->getCollection()->addFieldToFilter('faqs_topic_id', $topic_id)->addFieldToFilter('status', 1)->setOrder('faq_order', 'asc');
        if ($show_on_main) {
            $faqsCollection->addFieldToFilter('show_on_main', 1);
        }
        return $faqsCollection;
    }

    /**
     * Desc
     *
     * @param integer $customer_id
     * @return array|null
     */
    public function loadFaqsOfCustomer($customer_id)
    {

        $faqsCollection = $this->getCollection()->addFieldToFilter('user_id', $customer_id)->setOrder('faq_order', 'asc');
        return $faqsCollection->getData();
    }

    /**
     * Desc
     *
     * @param integer $faq_id
     * @return array|null
     */
    public function loadFaq($faq_id)
    {
        $faqsCollection = $this->getCollection()->addFieldToFilter('faq_id', $faq_id);
        return $faqsCollection->getData();
    }

    /**
     * Decs
     *
     * @param $query
     * @return array
     * @throws NoSuchEntityException
     */
    public function setFaqsQueryFilter($query)
    {

        $conn = $this->_resourceConnection->getConnection('core_read');
        $faqsTable = $conn->getTableName('i98commerce_faq');
        $faqsTopicTable = $conn->getTableName('i98commerce_faqs_topic');
        $faqsStoreTable = $conn->getTableName('i98commerce_faqs_topic_store');

        $select = $conn->select()->from(['f' => $faqsTable])->where('f.title LIKE "%'.$query.'%" OR f.faq_answer LIKE "%'.$query.'%" OR f.tags LIKE "%'.$query.'%"')->where('f.status = ?', 1)->where('fs.store_id = '. $this->storeManager->getStore()->getId() .' OR fs.store_id = 0')->order('f.faq_order '. \Magento\Framework\DB\Select::SQL_ASC);

        $select->joinLeft(['fs' => $faqsStoreTable], 'f.faqs_topic_id = fs.faqs_topic_id', []);

        $result = $conn->fetchAll($select);

        return $result;
    }

    /**
     * Desc
     *
     * @param int|string|array|null $identifier
     * @return mixed
     * @throws LocalizedException
     */
    public function checkIdentifier($identifier)
    {
        return $this->_getResource()->checkIdentifier($identifier);
    }

    /**
     * Desc
     *
     * @return array
     */
    public function getPopularTags()
    {

        $faqsCollection = $this->getCollection()->addFieldToFilter('status', 1)->setOrder('faq_order', 'asc');
        $tags = [];

        foreach ($faqsCollection as $data) {
            $tags[] = $data->getTags();
        }

        if (is_array($tags)) {
            $tags = implode(',', $tags);
            $tags = explode(',', $tags);
            $tags = array_unique($tags);
        }

        $max_num = $this->_helper->getTagsMaxNum();

        if ($max_num != '' && $max_num != 0) {
            $tags = array_slice($tags, 0, (int) $max_num);
        }

        return $tags;
    }

    /**
     * Desc
     *
     * @param Faqs $object
     * @return mixed
     */
    public function getProducts(\I98commerce\Prodfaqs\Model\Faqs $object)
    {
        return $this->getResource()->getProducts($object);
    }

    /**
     * Desc
     *
     * @return array|mixed|null
     */
    public function getProductsPosition()
    {
        if (!$this->getId()) {
            return [];
        }

        $array = $this->getData('products_position');
        if ($array === null) {
            $temp = $this->getData('product_id');

            for ($i=0; $i<sizeof($this->getData('product_id')); $i++) {
                $array[$temp[$i]] = 0;
            }

            $this->setData('products_position', $array);
        }

        return $array;
    }
}
