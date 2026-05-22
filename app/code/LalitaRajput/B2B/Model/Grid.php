<?php

/**
 * Grid Grid Model.
 * @category  Webkul
 * @package   Webkul_Grid
 * @author    Webkul
 * @copyright Copyright (c) 2010-2017 Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
namespace LalitaRajput\B2B\Model;

use LalitaRajput\B2B\Api\Data\GridInterface;

class Grid extends \Magento\Framework\Model\AbstractModel implements GridInterface
{
    /**
     * CMS page cache tag.
     */
    const CACHE_TAG = 'sales_order_grid';

    /**
     * @var string
     */
    protected $_cacheTag = 'sales_order_grid';

    /**
     * Prefix of model events names.
     *
     * @var string
     */
    protected $_eventPrefix = 'sales_order_grid';

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init('LalitaRajput\B2B\Model\ResourceModel\Grid', 'Magento\Sales\Model\ResourceModel\Order');
    }
    /**
     * Get EntityId.
     *
     * @return int
     */
    public function getEntityId()
    {
        return $this->getData(self::ENTITY_ID);
    }

    /**
     * Set EntityId.
     */
    public function setEntityId($entityId)
    {
        return $this->setData(self::ENTITY_ID, $entityId);
    }

    /**
     * Get getContent.
     *
     * @return varchar
     */
    public function getStatus()
    {
        return $this->getData(self::STATUS);
    }

    /**
     * Set Content.
     */
    public function setStatus($status)
    {
        return $this->setData(self::STATUS, $status);
    }

    /**
     * Get PublishDate.
     *
     * @return varchar
     */
    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * Set PublishDate.
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    /**
     * Get IsActive.
     *
     * @return varchar
     */
    public function getCustomerFirstname()
    {
        return $this->getData(self::CUSTOMER_FIRSTNAME);
    }

    /**
     * Set IsActive.
     */
    public function setCustomerFirstname($CustomerFirstname)
    {
        return $this->setData(self::CUSTOMER_FIRSTNAME, $CustomerFirstname);
    }

    /**
     * Get UpdateTime.
     *
     * @return varchar
     */
    public function getBaseGrandTotal()
    {
        return $this->getData(self::BASE_GRAND_TOTAL);
    }

    /**
     * Set UpdateTime.
     */
    public function setBaseGrandTotal($BaseGrandTotal)
    {
        return $this->setData(self::BASE_GRAND_TOTAL, $BaseGrandTotal);
    }

    /**
     * Get CreatedAt.
     *
     * @return varchar
     */
    public function getBaseTaxAmount()
    {
        return $this->getData(self::BASE_TAX_AMOUNT);
    }

    /**
     * Set CreatedAt.
     */
    public function setBaseTaxAmount($baseTaxAmount)
    {
        return $this->setData(self::BASE_TAX_AMOUNT, $baseTaxAmount);
    }
}
