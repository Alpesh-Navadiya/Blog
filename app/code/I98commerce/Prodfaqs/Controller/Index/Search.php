<?php
namespace I98commerce\Prodfaqs\Controller\Index;

use I98commerce\Prodfaqs\Helper\Data;
use I98commerce\Prodfaqs\Model\Faqs;
use I98commerce\Prodfaqs\Model\Topic;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Action\Context;

class Search extends \Magento\Framework\App\Action\Action
{

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;
    /**
     * @var Topic
     */
    protected $_topicModel;
    /**
     * @var Faqs
     */
    protected $_faqsModel;
    /**
     * @var Registry|null
     */
    protected $_coreRegistry = null;
    /**
     * @var Data
     */
    protected $_helper;

    /**
     * Desc
     *
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param Topic $topicModel
     * @param Faqs $faqsModel
     * @param Registry $registry
     * @param Data $helper
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        \I98commerce\Prodfaqs\Model\Topic $topicModel,
        \I98commerce\Prodfaqs\Model\Faqs $faqsModel,
        \Magento\Framework\Registry $registry,
        \I98commerce\Prodfaqs\Helper\Data $helper
    ) {

        $this->_faqsModel = $faqsModel;
        $this->_topicModel = $topicModel;
        $this->_coreRegistry = $registry;
        $this->resultPageFactory = $resultPageFactory;
        $this->_helper = $helper;
        parent::__construct($context);
    }

    /**
     * Disc
     *
     * @return ResponseInterface|ResultInterface|Page|void
     */
    public function execute()
    {
        $post = $this->getRequest()->getParam('faqssearch');

        if ($searchTerm = $post) {
            $result = $this->_faqsModel->setFaqsQueryFilter($searchTerm);

            $this->_coreRegistry->register('faq_search_results', $result);

            $resultPage = $this->resultPageFactory->create();

            $resultPage->getConfig()->getTitle()->set($searchTerm ? 'Search results for: '. "'" .$searchTerm."'" : $this->_helper->getFaqsPageTitle());
            $resultPage->getConfig()->setKeywords($this->_helper->getFaqsPageMetaKeywords());
            $resultPage->getConfig()->setDescription($this->_helper->getFaqsPageMetaDesc());

            return $resultPage;
        }
    }
}
