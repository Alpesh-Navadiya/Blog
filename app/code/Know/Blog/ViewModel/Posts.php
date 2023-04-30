<?php

namespace Know\Blog\ViewModel;

use Know\Blog\Model\ResourceModel\Post\Collection;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\DB\Select;
use Magento\Framework\Webapi\Rest\Request;

class Posts implements ArgumentInterface
{
    /**
     * @var \Know\Blog\Model\Post
     */
    protected $customFactory;
    /**
     * @var Collection
     */
    private $collection;


    /**
     * @param Collection $collection
     */
    public function __construct(
        \Magento\Framework\App\Request\Http $request,

        Collection       $collection,
    )
    {
        //$this->customFactory = $customFactory;
        $this->collection = $collection;
        $this->request = $request;

        //parent::__construct($context, $data);
    }

    /**
     * @return Collection
     */
//    public function getAllSuperHeroes()
//    {
//        $coll = $this->collection;
//        $coll->setOrder('creation_time', Select::SQL_DESC);
//
//        $coll->setPageSize(3);
//        return $coll;
//    }

    public function getCustomData()
    {
        $page = ($this->request->getParam('p')) ? $this->request->getParam('p') : 1;
        $pageSize = ($this->request->getParam('limit')) ? $this->request->getParam('limit') : 5;
        $collection = $this->collection;
        $collection->setPageSize($pageSize);
        $collection->setCurPage($page);
        return $collection;
    }



}
