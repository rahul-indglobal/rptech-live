<?php
/** Setubridge Technolabs
* http://www.setubridge.com/
* @author SetuBridge
* @license http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
**/
?>
<?php
namespace SetuBridge\Holidaytheme\Model;

class Headerpattern 
{ 

    protected $assetRepository;

    public function __construct(
        \Magento\Framework\View\Asset\Repository $assetRepository,
        array $data = []
    )
    {        
        $this->assetRepository = $assetRepository;
    }

    /**
    * Provide available options as a value/label array
    *
    * @return array
    */
    public function toOptionArray()
    {  

        $result = array();
        $result[] = array('class'=>'none','value'=>'none','label'=>'&nbsp;&nbsp;None&nbsp;
        <br/><br/>');
        $result[] = array('class'=>'headerimage','value'=>'pattern1.png','label'=>'&nbsp;&nbsp;
            <img id="headerpattern1" src="'.$this->getViewFileUrl('pattern1.png').'" style="vertical-align:middle;max-width:150px;"/>&nbsp;
        <br/><br/>');
        $result[] = array('class'=>'headerimage','value'=>'pattern2.png','label'=>'&nbsp;&nbsp;
            <img id="headerpattern2" src="'.$this->getViewFileUrl('pattern2.png').'" style="vertical-align:middle;max-width:150px;"/>&nbsp;
        <br/><br/>');
        $result[] = array('class'=>'headerimage','value'=>'pattern3.png','label'=>'&nbsp;&nbsp;
            <img id="headerpattern3" src="'.$this->getViewFileUrl('pattern3.png').'" style="vertical-align:middle;max-width:150px;"/>&nbsp;
        <br/><br/>');
        $result[] = array('class'=>'headerimage','value'=>'pattern4.png','label'=>'&nbsp;&nbsp;
            <img id="headerpattern4" src="'.$this->getViewFileUrl('pattern4.png').'" style="vertical-align:middle;max-width:150px;"/>&nbsp;
        <br/><br/>');
        $result[] = array('class'=>'headerimage','value'=>'pattern5.png','label'=>'&nbsp;&nbsp;
            <img id="headerpattern5" src="'.$this->getViewFileUrl('pattern5.png').'" style="vertical-align:middle;max-width:150px;"/>&nbsp;
        <br/><br/>');        
        return $result;
    }
    public function getViewFileUrl($filepath){
         $fileId = 'SetuBridge_Holidaytheme::images/festivethemebackgroundimage/header/header_patterns/'.$filepath; 
        $asset = $this->assetRepository->createAsset($fileId,[]);
        return $asset->getUrl();
    }
}