<?php
/**
 * Short desc
 *
 * @author I98commerce Team
 * @copyright Copyright (c) 2020 I98commerce (https://www.i98commerce.com)
 * @package I98commerce_Prodfaqs
 */

namespace I98commerce\Prodfaqs\Block;

use I98commerce\Prodfaqs\Helper\Data;
use I98commerce\Prodfaqs\Model\Answers;
use I98commerce\Prodfaqs\Model\Topic;
use Magento\Customer\Model\Session;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magento\Framework\Registry;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Store\Model\StoreManagerInterface;

class Faqs extends Template
{
    /**
     * @var Topic
     */
    protected $_topicModel;
    /**
     * @var \I98commerce\Prodfaqs\Model\Faqs
     */
    protected $_faqsModel;
    /**
     * @var Registry|null
     */
    protected $_coreRegistry = null;
    /**
     * @var Answers
     */
    protected $_answersModel;
    /**
     * @var UrlInterface
     */
    protected $urlBuilder;
    /**
     * @var Data
     */
    protected $_helper;
    /**
     * @var Session
     */
    protected $_customerSession;
    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * Desc
     *
     * @param Context $context
     * @param Topic $topicModel
     * @param \I98commerce\Prodfaqs\Model\Faqs $faqsModel
     * @param Answers $answersModel
     * @param Data $helper
     * @param Registry $registry
     * @param Session $customerSession
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \I98commerce\Prodfaqs\Model\Topic $topicModel,
        \I98commerce\Prodfaqs\Model\Faqs $faqsModel,
        \I98commerce\Prodfaqs\Model\Answers $answersModel,
        \I98commerce\Prodfaqs\Helper\Data $helper,
        \Magento\Framework\Registry $registry,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {

        $this->_topicModel = $topicModel;
        $this->_faqsModel = $faqsModel;
        $this->_answersModel = $answersModel;
        $this->_coreRegistry = $registry;
        $this->_helper = $helper;
        $this->urlBuilder = $context->getUrlBuilder();
        $this->_customerSession = $customerSession;
        $this->_storeManager = $storeManager;
        parent::__construct($context);
    }

    /**
     * Desc
     *
     * @param string|int|null $faq_id
     * @return array|null
     */
    public function getfaqanswers($faq_id)
    {
        $answers = $this->_answersModel->loadFaqanswers($faq_id);
        return $answers;
    }

    /**
     * Desc
     *
     * @return mixed
     * @throws NoSuchEntityException
     */
    public function getFrontPageTopics()
    {
        $topicsCollection = $this->_topicModel->loadFrontPageTopics();
        return $topicsCollection;
    }

    /**
     * Desc
     *
     * @param int|string|null  $topic_id
     * @return AbstractDb|AbstractCollection|null
     */
    public function getFrontPageFaqs($topic_id)
    {

        $faqsCollection = $this->_faqsModel->loadFaqsOfTopic($topic_id, true);
        return $faqsCollection;
    }

    /**
     * Desc
     *
     * @return mixed
     */
    public function numberOfQuestionToDisplay()
    {

        $noq = $this->_helper->getNumOfQuestionsForFaqsPage();
        return $noq;
    }

    /**
     * Desc
     *
     * @param int|null $topic_id
     * @return string
     */
    public function getTopicUrl($topic_id)
    {

        $topic = $this->_topicModel->load($topic_id);
        $topic_identifier = $topic->getIdentifier();

        $main_identifier = $this->_topicModel->getMainPageIdentifer();
        $url_sufix = $this->_topicModel->getFaqsSeoIdentifierPostfix();

        $url = $main_identifier.'/'.$topic_identifier.$url_sufix;
        return $this->urlBuilder->getUrl($url);
    }

    /**
     * Desc
     *
     * @return string
     */
    public function getMainPageUrl()
    {

        $main_identifier = $this->_topicModel->getMainPageIdentifer();
        $url_sufix = $this->_topicModel->getFaqsSeoIdentifierPostfix();

        $url = $main_identifier.$url_sufix;
        return $this->urlBuilder->getUrl($url);
    }

    /**
     * Desc
     *
     * @param string|null $faqObj
     * @return string
     */
    public function getFaqUrl($faqObj)
    {

        $faq_id = $faqObj->getId();
        $faq = $this->_faqsModel->load($faq_id);
        $faq_identifier = $faq->getIdentifier();

        $main_identifier = $this->_topicModel->getMainPageIdentifer();
        $url_sufix = $this->_topicModel->getFaqsSeoIdentifierPostfix();

        $url = $main_identifier.'/'.$faq_identifier.$url_sufix;
        return $this->urlBuilder->getUrl($url);
    }

