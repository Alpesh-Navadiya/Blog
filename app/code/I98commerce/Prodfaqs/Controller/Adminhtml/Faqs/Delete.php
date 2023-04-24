<?php
/**
 *
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace I98commerce\Prodfaqs\Controller\Adminhtml\Faqs;

use I98commerce\Prodfaqs\Model\Faqs;

class Delete extends \Magento\Backend\App\Action
{
    /**
     * @var Faqs
     */
    protected $_faqs;

    /**
     * @param Faqs $faqs
     */
    public function __construct(\I98commerce\Prodfaqs\Model\Faqs $faqs)
    {
        $this->_faqs = $faqs;
    }

    /**
     * Short desc
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('I98commerce_Prodfaqs::faqs');
    }

    /**
     * Delete action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        // check if we know what should be deleted
        $id = $this->getRequest()->getParam('faq_id');
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            $title = "";
            try {
                // init model and delete
                $model = $this->_faqs->load($id);

                $title = $model->getTitle();
                $model->delete();
                // display success message
                $this->messageManager->addSuccess(__('The faq has been deleted.'));
                // go to grid
                $this->_eventManager->dispatch(
                    'adminhtml_faqsfaqs_on_delete',
                    ['title' => $title, 'status' => 'success']
                );
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->_eventManager->dispatch(
                    'adminhtml_faqsfaqs_on_delete',
                    ['title' => $title, 'status' => 'fail']
                );
                // display error message
                $this->messageManager->addError($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['faq_id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addError(__('We can\'t find a faq to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}
