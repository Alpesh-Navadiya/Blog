<?php
namespace I98commerce\Prodfaqs\Controller\Index;

use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;

class Faqajax extends \Magento\Framework\App\Action\Action
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;
    /**
     * @var JsonFactory
     */
    protected $jsonFactory;

    /**
     * Desc
     *
     * @param Context $context
     * @param JsonFactory $jsonFactory
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        JsonFactory $jsonFactory,
        PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->jsonFactory = $jsonFactory;
        parent::__construct($context);
    }

    /**
     * Desc
     *
     * @return ResponseInterface|Json|ResultInterface
     */
    public function execute()
    {
        $resultJson = $this->jsonFactory->create();
        $params = $this->getRequest()->getParams();

        $values[] = $params['value'];
        $values[] = $params['proid'];
        $values[] = $params['sortbyarrow'];

        $data= $this->_view->getLayout()
                     ->createBlock("I98commerce\Prodfaqs\Block\Product\Faqs")
                     ->setTemplate("I98commerce_Prodfaqs::faqsajax.phtml")
                     ->setData('sort_type', $values)
                     ->toHtml();
        return  $resultJson->setData(['Data' => $data]);
    }
}
