<?php
/**
 * @author I98commerce Team
 * @copyright Copyright (c) 2020 I98commerce (https://www.i98commerce.com)
 * @package I98commerce_Prodfaqs
 */


namespace I98commerce\Prodfaqs\Block;

use I98commerce\Prodfaqs\Model\Answers;
use I98commerce\Prodfaqs\Model\Faqs;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ProductRepository;
use Magento\Customer\Model\Session;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\SessionException;
use Magento\Framework\Registry;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class Customer extends Template
{
    /**
     * @var Faqs
     */
    protected $_faqsModel;
    /**
     * @var Answers
     */
    protected $_answersModel;
    /**
     * @var Registry|null
     */
    protected ?Registry $_coreRegistry = null;
    /**
     * @var Session
     */
    protected $customerSession;
    /**
     * @var UrlInterface
     */
    protected $urlBuilder;
    /**
     * @var ProductRepository
     */
    protected $_productRepository;
    /**
     * @var Product
     */
    protected $_product;

    /**
     * @param Context $context
     * @param Faqs $faqsModel
     * @param Answers $answersModel
     * @param Session $customerSession
     * @param ProductRepository $productRepository
     * @param Registry $registry
     * @param Product $product
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \I98commerce\Prodfaqs\Model\Faqs $faqsModel,
        \I98commerce\Prodfaqs\Model\Answers $answersModel,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Magento\Framework\Registry $registry,
        \Magento\Catalog\Model\Product $product
    ) {

        $this->customerSession = $customerSession;
        $this->_coreRegistry = $registry;
        $this->_productRepository = $productRepository;
        $this->_faqsModel = $faqsModel;
        $this->_answersModel = $answersModel;
        $this->urlBuilder = $context->getUrlBuilder();
        $this->_product = $product;
        parent::__construct($context);
    }

    /**
     * Short desc
     *
     * @param int|string|null $customer_id
     * @return array|null
     */
    public function getCustomerQuestions($customer_id)
    {

        $faqsCollection = $this->_faqsModel->loadFaqsOfCustomer($customer_id);

        return $faqsCollection;
    }

    /**
     * Short desc
     *
     * @param int|string|null $customer_id
     * @return array|null
     */
    public function getCustomerAnswers($customer_id)
    {

        $answers = $this->_answersModel->loadAnswers($customer_id);

        return $answers;
    }

    /**
     * Short desc
     *
     * @return mixed
     */
    public function getLoginId()
    {
        return($this->customerSession->getCustomer()->getId());
    }

    /**
     * Short desc
     *
     * @param int|string|null $faq_id
     * @return array|null
     */
    public function getAnswersCount($faq_id)
    {
        $answersCollection = $this->_answersModel->loadAnswersCount($faq_id);

        return $answersCollection;
    }

    /**
     * Short desc
     *
     * @param int|string|null $faq_id
     * @return array|null
     */
    public function getFaq($faq_id)
    {

        $faq = $this->_faqsModel->loadFaq($faq_id);
        return $faq;
    }

    /**
     * Short desc
     *
     * @param int|string|null $productid
     * @return mixed|null
     */
    public function getproname($productid)
    {
        $product = $this->_product->load($productid);
        return $product['name'];
    }

    /**
     * Short desc
     *
     * @param int|string|null $productid
     * @return mixed
     * @throws NoSuchEntityException
     */
    public function getprourl($productid)
    {
        $product = $this->_productRepository->getById($productid);
        return $product->getUrlModel()->getUrl($product);
    }

    /**
     * Short desc
     *
     * @return void
     * @throws SessionException
     */
    public function redirectIfNotLoggedIn()
    {
        if (!$this->customerSession->isLoggedIn()) {
            $this->customerSession->setAfterAuthUrl($this->urlBuilder->getCurrentUrl());
            $this->customerSession->authenticate();
        }
    }
}
