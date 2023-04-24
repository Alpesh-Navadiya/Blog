<?php
namespace I98commerce\Prodfaqs\Controller\Router;

/**
 * Interceptor class for @see \I98commerce\Prodfaqs\Controller\Router
 */
class Interceptor extends \I98commerce\Prodfaqs\Controller\Router implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\ActionFactory $actionFactory, \Magento\Framework\Event\ManagerInterface $eventManager, \Magento\Framework\UrlInterface $url, \I98commerce\Prodfaqs\Model\TopicFactory $topicFactory, \I98commerce\Prodfaqs\Model\FaqsFactory $faqsFactory, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Framework\App\ResponseInterface $response)
    {
        $this->___init();
        parent::__construct($actionFactory, $eventManager, $url, $topicFactory, $faqsFactory, $storeManager, $response);
    }

    /**
     * {@inheritdoc}
     */
    public function match(\Magento\Framework\App\RequestInterface $request)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'match');
        return $pluginInfo ? $this->___callPlugins('match', func_get_args(), $pluginInfo) : parent::match($request);
    }
}
