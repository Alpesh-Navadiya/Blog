<?php
/**
 *
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace I98commerce\Prodfaqs\Controller\Adminhtml\Topic;

use I98commerce\Prodfaqs\Model\Topic;

class Delete extends \Magento\Backend\App\Action
{
    /**
     * Short desc
     *
     * @var Topic
     */
    protected $_topic;

    /**
     * Short desc
     *
     * @param Topic $topic
     */
    public function __construct(\I98commerce\Prodfaqs\Model\Topic $topic)
    {
        $this->_topic = $topic;
    }

    /**
     * Short desc
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('I98commerce_Prodfaqs::topic');
    }

    /**
     * Delete action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        // check if we know what should be deleted
        $id = $this->getRequest()->getParam('faqs_topic_id');
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            $title = "";
            try {
                // init model and delete
                $model = $this->_topic->load($id);
                $title = $model->getTitle();
                $model->delete();
                // display success message
                $this->messageManager->addSuccess(__('The topic has been deleted.'));
                // go to grid
                $this->_eventManager->dispatch(
                    'adminhtml_faqstopic_on_delete',
                    ['title' => $title, 'status' => 'success']
                );
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->_eventManager->dispatch(
                    'adminhtml_faqstopic_on_delete',
                    ['title' => $title, 'status' => 'fail']
                );
                // display error message
                $this->messageManager->addError($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['faqs_topic_id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addError(__('We can\'t find a topic to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}
