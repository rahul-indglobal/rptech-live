<?php
/** Setubridge Technolabs
* http://www.setubridge.com/
* @author SetuBridge
* @license http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
**/
?>
<?php
namespace SetuBridge\Holidaytheme\Model\Config\Source;

class ConfigOption implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()

    {
        return array(
            array(
                'value' => 'all',
                'label' => 'All',
            ),
            array(
                'value' => 'cms_index_index',
                'label' => 'Home Page',
            ),
            array(
                'value' => 'catalog_category_view',
                'label' => 'Catagory Page',
            ),
            array(
                'value' => 'catalog_product_view',
                'label' => 'Product Page',
            ),
            array(
                'value' => 'cms_page_view',
                'label' => 'CMS Pages',
            ), 
            array(
                'value' => 'contact_index_index',
                'label' => 'Contact Page',
            ),       
        );
    }   
}