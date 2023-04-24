<?php
declare(strict_types=1);

namespace Know\Grid\Ui\Component\Listing\Column;

use Magento\Framework\Escaper;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

class Actions extends Column
{
    const URL_PATH_EDIT = 'kgrid/index/form';
    const URL_PATH_DELETE = 'kgrid/index/delete';

    /**
     * PostActions constructor.
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param Escaper $escaper
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface     $context,
        UiComponentFactory   $uiComponentFactory,
        private UrlInterface $urlBuilder,
        private Escaper      $escaper,
        array                $components = [],
        array                $data = []
    )
    {
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource): array
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                if (isset($item['g_id'])) {
                    $name = $this->getData('name');
                    $item[$name]['edit'] = [
                        'href' => $this->getEditUrl($item),
                        'label' => __('Edit')
                    ];
                    $title = $this->escaper->escapeHtml($item['title']);
                    $item[$name]['delete'] = [
                        'href' => $this->getDeleteUrl($item),
                        'label' => __('Delete'),
                        'confirm' => [
                            'title' => __('Delete %1', $title),
                            'message' => __('Are you sure you want to delete a %1?', $title)
                        ],
                        'post' => true
                    ];
                }
            }
        }

        return $dataSource;
    }

    /**
     * @param array $item
     * @return string
     */
    private function getEditUrl(array $item): string
    {
        return $this->urlBuilder->getUrl(self::URL_PATH_EDIT, ['g_id' => $item['g_id']]);
    }

    /**
     * @param array $item
     * @return string
     */
    private function getDeleteUrl(array $item): string
    {
        return $this->urlBuilder->getUrl(self::URL_PATH_DELETE, ['g_id' => $item['g_id']]);
    }
}
