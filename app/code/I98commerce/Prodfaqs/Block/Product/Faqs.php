<?php
namespace I98commerce\Prodfaqs\Block\Product;

use I98commerce\Prodfaqs\Helper\Data;
use I98commerce\Prodfaqs\Model\Answers;
use I98commerce\Prodfaqs\Model\Topic;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Module\Dir\Reader;
use Magento\Framework\Phrase;
use Magento\Framework\Registry;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Template\Context;
use Magento\Store\Model\StoreManagerInterface;

class Faqs extends \I98commerce\Prodfaqs\Block\Faqs
{
    const CONFIG_CAPTCHA_ENABLE = 'prodfaqs/google_options/captchastatus';
    const CONFIG_CAPTCHA_PRIVATE_KEY = 'prodfaqs/google_options/googleprivatekey';
    const CONFIG_CAPTCHA_PUBLIC_KEY = 'prodfaqs/google_options/googlepublickey';
    const CONFIG_CAPTCHA_THEME = 'prodfaqs/google_options/theme';

    const PFAQS_HEADING = 'prodfaqs/product_questions/title';
    const PFAQS_ASK_ENABLE = 'prodfaqs/product_questions/enable_ask';
    const PFAQS_CUSTOMR_ASK_ALLOWED = 'prodfaqs/product_questions/allow_customers';
    const PFAQS_ASK_POPUPSLIDE   = 'prodfaqs/product_questions/open_form';

    const PFAQS_FAQS_SORTBY   = 'prodfaqs/product_questions/sortby';

    const ANS_ASK_ENABLE = 'prodfaqs/answers/enable_ask';
    const ANS_CUSTOMR_ASK_ALLOWED = 'prodfaqs/answers/allow_customers';

    const ANS_LIKES_ENABLE = 'prodfaqs/answers/enable_likes';
    const ANS_LIKES_ALLOWED = 'prodfaqs/answers/likes_allow_customers';
    const PFAQS_FAQS_LOADER = 'prodfaqs/ajaxloader/placeholder';
    /**
     * @var UrlInterface
     */
    protected $urlBuilder;
    /**
     * @var null
     */
    protected $_answersModel;
    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;
    /**
     * @var StoreManagerInterface
     */
    public $_storeManager;
    /**
     * @var null
     */
    protected $customerSession;
    /**
     * Short desc
     *
     * @var \Magento\Framework\Module\Dir\Reader
     */
    protected $moduleReader;

    /**
     * Short desc
     *
     * @param Context $context
     * @param Topic $topicModel
     * @param \I98commerce\Prodfaqs\Model\Faqs $faqsModel
     * @param Answers $answersModel
     * @param Data $helper
     * @param Registry $registry
     * @param Session $customerSession
     * @param StoreManagerInterface $storeManager
     * @param Reader $moduleReader
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \I98commerce\Prodfaqs\Model\Topic $topicModel,
        \I98commerce\Prodfaqs\Model\Faqs $faqsModel,
        \I98commerce\Prodfaqs\Model\Answers $answersModel,
        \I98commerce\Prodfaqs\Helper\Data $helper,
        \Magento\Framework\Registry $registry,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Module\Dir\Reader $moduleReader
    ) {

        $this->urlBuilder = $context->getUrlBuilder();
        $this->scopeConfig = $context->getScopeConfig();
        $this->_storeManager = $context->getStoreManager();
        $this->moduleReader = $moduleReader;
        parent::__construct($context, $topicModel, $faqsModel, $answersModel, $helper, $registry, $customerSession, $storeManager);
        $this->setTabTitle();
    }

    /**
     * Short desc
     *
     * @return mixed
     */
    public function getLoginId()
    {
        return($this->_customerSession->getCustomer()->getId());
    }

    /**
     * Short desc
     *
     * @return string
     */
    public function getLoginEmail()
    {
        return($this->_customerSession->getCustomer()->getEmail());
    }

    /**
     * Short desc
     *
     * @return string
     * @throws LocalizedException
     */
    public function getLoginName()
    {
        return($this->_customerSession->getCustomer()->getName());
    }

