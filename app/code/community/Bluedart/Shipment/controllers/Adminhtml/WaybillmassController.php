<?php
class Bluedart_Shipment_Adminhtml_WaybillmassController extends Mage_Adminhtml_Controller_Action
{
    	public function indexAction()
        {
         $this->loadLayout();
    	   $this->_title($this->__("Blue Dart"));
    	   $this->renderLayout();
        }

      public function WaybillimportAction() {

        $count = 0;

        // Copy uploaded file to server
        $random_file = rand(1500000000, 2500000000); 
        $upld_filename = basename($_FILES["waybillmassfile"]["name"]);
        $newupld_filename = $random_file."-".$upld_filename;	
        $target_dir = "var/bluedart/uploads/";
		$target_file = $target_dir . $newupld_filename;

		$upld_file = move_uploaded_file($_FILES["waybillmassfile"]["tmp_name"], $target_file);
        	
        // Copy Uploaded file to server

        $handle = fopen($target_file, 'r');

        if($handle){
          
          while (($line = fgetcsv($handle, 4096, ',', '"')) !== false) {
            $count++;
            if ($count > 1) {
                $data[] = $line;
            }
          }

          $this->insertwaybill($data,$target_file,$newupld_filename);

        }else{
        
          echo "can not open file";
        
        }

        $this->_redirect('admin_shipment/adminhtml_waybillmass/index/');
        // print_r($data);
        //exit;
        
      }

