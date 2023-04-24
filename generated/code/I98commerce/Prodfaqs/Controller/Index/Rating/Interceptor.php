<?php
namespace I98commerce\Prodfaqs\Controller\Index\Rating;

/**
 * Interceptor class for @see \I98commerce\Prodfaqs\Controller\Index\Rating
 */
class Interceptor extends \I98commerce\Prodfaqs\Controller\Index\Rating implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Framework\Controller\Result\JsonFactory $jsonFactory, \I98commerce\Prodfaqs\Model\Faqs $faqsModel, \Magento\Customer\Model\Session $customerSession)
    {
        $this->___init();
        parent::__construct($context, $jsonFactory, $faqsModel, $customerSession);
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
