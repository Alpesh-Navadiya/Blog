<?php
/**
 * @category Msquare AjaxCustomPagination
 * @package Msquare_AjaxCustomPagination
 * @author Mahesh Makwana <maheshmakwana588@gmail.com>
 */

namespace Know\Blog\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\Controller\ResultFactory;

class AjaxData extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $block = $resultPage->getLayout()
            ->createBlock(\Know\Blog\Block\Pager::class)
            ->setData('ajax_data', true)
            ->setTemplate("Know_Blog::pager.phtml");
        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $data = ['content' => $block->toHtml()];
        $resultJson->setData($data);
        return $resultJson;
    }
}
