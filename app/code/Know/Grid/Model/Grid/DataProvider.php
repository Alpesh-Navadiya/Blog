<?php
namespace Know\Grid\Model\Grid;
use Know\Grid\Model\ResourceModel\Post\CollectionFactory;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    protected $loadedData;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $faqCollectionFactory,
        array $meta = [],
        array $data = []
    )
    {
        $this->collection = $faqCollectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();
        foreach ($items as $faqData) {
            $this->loadedData[$faqData->getData('g_id')] = $faqData->getData();
        }
//        echo "<pre>";
       //dd($this->loadedData);
//        exit();
        return $this->loadedData;
    }
}
