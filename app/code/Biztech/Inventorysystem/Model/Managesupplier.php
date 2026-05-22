<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Model;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Exception\ManagesupplierException;

class Managesupplier extends \Magento\Framework\Model\AbstractModel implements IdentityInterface
{

    const CACHE_TAG = 'supplier_products_grid';
    protected $_cacheTag = 'supplier_products_grid';
    protected $_eventPrefix = 'supplier_products_grid';

    /**
     * @param \Magento\Framework\Model\Context                             $context
     * @param \Magento\Framework\Registry                                  $registry
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null           $resourceCollection
     * @param array                                                        $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * @return void
     */
    public function _construct()
    {
        $this->_init('Biztech\Inventorysystem\Model\ResourceModel\Managesupplier');
    }

    /**
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * @param Managesupplier $object
     * @return mixed
     */
    public function getProducts(\Biztech\Inventorysystem\Model\Managesupplier $object)
    {
        $tbl = $this->getResource()->getTable(\Biztech\Inventorysystem\Model\ResourceModel\Managesupplier::TBL_ATT_PRODUCT);
        $select = $this->getResource()->getConnection()->select()->from(
            $tbl,
            ['product_id']
        )
            ->where(
                'supplier_id = ?',
                (int)$object->getId()
            );
        return $this->getResource()->getConnection()->fetchCol($select);
    }

    /**
     * @param Managesupplier $object
     * @return mixed
     */
    public function getSupplierAddress(\Biztech\Inventorysystem\Model\Managesupplier $object)
    {
        $tbl = $this->getResource()->getTable(\Biztech\Inventorysystem\Model\ResourceModel\Managesupplieraddr::TBL_SUPPLIER_ADDRESS);
        $select = $this->getResource()->getConnection()->select()->from(
            $tbl,
            ['*']
        )
            ->where(
                'supplier_id = ?',
                (int)$object->getId()
            );
        return $this->getResource()->getConnection()->fetchCol($select);
    }

    /**
     * @return array
     */
    public function getAllOptions()
    {
        $options = [];
        if (empty($options)) {
            $supplierData = $this->getCollection()->addFieldToFilter('is_active', 1)->getData();

            foreach ($supplierData as $key => $value) {
                $options[] = [
                    'value' => $value['supplier_id'],
                    'label' => $value['first_name'] . ' ' . $value['last_name']
                ];
            }
        }

        return $options;
    }

    /**
     * Confirm supplier details
     * @param  Array $loginDetails
     * @return Array
     */
    public function confirmSupplierDetails($loginDetails)
    {
        $supplierDetails = $this->load($loginDetails['username'], 'email');
        if ($supplierDetails->getEmail() != $loginDetails['username']) {
            return $response = array('response' => __('E-mail does not exist.'));
        } else if (!empty($supplierDetails->getData()) && $supplierDetails->getIsActive() == 0) {
            return $response = array('response' => __('User is not activated by Admin'));
        } else if ($supplierDetails->getPassword() != md5($loginDetails['password'])) {
            return $response = array('response' => __('Invalid Password!'));
        } else if (empty($supplierDetails->getData())) {
            return false;
        } else {
            return $supplierDetails;
        }
    }
}
