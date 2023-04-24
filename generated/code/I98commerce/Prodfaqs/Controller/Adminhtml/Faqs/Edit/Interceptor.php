<?php
namespace I98commerce\Prodfaqs\Controller\Adminhtml\Faqs\Edit;

/**
 * Interceptor class for @see \I98commerce\Prodfaqs\Controller\Adminhtml\Faqs\Edit
 */
class Interceptor extends \I98commerce\Prodfaqs\Controller\Adminhtml\Faqs\Edit implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\View\Result\PageFactory $resultPageFactory, \Magento\Framework\Registry $registry, \I98commerce\Prodfaqs\Model\Faqs $faqs, \Magento\Backend\Model\Session $backendSession)
    {
        $this->___init();
        parent::__construct($context, $resultPageFactory, $registry, $faqs, $backendSession);
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'execute');
        return $pluginInfo ? $this->___callPlugins('execute', func_get_args(), $pluginInfo) : parent::execute();
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch(\Magento\Framework\App\RequestInterface $request)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'dispatch');
        return $pluginInfo ? $this->___callPlugins('dispatch', func_get_args(), $pluginInfo) : parent::dispatch($request);
    }
}
