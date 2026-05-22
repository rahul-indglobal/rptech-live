<?php
/** Setubridge Technolabs
* http://www.setubridge.com/
* @author SetuBridge
* @license http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
**/
?>
<?php
namespace SetuBridge\Holidaytheme\Model;

class Animationmode extends \Magento\Framework\View\Element\Template
{
    public function toOptionArray()
    {
        return array(
            array(
                'value' => '0',
                'label' => 'Horizontal',
            ),
            array(
                'value' => '1',
                'label' => 'Random',
            )
        );
    }   
}