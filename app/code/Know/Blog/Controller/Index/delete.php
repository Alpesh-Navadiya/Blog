<?php
namespace Know\Blog\Controller\Index;

use Magento\Framework\App\Action\Action;

class delete extends Action
{
    protected $_pageFactory;
    protected $_request;
    protected $_modelFactory;
    protected $resource;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \Magento\Framework\App\Request\Http $request,
        \Know\Blog\Model\PostFactory $modelFactory,
        \Know\Blog\Model\ResourceModel\Post $resource,

    ) {
        $this->_pageFactory = $pageFactory;
        $this->_request = $request;
        $this->_modelFactory = $modelFactory;
        $this->resource = $resource;
        return parent::__construct($context);
    }

    public function execute()
    {
        try {
            $postId = $this->_request->getParam('id');
            $model = $this->_modelFactory->create();
            $this->resource->load($model, $postId);
            $this->resource->delete($model);

//            $result = $postData->setId($id);
//            $result = $result->delete();
            $this->messageManager->addSuccessMessage(__("Record deleted successfully..."));
        } catch (\Exception $e) {
            dd($e);
        }
        // return $this->_redirect('superhero/index/index');
    }
}
