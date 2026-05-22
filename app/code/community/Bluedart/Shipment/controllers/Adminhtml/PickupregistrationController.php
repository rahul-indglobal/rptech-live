<?php
class Bluedart_Shipment_Adminhtml_PickupregistrationController extends Mage_Adminhtml_Controller_Action
{
    	public function indexAction()
        {
         $this->loadLayout();
    	   $this->_title($this->__("Blue Dart"));
    	   $this->renderLayout();
        }
      public function PickupregisterAction(){
      /* $waybillnumber = $this->getRequest()->getParam('waybillnumber');
      error_log($waybillnumber);*/
         $url = 'http://basic.bluedart.com/index.php/admin_shipment/adminhtml_pickupregistration/index/key/fd58c32b29f2b737d278ba53bd1194c2/';
        
        $area = $this->getRequest()->getParam('area');
        $customercode = strtoupper($this->getRequest()->getParam('customercode'));
        $clientname = $this->getRequest()->getParam('clientname');

        $isToPayShipper = $this->getRequest()->getParam('isToPayShipper');
        
        $chkval_shipper = "";  
        if($isToPayShipper == 0){
          $chkval_shipper = "N"; 
        } else if($isToPayShipper == 1){
          $chkval_shipper = "Y";    
        } else {
          $chkval_shipper = "";
        }
        $DoxNDox  = $this->getRequest()->getParam('DoxNDox');

        $address1 = $this->getRequest()->getParam('address1');
        $address2 = $this->getRequest()->getParam('address2');
        $address3 = $this->getRequest()->getParam('address3');
        $pincode = $this->getRequest()->getParam('pincode');
        $mobile = $this->getRequest()->getParam('mobile');
        $telephone = $this->getRequest()->getParam('telephone');
        $contactperson = $this->getRequest()->getParam('contactperson');
        $email = $this->getRequest()->getParam('email');
        $pickupdate = $this->getRequest()->getParam('pickupdate');
        $pickupreadytime = $this->getRequest()->getParam('pickupreadytime');
        $ofcclosingtime = $this->getRequest()->getParam('ofcclosingtime');
        $pieces = $this->getRequest()->getParam('pieces');
        $productcode = $this->getRequest()->getParam('productcode');
        $actweight = $this->getRequest()->getParam('actweight');
        $volwt = $this->getRequest()->getParam('volwt');
        $routecode = $this->getRequest()->getParam('routecode');
        $remarks = $this->getRequest()->getParam('remarks');
        $refno = $this->getRequest()->getParam('refno');

        // Store Config Data
       $apitype = Mage::getStoreConfig('bluedart/settings/api_type');
       $area = Mage::getStoreConfig('bluedart/settings/area');
       $customercode = Mage::getStoreConfig('bluedart/settings/customercode');
       $licencekey = Mage::getStoreConfig('bluedart/settings/licencekey');
       $loginid = Mage::getStoreConfig('bluedart/settings/loginid');
       $password = Mage::getStoreConfig('bluedart/settings/password');
       $version = Mage::getStoreConfig('bluedart/settings/version');
       $previuosUrl = Mage::getSingleton('core/session')->getPreviousUrl();
       // Store Config Data

      try{
        $pickupregistrationserviceurl = Mage::getStoreConfig('bluedart/url/pickupregistrationservice');
        if(!$pickupregistrationserviceurl)
		  {
			echo "Please enter Pickup Registration Service url from system configuration.";
			exit;
		  }
	  
        $soap = new SoapClient($pickupregistrationserviceurl.'?wsdl',
        array(
        'trace'               => 1,  
        'style'               => SOAP_DOCUMENT,
        'use'                 => SOAP_LITERAL,
        'soap_version'        => SOAP_1_2
        ));
        
        $soap->__setLocation($pickupregistrationserviceurl);
        
        $soap->sendRequest = true;
        $soap->printRequest = false;
        $soap->formatXML = true;
        
        
        $actionHeader = new SoapHeader('http://www.w3.org/2005/08/addressing','Action','http://tempuri.org/IPickupRegistration/RegisterPickup',true);
        $soap->__setSoapHeaders($actionHeader);

        $params = array(
        'request' => 
          array (
          //  'AWBNo' =>array('58400031395'),
            'AreaCode' => $area,
            'ContactPersonName' =>$contactperson,
            'CustomerAddress1' =>$address1,
            'CustomerAddress2' =>$address2,
            'CustomerAddress3' =>$address3,
            'CustomerCode' =>$customercode,
            'CustomerName' =>$clientname,
            'CustomerPincode' =>$pincode,
            'CustomerTelephoneNumber' =>$telephone,
            'DoxNDox' =>$DoxNDox,
            'EmailID' =>$email,
            'MobileTelNo' =>$mobile,
            'NumberofPieces' =>$pieces,
            'OfficeCloseTime' =>$ofcclosingtime,
            'ProductCode' =>$productcode,
            'ReferenceNo' =>$refno,
            'Remarks' =>$remarks,
            'RouteCode' =>'99',
            'ShipmentPickupDate' =>$pickupdate,
            'ShipmentPickupTime' =>$pickupreadytime,
           // 'SubProducts' =>array('TDD'),
            'VolumeWeight' =>$volwt,
            'WeightofShipment' =>$actweight,
            'isToPayShipper' =>$chkval_shipper),
          'profile' => 
             array(
                'Api_type' => $apitype,
                'Area'=>$area,
                'Customercode'=>$customercode,
                'IsAdmin'=>'',
                'LicenceKey'=>$licencekey,
                'LoginID'=>$loginid,
                'Password'=>$password,
                'Version'=>$version)
                );

            try{
                 $result = $soap->__soapCall('RegisterPickup',array($params));
                 $res_error = $result->RegisterPickupResult->TokenNumber;
                 /* echo "<pre>";
                 print_r($result); */
                 if($res_error != ''){
                   echo "
                    <div class='divTable'>
                    <div class='headRow'>
                    <div class='divCell pincodes' align='center'>
                    <p><span><b>Pickup Registraion</b></span></p>
                    <p><span><b>IsError :</b></span><span class='error_msg'>False</span></p>
                    <p><span><b>Token No. :</b></span><span class='error_msg'>" . $res_error . "</span></p>
                    <p><span><b>Error Message :</b></span><span class='error_msg'>Token Generated successfully</span></p>
                    </div>
                    </div>
                    </div>";

                  //echo "Token Generated successfully ".$result->RegisterPickupResult->TokenNumber;
                }
                else{
                    $error = '';
                    $count = count($result->RegisterPickupResult->Status->ResponseStatus);
                  
                    error_log('count'.$count.'------------------>');

                    echo "
                    <div class='divTable'>
                    <div class='headRow'>
                    <div class='divCell pincodes' align='center'>
                    <p><span><b>Pickup Registraion</b></span></p>
                    <p><span><b>IsError :</b></span><span class='error_msg'>True</span></p>
                    <p><span><b>Error Message :</b></span><span class='error_msg'>";

                    if($count>1){
                      for($i=0;$i<$count;$i++){
                       // echo "<span class='err_info'><span>". $result->RegisterPickupResult->Status->ResponseStatus[$i]->StatusCode."</span>";
                        echo "<span class='err_info'><span>".$result->RegisterPickupResult->Status->ResponseStatus[$i]->StatusInformation."</span></span>";
                      }
                    }else{
                      echo $result->RegisterPickupResult->Status->ResponseStatus->StatusInformation;
                    }
                    
                    
                    echo "</span></div></div></div>";
                }  
                 exit;
            }
            catch (Exception $e) {
              echo $e;
            }
exit;
}
catch (Exception $e) {
   echo $e;
        }

    }
}
