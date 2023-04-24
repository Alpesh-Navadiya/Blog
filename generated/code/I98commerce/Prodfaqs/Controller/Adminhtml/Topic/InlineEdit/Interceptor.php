<?php
namespace I98commerce\Prodfaqs\Controller\Adminhtml\Topic\InlineEdit;

/**
 * Interceptor class for @see \I98commerce\Prodfaqs\Controller\Adminhtml\Topic\InlineEdit
 */
class Interceptor extends \I98commerce\Prodfaqs\Controller\Adminhtml\Topic\InlineEdit implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \I98commerce\Prodfaqs\Controller\Adminhtml\Topic\PostDataProcessor $dataProcessor, \I98commerce\Prodfaqs\Model\Topic $TopicModel, \Magento\Framework\Controller\Result\JsonFactory $jsonFactory)
    {
        $this->___init();
        parent::__construct($context, $dataProcessor, $TopicModel, $jsonFactory);
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