    /**
     * Desc
     *
     * @return mixed
     */
    public function getFaqsBlockNumberOfTopics()
    {

        return $this->_helper->getFaqsBlockNumberOfTopics();
    }

    /**
     * Desc
     *
     * @return mixed
     */
    public function isViewMoreLinkEnable()
    {

        return $this->_helper->isViewMoreLinkEnable();
    }

    /**
     * Desc
     *
     * @return mixed
     */
    public function isAccordionEnable()
    {

        return $this->_helper->isAccordionEnable();
    }

    /**
     * Desc
     *
     * @return int|mixed
     */
    public function allowedAnswerLength()
    {

        return $this->_helper->allowedAnswerLength();
    }

    /**
     * Desc
     *
     * @return mixed
     */
    public function isRatingEnable()
    {

        return $this->_helper->isRatingEnable();
    }

    /**
     * Desc
     *
     * @return string
     */
    public function isCustomerRatingReadonly()
    {

        $conf = $this->_helper->getAllowedCustomerForRating();
        $readonly = 'true';
        if ($conf == 'all') {
            $readonly = 'false';
        } elseif ($conf == 'guests') {
            if (!$this->_customerSession->isLoggedIn()) {
                $readonly = 'false';
            } else {
                $readonly = 'true';
            }
        } elseif ($conf == 'registered') {
            if ($this->_customerSession->isLoggedIn()) {
                $readonly = 'false';
            } else {
                $readonly = 'true';
            }
        } else {
            $readonly = 'true';
        }
        return $readonly;
    }

    /**
     * Desc
     *
     * @param int|string|void|null  $faq_id
     * @return false|int|string|void
     */
    public function isCustomerReadonlyStars($faq_id)
    {
        $conf = $this->isCustomerRatingReadonly();
        if ($conf == 'true') {
            return 1;
        }

        $faqRating = $this->_customerSession->getFaqRating();
        if (isset($faqRating)) {
            $ar = explode(',', $faqRating);
            $found = array_search($faq_id, $ar);
            return $found;
        }
    }

    /**
     * Short
     *
     * @param int|string|null $faq_id
     * @return false|int|string|void
     */
    public function isCustomerReadonlyLikes($faq_id)
    {
        // form configuration

        //now check if customer already rated for that faq
        $faqRating = $this->_customerSession->getLikesRating();
        if (isset($faqRating)) {
            $ar = explode(',', $faqRating);
            $found = array_search($faq_id, $ar);
            return $found;
        }
    }

    /**
     * Desc
     *
     * @return false|mixed
     */
    public function getCurrentTopicDetail()
    {
        $topicData = $this->_coreRegistry->registry('current_topic');
        return $topicData ? $topicData : false;
    }

    /**
     * Desc
     *
     * @return false|mixed
     */
    public function getCurrentFaqDetail()
    {
        $faqData = $this->_coreRegistry->registry('current_faq');
        return $faqData ? $faqData : false;
    }

    /**
     * Desc
     *
     * @param int|null $topic_id
     * @return AbstractDb|AbstractCollection|null
     */
    public function getDetailPageFaqs($topic_id)
    {

        $faqsCollection = $this->_faqsModel->loadFaqsOfTopic($topic_id, false);

        return $faqsCollection;
    }

    /**
     * Desc
     *
     * @return string
     */
    public function getRatingAjaxUrl()
    {

        $rating_url = $this->urlBuilder->getUrl('prodfaqs/index/rating');

        return $rating_url;
    }

    /**
     * Desc
     *
     * @return string
     */
    public function getLikesAjaxUrl()
    {

        $rating_url = $this->urlBuilder->getUrl('prodfaqs/index/likes');

        return $rating_url;
    }

    /**
     * Desc
     *
     * @return string
     */
    public function getAjaxUrl()
    {

        $rating_url = $this->urlBuilder->getUrl('prodfaqs/index/faqajax');

        return $rating_url;
    }

    /**
     * Desc
     *
     * @return string
     */
    public function getSearchUrl()
    {

        $search_url = $this->urlBuilder->getUrl('prodfaqs/index/search');

        return $search_url;
    }

    /**
     * Desc
     *
     * @return mixed|null
     */
    public function getFaqSearchDetail()
    {

        $searchData = $this->_coreRegistry->registry('faq_search_results');

        return $searchData;
    }

    /**
     * Desc
     *
     * @return array
     */
    public function getPopularTags()
    {

        $tags = $this->_faqsModel->getPopularTags();
        return $tags;
    }

    /**
     * Desc
     *
     * @return string
     * @throws NoSuchEntityException
     */
    public function getMediaDirectoryUrl()
    {
        $media_dir = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        return $media_dir;
    }
}
