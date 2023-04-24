<?php
namespace I98commerce\Prodfaqs\Controller\Adminhtml\Topic\Delete;

/**
 * Interceptor class for @see \I98commerce\Prodfaqs\Controller\Adminhtml\Topic\Delete
 */
class Interceptor extends \I98commerce\Prodfaqs\Controller\Adminhtml\Topic\Delete implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\I98commerce\Prodfaqs\Model\Topic $topic)
    {
        $this->___init();
        parent::__construct($topic);
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
