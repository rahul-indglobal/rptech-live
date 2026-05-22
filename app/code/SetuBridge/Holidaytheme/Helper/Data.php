<?php
/** Setubridge Technolabs
* http://www.setubridge.com/
* @author SetuBridge
* @license http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
**/
?>
<?php
namespace SetuBridge\Holidaytheme\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Filesystem\DirectoryList;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{

    const XML_PATH_MODULE_ENABLED='setubridge_holidaytheme/general/generalactive';

    protected $date;
    protected $_page;
    protected $_request;
    protected $_storeManager;
    protected $_directorylist;
    protected $_filesystem ;
    protected $_imageFactory;

    public function __construct(
        Context $context,
        \Magento\Cms\Model\Page $page,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Magento\Framework\App\Request\Http $request,
        \Magento\Customer\Model\SessionFactory $sessionFactory,
        \Magento\Framework\Filesystem\DirectoryList $dir,
        \Magento\Framework\Filesystem $filesystem,         
        \Magento\Framework\Image\AdapterFactory $imageFactory,
        array $data = []  
    ) {
        parent::__construct($context);
        $this->date = $date;
        $this->_page = $page;
        $this->_storeManager = $storeManager;
        $this->_sessionFactory = $sessionFactory;
        $this->_request = $request;
        $this->_currentStore = $this->_storeManager->getStore();
        $this->_dir = $dir;
        $this->_filesystem = $filesystem;               
        $this->_imageFactory = $imageFactory;  
    }

    public function getConfig($path,$storeId=null){
        $store=$storeId?$this->_storeManager->getStore($storeId):$this->_currentStore;

        return $this->scopeConfig->getValue($path, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $store);
    }
    public function isActive(){
        return (bool) $this->getConfig(self::XML_PATH_MODULE_ENABLED);
    }
    public function isActiveForDate($fromDate,$toDate){
        if(!$fromDate && !$toDate) return true;


        $currantDate=$this->date->gmtDate();
        $start_ts = $this->date->timestamp($fromDate);
        $end_ts = $this->date->timestamp($toDate);
        $user_ts = $this->date->timestamp($currantDate);
        if($start_ts < $user_ts && !$toDate) return true;
        // Check that user date is between start & end
        return (($user_ts >= $start_ts) && ($user_ts <= $end_ts)); 
    }

    public function imageResize(
        $src,
        $dir='resize/'
    ){
        $image = $src;
        $src = $this->_filesystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath() .'/festivetheme' . '/footer' . '/default/'.$src;
        $absPath = $src;
        $imageResized = $this->_filesystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath() .'/festivetheme' . '/footer' . '/default/resize/'.$image;
        $imageResize = $this->_imageFactory->create();
        $imageResize->open($absPath);
        
        $imageResize->constrainOnly(TRUE);
        $imageResize->keepTransparency(TRUE);
        $imageResize->keepFrame(true);
        $imageResize->keepAspectRatio(true);
        $imageResize->resize(150);
        $dest = $imageResized ;
        $imageResize->save($dest);

        return $image;
    }
    public function getNewDirectoryImage($src){
        $segments = array_reverse(explode('/',$src));
        $first_dir = substr($segments[0],0,1);
        $second_dir = substr($segments[0],1,1);
        return 'cache/'.$first_dir.'/'.$second_dir.'/'.$segments[0];
    }
    public function resize($image)
    {
        $absolutePath = $this->_filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA)->getAbsolutePath('SetuBridge_Holidaytheme/festivethemebackgroundimage/').$image;
        if (!file_exists($absolutePath)) return false;
        $imageResized = $this->_filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA)->getAbsolutePath('resized/'.$width.'/').$image;
        if (!file_exists($imageResized)) { // Only resize image if not already exists.

            $imageResize = $this->_imageFactory->create();         
            $imageResize->open($absolutePath);
            $imageResize->constrainOnly(TRUE);         
            $imageResize->keepTransparency(TRUE);         
            $imageResize->keepFrame(FALSE);         
            $imageResize->keepAspectRatio(TRUE);         
            $imageResize->resize($width,$height);  
            //destination folder                
            $destination = $imageResized ;    
            //save image      
            $imageResize->save($destination);         
        } 
        $resizedURL = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA).'resized/'.$width.'/'.$image;
        return $resizedURL;
    } 
    public function isAllowedPage(){
        $routeName = $this->_request->getRouteName(); 
        $identifier = $this->_page->getIdentifier();
        $current_page = $this->_request->getFullActionName();
        $allowedPage=$this->getConfig('setubridge_holidaytheme/general/cmspages');


        if($allowedPage=='all') return true;
        $allowedPage =explode(',',$allowedPage);

        if($routeName == 'cms' && $identifier == 'home' ):
            $current_page = 'cms_index_index'; 
            endif;
            
        return in_array($current_page,$allowedPage);
    }
}