    /**
     * Get current product id
     *
     * @return null|int
     */
    public function getProductId()
    {
        $product = $this->_coreRegistry->registry('product');
        return $product ? $product->getId() : null;
    }

    /**
     * Short desc
     *
     * @return null
     */
    public function getProductName()
    {
        $product = $this->_coreRegistry->registry('product');
        return $product ? $product->getName() : null;
    }

    /**
     * Short desc
     *
     * @return null
     */
    public function getProductUrl()
    {

        $product = $this->_coreRegistry->registry('product');
        return $product ? $product->getUrlModel()->getUrl($product) : null;
    }

    /**
     * Set tab title
     *
     * @return void
     */
    public function setTabTitle()
    {
        $title = __('Faqs');
        $this->setTitle($title);
    }

    /**
     * Short desc
     *
     * @return string
     */
    public function getFaqPostUrl()
    {
        return $this->urlBuilder->getUrl('prodfaqs/index/post');
    }

    /**
     * Short desc
     *
     * @return string
     */
    public function getAnswerPostUrl()
    {
        return $this->urlBuilder->getUrl('prodfaqs/index/answerpost');
    }

    /**
     * Short desc
     *
     * @param int|string|null $product_id
     * @return mixed
     */
    public function getProductPageFaqs($product_id)
    {

        $collection = $this->_faqsModel->getCollection()
                                        ->joinProductAttachmentTable()
                                        ->addFieldToFilter('product_id', $product_id)
                                        ->addFieldToFilter('main_table.status', 1)
                                        ->joinTopicTable()
                                        ->addFieldToFilter('topic_table.identifier', 'product-faqs');

        if ($this->getSortbyOrder() == 'asc') {
            $collection->setOrder('faq_order', 'asc');
        } elseif ($this->getSortbyOrder() == 'desc') {
            $collection->setOrder('faq_order', 'desc');
        } else {
            $collection->setOrder('create_date', 'desc');
        }
        return $collection;
    }

    /**
     * Short desc
     *
     * @param int|string|array|null $product_id
     * @param int|string|array|null $type
     * @param int|string|array|null $arrow
     * @return mixed
     */
    public function getProductPageAjaxFaqs($product_id, $type, $arrow)
    {

        $collection = $this->_faqsModel->getCollection()
                                        ->joinProductAttachmentTable()
                                        ->addFieldToFilter('product_id', $product_id)
                                        ->addFieldToFilter('main_table.status', 1)
                                        ->joinTopicTable()
                                        ->addFieldToFilter('topic_table.identifier', 'product-faqs');

        if ($type == 0 && $arrow == 'asc') {
            $collection->setOrder('rating_stars', 'asc');
        } elseif ($type == 0 && $arrow == 'desc') {
            $collection->setOrder('rating_stars', 'desc');
        } elseif ($type == 1 && $arrow == 'asc') {
            $collection->setOrder('create_date', 'asc');
        } elseif ($type == 1 && $arrow == 'desc') {
            $collection->setOrder('create_date', 'desc');
        }

        return $collection;
    }

    /**
     * Short desc
     *
     * @param int|string|array|null $faq_id
     * @return array|null
     */
    public function getfaqanswers($faq_id)
    {

        $answers = $this->_answersModel->loadFaqanswers($faq_id);
        return $answers;
    }

    /**
     * Short desc
     *
     * @param int|string|array|null $moduleName
     * @param int|string|array|null $type
     * @return string
     */
    public function df_module_dir($moduleName, $type = '')
    {
        return $this->moduleReader->getModuleDir($type, $moduleName);
    }

    /**
     * Short desc
     *
     * @return mixed
     */
    public function isCaptchaEnable()
    {

        return $this->scopeConfig->getValue(self::CONFIG_CAPTCHA_ENABLE);
    }

    /**
     * Short desc
     *
     * @return mixed
     */
    public function getPrivateKey()
    {

        return $this->scopeConfig->getValue(self::CONFIG_CAPTCHA_PRIVATE_KEY);
    }

