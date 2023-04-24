<?php
namespace I98commerce\Prodfaqs\Controller\Adminhtml\Faqs\Filters;

/**
 * Interceptor class for @see \I98commerce\Prodfaqs\Controller\Adminhtml\Faqs\Filters
 */
class Interceptor extends \I98commerce\Prodfaqs\Controller\Adminhtml\Faqs\Filters implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Ui\Api\BookmarkRepositoryInterface $bookmarkRepository, \Magento\Ui\Api\BookmarkManagementInterface $bookmarkManagement, \Magento\Ui\Api\Data\BookmarkInterfaceFactory $bookmarkFactory, \Magento\Authorization\Model\UserContextInterface $userContext, \Magento\Framework\Json\DecoderInterface $jsonDecoder, \Magento\Framework\Json\EncoderInterface $jsonEncoder, \Magento\Catalog\Model\Product $product)
    {
        $this->___init();
        parent::__construct($context, $bookmarkRepository, $bookmarkManagement, $bookmarkFactory, $userContext, $jsonDecoder, $jsonEncoder, $product);
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
