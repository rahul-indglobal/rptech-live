<?php

namespace Managehomeslider\Homepageslider\Block\Widget;

use Magento\Framework\View\Element\Template;
use Magento\Widget\Block\BlockInterface;

class Homepageslider extends Template implements BlockInterface {

    protected $_template = "widget/homepageslider.phtml";

    public function getSliderCollection() {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of object manager
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();
        $tableName = $resource->getTableName('rptech_homeSlider');
        $sql = "Select entity_id,title,content,slider_path,slider_link,sorting_order FROM " . $tableName." where is_active=1 order by sorting_order asc";
        $result = $connection->fetchAll($sql);
//	echo '<pre>';print_r($result);die;
        return $result;
    }
    
   
    
    

}
