<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Block\ManageSupplier\Dashboard;

use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Session\SessionManager;

class Sidebar extends \Magento\Framework\View\Element\Template
{

    protected $_links = array();
    protected $_activeLink = false;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param array                                            $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    /**
     * Add link to sidebar
     * @param String $name
     * @param String $path
     * @param String $label
     * @param array  $urlParams
     */
    public function addLink($name, $path, $label, $urlParams = array())
    {
        $this->_links[$name] = new \Magento\Framework\DataObject(array(
            'name' => $name,
            'path' => $path,
            'label' => $label,
            'url' => $this->getUrl($path, $urlParams),
        ));
        return $this;
    }

    /**
     * Set active
     * @param String $path [description]
     */
    public function setActive($path)
    {
        $this->_activeLink = $this->_completePath($path);
        return $this;
    }

    /**
     * Get links
     * @return Object
     */
    public function getLinks()
    {
        return $this->_links;
    }

    /**
     * Check is active or not
     * @param  String  $link
     * @return boolean
     */
    public function isActive($link)
    {
        if (empty($this->_activeLink)) {
            $a = $this->getRequest()->getFullActionName('/');
        }
        if ($this->_completePath($link->getPath()) == $a) {
            return true;
        }
        return false;
    }

    /**
     * complete path
     * @param  String $path
     * @return String
     */
    protected function _completePath($path)
    {
        $path = rtrim($path, '/');
        switch (sizeof(explode('/', $path))) {
            case 1:
                $path .= '/index';
                // no break

            case 2:
                $path .= '/index';
        }
        return $path;
    }
}
