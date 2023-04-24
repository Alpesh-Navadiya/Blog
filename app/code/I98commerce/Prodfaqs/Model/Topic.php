<?php

namespace I98commerce\Prodfaqs\Model;

use I98commerce\Prodfaqs\Helper\Data;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use Magento\Store\Model\StoreManagerInterface;

class Topic extends \Magento\Framework\Model\AbstractModel
{
    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

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
     * @param Data $helper
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \I98commerce\Prodfaqs\Helper\Data $helper,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {

        $this->storeManager = $storeManager;
        $this->_helper = $helper;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Desc
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('I98commerce\Prodfaqs\Model\ResourceModel\Topic');
    }

    /**
     * Doc
     *
     * @return string[]
     */
    public function getAvailableStatuses()
    {
        $availableOptions = ['1' => 'Enable',
                          '0' => 'Disable'];

        return $availableOptions;
    }

        /*
         * Desc
         *
         * topic list for admin dropdown, to attach with faqs
         */

    /**
     * Desc
     *
     * @return array
     */
    public function getTopicsList()
    {

        $collection = $this->getCollection()->addFieldToFilter('status', 1);
        $topicList = [];

        foreach ($collection as $data) {
            $topicList[$data->getId()] = $data->getTitle();
        }

        return $topicList;
    }

    /**
     * Desc
     *
     * @return array
     */
    public function gettopics()
    {
        $topics = [];

        $collection = $this->getCollection()->addFieldToFilter('status', 1);
        $topicList = [];
        $i=0;
        foreach ($collection as $data) {
            $topics[$i] =['value' => $data->getFaqs_topic_id(), 'label' => __($data->getTitle())];
            $i++;
        }
        return $topics;
    }

    /**
     * Desc
     *
     * @return mixed
     * @throws NoSuchEntityException
     */
    public function loadFrontPageTopics()
    {
        $collection = $this->getCollection()->addFieldToFilter('status', 1)
                                        ->setOrder('topic_order', 'asc')
                                        ->addStoreFilter($this->storeManager->getStore()->getId(), true);

        if ($this->_helper->displaySelectedTopics()) {
            $collection->addFieldToFilter('show_on_main', 1);
        }

        return $collection;
    }

    /**
     * Desc
     *
     * @return mixed
     */
    public function getMainPageIdentifer()
    {
        return $this->_helper->getFaqsPageIdentifier();
    }

    /**
     * Desc
     *
     * @return mixed
     */
    public function getFaqsSeoIdentifierPostfix()
    {
        return $this->_helper->getFaqsSeoIdentifierPostfix();
    }

    /**
     * Desc
     *
     * @param string $identifier
     * @param string $storeId
     * @return mixed
     * @throws LocalizedException
     */
    public function checkIdentifier($identifier, $storeId)
    {
        return $this->_getResource()->checkIdentifier($identifier, $storeId);
    }
}
