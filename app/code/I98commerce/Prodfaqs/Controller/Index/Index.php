<?php
namespace I98commerce\Prodfaqs\Controller\Index;

use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Action\Context;
use I98commerce\Prodfaqs\Helper\Data;

class Index extends \Magento\Framework\App\Action\Action
{

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var Data
     */
    protected $_helper;

    /**
     * Desc
     *
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param Data $helper
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        Data $helper
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->_helper = $helper;
        parent::__construct($context);
    }

    /**
     * Desc
     *
     * @return ResponseInterface|ResultInterface|Page
     */
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();

        $resultPage->getConfig()->getTitle()->set($this->_helper->getFaqsPageTitle());
        $resultPage->getConfig()->setKeywords($this->_helper->getFaqsPageMetaKeywords());
        $resultPage->getConfig()->setDescription($this->_helper->getFaqsPageMetaDesc());

        return $resultPage;
    }
}
