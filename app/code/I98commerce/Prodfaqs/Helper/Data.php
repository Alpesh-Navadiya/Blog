<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace I98commerce\Prodfaqs\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{

    const XML_FAQS_ENABLE               = 'prodfaqs/list/enabled';
    const XML_FAQS_PAGE_TITLE           = 'prodfaqs/list/page_title';
    const XML_FAQS_IDENTIFIER           = 'prodfaqs/list/identifier';
    const XML_FAQS_META_KEYWORDS        = 'prodfaqs/list/meta_keywords';
    const XML_FAQS_META_DESC            = 'prodfaqs/list/meta_description';
    const XML_FAQS_DISPLAY_TOPICS       = 'prodfaqs/list/display_topics';
    const XML_FAQS_NUM_OF_QUESTIONS     = 'prodfaqs/list/show_number_of_questions';
    const XML_FAQS_VIEW_MORE            = 'prodfaqs/list/enable_view_more';
    const XML_FAQS_ACCORDION            = 'prodfaqs/list/enable_accordion';
    const XML_ANSWER_LENGTH             = 'prodfaqs/list/answer_length';

    const XML_RATING_ENABLE             = 'prodfaqs/rating/enable';
    const XML_FAQS_ALLOW_CUSTOMERS      = 'prodfaqs/rating/allow_customers';

    const XML_FAQS_BLOCK                = 'prodfaqs/general/faq_block';
    const XML_FAQS_SEARCH_BLOCK         = 'prodfaqs/general/faq_search_block';
    const XML_FAQS_MAX_TOPIC            = 'prodfaqs/general/faq_maxtopic';
    const XML_TAGS_BLOCK                = 'prodfaqs/general/tags_block';
    const XML_MAX_TAGS                  = 'prodfaqs/general/max_tags';

    const XML_FAQS_URL_SUFFIX           = 'prodfaqs/seo/url_suffix';
    const TIME_ZONE = 'general/locale/timezone';

    /**
     * Short desc
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * Short desc
     *
     * @var UrlInterface
     */
    protected $_urlBuilder;

    /**
     * Short desc
     *
     * @var \Magento\Backend\Helper\Data
     */
    protected $_backendHelper;

    /**
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     * @param \Magento\Backend\Helper\Data $backendHelper
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Backend\Helper\Data $backendHelper
    ) {

        $this->_storeManager = $storeManager;
        $this->_urlBuilder = $context->getUrlBuilder();
        $this->_backendHelper = $backendHelper;

        parent::__construct($context);
    }

    /**
     * Short des
     *
     * @return true|void
     */
    public function isModuleEnabled()
    {

        if ($this->isModuleOutputEnabled('I98commerce_Prodfaqs') &&
                $this->scopeConfig->isSetFlag(
                    self::XML_FAQS_ENABLE,
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                )) {
            return true;
        }
    }

    /**
     * Short desc
     *
     * @return mixed
     */
    public function timezone()
    {
        return $this->scopeConfig->getValue(
            self::TIME_ZONE,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Short desc
     *
     * @return mixed
     */
    public function getFaqsPageTitle()
    {

        return $this->scopeConfig->getValue(
            self::XML_FAQS_PAGE_TITLE,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Short desc
     *
     * @return mixed
     */
    public function getFaqsPageIdentifier()
    {

        return $this->scopeConfig->getValue(
            self::XML_FAQS_IDENTIFIER,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Short desc
     *
     * @return mixed
     */
    public function getFaqsPageMetaKeywords()
    {

        return $this->scopeConfig->getValue(
            self::XML_FAQS_META_KEYWORDS,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Short desc
     *
     * @return mixed
     */
    public function getFaqsPageMetaDesc()
    {

        return $this->scopeConfig->getValue(
            self::XML_FAQS_META_DESC,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Short desc
     *
     * @return mixed
     */
    public function displaySelectedTopics()
    {

        return $this->scopeConfig->getValue(
            self::XML_FAQS_DISPLAY_TOPICS,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Short desc
     *
     * @return mixed
     */
    public function getNumOfQuestionsForFaqsPage()
    {

        return $this->scopeConfig->getValue(
            self::XML_FAQS_NUM_OF_QUESTIONS,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Short desc
     *
     * @return mixed
     */
    public function isViewMoreLinkEnable()
    {

        return $this->scopeConfig->getValue(
            self::XML_FAQS_VIEW_MORE,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Short desc
     *
     * @return mixed
     */
    public function isAccordionEnable()
    {

        return $this->scopeConfig->getValue(
            self::XML_FAQS_ACCORDION,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Short desc
     *
     * @return int|mixed
     */
    public function allowedAnswerLength()
    {

        $length = $this->scopeConfig->getValue(
            self::XML_ANSWER_LENGTH,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        return ($length == '') ? 0 : $length;
    }

    /**
     * Short desc
     *
     * @return mixed
     */
    public function isRatingEnable()
    {

        return $this->scopeConfig->getValue(
            self::XML_RATING_ENABLE,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Short desc
     *
     * @return mixed
     */
    public function getAllowedCustomerForRating()
    {

        return $this->scopeConfig->getValue(
            self::XML_FAQS_ALLOW_CUSTOMERS,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Short desc
     *
     * @return mixed
     */
    public function isFaqsBlockEnable()
    {

        return $this->scopeConfig->getValue(
            self::XML_FAQS_BLOCK,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Short desc
     *
     * @return mixed
     */
    public function isFaqsSearchBlockEnable()
    {

        return $this->scopeConfig->getValue(
            self::XML_FAQS_SEARCH_BLOCK,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Short desc
     *
     * @return mixed
     */
    public function getFaqsBlockNumberOfTopics()
    {

        return $this->scopeConfig->getValue(
            self::XML_FAQS_MAX_TOPIC,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Short desc
     *
     * @return mixed
     */
    public function getTagsMaxNum()
    {

        return $this->scopeConfig->getValue(
            self::XML_MAX_TAGS,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Short desc
     *
     * @return mixed
     */
    public function getFaqsSeoIdentifierPostfix()
    {

        return $this->scopeConfig->getValue(
            self::XML_FAQS_URL_SUFFIX,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Short desc
     *
     * @return string
     */
    public function getProductsGridUrl()
    {

        return  $this->_backendHelper->getUrl('prodfaqs/faqs/products', ['_current' => true]);
    }
}
