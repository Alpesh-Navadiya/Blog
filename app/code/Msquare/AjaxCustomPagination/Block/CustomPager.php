<?php
/**
 * @category Msquare AjaxCustomPagination
 * @package Msquare_AjaxCustomPagination
 * @author Mahesh Makwana <maheshmakwana588@gmail.com>
 */

namespace Msquare\AjaxCustomPagination\Block;

class CustomPager extends \Magento\Theme\Block\Html\Pager
{
    /**
     * Current template name
     *
     * @var string
     */
    protected $_template = 'Msquare_AjaxCustomPagination::html/custompager.phtml';

    /**
     * Return page limit params
     *
     * @param int $limit
     * @return array
     */
    private function getPageLimitParams(int $limit): array
    {
        $data = [$this->getLimitVarName() => $limit];

        $currentPage = $this->getCurrentPage();
        $availableCount = (int) ceil($this->getTotalNum() / $limit);
        if ($currentPage !== 1 && $availableCount < $currentPage) {
            $data = array_merge($data, [$this->getPageVarName() => $availableCount === 1 ? null : $availableCount]);
        }

        return $data;
    }

    /**
     * Retrieve page URL by defined parameters
     *
     * @param array $params
     *
     * @return string
     */
    public function getPagerUrl($params = [])
    {
        $urlParams = [];
        $urlParams['_current'] = true;
        $urlParams['_escape'] = true;
        $urlParams['_fragment'] = $this->getFragment();
        $urlParams['_query'] = $params;

        return $this->getUrl($this->getPath(), $urlParams);
    }

    /**
     * Get path
     *
     * @return string
     */
    protected function getPath()
    {
        return "custom/index/ajaxdata";
    }
}
