<?php

namespace Know\Blog\Controller\Index;
use Know\Blog\Model\Post;
use Know\Blog\Model\ResourceModel\Post as HeroResourceModel;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;

class view extends \Magento\Framework\App\Action\Action
{
    protected $_pageFactory;
    protected $_request;
    protected $_coreRegistry;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \Magento\Framework\App\Request\Http $request,
        \Magento\Framework\Registry $coreRegistry
    ){
        $this->_pageFactory = $pageFactory;
        $this->_request = $request;
        $this->_coreRegistry = $coreRegistry;
        return parent::__construct($context);
    }

    public function execute()
    {
        $id = $this->_request->getParam('id');
        $this->_coreRegistry->register('id', $id);
        return $this->_pageFactory->create();
    }
}
