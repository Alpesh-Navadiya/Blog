<?php

declare(strict_types=1);

namespace Know\Grid\Controller\Adminhtml\Index;

use Know\Grid\Model\PostFactory;
use Know\Grid\Model\ResourceModel\Post as PostResource;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Framework\View\Result\PageFactory;

class Save extends Action
{

    public function __construct(
        Context     $context,
        PageFactory $resultPageFactory,
        PostFactory $PostFactory,
        private PostResource $resource,
        Validator   $formKeyValidator
    )
    {
        $this->resultPageFactory = $resultPageFactory;
        $this->PostFactory = $PostFactory;
        $this->formKeyValidator = $formKeyValidator;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultPageFactory = $this->resultRedirectFactory->create();
        if (!$this->formKeyValidator->validate($this->getRequest())) {
            $this->messageManager->addErrorMessage(__("Form key is Invalidate"));
            return $resultPageFactory->setPath('*/*/index');
        }
        $data = $this->getRequest()->getPostValue();
        try {
            if ($data) {
                $model = $this->PostFactory->create();
                $model->setData($data);
                $this->resource->save($model);
                $this->messageManager->addSuccessMessage(__("Data Saved Successfully."));
                $buttondata = $this->getRequest()->getParam('back');
                if ($buttondata == 'add') {
                    return $resultPageFactory->setPath('*/*/form');
                }
                if ($buttondata == 'close') {
                    return $resultPageFactory->setPath('*/*/index');
                }
                $id = $model->getId();

                return $resultPageFactory->setPath('*/*/form', ['id' => $id]);
            }
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e, __("We can't submit your request, Please try again."));
        }
        return $resultPageFactory->setPath('*/*/index');
    }
}

