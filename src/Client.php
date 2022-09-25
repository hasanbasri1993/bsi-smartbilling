<?php
	
	namespace BSISMARTBILLING;
	
	use GuzzleHttp\Client as GuzzleClient;
	use GuzzleHttp\Exception\GuzzleException;
	
	class Client
	{
		const TIME_DIFF_LIMIT = 480;
		
		public string $client_id;
		public string $secret_key;
		private string $url;
		private GuzzleClient $client;
		
		function __construct(Configurator $config)
		{
			$this->client = new GuzzleClient();
			$this->client_id = $config->getClientId();
			$this->secret_key = $config->getClientSecret();
			$this->url = $config->getUrl();
		}
		
		private static function encrypt(array $json_data, $cid, $secret): string
		{
			return self::doubleEncrypt(strrev(time()) . '.' . json_encode($json_data), $cid, $secret);
		}
		
		private static function decrypt($hashed_string, $cid, $secret)
		{
			$parsed_string = self::doubleDecrypt($hashed_string, $cid, $secret);
			list($timestamp, $data) = array_pad(explode('.', $parsed_string, 2), 2, null);
			if (self::tsDiff(strrev($timestamp)) === true) {
				return json_decode($data, true);
			}
			return null;
		}
		
		private static function tsDiff($ts): bool
		{
			return abs($ts - time()) <= self::TIME_DIFF_LIMIT;
		}
		
		private static function doubleEncrypt($string, $cid, $secret): string
		{
			$result = self::enc($string, $cid);
			$result = self::enc($result, $secret);
			return strtr(rtrim(base64_encode($result), '='), '+/', '-_');
		}
		
		private static function enc($string, $key): string
		{
			$result = '';
			$str_length = strlen($string);
			$str__key_length = strlen($key);
			for ($i = 0; $i < $str_length; $i++) {
				$char = substr($string, $i, 1);
				$keychar = substr($key, ($i % $str__key_length) - 1, 1);
				$char = chr((ord($char) + ord($keychar)) % 128);
				$result .= $char;
			}
			return $result;
		}
		
		private static function doubleDecrypt($string, $cid, $secret): string
		{
			$result = base64_decode(strtr(str_pad($string, ceil(strlen($string) / 4) * 4, '='), '-_', '+/'));
			$result = self::dec($result, $cid);
			return self::dec($result, $secret);
		}
		
		private static function dec($string, $key): string
		{
			$result = '';
			$str_length = strlen($string);
			$str__key_length = strlen($key);
			for ($i = 0; $i < $str_length; $i++) {
				$char = substr($string, $i, 1);
				$keychar = substr($key, ($i % $str__key_length) - 1, 1);
				$char = chr(((ord($char) - ord($keychar)) + 256) % 128);
				$result .= $char;
			}
			return $result;
		}
		
		private function hashed_string($data): string
		{
			return self::encrypt(
				$data,
				$this->client_id,
				$this->secret_key
			);
		}
		
		function responeClient($data)
		{
			return self::decrypt($data, $this->client_id, $this->secret_key);
		}
		
		private function requestClient($body)
		{
			try {
				$hashed_string = $this->hashed_string($body);
				$response = $this->client->request('POST', $this->url, [
					'headers' => [
						'Content-Type' => 'application/json'
					],
					'json' => array(
						'client_id' => $this->client_id,
						'data' => $hashed_string,
					)
				]);
				if ($response->getBody()) {
					$response_json = json_decode($response->getBody(), true);
					if ($response_json['status'] !== '000') return $response_json;
					else return self::decrypt($response_json['data'], $this->client_id, $this->secret_key);
				}
			} catch (GuzzleException $e) {
				return $e->getMessage();
			}
			return true;
		}
		
		/**
		 * @param Parameter $param
		 * Parameter Mandatory
		 *  client_id
		 *  trx_id
		 *  trx_amount
		 *  billing_type
		 *  customer_name
		 *  customer_email
		 *  customer_phone
		 * @return mixed|string|null
		 */
		public function createBilling(Parameter $param)
		{
			if (!empty(self::createBillingCheck($param)))
				return self::createBillingCheck($param);
			
			$data_request = array(
				'type' => 'createbilling',
				'client_id' => $param->getClientId(),
				'trx_id' => $param->getTrxId(),
				'trx_amount' => $param->getTrxAmount(),
				'billing_type' => $param->getBillingType(),
				'customer_name' => $param->getCustomerName(),
				'customer_email' => $param->getCustomerEmail(),
				'customer_phone' => $param->getCustomerPhone()
			);
			if (!empty($param->getVirtualAccount())) $data_request['virtual_account'] = $param->getVirtualAccount();
			if (!empty($param->getDatetimeExpired())) $data_request['datetime_expired'] = $param->getDatetimeExpired();
			if (!empty($param->getDescription())) $data_request['description'] = $param->getDescription();
			return $this->requestClient($data_request);
		}
		
		function createBillingCheck(Parameter $param): string
		{
			if (empty($param->getClientId())) return 'Client ID is required';
			if (empty($param->getTrxId())) return 'Transaction ID is required';
			if (empty($param->getTrxAmount())) return 'Transaction Amount is required';
			if (empty($param->getBillingType())) return 'Billing Type is required';
			if (empty($param->getCustomerName())) return 'Customer Name is required';
			if (empty($param->getCustomerEmail())) return 'Customer Email is required';
			if (empty($param->getCustomerPhone())) return 'Customer Phone is required';
			return "";
		}
		
		/**
		 * @param Parameter $param
		 * Parameter Mandatory
		 *  client_id
		 *  trx_id
		 * @return mixed|string|null
		 */
		public function inquiryBilling(Parameter $param)
		{
			if (!empty(self::inquiryBillingCheck($param)))
				return self::inquiryBillingCheck($param);
			
			$data_request = array(
				'type' => 'inquirybilling',
				'client_id' => $param->getClientId(),
				'trx_id' => $param->getTrxId()
			);
			return $this->requestClient($data_request);
		}
		
		function inquiryBillingCheck(Parameter $param): string
		{
			if (empty($param->getClientId())) return 'Client ID is required';
			if (empty($param->getTrxId())) return 'Transaction ID is required';
			return "";
		}
		
		/**
		 * @param Parameter $param
		 * Parameter Mandatory
		 *  client_id
		 *  trx_id
		 *  trx_amount
		 *  customer_name
		 *  customer_email
		 *  customer_phone
		 * @return mixed|string|null
		 */
		public function updateBilling(Parameter $param)
		{
			if (!empty(self::updateBillingCheck($param)))
				return self::updateBillingCheck($param);
			
			$data_request = array(
				'type' => 'updatebilling',
				'client_id' => $param->getClientId(),
				'trx_id' => $param->getTrxId(),
				'trx_amount' => $param->getTrxAmount(),
				'customer_name' => $param->getCustomerName(),
				'customer_email' => $param->getCustomerEmail(),
				'customer_phone' => $param->getCustomerPhone()
			);
			if (!empty($param->getDatetimeExpired())) $data_request['datetime_expired'] = $param->getDatetimeExpired();
			if (!empty($param->getDescription())) $data_request['description'] = $param->getDescription();
			return $this->requestClient($data_request);
		}
		
		function updateBillingCheck(Parameter $param): string
		{
			if (empty($param->getClientId())) return 'Client ID is required';
			if (empty($param->getTrxId())) return 'Transaction ID is required';
			if (empty($param->getTrxAmount())) return 'Amount is required';
			if (empty($param->getCustomerName())) return 'Customer Name is required';
			if (empty($param->getCustomerEmail())) return 'Customer Email is required';
			if (empty($param->getCustomerPhone())) return 'Customer Phone is required';
			return "";
		}
		
		/**
		 * @param Parameter $param
		 * Parameter Mandatory
		 *  client_id
		 *  trx_id
		 * @return mixed|string|null
		 */
		public function deleteBilling(Parameter $param)
		{
			if (!empty(self::deleteBillingCheck($param)))
				return self::deleteBillingCheck($param);
			
			$data_request = array(
				'type' => 'deletebilling',
				'client_id' => $param->getClientId(),
				'trx_id' => $param->getTrxId()
			);
			return $this->requestClient($data_request);
		}
		
		function deleteBillingCheck(Parameter $param): string
		{
			if (empty($param->getClientId())) return 'Client ID is required';
			if (empty($param->getTrxId())) return 'Transaction ID is required';
			return "";
		}
		
	}