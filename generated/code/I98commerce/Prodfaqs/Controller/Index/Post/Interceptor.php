<?php
namespace I98commerce\Prodfaqs\Controller\Index\Post;

/**
 * Interceptor class for @see \I98commerce\Prodfaqs\Controller\Index\Post
 */
class Interceptor extends \I98commerce\Prodfaqs\Controller\Index\Post implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Framework\Controller\Result\JsonFactory $jsonFactory, \I98commerce\Prodfaqs\Model\Faqs $faqsModel, \I98commerce\Prodfaqs\Model\Topic $topicModel, \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig, \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Framework\Module\Dir\Reader $moduleReader, \Magento\Framework\HTTP\PhpEnvironment\RemoteAddress $remoteAddress)
    {
        $this->___init();
        parent::__construct($context, $jsonFactory, $faqsModel, $topicModel, $scopeConfig, $transportBuilder, $storeManager, $moduleReader, $remoteAddress);
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
