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

class Detail extends \Magento\Framework\App\Action\Action
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
     * Desc
     *
     * @return ResponseInterface|ResultInterface|Page|void
     */
    public function execute()
    {
        $params = $this->getRequest()->getParams();

        if ($topic_id = $params['topic']) {
            $topicData = $this->_topicModel->load($topic_id);
            $this->_coreRegistry->register('current_topic', $topicData);

            $resultPage = $this->resultPageFactory->create();

            $resultPage->getConfig()->getTitle()->set($topicData->getTitle() ? $topicData->getTitle() : $this->_helper->getFaqsPageTitle());
            $resultPage->getConfig()->setKeywords($this->_helper->getFaqsPageMetaKeywords());
            $resultPage->getConfig()->setDescription($this->_helper->getFaqsPageMetaDesc());

            return $resultPage;
        }
    }
}
