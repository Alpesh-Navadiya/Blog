<?php
namespace I98commerce\Prodfaqs\Controller\Adminhtml\Faqs\ImportPost;

/**
 * Interceptor class for @see \I98commerce\Prodfaqs\Controller\Adminhtml\Faqs\ImportPost
 */
class Interceptor extends \I98commerce\Prodfaqs\Controller\Adminhtml\Faqs\ImportPost implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\App\Response\Http\FileFactory $fileFactory, \Magento\TaxImportExport\Model\Rate\CsvImportHandler $CsvImportHandler)
    {
        $this->___init();
        parent::__construct($context, $fileFactory, $CsvImportHandler);
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
