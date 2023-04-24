<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace I98commerce\Prodfaqs\Block\Adminhtml\Faqs\Edit\Tab;

use I98commerce\Prodfaqs\Model\Topic as TopicModel;
use Magento\Backend\Block\Template\Context;
use Magento\Cms\Model\Wysiwyg\Config;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Registry;
use Magento\Store\Model\System\Store;

/**
 * Cms page edit form main tab
 */
class Form extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    /**
     * Short desc
     *
     * @var Config
     */
    protected $_wysiwygConfig;

    /**
     * Short desc
     *
     * @var TopicModel
     */
    protected $_topicModel;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param Store $systemStore
     * @param Config $wysiwygConfig
     * @param TopicModel $topicModel
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig,
        TopicModel $topicModel,
        array $data = []
    ) {
        $this->_systemStore = $systemStore;
        $this->_wysiwygConfig = $wysiwygConfig;
        $this->_topicModel = $topicModel;

        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form
     *
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareForm()
    {
        /* @var $model \Magento\Cms\Model\Page */
        $model = $this->_coreRegistry->registry('faqs');

        /*
         * Checking if user have permissions to save information
         */
        if ($this->_isAllowedAction('I98commerce_Prodfaqs::faqs')) {
            $isElementDisabled = false;
        } else {
            $isElementDisabled = true;
        }

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('faqs_');

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Faqs Information')]);

        if ($model->getId()) {
            $fieldset->addField('faq_id', 'hidden', ['name' => 'faq_id']);
        }

        $fieldset->addField(
            'title',
            'text',
            [
                'name' => 'title',
                'label' => __('Question'),
                'title' => __('Question'),
                'required' => true,
                'disabled' => $isElementDisabled
            ]
        );

        $fieldset->addField(
            'faq_answer',
            'textarea',
            [
                'name' => 'faq_answer',
                'label' => __('Answer'),
                'title' => __('Answer'),
                'required' => true,
                'disabled' => $isElementDisabled
            ]
        );

        $fieldset->addField(
            'identifier',
            'text',
            [
                'name' => 'identifier',
                'label' => __('Identifier'),
                'title' => __('Faq Identifier'),
                'required' => true,
                'disabled' => $isElementDisabled
            ]
        );

        $fieldset->addField(
            'faqs_topic_id',
            'select',
            [
                'label' => __('Select Topic'),
                'title' => __('Select Topic'),
                'name' => 'faqs_topic_id',
                'required' => true,
                'options' => $this->_topicModel->getTopicsList(),
                'disabled' => $isElementDisabled
            ]
        );

        $fieldset->addField(
            'tags',
            'text',
            [
                'label' => __('Tags'),
                'title' => __('Tags'),
                'name'  => 'tags',
                'required'  => false,
                'disabled' => $isElementDisabled,
                'note'  => __('Separate tags with commas')
            ]
        );

        $fieldset->addField(
            'show_on_main',
            'select',
            [
                'name' => 'show_on_main',
                'label' => __('Show on main page'),
                'title' => __('Show on main page'),
                'class' => '',
                'options' => ['0' => 'No', '1' => 'Yes'],
                'disabled' => $isElementDisabled
            ]
        );

        $fieldset->addField(
            'faq_order',
            'text',
            [
                'name' => 'faq_order',
                'label' => __('Faq Order'),
                'title' => __('Faq Order'),
                'required' => false,
                'disabled' => $isElementDisabled
            ]
        );

        $fieldset->addField(
            'status',
            'select',
            [
                'label' => __('Change Status'),
                'title' => __('Change Status'),
                'name' => 'status',
                'required' => false,
                'options' => $model->getAvailableStatuses(),
                'disabled' => $isElementDisabled
            ]
        );

        $this->_eventManager->dispatch('adminhtml_faqsfaqs_edit_tab_main_prepare_form', ['form' => $form]);

        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('Faqs Information');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Faqs Information');
    }

    /**
     * Short desc
     *
     * @return true
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Short desc
     *
     * @return false
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }
}
