<?php
namespace Know\Grid\Controller\Adminhtml\Index\Delete;

/**
 * Interceptor class for @see \Know\Grid\Controller\Adminhtml\Index\Delete
 */
class Interceptor extends \Know\Grid\Controller\Adminhtml\Index\Delete implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Know\Grid\Model\ResourceModel\Post $resource, \Know\Grid\Model\PostFactory $postFactory)
    {
        $this->___init();
        parent::__construct($context, $resource, $postFactory);
    }

    /**
     * {@inheritdoc}
     */
    public function execute() : \Magento\Framework\Controller\ResultInterface
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
