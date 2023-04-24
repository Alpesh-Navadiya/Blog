<?php
namespace I98commerce\Prodfaqs\Block\Adminhtml\Faqs\Edit\Tab\Form;

/**
 * Interceptor class for @see \I98commerce\Prodfaqs\Block\Adminhtml\Faqs\Edit\Tab\Form
 */
class Interceptor extends \I98commerce\Prodfaqs\Block\Adminhtml\Faqs\Edit\Tab\Form implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\Block\Template\Context $context, \Magento\Framework\Registry $registry, \Magento\Framework\Data\FormFactory $formFactory, \Magento\Store\Model\System\Store $systemStore, \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig, \I98commerce\Prodfaqs\Model\Topic $topicModel, array $data = [])
    {
        $this->___init();
        parent::__construct($context, $registry, $formFactory, $systemStore, $wysiwygConfig, $topicModel, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function getForm()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getForm');
        return $pluginInfo ? $this->___callPlugins('getForm', func_get_args(), $pluginInfo) : parent::getForm();
    }
}
