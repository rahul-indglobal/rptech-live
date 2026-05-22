<?php
/**
 * Grid GridInterface.
 * @category  Webkul
 * @package   Webkul_Grid
 * @author    Webkul
 * @copyright Copyright (c) 2010-2017 Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */

namespace LalitaRajput\B2B\Api\Data;

interface GridInterface
{
    /**
     * Constants for keys of data array. Identical to the name of the getter in snake case.
     */
    const ENTITY_ID = 'entity_id';
    const STATUS = 'status';
    const CUSTOMER_FIRSTNAME = 'customer_firstname';
    const BASE_GRAND_TOTAL = 'base_grand_total';
    const BASE_TAX_AMOUNT = 'base_tax_amount';
    const CREATED_AT = 'created_at';

   /**
    * Get EntityId.
    *
    * @return int
    */
    public function getEntityId();

   /**
    * Set EntityId.
    */
    public function setEntityId($entityId);


   /**
    * Get Status.
    *
    * @return varchar
    */
    public function getStatus();

   /**
    * Set Status.
    */
    public function setStatus($status);

   /**
    * Get Publish Date.
    *
    * @return varchar
    */
    public function getCustomerFirstname();

   /**
    * Set PublishDate.
    */
    public function setCustomerFirstname($CustomerFirstname);

   /**
    * Get IsActive.
    *
    * @return varchar
    */
    public function getBaseGrandTotal();

   /**
    * Set StartingPrice.
    */
    public function setBaseGrandTotal($BaseGrandTotal);

   /**
    * Get UpdateTime.
    *
    * @return varchar
    */
    public function getBaseTaxAmount();

   /**
    * Set UpdateTime.
    */
    public function setBaseTaxAmount($baseTaxAmount);

   /**
    * Get CreatedAt.
    *
    * @return varchar
    */
    public function getCreatedAt();

   /**
    * Set CreatedAt.
    */
    public function setCreatedAt($createdAt);
}
