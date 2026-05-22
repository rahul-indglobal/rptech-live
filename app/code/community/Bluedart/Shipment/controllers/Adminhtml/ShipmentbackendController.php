<?php
class Bluedart_Shipment_Adminhtml_ShipmentbackendController extends Mage_Adminhtml_Controller_Action
{

	public function indexAction()
    {
       $this->loadLayout();
	   $this->_title($this->__("Blue Dart"));
	   $this->renderLayout();
    }
    
    public function downloadlabelAction()
    {		
		$filenumber =  $this->getRequest()->getParam('filenumber',false);			
		if ($filenumber) {
			$pdfContent = file_get_contents( Mage::getBaseDir('base')."/var/bluedart/". $filenumber . '.pdf');
			if ($pdfContent) {
				$this->_prepareDownloadResponse($filenumber.".pdf", $pdfContent);
				return;
			}
		}		
	}

    public function PincodevalidateAction(){

       $pincode = $this->getRequest()->getParam('pincode');
       $productcode = strtoupper($this->getRequest()->getParam('productcode'));
       $subproduct = strtoupper($this->getRequest()->getParam('subproduct'));

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
		$servicefinderurl = Mage::getStoreConfig('bluedart/url/servicefinder');
		if(!$servicefinderurl)
		  {
			echo "Please enter Service Finder url from system configuration.";
			exit;
		  }
        $soap = new SoapClient($servicefinderurl.'?wsdl',
        array(
        'trace'               => 1,  
        'style'               => SOAP_DOCUMENT,
        'use'                 => SOAP_LITERAL,
        'soap_version'        => SOAP_1_2
        ));
        

        $soap->__setLocation($servicefinderurl);   

        $soap->sendRequest = true;
        $soap->printRequest = false;
        $soap->formatXML = true;
        
        
        if($productcode != "" || $subproduct != "" ) {

          $actionHeader = new SoapHeader('http://www.w3.org/2005/08/addressing','Action','http://tempuri.org/IServiceFinderQuery/GetServicesforProduct',true);
          $soap->__setSoapHeaders($actionHeader);

          $params = array('pinCode' => $pincode,
            'pProductCode' => $productcode,
            'pSubProductCode' => $subproduct,
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
          $result = $soap->__soapCall('GetServicesforProduct',array($params));
          
          //echo '<h2>Parameters</h2><pre>'; print_r($result); echo '</pre>';
          
          $holidayList = $result->GetServicesforProductResult->BlueDartHolidays->Holiday;
              $holidayhtml = '';
                foreach($holidayList as $value){
                $time_other_format = $value->HolidayDate;
                $dt = new DateTime($time_other_format);
                $holidayhtml .= "<p><span><b>". $dt->format('Y-m-d') ." </b></span><span>". $value->Description ."</span></p>";

      }
      
      //echo '<h2>Parameters</h2><pre>'; print_r($result); echo '</pre>';

     if($result->GetServicesforProductResult->ErrorMessage == 'Valid') {
      echo "
        <div class='divTable2'>
              <div class='headRow2'>
                <div class='divCell pincodes' align='center'>
                  <p><span><b>Pin Code Details</b></span></p>
                  <p><span><b>Pin Code </b></span><span>". $result->GetServicesforProductResult->PinCode ."</span></p>
                  <p><span><b>Pin Code Description </b></span><span>". $result->GetServicesforProductResult->PinDescription ."</span></p>
                  <p><span><b>Area Code </b></span><span>". $result->GetServicesforProductResult->AreaCode ."</span></p>
                  <p><span><b>Service Centre Code </b></span><span>". $result->GetServicesforProductResult->ServiceCenterCode ."</span></p>
                  <p><span><b>Product Code </b></span><span>". $result->GetServicesforProductResult->Product ."</span></p>
                  <p><span><b>Sub Product Code </b></span><span>". $result->GetServicesforProductResult->SubProduct ."</span></p>
                  <p><span><b>Product Description </b></span><span></span></p>
                  <p><span><b>Service </b></span><span>". $result->GetServicesforProductResult->Service ."</span></p>
                  <p><span><b>Service Name </b></span><span>". $result->GetServicesforProductResult->ServiceName ."</span></p>

                </div>
              </div>
      </div>
      <div class='divTable3'>
              <div class='headRow3'>
                <div class='divCell pincodes' align='center'>
                  <p><span><b>BDE Holiday Date</b></span><span><b>Description</b></span></p>
                 ".$holidayhtml."
                  <p><span></span><span></span></p>
                </div>
              </div>
      </div>
      ";

    } else {
      
        if($result->GetServicesforProductResult->ErrorMessage != "") {
          $res_error = $result->GetServicesforProductResult->ErrorMessage;
        } else {
          $res_error = "Invalid Input"; 
        } 
      
        echo "
          <div class='divTable2'>
          <div class='headRow2'>
          <div class='divCell pincodes' align='center'>
          <p><span><b>Pincode Finder Details </b></span></p>
          <p><span><b>IsError </b></span><span class='error_msg'>True</span></p>
          <p><span><b>Description </b></span><span class='error_msg'>".$res_error."</span></p>
          </div>
          </div>
          </div>";

    }
}
catch (Exception $e) {
          Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }

        } else {
         
          $actionHeader = new SoapHeader('http://www.w3.org/2005/08/addressing','Action','http://tempuri.org/IServiceFinderQuery/GetServicesforPincode',true);
          $soap->__setSoapHeaders($actionHeader);

          $params = array('pinCode' => $pincode,
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

          $result = $soap->__soapCall('GetServicesforPincode',array($params));
          
          //echo '<h2>Parameters</h2><pre>'; print_r($result); echo '</pre>';
          
          $holidayList = $result->GetServicesforPincodeResult->BlueDartHolidays->Holiday;
          $holidayhtml = '';
            foreach($holidayList as $value){
                  $time_other_format = $value->HolidayDate;
                  $dt = new DateTime($time_other_format);
            $holidayhtml .= "<p><span><b>". $dt->format('Y-m-d') ." </b></span><span>". $value->Description ."</span></p>";
           

            }

          if($result->GetServicesforPincodeResult->ErrorMessage == 'Valid') {

              echo "
                <div class='divTable1'>
                 <div class='headRow1'>
                    <div class='divCell pincodes' align='center'>
                      <p>
                      <span><b>Product Details</b></span><span><b>Service In Bound</b></span><span><b>Service Out  Bound</b></span>
                      </p>
                      <p>
                      <span><b>DomesticPriority </b></span><span>". $result->GetServicesforPincodeResult->DomesticPriorityInbound ."</span><span>".$result->GetServicesforPincodeResult->DomesticPriorityOutbound."</span>
                      </p>
                      <p>
                      <span><b>Apex </b></span><span>". $result->GetServicesforPincodeResult->ApexInbound ."</span><span>". $result->GetServicesforPincodeResult->ApexOutbound ."</span>
                      </p>
                      <p>
                      <span><b>Ground </b></span><span>". $result->GetServicesforPincodeResult->GroundInbound ."</span><span>". $result->GetServicesforPincodeResult->GroundOutbound ."</span>
                      </p>
                      <p>
                      <span><b>eTailCODAir </b></span><span>". $result->GetServicesforPincodeResult->eTailCODAirInbound ."</span><span>". $result->GetServicesforPincodeResult->eTailCODAirOutbound ."</span>
                      </p>
                      <p>
                      <span><b>eTailCODGround </b></span><span>". $result->GetServicesforPincodeResult->eTailCODGroundInbound ."</span><span>". $result->GetServicesforPincodeResult->eTailCODGroundOutbound ."</span>
                      </p>
                      <p>
                      <span><b>eTailPrePaidAir </b></span><span>". $result->GetServicesforPincodeResult->eTailPrePaidAirInbound ."</span><span>". $result->GetServicesforPincodeResult->eTailPrePaidAirOutound ."</span>
                      </p>
                      <p>
                      <span><b>eTailPrePaidGround </b></span><span>". $result->GetServicesforPincodeResult->eTailPrePaidGroundInbound ."</span><span>". $result->GetServicesforPincodeResult->eTailPrePaidGroundOutbound ."</span>
                      </p>
                      <p>
                      <span><b></b></span><span></span><span></span>
                      </p>

                    </div>
                    </div>
                  </div>
                  <div class='divTable2'>
                  <div class='headRow2'>
                    <div class='divCell pincodes' align='center'>
                      <p><span><b>Pin Code Details</b></span></p>
                      <p><span><b>Pin Code </b></span><span>". $result->GetServicesforPincodeResult->PinCode ."</span></p>
                      <p><span><b>Pin Code Description </b></span><span>". $result->GetServicesforPincodeResult->PincodeDescription ."</span></p>
                      <p><span><b>Area Code </b></span><span>". $result->GetServicesforPincodeResult->AreaCode ."</span></p>
                      <p><span><b>Service Centre Code </b></span><span>". $result->GetServicesforPincodeResult->ServiceCenterCode ."</span></p>
                      <p><span><b>Air Value Limit </b></span><span>". $result->GetServicesforPincodeResult->AirValueLimit ."</span></p>

                      <p><span><b>Air Value Limit Trail Pre Paid </b></span><span>". $result->GetServicesforPincodeResult->AirValueLimiteTailPrePaid ."</span></p>

                      <p><span><b>Ground Value Limit </b></span><span>". $result->GetServicesforPincodeResult->GroundValueLimit ."</span></p>
                      
                      <p><span><b>Ground Value Limit Trail Pre Paid </b></span><span>". $result->GetServicesforPincodeResult->GroundValueLimiteTailPrePaid ."</span></p>

                      <p><span><b>DomesticPriorityTDD </b></span><span>". $result->GetServicesforPincodeResult->DomesticPriorityTDD ."</span></p>
                      <p><span><b>ApexTDD </b></span><span>". $result->GetServicesforPincodeResult->ApexTDD ."</span></p>

                    </div>
                  </div>
                </div>
                
                <div class='divTable3'>
                        <div class='headRow3'>
                          <div class='divCell pincodes' align='center'>
                            <p><span><b>BDE Holiday Date</b></span><span><b>Description</b></span></p>
                            ".$holidayhtml."
                            <p><span></span><span></span></p>
                          </div>
                        </div>
                </div>
                
                <div class='divTable4'>
                  <div class='headRow4'>
                    <div class='divCell pincodes' align='center'>
                      <p><span><b>EDL Details</b></span><span><b>Description</b></span></p>
                      <p><span><b>Distance </b></span><span>". $result->GetServicesforPincodeResult->EDLDist ."</span></p>
                      <p><span><b>Additional Days </b></span><span>". $result->GetServicesforPincodeResult->EDLAddDays ."</span></p>
                      <p><span><b>EDL Product </b></span><span>". $result->GetServicesforPincodeResult->EDLProduct ."</span></p>
                      <p><span><b>Embargo </b></span><span>". $result->GetServicesforPincodeResult->Embargo ."</span></p>
                    </div>
                  </div>
                </div>

          ";
      
      } else {

        if($result->GetServicesforPincodeResult->ErrorMessage != "") {
          $res_error = $result->GetServicesforPincodeResult->ErrorMessage;
        } else {
          $res_error = "Invalid Input"; 
        } 
      
        echo "
          <div class='divTable1'>
          <div class='headRow1'>
          <div class='divCell pincodes' align='center'>
          <p><span><b>Pincode Finder Details </b></span></p>
          <p><span><b>IsError </b></span><span class='error_msg'>True</span></p>
          <p><span><b>Description </b></span><span class='error_msg'>".$res_error."</span></p>
          </div>
          </div>
          </div>";

      }


}
catch (Exception $e) {
          Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }

        }             
    }

}
