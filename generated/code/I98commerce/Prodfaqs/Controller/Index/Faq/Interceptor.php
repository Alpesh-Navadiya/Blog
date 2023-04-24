<?php
namespace I98commerce\Prodfaqs\Controller\Index\Faq;

/**
 * Interceptor class for @see \I98commerce\Prodfaqs\Controller\Index\Faq
 */
class Interceptor extends \I98commerce\Prodfaqs\Controller\Index\Faq implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Framework\View\Result\PageFactory $resultPageFactory, \I98commerce\Prodfaqs\Model\Topic $topicModel, \I98commerce\Prodfaqs\Model\Faqs $faqsModel, \Magento\Framework\Registry $registry, \I98commerce\Prodfaqs\Helper\Data $helper)
    {
        $this->___init();
        parent::__construct($context, $resultPageFactory, $topicModel, $faqsModel, $registry, $helper);
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