      public function insertwaybill($data,$target_file,$newupld_filename){

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
		$cnt_res = 0;     
		$arr_res = array();
    	$_SESSION['export_bluefile'] = "";

        foreach ($data as $value) {

          $shipper_area = $value[0];
          $shipper_customercode = trim($value[1], "'");
          //$shipper_customercode = '099960';
          $shipper_clientname =  $value[2];
          $shipper_address1 =  $value[3];
          $shipper_address2 =  $value[4];
          $shipper_address3 =  $value[5];
          $shipper_pincode =  $value[6];
          $shipper_mobile =  $value[7];
          $shipper_telephone =  $value[8];
          $shipper_sender =  $value[9];          
          $shipper_sender = substr($shipper_sender,0,20);
          $shipper_email =  $value[10];
          

          $Istopaycustomer_val =  $value[11];
          if($Istopaycustomer_val == '0' || $Istopaycustomer_val == 0) {
          	$Istopaycustomer = 'false';
          } else {
          	$Istopaycustomer = 'true';
          }
           

          $shipper_vendorcode = trim($value[12], "'");
          //$shipper_vendorcode = '898989';
          $consignee_name =  $value[13];
          $consignee_adress1 =  $value[14];
          $consignee_adress2 =  $value[15];
          $consignee_adress3 =  $value[16];
          $consignee_pincode =  $value[17];
          $consignee_mobile =  $value[18];
          $consignee_telephone = $value[19];
          $consignee_attention = $value[20];
          $service_pieces = $value[21];
          $service_productcode = $value[22];
          $service_subproduct = $value[23];

          $ProductType_val = $value[24];

          if($ProductType_val == 'NDOX') {
          	$ProductType = 'Dutiables';
          } else if($ProductType_val == 'DOX') {
          	$ProductType = 'Docs';
          } else {
          	$ProductType = $ProductType_val;
          }


          $service_actweight = $value[25];
          $service_invoiceno = $value[26];
          $service_packtype = $value[27];
          $service_creaditrefno = $value[28];
          $service_spclinstruction = $value[29];
          $service_pickupdate = $value[30];
          $service_pickupreadytime = $value[31];
          $service_declaredval = $value[32];
          $service_colamount = $value[33];
          $service_waybillnumber = $value[34];
          $service_cmdtydetail1 = $value[35];
          $service_cmdtydetail1 = substr($service_cmdtydetail1,0,30);
          $service_cmdtydetail2 = $value[36];
          $service_cmdtydetail2 = substr($service_cmdtydetail2,0,30);
          $service_cmdtydetail3 = $value[37];
          $service_cmdtydetail3 = substr($service_cmdtydetail3,0,30);
          $service_dimentionsl = $value[38];
          $service_dimentionsb = $value[39];
          $service_dimentionsh = $value[40];
          $service_count = $value[41];
          $itemstoship = $value[43];
          $order = Mage::getModel('sales/order')->loadByIncrementId($value[42]);

          $actual_orderid = $value[42];

          $waybillgenerationurl = Mage::getStoreConfig('bluedart/url/waybillgeneration');
          if(!$waybillgenerationurl)
  		    {
  			   Mage::getSingleton('adminhtml/session')->addError("Please enter waybill generation URL from system configuration.");
  			   Mage::app()->getResponse()->setRedirect($url)->sendResponse();
  			   return;
  		    }
          
          $soap = new SoapClient($waybillgenerationurl.'?wsdl',
          array(
          'trace'               => 1,  
          'style'               => SOAP_DOCUMENT,
          'use'                 => SOAP_LITERAL,
          'soap_version'        => SOAP_1_1
          ));
        
          $soap->__setLocation($waybillgenerationurl."/Basic");
          
          $soap->sendRequest = true;
          $soap->printRequest = false;
          $soap->formatXML = true;  
       
          #echo "Start  of Soap 1.2 version (ws_http_Binding)  setting";
          $waybillgenerationurl = Mage::getStoreConfig('bluedart/url/waybillgeneration');
          $soap = new SoapClient($waybillgenerationurl.'?wsdl',
          array(
          'trace'               => 1,  
          'style'               => SOAP_DOCUMENT,
          'use'                 => SOAP_LITERAL,
          'soap_version'        => SOAP_1_2
          ));
          
          $soap->__setLocation($waybillgenerationurl);
          
          $soap->sendRequest = true;
          $soap->printRequest = false;
          $soap->formatXML = true; 
          
          $actionHeader = new SoapHeader('http://www.w3.org/2005/08/addressing','Action','http://tempuri.org/IWayBillGeneration/GenerateWayBill',true);
          $soap->__setSoapHeaders($actionHeader); 
          #echo "end of Soap 1.2 version (WSHttpBinding)  setting";


          $params = array(
          'Request' => 
            array (
              'Consignee' =>
                array (
                  'ConsigneeAddress1' => $consignee_adress1,
                  'ConsigneeAddress2' => $consignee_adress2,
                  'ConsigneeAddress3'=> $consignee_adress3,
                  'ConsigneeAttention'=> $consignee_attention,
                  'ConsigneeMobile'=> $consignee_mobile,
                  'ConsigneeName'=> $consignee_name,
                  'ConsigneePincode'=> $consignee_pincode,
                  'ConsigneeTelephone'=> $consignee_telephone,
                ) ,
              'Services' => 
                array (
                  'ActualWeight' => $service_actweight,
                  'CollectableAmount' => $service_colamount,
                  'Commodity' =>
                    array (
                      'CommodityDetail1'  => $service_cmdtydetail1,
                      'CommodityDetail2' => $service_cmdtydetail2,
                      'CommodityDetail3' => $service_cmdtydetail3
                  ),
                  'CreditReferenceNo' => $service_creaditrefno,
                  'DeclaredValue' => $service_declaredval,
                  'Dimensions' =>
                    array (
                      'Dimension' =>
                        array (
                          'Breadth' => $service_dimentionsb,
                          'Count' => $service_count,
                          'Height' => $service_dimentionsh,
                          'Length' => $service_dimentionsl
                        ),
                    ),
                    'InvoiceNo' => $service_invoiceno,
                    'PackType' => $service_packtype,
                    'PickupDate' => $service_pickupdate,
                    'PickupTime' => $service_pickupreadytime,
                    'PieceCount' => $service_pieces,
                    'ProductCode' => $service_productcode,
                    'ProductType' => $ProductType,
                    'SpecialInstruction' => $service_spclinstruction,
                    'SubProductCode' => $service_subproduct,
                ),
                'Shipper' =>
                  array(
                    'CustomerAddress1' => $shipper_address1,
                    'CustomerAddress2' => $shipper_address2,
                    'CustomerAddress3' => $shipper_address3,
                    'CustomerCode' => $shipper_customercode,
                    'CustomerEmailID' => $shipper_email,
                    'CustomerMobile' => $shipper_mobile,
                    'CustomerName' => $shipper_clientname ,
                    'CustomerPincode' => $shipper_pincode,
                    'CustomerTelephone' => $shipper_telephone,
                    'IsToPayCustomer' => $IsToPayCustomer,
                    'OriginArea' => $shipper_area,
                    'Sender' => $shipper_sender,
                    'VendorCode' => $shipper_vendorcode
                  )
            ),
            'Profile' => 
               array(
                'Api_type' => $apitype,
                'LicenceKey'=>$licencekey,
                'LoginID'=>$loginid,
                'Version'=>$version)
            );
        // Here I call my external function
  
      try{
  		
  		$result = $soap->__soapCall('GenerateWayBill',array($params));
    	
    	

    	$awbno = $result->GenerateWayBillResult->AWBNo;

        if($awbno){

          $DestinationArea = $result->GenerateWayBillResult->DestinationArea;
          $DestinationLocation = $result->GenerateWayBillResult->DestinationLocation;
          $CCRCRDREF = $result->GenerateWayBillResult->CCRCRDREF;
          $extra_details = $DestinationArea.":".$DestinationLocation.":".$CCRCRDREF;
          
          $StatusCode = $result->GenerateWayBillResult->Status->WayBillGenerationStatus->StatusCode;
          $StatusInformation = $result->GenerateWayBillResult->Status->WayBillGenerationStatus->StatusInformation;
          $IsError = $result->GenerateWayBillResult->IsError;

          $PDFOutputNotRequired = "false";
          if($PDFOutputNotRequired == "false") {
            if($result->GenerateWayBillResult->AWBPrintContent) {      
              $awb_pdf =  $result->GenerateWayBillResult->AWBPrintContent;    
            }
          
          }

          unset($_SESSION['GenerateWayBillSession']);
          unset($_SESSION['bluedartItemsQty']);
        	        	
          $ordered_items = $order->getAllItems(); 
                $shipableitems = array();
        		  foreach($ordered_items as $item){     
        			  $shipableitems[$item->getSku()]['id'] = $item->getItemId();
        			  $shipableitems[$item->getSku()]['qty'] = $item->getQtyOrdered() - $item->getQtyCanceled() - $item->getQtyRefunded() - $item->getQtyShipped();			
        		  }

        		 if($itemstoship)
        		 {		 
        			 $itemsfromsheet = array();
        			 foreach(explode(',',$itemstoship) as $inditem)
        			 {
        				 $itemvalues = explode(':',$inditem);
        				 if(isset($itemvalues[0]) && isset($itemvalues[1]))
        				 {
        					$itemsfromsheet[$itemvalues[0]] =  $itemvalues[1];				
        				 }
        					
        			 } 
        			 $finalshipdata = array();
        			 $errormsgforqty = "";
        			 foreach($itemsfromsheet as $sku => $qty)
        			 {
        			 	if(isset($shipableitems[$sku]))
        				{
        					if($shipableitems[$sku]['qty'] >= $qty)
        					{
        						//$finalshipdata[$shipableitems[$sku]['id']] = $shipableitems[$sku]['qty'];
        						$finalshipdata[$shipableitems[$sku]['id']] = $qty;
        					}
        					else
        					{
        						 $errormsgforqty = $value[42]." : Please check sku_list form Order No";
        					}
        				} 
        			} 
        		}
        		else
        		{
        			$finalshipdata = $shipableitems;
        		}

        		
        		if($order->canShip() && !$errormsgforqty) {  			  
        			$shipmentid = Mage::getModel('sales/order_shipment_api')->create($order->getIncrementId(),$finalshipdata);              

        			$shipment = Mage::getModel('sales/order_shipment')->loadByIncrementId($shipmentid);          

        			$track = Mage::getModel('sales/order_shipment_track')->setCarrierCode('bluedart')
        									  ->setTitle('Blue Dart')
                            ->setDescription($extra_details)
        									  ->setNumber($awbno);

        			$shipment->addTrack($track)->save();     

        			if($awb_pdf != "") {
        				$this->generatepdf($awb_pdf,$awbno);
        			}


        			//$arr_res[$cnt_res]['orderid'] = $actual_orderid;
	          		$arr_res[$actual_orderid]['isError'] = $IsError;
	          		$arr_res[$actual_orderid]['wayBillNumber'] = $awbno;
	          		$arr_res[$actual_orderid]['DestinationArea'] = $DestinationArea;
	          		$arr_res[$actual_orderid]['DestinationLocation'] = $DestinationLocation;
	          		$arr_res[$actual_orderid]['ErrorMessage'] = $StatusInformation;

        			Mage::getSingleton('core/session')->addSuccess($value[42].' : '.'Blue Dart Shipment Number: '.$shipmentid.' With AWB Number: '.$awbno.' has been created.');
        			//error_log('before redirect--->'.$url);
        			Mage::app()->getResponse()->setRedirect($url)->sendResponse();




        		} 
        		else{
        			if(!$errormsgforqty)
        			{
        				$ermsg = $value[42].' : Cannot do shipment for the order';
        				Mage::getSingleton('core/session')->addError($ermsg);
        			}
        			else
        			{
        				Mage::getSingleton('core/session')->addError($errormsgforqty);
        			}         
        		}

        	} else {
        		
        		// Storing Status Message
        		$StatusCode = $result->GenerateWayBillResult->Status->WayBillGenerationStatus->StatusCode;
          		$StatusInformation = $result->GenerateWayBillResult->Status->WayBillGenerationStatus->StatusInformation;
          		$IsError = $result->GenerateWayBillResult->IsError;
          		// Storing Status Message

          		//$arr_res[$cnt_res]['orderid'] = $actual_orderid;
          		$arr_res[$actual_orderid]['isError'] = $IsError;
          		$arr_res[$actual_orderid]['wayBillNumber'] = "";
          		$arr_res[$actual_orderid]['DestinationArea'] = "";
          		$arr_res[$actual_orderid]['DestinationLocation'] = "";
          		$arr_res[$actual_orderid]['ErrorMessage'] = $StatusInformation;


      		    $errmsg = $result->GenerateWayBillResult->Status->WayBillGenerationStatus->StatusInformation;			
      			Mage::getSingleton('core/session')->addError($value[42]." : ".$errmsg);
      		
      		}
  		
        } catch(Exception $e){
  			 Mage::getSingleton('adminhtml/session')->addError($value[42]." : ".$e->getMessage());
  	     }
  		$cnt_res++;
  	}

  	// Append Data code for CSV
		// $target_file

		$csvPath = $target_file;
		$export_filename = "new-".$newupld_filename;
		$export = "media/bluedart-sample/export/".$export_filename;

		if (($handle_exp = fopen($csvPath, "r")) !== false) { 
	   	 	
	   	 	if ($fp = fopen($export, 'w')) { 
	   	 		
	   	 		$cnt_csv = 0;
	        	
	        	while (($data_new = fgetcsv($handle_exp, 1000, ",")) !== false) {
	        		
		        		$num = count($data_new);

		        		$arr_res[$actual_orderid]['ErrorMessage'];
		        		
		        		$crr_ord_id = $data_new[42];

		        		if($cnt_csv ==0) {

		        			$data_new[44] = 'isError'; // isError
		        			$data_new[45] = 'wayBillNumber'; // wayBillNumber
		        			$data_new[46] = 'DestinationArea'; // DestinationArea
		        			$data_new[47] = 'DestinationLocation'; // DestinationLocation
		        			$data_new[48] = 'ErrorMessage'; // ErrorMessage

		        		} else {

		        			$data_new[44] = $arr_res[$crr_ord_id]['isError']; // isError
		        			$data_new[45] = $arr_res[$crr_ord_id]['wayBillNumber']; // wayBillNumber
		        			$data_new[46] = $arr_res[$crr_ord_id]['DestinationArea']; // DestinationArea
		        			$data_new[47] = $arr_res[$crr_ord_id]['DestinationLocation']; // 
		        			$data_new[48] = $arr_res[$crr_ord_id]['ErrorMessage']; // ErrorMessage

		        		}	
		        		

		        		fputcsv($fp, array( 
	                        $data_new[0], 
	                        $data_new[1], 
	                        $data_new[2], 
	                        $data_new[3], 
	                        $data_new[4], 
	                        $data_new[5], 
	                        $data_new[6], 
	                        $data_new[7], 
	                        $data_new[8], 
	                        $data_new[9], 
	                        $data_new[10], 
	                        $data_new[11], 
	                        $data_new[12], 
	                        $data_new[13], 
	                        $data_new[14], 
	                        $data_new[15], 
	                        $data_new[16], 
	                        $data_new[17], 
	                        $data_new[18], 
	                        $data_new[19], 
	                        $data_new[20], 
	                        $data_new[21], 
	                        $data_new[22], 
	                        $data_new[23], 
	                        $data_new[24], 
	                        $data_new[25], 
	                        $data_new[26], 
	                        $data_new[27], 
	                        $data_new[28], 
	                        $data_new[29], 
	                        $data_new[30], 
	                        $data_new[31], 
	                        $data_new[32], 
	                        $data_new[33], 
	                        $data_new[34], 
	                        $data_new[35], 
	                        $data_new[36], 
	                        $data_new[37], 
	                        $data_new[38], 
	                        $data_new[39], 
	                        $data_new[40], 
	                        $data_new[41], 
	                        $data_new[42], 
	                        $data_new[43], 
	                        $data_new[44], 
	                        $data_new[45], 
	                        $data_new[46], 
	                        $data_new[47], 
	                        $data_new[48] 
	                    )); 
	        		
	        		$cnt_csv++;
	        	}
	        	fclose($fp);
	        }
	        fclose($handle_exp); 
	    }

      $_SESSION['export_bluefile'] = $export_filename;

	    return;
}

