<?php
namespace I98commerce\Prodfaqs\Controller\Adminhtml\Faqs\ExportPost;

/**
 * Interceptor class for @see \I98commerce\Prodfaqs\Controller\Adminhtml\Faqs\ExportPost
 */
class Interceptor extends \I98commerce\Prodfaqs\Controller\Adminhtml\Faqs\ExportPost implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\App\Response\Http\FileFactory $fileFactory, \I98commerce\Prodfaqs\Model\ResourceModel\Faqs\Collection $faqsCollection)
    {
        $this->___init();
        parent::__construct($context, $fileFactory, $faqsCollection);
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
