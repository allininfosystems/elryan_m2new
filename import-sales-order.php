<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set("default_socket_timeout", 6000);

$con = mysqli_connect("localhost",'elryan35_test','m2$_*+g$VPA(','elryan35_mag_test');

$con1 = mysqli_connect("localhost","elryan35_m2new","icPRo#w6PC{u","elryan35_m2new");

// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
  else
  {
 /* echo 'Connected'; 
 echo '<br>'; */
}


/* use \Magento\Framework\App\Bootstrap;
include('../app/bootstrap.php');
$bootstrap = Bootstrap::create(BP, $_SERVER);
$objectManager = $bootstrap->getObjectManager(); */
use Magento\Framework\App\Bootstrap;
require __DIR__ . '/app/bootstrap.php';

$bootstrap = Bootstrap::create(BP, $_SERVER);
$objectManager = $bootstrap->getObjectManager();
$objectManager->get('\Magento\Framework\App\State')->setAreaCode('frontend'); // for remove Area code is not set error
$storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
//echo $baseUrl=$storeManager->getStore()->getBaseUrl();

$storeManager = $objectManager->create('\Magento\Store\Model\StoreManagerInterface');


$state = $objectManager->get('\Magento\Framework\App\State');
//$state->setAreaCode('frontend');


    /**
     * Create Order On Your Store
     * 
     * @param array $orderData
     * @return array
     * 
    */
	
$query ="SELECT * from sales_flat_order where entity_id ='5209'";
$resquery = mysqli_query($con,$query);

