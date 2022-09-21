<?php
	
	namespace BSIMAJA;
	
	
	use PHPUnit\Framework\TestCase;
	
	class ClientTest extends TestCase
	{
		
		/**
		 * @var \BSISMARTBILLING\Client
		 */
		private Client $bsiMajaLib;
		
		public function __construct()
		{
			parent::__construct();
			$config = Configurator::getDefaultConfiguration()
				->setUrlMaja(getenv('BSI_URL_MAJA_URL'))
				->setUrlRefreshToken(getenv('BSI_REFRESH_TOKEN_URL'))
				->setClientId(getenv('BSI_CLIENT_ID'))
				->setClientSecret(getenv('BSI_CLIENT_SECRET'))
				->setUsername(getenv('BSI_USERNAME'))
				->setPassword(getenv('BSI_PASSWORD'));
			$this->bsiMajaLib = new Client($config);
		}
		
		public function testCreateInvoice()
		{
			$orders_item_maja = [
				[
					"description" => "Tagihan",
					"unitPrice" => 30000,
					"qty" => 1,
					"amount" => 30000
				]
			];
			$dataAddInvoiceMaja = array(
				'openPayment' => false,
				'va' => 2646546464,
				'amount' => 65000,
				'name' => "Hasan Basri",
				"phone" => "082213542319",
				'attribute1' => "1A",
				'email' => "133333@daarululuumllido.com",
				'items' => $orders_item_maja,
				'date' => date("Y-m-d H:i:s"),
				'activeDate' => date("Y-m-d H:i:s"),
				'inactiveDate' => date('Y-m-d', strtotime("+2 day")),
				'attributes' => []
			);
			$responseCreateInvoice = $this->bsiMajaLib->createInvoice($dataAddInvoiceMaja);
			var_dump($responseCreateInvoice);
		}
		
		public function testUpdateInvoice()
		{
		
		}
		
		public function testInquiryInvoice()
		{
		
		}
		
		public function testCancelInvoice()
		{
		
		}
	}
