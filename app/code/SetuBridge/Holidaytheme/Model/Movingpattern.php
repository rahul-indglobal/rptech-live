<?php
/** Setubridge Technolabs
* http://www.setubridge.com/
* @author SetuBridge
* @license http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
**/
?>
<?php
namespace SetuBridge\Holidaytheme\Model;

class Movingpattern 
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
        $result[] = array('class'=>'movingimage','value'=>'pattern1.gif','label'=>'&nbsp;&nbsp;
            <img id="movingpattern1" src="'.$this->getViewFileUrl('pattern1.gif').'" style="vertical-align:middle;max-width: 100px;"/>&nbsp;
        <br/><br/>');
        $result[] = array('class'=>'movingimage','value'=>'pattern2.gif','label'=>'&nbsp;&nbsp;
            <img id="movingpattern2" src="'.$this->getViewFileUrl('pattern2.gif').'" style="vertical-align:middle;max-width: 100px;"/>&nbsp;
        <br/><br/>');
        $result[] = array('class'=>'movingimage','value'=>'pattern3.gif','label'=>'&nbsp;&nbsp;
            <img id="movingpattern3" src="'.$this->getViewFileUrl('pattern3.gif').'" style="vertical-align:middle;max-width: 280px;"/>&nbsp;
        <br/><br/>');    
        return $result;
    }
    public function getViewFileUrl($filepath){
         $fileId = 'SetuBridge_Holidaytheme::images/festivethemebackgroundimage/santa/moving_patterns/'.$filepath; 
        $asset = $this->assetRepository->createAsset($fileId,[]);
        return $asset->getUrl();
    }
}