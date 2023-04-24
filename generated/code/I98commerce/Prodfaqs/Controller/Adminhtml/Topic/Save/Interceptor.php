<?php
namespace I98commerce\Prodfaqs\Controller\Adminhtml\Topic\Save;

/**
 * Interceptor class for @see \I98commerce\Prodfaqs\Controller\Adminhtml\Topic\Save
 */
class Interceptor extends \I98commerce\Prodfaqs\Controller\Adminhtml\Topic\Save implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor, \Magento\Framework\Escaper $escaper, \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation, \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig, \Magento\Framework\Stdlib\DateTime\DateTimeFactory $dateFactory, \I98commerce\Prodfaqs\Model\Topic $topic)
    {
        $this->___init();
        parent::__construct($context, $dataPersistor, $escaper, $inlineTranslation, $scopeConfig, $dateFactory, $topic);
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