    /**
     * Short desc
     *
     * @return mixed
     */
    public function getPublicKey()
    {

        return $this->scopeConfig->getValue(self::CONFIG_CAPTCHA_PUBLIC_KEY);
    }

    /**
     * Short desc
     *
     * @return mixed
     */
    public function getCaptchaTheme()
    {

        return $this->scopeConfig->getValue(self::CONFIG_CAPTCHA_THEME);
    }

    /**
     * Short desc
     *
     * @return Phrase|mixed
     */
    public function getProductFaqsHeading()
    {

        $title = $this->scopeConfig->getValue(self::PFAQS_HEADING);
        return $title ? $title : __('Faqs');
    }

    /**
     * Short desc
     *
     * @return mixed
     */
    public function getSortbyOrder()
    {

        $order = $this->scopeConfig->getValue(self::PFAQS_FAQS_SORTBY);
        return $order;
    }

    /**
     * Short desc
     *
     * @return mixed
     */
    public function getAjaxLoader()
    {
        $loader = $this->scopeConfig->getValue(self::PFAQS_FAQS_LOADER);
        return $loader;
    }

    /**
     * Short desc
     *
     * @return mixed
     */
    public function isAskQuestionEnable()
    {

        return $this->scopeConfig->getValue(self::PFAQS_ASK_ENABLE);
    }

    /**
     * Short desc
     *
     * @return bool
     */
    public function isAskQuestionAllowed()
    {

        $conf = $this->scopeConfig->getValue(self::PFAQS_CUSTOMR_ASK_ALLOWED);
        $allow = false;
        if ($conf == 'all') {
            $allow = true;
        } elseif ($conf == 'guests') {
            if (!$this->_customerSession->isLoggedIn()) {
                $allow = true;
            } else {
                $allow = false;
            }
        } elseif ($conf == 'registered') {
            if ($this->_customerSession->isLoggedIn()) {
                $allow = true;
            } else {
                $allow = false;
            }
        } else {
            $allow = false;
        }
                return $allow;
    }

    /**
     * Short desc
     *
     * @return mixed
     */
    public function isAskAnswerEnable()
    {

        return $this->scopeConfig->getValue(self::ANS_ASK_ENABLE);
    }

    /**
     * Short desc
     *
     * @return bool
     */
    public function isAskAnswerAllowed()
    {

        $conf = $this->scopeConfig->getValue(self::ANS_CUSTOMR_ASK_ALLOWED);
        $allow = false;
        if ($conf == 'all') {
            $allow = true;
        } elseif ($conf == 'guests') {
            if (!$this->_customerSession->isLoggedIn()) {
                $allow = true;
            } else {
                $allow = false;
            }
        } elseif ($conf == 'registered') {
            if ($this->_customerSession->isLoggedIn()) {
                $allow = true;
            } else {
                $allow = false;
            }
        } else {
            $allow = false;
        }
                return $allow;
    }

    /**
     * Short desc
     *
     * @return mixed
     */
    public function isLikesEnable()
    {

        return $this->scopeConfig->getValue(self::ANS_LIKES_ENABLE);
    }

    /**
     * Short desc
     *
     * @return bool
     */
    public function isLikesAllowed()
    {

        $conf = $this->scopeConfig->getValue(self::ANS_LIKES_ALLOWED);
        $allow = false;
        if ($conf == 'all') {
            $allow = true;
        } elseif ($conf == 'guests') {
            if (!$this->_customerSession->isLoggedIn()) {
                $allow = true;
            } else {
                $allow = false;
            }
        } elseif ($conf == 'registered') {
            if ($this->_customerSession->isLoggedIn()) {
                $allow = true;
            } else {
                $allow = false;
            }
        } else {
            $allow = false;
        }
                return $allow;
    }

    /**
     * Short desc
     *
     * @return mixed|string
     */
    public function openFormPopupSlide()
    {
        $popupSlide = $this->scopeConfig->getValue(self::PFAQS_ASK_POPUPSLIDE);
        return $popupSlide ? $popupSlide : 'popup';
    }
}
