<?php
/**
 * Copyright © 2016 AionNext Ltd. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace I98commerce\Prodfaqs\Model;

use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use Magento\Store\Model\StoreManagerInterface;

class Answers extends \Magento\Framework\Model\AbstractModel
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
    protected $_cacheTag = 'prodfaqs_answer';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'prodfaqs_answer';

    /**
     * Desc
     *
     * @var void
     */

    protected $_resource;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * Desc
     *
     * @param Context $context
     * @param Registry $registry
     * @param StoreManagerInterface $storeManager
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {

        $this->storeManager = $storeManager;
        $this->_resource = $resource;

        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Desc
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('I98commerce\Prodfaqs\Model\ResourceModel\Answers');
    }

    /**
     * Desc
     *
     * @param integer $faq_id
     * @return array|null
     */
    public function loadAnswersCount($faq_id)
    {

        $faqsCollection = $this->getCollection()->addFieldToFilter('faq_id', $faq_id);
        return $faqsCollection->getData();
    }

    /**
     * Desc
     *
     * @param integer $faq_id
     * @return array|null
     */
    public function loadNewAnswers($faq_id)
    {

        $faqsCollection = $this->getCollection()->addFieldToFilter('faq_id', $faq_id)->addFieldToFilter('is_email', 0)->addFieldToFilter('answer_by', ['neq' => 'admin']);
        return $faqsCollection->getData();
    }

    /**
     * Desc
     *
     * @param integer $customer_id
     * @return array|null
     */
    public function loadAnswers($customer_id)
    {

        $ansCollection = $this->getCollection()->addFieldToFilter('user_id', $customer_id);
        return $ansCollection->getData();
    }

    /**
     * Desc
     *
     * @param integer $faq_id
     * @return array|null
     */
    public function loadFaqanswers($faq_id)
    {

        $ansCollection = $this->getCollection()->addFieldToFilter('faq_id', $faq_id)->addFieldToFilter('status', 1);
        return $ansCollection->getData();
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
