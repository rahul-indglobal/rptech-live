<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Company\Cms\Block\Admin\Dashboard;

/**
 * Adminhtml dashboard bottom tabs
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Grids extends \Magento\Backend\Block\Widget\Tabs
{
    /**
     * @var string
     */
    protected $_template = 'Magento_Backend::widget/tabshoriz.phtml';

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('grid_tab');
        $this->setDestElementId('grid_tab_content');
    }

    /**
     * Prepare layout for dashboard bottom tabs
     *
     * To load block statically:
     *     1) content must be generated
     *     2) url should not be specified
     *     3) class should not be 'ajax'
     * To load with ajax:
     *     1) do not load content
     *     2) specify url (BE CAREFUL)
     *     3) specify class 'ajax'
     *
     * @return $this
     */
    protected function _prepareLayout()
    {
        // load this active tab statically
        $this->addTab(
            'ordered_products',
            [
                'label' => __('Bestsellers'),
                'content' => $this->getLayout()->createBlock(\Magento\Backend\Block\Dashboard\Tab\Products\Ordered::class)->toHtml(),
                'active' => true
            ]
        );

        // load other tabs with ajax
        $this->addTab(
            'reviewed_products',
            [
                'label' => __('Most Viewed Products'),
                'url' => $this->getUrl('adminhtml/*/productsViewed', ['_current' => true]),
                'class' => 'ajax'
            ]
        );

        $this->addTab(
            'new_customers',
            [
                'label' => __('New Customers'),
                'url' => $this->getUrl('adminhtml/*/customersNewest', ['_current' => true]),
                'class' => 'ajax'
            ]
        );

        $this->addTab(
            'customers',
            [
                'label' => __('B2C Customers'),
                'url' => $this->getUrl('adminhtml/*/customersMost', ['_current' => true]),
                'class' => 'ajax'
            ]
        );
//         $this->addTab(
//            'B2B_customers',
//            [
//                 'label' => __('B2B Customers'),
//                'title' => __('B2B'),
//                'content' => $this->getdatab2b()
//            ]
//        );

        return parent::_prepareLayout();
    }
    
     public function getdatab2b(){

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of object manager
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();
        $tableName = $resource->getTableName('customer_entity'); //gives table name with prefix

        $sql = "SELECT concat(c.firstname,' ',c.lastname) as customer_name,COUNT(s.customer_id) as Orders,AVG(s.base_subtotal) as Average,SUM(s.base_subtotal) as Total FROM $tableName c left join sales_order s on c.entity_id = s.customer_id where c.group_id = 4 group by s.customer_id,c.entity_id LIMIT 5";
        $result = $connection->fetchAll($sql); // gives associated array, table fields as key in array.
        
        $b2bdata = "";
        $b2bdata .= "<table class='admin__table-primary dashboard-data'> 
            <thead>
                <tr>
                    <th class='data-grid-th  no-link col-name'>B2B Customer</td>
                    <th class='data-grid-th col-orders no-link col-orders_count'>Orders</th>
                    <th class='data-grid-th col-avg no-link col-orders_avg_amount'>Average</th>
                    <th class='data-grid-th col-total no-link col-orders_sum_amount'>Total</th>
                </tr>
            </thead>";
            if(!empty($result)){
                foreach ($result as $key => $value) {
                    $orders = !empty($value['Orders'] ) ? $value['Orders'] : "";
                    $avg = !empty($value['Average'] ) ? "&#x20B9;".round($value['Average']) : "";
                    $total = !empty($value['Total'] ) ? "&#x20B9;".round($value['Total']) : "";
                    $b2bdata .= "<tr>
                        <td class=' col-name '>". $value['customer_name']."</td>
                        <td class=' col-orders col-orders_count col-number '>".$orders."</td>
                        <td class=' col-avg col-orders_avg_amount a-right '>".$avg."</td>
                        <td class=' col-total col-orders_sum_amount a-right last'>".$total."</td>
                    </tr>";
                }
            }else{
                $b2bdata .= "<tr>
                    <td>'We couldn\'t find any record'</td>
                </tr>";   
            }
            
        $b2bdata .= "</table>";

        return $b2bdata;
        
    }
}
