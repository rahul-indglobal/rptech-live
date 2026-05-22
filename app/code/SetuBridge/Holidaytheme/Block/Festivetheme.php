<?php
/** Setubridge Technolabs
* http://www.setubridge.com/
* @author SetuBridge
* @license http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
**/
?>
<?php
namespace SetuBridge\Holidaytheme\Block;

class Festivetheme extends \Magento\Framework\View\Element\Template {
  protected $_helper;
  protected $_urlInterface;

  public function __construct(
   \Magento\Framework\View\Element\Template\Context $context,
   \Magento\Framework\UrlInterface $urlInterface,  
   \SetuBridge\Holidaytheme\Helper\Data $_helper,
   \Magento\Store\Model\StoreManagerInterface $storeManager,
   array $data = []
 ) {
    parent::__construct($context, $data);
    $this->_helper=$_helper;
    $this->_urlInterface = $urlInterface;
    $this->_storeManager = $storeManager;
  }
  public function getJsonOptions() {

    $min = $this->_helper->getConfig('setubridge_holidaytheme/snowfallsconfig/snowfallselementminspeed') ? $this->_helper->getConfig('setubridge_holidaytheme/snowfallsconfig/snowfallselementminspeed'): 10;

    $max = $this->_helper->getConfig('setubridge_holidaytheme/snowfallsconfig/snowfallselementmaxspeed') ? $this->_helper->getConfig('setubridge_holidaytheme/snowfallsconfig/snowfallselementmaxspeed'): 50;

    $options = new \stdClass();
    $colorPattern=$this->getPatternWithdColor();
    $options->flakes = round($this->_helper->getConfig('setubridge_holidaytheme/snowfallsconfig/snowfallsduplicatecount'));
    $options->color = isset($colorPattern['color']) ? $colorPattern['color'] : array();
    $options->text = isset($colorPattern['patterns']) ? $colorPattern['patterns'] : array();;
    $options->speed = $this->_helper->getConfig('setubridge_holidaytheme/snowfallsconfig/snowfallsfallanimatespeed');
    $options->size = (object) array(
      'min' => $min,
      'max' => $max
    );
    return json_encode($options);
  }
  protected function getPatternWithdColor(){
   $_colors=array(); 
   $_patterns=array(); 
   $patternColors= explode(',',$this->_helper->getConfig('setubridge_holidaytheme/snowfallsconfig/snowfallsdesignpattern'));
   foreach($patternColors as $patternColor){
     $patternColorArr= explode('|' , $patternColor);
     if(isset($patternColorArr[1])) {
       $_colors[]=trim($patternColorArr[1]);
     }
     if(isset($patternColorArr[0])) {
       $_patterns[]=trim($patternColorArr[0]);
     }
   }
   return array('color'=>$_colors,'patterns'=>$_patterns);  
 }

public function getStore(){
  return $this->_storeManager->getStore();
}

public function getMediaurl(){
  return $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
}
}
