<?php
/** Setubridge Technolabs
* http://www.setubridge.com/
* @author SetuBridge
* @license http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
**/
?>
<?php
namespace SetuBridge\Holidaytheme\Model;

class Backgroundpattern
{


    protected $_storeManager;
    protected $_urlInterface;

    public function __construct(     
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\UrlInterface $urlInterface
    )
    {        
        $this->_storeManager = $storeManager;
        $this->_urlInterface = $urlInterface;
    }

    /**
    * Provide available options as a value/label array
    *
    * @return array
    */
    public function toOptionArray()
    {
        $result = array();
        $backgroundpattern=$this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) .'festivethemebackgroundimage/bgimage/background_patterns';
        $result[] = array('class'=>'none','value'=>'none','label'=>'&nbsp;&nbsp;None&nbsp;
        <br/><br/>');
        $result[] = array('class'=>'backgroundimage','value'=>'pattern1.gif','label'=>'&nbsp;&nbsp;
            <img id="backgroundpattern1" src="'.$backgroundpattern.'/pattern1.gif" style="vertical-align:middle;width:70%;"/>&nbsp;
        <br/><br/>');
        $result[] = array('class'=>'backgroundimage','value'=>'pattern2.gif','label'=>'&nbsp;&nbsp;
            <img id="backgroundpattern2" src="'.$backgroundpattern.'/pattern2.gif" style="vertical-align:middle;width:70%;"/>&nbsp;
        <br/><br/>');
        $result[] = array('class'=>'backgroundimage','value'=>'pattern3.gif','label'=>'&nbsp;&nbsp;
            <img id="backgroundpattern3" src="'.$backgroundpattern.'/pattern3.gif" style="vertical-align:middle;width:70%;"/>&nbsp;
        <br/><br/>');
        $result[] = array('class'=>'backgroundimage','value'=>'pattern4.gif','label'=>'&nbsp;&nbsp;
            <img id="backgroundpattern4" src="'.$backgroundpattern.'/pattern4.gif" style="vertical-align:middle;width:70%;"/>&nbsp;
        <br/><br/>');
        $result[] = array('class'=>'backgroundimage','value'=>'pattern5.gif','label'=>'&nbsp;&nbsp;
            <img id="backgroundpattern5" src="'.$backgroundpattern.'/pattern5.gif" style="vertical-align:middle;width:70%;"/>&nbsp;
        <br/><br/>');
        return $result;
    }
}