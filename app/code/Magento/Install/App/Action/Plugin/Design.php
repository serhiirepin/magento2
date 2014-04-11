<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @copyright   Copyright (c) 2014 X.commerce, Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
namespace Magento\Install\App\Action\Plugin;

use Magento\App\RequestInterface;

class Design
{
    /**
     * @var \Magento\App\RequestInterface
     */
    protected $_request;

    /**
     * @var \Magento\App\AreaList
     */
    protected $_areaList;

    /**
     * @var \Magento\App\State
     */
    protected $appState;

    /**
     * @var \Magento\View\Design\Theme\ListInterface
     */
    protected $_themeList;

    /**
     * @var \Magento\View\DesignInterface
     */
    protected $_viewDesign;

    /**
     * @param RequestInterface $request
     * @param \Magento\App\AreaList $areaList
     * @param \Magento\App\State $appState
     * @param \Magento\View\DesignInterface $viewDesign
     * @param \Magento\View\Design\Theme\ListInterface $themeList
     */
    public function __construct(
        \Magento\App\RequestInterface $request,
        \Magento\App\AreaList $areaList,
        \Magento\App\State $appState,
        \Magento\View\DesignInterface $viewDesign,
        \Magento\View\Design\Theme\ListInterface $themeList
    ) {
        $this->_viewDesign = $viewDesign;
        $this->_themeList = $themeList;
        $this->_request = $request;
        $this->_areaList = $areaList;
        $this->appState = $appState;
    }

    /**
     * Initialize design
     *
     * @param \Magento\Install\Controller\Action $subject
     * @param RequestInterface $request
     *
     * @return void
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function beforeDispatch(\Magento\Install\Controller\Action $subject, RequestInterface $request)
    {
        $areaCode = $this->appState->getAreaCode();
        $area = $this->_areaList->getArea($areaCode);
        $area->load(\Magento\Core\Model\App\Area::PART_CONFIG);

        $themePath = $this->_viewDesign->getConfigurationDesignTheme($areaCode);
        $themeFullPath = $areaCode . \Magento\View\Design\ThemeInterface::PATH_SEPARATOR . $themePath;
        $themeModel = $this->_themeList->getThemeByFullPath($themeFullPath);
        $this->_viewDesign->setDesignTheme($themeModel);

        $area->detectDesign($this->_request);
        $area->load(\Magento\Core\Model\App\Area::PART_TRANSLATE);
    }
}