 public function generatepdf($awb_pdf,$awbno){	 
      $file = fopen("var/bluedart/".$awbno.".pdf","w+");
      fwrite($file,$awb_pdf);
      fclose($file);
      return;
   }
    
    public function orderexportbluedartAction()
    {
        $request = $this->getRequest();
        $ids = $request->getParam('order_ids');
        $result = $this->generateOrderList($ids);
        $filename = 'Order_bluedart.csv';
        $this->_prepareDownloadResponse($filename, $result);
      
    }
    
    public function generateOrderList($ids)
    {
                $io = new Varien_Io_File();
                $path = Mage::getBaseDir('var') . DS . 'export' . DS;
                $name = md5(microtime());
                $file = $path . DS . $name . '.csv';
                $io->setAllowCreateFolders(true);
                $io->open(array('path' => $path));
                $io->streamOpen($file, 'w+');
                $io->streamLock(true);
				$headers = array(
				'shipper_area',
				'shipper_customercode',
				'shipper_clientname',
				'shipper_address1',
				'shipper_address2',
				'shipper_address3',
				'shipper_pincode',
				'shipper_mobile',
				'shipper_telephone',
				'shipper_sender',
				'shipper_email',
				'IsToPayCustomer',
				'shipper_vendorcode',
				'consignee_name',
				'consignee_adress1',
				'consignee_adress2',
				'consignee_adress3',
				'consignee_pincode',
				'consignee_mobile',
				'consignee_telephone',
				'consignee_attention',
				'service_pieces',
				'service_productcode',
				'service_subproduct',
				'ProductType',
				'service_actweight',
				'service_invoiceno',
				'service_packtype',
				'service_creaditrefno',
				'service_spclinstruction',
				'service_pickupdate',
				'service_pickupreadytime',
				'service_declaredval',
				'service_colamount',
				'service_waybillnumber',
				'service_cmdtydetail1',
				'service_cmdtydetail2',
				'service_cmdtydetail3',
				'service_dimentionsl',
				'service_dimentionsb',
				'service_dimentionsh',
				'service_count',
				'orderid',
				'sku_list',
				'isError',
				'wayBillNumber',
				'DestinationArea',
				'DestinationLocation',
				'ErrorMessage'
				);	
									
			
                $io->streamWriteCsv($headers);
                if (!empty($ids)) {
					foreach($ids as $id)
					{
						$order = Mage::getModel('sales/order')->load($id);
						if($order->canShip())
						{
							$data = array();
							$billingAddress = $order->getBillingAddress();
							$shippingAddress = $order->getShippingAddress();
							
							$street=($shippingAddress) ? $shippingAddress->getData('street'):'';				
							$street_length = strlen($street);	
							$street_addr1 = substr($street, 0, 30); 
							$street_addr2 = substr($street, 30, 30);
							$street_addr3 = substr($street, 60, $street_length);

							if($ConsigneeAddress1 == "") {
								$ConsigneeAddress1 = $street_addr1;
							}
							
							if($ConsigneeAddress2 == "") {
								$ConsigneeAddress2 = $street_addr2;
							}

							if($ConsigneeAddress3 == "") {
								$city = ($shippingAddress) ? $shippingAddress->getData('city'):'';				
								$region = ($shippingAddress) ? $shippingAddress->getData('region'):'';
								$ConsigneeAddress3 = $street_addr3.", ".$city . ", " .$region;
							}
							
							$street=($billingAddress) ? $billingAddress->getData('street'):'';				
							$street_length = strlen($street);	
							$street_addr1 = substr($street, 0, 30); 
							$street_addr2 = substr($street, 30, 30);
							$street_addr3 = substr($street, 60, $street_length);

							if($billingAddress1 == "") {
								$billingAddress1 = $street_addr1;
							}
							
							if($billingAddress2 == "") {
								$billingAddress2 = $street_addr2;
							}

							if($billingAddress3 == "") {
								$city = ($billingAddress) ? $billingAddress->getData('city'):'';				
								$region = ($billingAddress) ? $billingAddress->getData('region'):'';
								$billingAddress3 = $street_addr3.", ".$city . ", " .$region;
							}						
							
							$data['shipper_area'] = Mage::getStoreConfig('bluedart/settings/area');
							$data['shipper_customercode'] = '\''.Mage::getStoreConfig('bluedart/settings/customercode');
							$data['shipper_clientname'] = Mage::getStoreConfig('bluedart/settings/client_name');
							$data['shipper_address1'] = Mage::getStoreConfig('bluedart/settings/client_address1');
							$data['shipper_address2'] = Mage::getStoreConfig('bluedart/settings/client_address2');
							$data['shipper_address3'] = Mage::getStoreConfig('bluedart/settings/client_address3');
							$data['shipper_pincode'] = Mage::getStoreConfig('bluedart/settings/client_pincode');
							
							$data['shipper_mobile'] = Mage::getStoreConfig('bluedart/settings/client_mobile');
							$data['shipper_telephone'] =Mage::getStoreConfig('bluedart/settings/client_telephone');
							$data['shipper_sender'] = Mage::getStoreConfig('bluedart/settings/client_name');

							$data['shipper_email'] = Mage::getStoreConfig('bluedart/settings/client_email');

							$data['IsToPayCustomer'] = '0';
							

							$data['shipper_vendorcode'] = '\''.Mage::getStoreConfig('bluedart/settings/client_vendorcode');
							
							$data['consignee_name'] = $shippingAddress->getFirstname()." ".$shippingAddress->getLastname();
							$data['consignee_adress1'] = $ConsigneeAddress1;							
							$data['consignee_adress2'] = $ConsigneeAddress2;							
							$data['consignee_adress3'] = $ConsigneeAddress3;
							$data['consignee_pincode'] = $shippingAddress->getPostcode();
							$data['consignee_mobile'] = $shippingAddress->getTelephone();
							$data['consignee_telephone'] = $shippingAddress->getTelephone();
							$data['consignee_attention'] = $shippingAddress->getFirstname()." ".$shippingAddress->getLastname();

							$data['service_pieces'] = '';
							$data['service_productcode'] = 'A';
							if($order->getPayment()->getMethodInstance()->getCode() == 'cashondelivery')							
								$data['service_subproduct'] = 'C';
							else
								$data['service_subproduct'] = 'P';
							$data['ProductType'] = 'NDOX';
							
							$data['service_actweight'] = '';
							$data['service_invoiceno'] = '';
							$data['service_packtype'] = '';
							$data['service_creaditrefno'] = $order->getIncrementId();
							$data['service_spclinstruction'] = '';
							$data['service_pickupdate'] = Mage::getModel('core/date')->Date('Y-m-d');  //date('Y-m-d');
							$data['service_pickupreadytime'] = Mage::getModel('core/date')->Date('Hi');
							$data['service_declaredval'] = 0;
							$data['service_colamount'] = '';
							$data['service_waybillnumber'] = '';
							$data['service_cmdtydetail1'] = '';
							$data['service_cmdtydetail2'] = '';
							$data['service_cmdtydetail3'] = '';
							
							$data['service_dimentionsl'] = '1';
							$data['service_dimentionsb'] = '1';
							$data['service_dimentionsh'] = '1';
							$data['service_count'] = '';
							
							$data['orderid'] = $order->getIncrementId();
							$data['sku_list'] = '';				
							
							$data['isError'] = '';
							$data['wayBillNumber'] = '';
							$data['DestinationArea'] = '';
							$data['DestinationLocation'] = '';
							$data['ErrorMessage'] = '';
							

							$ordered_items = $order->getAllItems(); 
							$shipableitems = array();
							$skulistis = "";

							$icount = 0;
							$shpqtt_tot = 0;

							$itm_wt = 0;
							$itm_price = 0;
							$tot_wt = 0;
							$tot_price = 0;

							foreach($ordered_items as $item){ 
								 $shipqty = $item->getQtyOrdered() - $item->getQtyCanceled() - $item->getQtyRefunded() - $item->getQtyShipped();
								 if($shipqty > 0)
								 {
									$icount++;									 
									$data['service_cmdtydetail'.$icount]=$item->getName();									 
									$skulistis = $skulistis.",".$item->getSku().":".$shipqty;

									$shpqtt_tot += $shipqty; // Getting Total Quantity
									
									$itm_price = 0;
									$itm_wt = 0;

									$itm_wt = $item->getWeight() * $shipqty;
									$itm_price = $item->getBasePrice() * $shipqty;

									if($itm_wt != "" && $itm_wt > 0) {
										$tot_wt += $itm_wt;	
									} else {
										$tot_wt += 0;
									}

									if($itm_price != "" && $itm_price > 0) {
										$tot_price += $itm_price;
									} else {
										$tot_price += 0;
									}

								 }	
								
							}
							$skulistis = trim($skulistis,',');
							$data['sku_list']=$skulistis;
							
							$data['service_pieces'] = $shpqtt_tot;
							$data['service_count'] = $shpqtt_tot;
							$data['service_actweight'] = $tot_wt;
							$data['service_declaredval'] = $tot_price;
							if($order->getPayment()->getMethodInstance()->getCode() == 'cashondelivery')	
								$data['service_colamount'] = $data['service_declaredval']; //$order->getGrandTotal();
							else
								$data['service_colamount'] = 0;

							$io->streamWriteCsv($data);							
						
						}
						
					}
				} 
                return array(
                    'type'  => 'filename',
                    'value' => $file,
                    'rm'    => true // can delete file after use
                );
            
        
    }

}
