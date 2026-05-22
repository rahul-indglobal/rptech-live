<?php

namespace Company\Cms\Block\Html;

class Topmenu extends \Magento\Theme\Block\Html\Topmenu {

	public function getHtml($outermostClass = '', $childrenWrapClass = '', $limit = 0) {
		$this->_eventManager->dispatch(
			'page_block_html_topmenu_gethtml_before', ['menu' => $this->getMenu(), 'block' => $this, 'request' => $this->getRequest()]
		);

		$this->getMenu()->setOutermostClass($outermostClass);
		$this->getMenu()->setChildrenWrapClass($childrenWrapClass);

		$html = $this->_getHtml($this->getMenu(), $childrenWrapClass, $limit);

		$transportObject = new \Magento\Framework\DataObject(['html' => $html]);
		$this->_eventManager->dispatch(
			'page_block_html_topmenu_gethtml_after', ['menu' => $this->getMenu(), 'transportObject' => $transportObject]
		);
		$html = $transportObject->getHtml();
		return $html;
	}

	/**
	 * Recursively generates top menu html from data that is specified in $menuTree
	 *
	 * @param \Magento\Framework\Data\Tree\Node $menuTree
	 * @param string $childrenWrapClass
	 * @param int $limit
	 * @param array $colBrakes
	 * @return string
	 *
	 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
	 * @SuppressWarnings(PHPMD.NPathComplexity)
	 */
	protected function _getHtml(
	\Magento\Framework\Data\Tree\Node $menuTree, $childrenWrapClass, $limit, $colBrakes = []
	) {
		$html = '';

		$children = $menuTree->getChildren();
		$parentLevel = $menuTree->getLevel();
		$childLevel = $parentLevel === null ? 0 : $parentLevel + 1;

		$counter = 1;
		$itemPosition = 1;
		$childrenCount = $children->count();

		$parentPositionClass = $menuTree->getPositionClass();
		$itemPositionClassPrefix = $parentPositionClass ? $parentPositionClass . '-' : 'nav-';

		/** @var \Magento\Framework\Data\Tree\Node $child */
		foreach ($children as $child) {
			if ($childLevel === 0 && $child->getData('is_parent_active') === false) {
				continue;
			}
			$child->setLevel($childLevel);
			$child->setIsFirst($counter == 1);
			$child->setIsLast($counter == $childrenCount);
			$child->setPositionClass($itemPositionClassPrefix . $counter);

			$outermostClassCode = '';
			$outermostClass = $menuTree->getOutermostClass();

			if ($childLevel == 0 && $outermostClass) {
				$outermostClassCode = ' class="' . $outermostClass . '" ';
				$currentClass = $child->getClass();

				if (empty($currentClass)) {
					$child->setClass($outermostClass);
				} else {
					$child->setClass($currentClass . ' ' . $outermostClass);
				}
			}

			if (count((array)$colBrakes) && $colBrakes[$counter]['colbrake']) {
				$html .= '</ul></li><li class="column"><ul>';
			}

			$html .= '<li ' . $this->_getRenderedMenuItemAttributes($child) . '><div class="m-link">';
			$html .= '<a href="' . $child->getUrl() . '" ' . $outermostClassCode . '><span>' . $this->escapeHtml(
					$child->getName()
				) . '</span></a></div>' . $this->_addSubMenu(
					$child, $childLevel, $childrenWrapClass, $limit
				) . '</li>';
			$itemPosition++;
			$counter++;
		}

		if (count((array)$colBrakes) && $limit) {
			$html = '<li class="column"><ul>' . $html . '</ul></li>';
		}

		return $html;
	}

	/**
	 * Add sub menu HTML code for current menu item
	 *
	 * @param \Magento\Framework\Data\Tree\Node $child
	 * @param string $childLevel
	 * @param string $childrenWrapClass
	 * @param int $limit
	 * @return string HTML code
	 */
	protected function _addSubMenu($child, $childLevel, $childrenWrapClass, $limit) {
		$html = '';
		if (!$child->hasChildren()) {
			return $html;
		}

		$colStops = null;
		if ($childLevel == 0 && $limit) {
			$colStops = $this->_columnBrake($child->getChildren(), $limit);
		}

		$html .= '<ul class="clearfix subcat  level' . $childLevel . ' ' . $childrenWrapClass . '">';
		$html .= $this->_getHtml($child, $childrenWrapClass, $limit, $colStops);
		$html .= '</ul>';

		return $html;
	}

	/**
     * Returns array of menu item's classes
     *
     * @param \Magento\Framework\Data\Tree\Node $item
     * @return array
     */
    protected function _getMenuItemClasses(\Magento\Framework\Data\Tree\Node $item)
    {
        $classes = [];

        $classes[] = 'level' . $item->getLevel();
        $classes[] = $item->getPositionClass();

	$classes = ['menu-item'];

        if ($item->getIsCategory()) {
            $classes[] = 'category-item';
        }

        if ($item->getIsFirst()) {
            $classes[] = 'first';
        }

        if ($item->getIsActive()) {
            $classes[] = 'active';
        } elseif ($item->getHasActive()) {
            $classes[] = 'has-active';
        }

        if ($item->getIsLast()) {
            $classes[] = 'last';
        }

        if ($item->getClass()) {
            $classes[] = $item->getClass();
        }

        if ($item->hasChildren()) {
            $classes[] = 'parent dropdown-item';
        }

        return $classes;
    }

}