//$i = 0;
while($rows = mysqli_fetch_array($resquery))
{
//print_r($rows);
        try
		{
$customerFactory = $objectManager->create('\Magento\Customer\Model\CustomerFactory');
$customerRepository = $objectManager->create('\Magento\Customer\Api\CustomerRepositoryInterface');

$formkey = $objectManager->create('\Magento\Framework\Data\Form\FormKey');
$quote = $objectManager->create('\Magento\Quote\Model\QuoteFactory');
$quoteManagement = $objectManager->create('\Magento\Quote\Model\QuoteManagement');
$orderService = $objectManager->create('\Magento\Sales\Model\Service\OrderService');

$cartRepositoryInterface = $objectManager->create('\Magento\Quote\Api\CartRepositoryInterface');
$cartManagementInterface = $objectManager->create('\Magento\Quote\Api\CartManagementInterface');
$shippingRate = $objectManager->create('\Magento\Quote\Model\Quote\Address\Rate');

    	

   $email = $rows['email'];
   
   $order_id = $rows['parent_id'];
   $firstname = $rows['firstname'];
   $lastname = $rows['lastname'];
   
   $date_added = $rows['date_added'];
   $date_modified = $rows['date_modified'];
   
    
	    $store=$storeManager->getStore();
        $websiteId = $storeManager->getStore()->getWebsiteId();
	    $customer=$customerFactory->create();
        $customer->setWebsiteId($websiteId);
        $customer->loadByEmail($email);
		
		$customerId = $customer->getEntityId();
		
		
	 $qbill = "SELECT * FROM `sales_flat_order_payment` where order_id='$order_id'";
    
	$resbill = mysqli_query($con,$qbill);
    $rowsbill = mysqli_fetch_array($resbill);
	
	
	 $qship = "SELECT * FROM `sales_flat_order_address` where order_id='$order_id'";
    
	$resqship = mysqli_query($con,$qship);
    $rowsship = mysqli_fetch_array($resqship);
	
	
		// load customet by email address
        if(!$customer->getEntityId()){
            //If not avilable then create this customer 
            $customer->setWebsiteId($websiteId)
                    ->setStore($store)
                    ->setFirstname($firstname)
                    ->setLastname($lastname)
                    ->setEmail($email) 
                    ->setPassword($email);
            $customer->save();
        }
     
	 
	    $cart_id = $cartManagementInterface->createEmptyCart();
        $cart = $cartRepositoryInterface->get($cart_id);
        $cart->setStore($store);
        // if you have already buyer id then you can load customer directly
        $customer= $customerRepository->getById($customer->getEntityId());
        $cart->setCurrency();
        $cart->assignCustomer($customer); //Assign quote to customer
        //add items in quote
        
		
		$pmethod = $rows['method'];

    $shipmethod = $rows['shipping_method'];

$oquery ="SELECT * FROM `sales_flat_order_item` WHERE parent_id='$order_id'";
$oresquery = mysqli_query($con,$oquery);



while($rows1 = mysqli_fetch_array($oresquery))
{
	try
		{

 $product = $objectManager->create('\Magento\Catalog\Model\Product');

 $sku = $rows1['model'];
 $quantity = $rows1['quantity'];
 $price = $rows1['price'];
 $total = $rows1['total'];

$oquery ="SELECT entity_id FROM `catalog_product_entity` WHERE sku='$sku'";
$oresquery = mysqli_query($con1,$oquery);
$rowsp = mysqli_fetch_array($oresquery);


$product_id = $rowsp['entity_id'];
if($product_id!='')
{
	
        $product=$product->load($product_id);
            $product->setPrice($price);
            $cart->addProduct(
                $product,
                intval($quantity)
            );

}
//add items in quote
		}
		catch(Exception $ex)
			{
			
		echo "Error=".$ex->getMessage();
		$myfile = fopen("log/import-sales-order-error-logs.txt", "a") or die("Unable to open file!"); 
		 $txt = '('.$sku.')'.$ex->getMessage(); fwrite($myfile, "\n". $txt); fclose($myfile);
		}

}
		
		
        //Set Address to quote @todo add section in order data for seperate billing and handle it
        $cart->getBillingAddress()->addData($rowsbill);
        $cart->getShippingAddress()->addData($rowsship);
        // Collect Rates and Set Shipping & Payment Method
      //  $this->shippingRate
        //    ->setCode('flatrate_flatrate')
          //  ->getPrice(1);
        $shippingAddress = $cart->getShippingAddress();
        //@todo set in order data
        $shippingAddress->setCollectShippingRates(true)
            ->collectShippingRates()
            ->setShippingMethod('flatrate_flatrate'); //shipping method
       // $cart->getShippingAddress()->addShippingRate($this->shippingRate);
		
		$shippingAddress->setCollectShippingRates(true)
                        ->collectShippingRates()
                        ->setShippingMethod('flatrate_flatrate');
						
        $cart->setPaymentMethod('checkmo'); //payment method
        //@todo insert a variable to affect the invetory
        $cart->setInventoryProcessed(false);
        // Set sales order payment
        $cart->getPayment()->importData(['method' => 'checkmo']);
        // Collect total and saeve
        $cart->collectTotals();
		//$cart->setCreated_at($date_added);
        //$cart->setUpdated_at($date_modified);
        // Submit the quote and create the order


	    $cart->save();
        $cart = $cartRepositoryInterface->get($cart->getId());
		$order_id = $cartManagementInterface->placeOrder($cart->getId());
		
		$order = $objectManager->create('Magento\Sales\Model\Order')->load($order_id);
		//$order->setCreated_at($date_added);
		//$order->setUpdated_at($date_modified);
        $order->setData('Created_at',$date_added);
		$order->setData('Created_at',$date_added);

		
		//return $order_id;
	    echo '<br> Order id : '.$order_id.'&nbsp; Order Created Successfully';
	
		 } 
			catch(Exception $ex)
			{
			echo "Error=".$ex->getMessage();
		echo '<br>';

		 $myfile = fopen("var/log/import-sales-order-error-logs.txt", "a") or die("Unable to open file!"); 
		 $txt = '('.$order_id.')'.$ex->getMessage(); fwrite($myfile, "\n". $txt); fclose($myfile);
		 
		 
		 
			}
}

?>