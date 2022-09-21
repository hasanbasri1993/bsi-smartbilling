<?php
	
	namespace BSISMARTBILLING;
	
	class Parameter
	{
		
		private static Parameter $defaultConfiguration;
		
		/**
		 * createbilling, updatebilling, inquirybilling, deletebilling
		 * This field indicates your
		 * type of action, it is
		 * required for each API
		 * request.
		 * If this parameter is omitted,
		 * error code 005 will be
		 * thrown.
		 * If the provided value other
		 * than the accepted value,
		 * error code 004 will be
		 * thrown.
		 * @var string
		 */
		protected string $type = 'createbilling';
		protected string $client_id = '';
		protected string $trx_id = '';
		protected int $trx_amount = 0;
		/**
		 *  -o
		 *    -c
		 *  -i
		 *  -m
		 *  -n
		 *  -x
		 * @var string
		 */
		protected string $billing_type = 'c';
		protected string $customer_name = '';
		protected string $customer_email = '';
		protected string $customer_phone = '';
		protected string $virtual_account = '';
		protected string $datetime_expired = '';
		protected string $description = '';
		protected string $info = '';
		protected string $datetime_created = '';
		protected string $datetime_last_updated = '';
		protected string $datetime_payment = '';
		protected string $payment_ntb = '';
		protected string $payment_amount = '';
		protected int $va_status;
		protected string $status;
		
		/**
		 * Gets the default configuration instance
		 *
		 */
		public static function getDefaultConfiguration(): Parameter
		{
			self::$defaultConfiguration = new Parameter();
			return self::$defaultConfiguration;
		}
		
		public function setClientId(string $client_id): Parameter
		{
			$this->client_id = $client_id;
			return $this;
		}
		
		public function getClientId(): string
		{
			return $this->client_id;
		}
		
		public function setStatus(string $status): Parameter
		{
			$this->status = $status;
			return $this;
		}
		
		public function getStatus(): string
		{
			return $this->status;
		}
		
		public function setTrxId(string $trx_id): Parameter
		{
			$this->trx_id = $trx_id;
			return $this;
		}
		
		public function getTrxId(): string
		{
			return $this->trx_id;
		}
		
		public function setTrxAmount(int $trx_amount): Parameter
		{
			$this->trx_amount = $trx_amount;
			return $this;
		}
		
		public function getTrxAmount(): int
		{
			return $this->trx_amount;
		}
		
		public function setBillingType(string $billing_type): Parameter
		{
			$this->billing_type = $billing_type;
			return $this;
		}
		
		public function getBillingType(): string
		{
			return $this->billing_type;
		}
		
		public function setCustomerEmail(string $customer_email): Parameter
		{
			$this->customer_email = $customer_email;
			return $this;
		}
		
		public function getCustomerEmail(): string
		{
			return $this->customer_email;
		}
		
		public function setCustomerName(string $customer_name): Parameter
		{
			$this->customer_name = $customer_name;
			return $this;
		}
		
		public function getCustomerName(): string
		{
			return $this->customer_name;
		}
		
		public function setCustomerPhone(string $customer_phone): Parameter
		{
			$this->customer_phone = $customer_phone;
			return $this;
		}
		
		public function getCustomerPhone(): string
		{
			return substr($this->customer_phone, 0, 2) == '62' ? substr_replace($this->customer_phone, "0", 0, 2) : $this->customer_phone;
		}
		
		public function setVirtualAccount(string $virtual_account): Parameter
		{
			$this->virtual_account = $virtual_account;
			return $this;
		}
		
		public function getVirtualAccount(): string
		{
			return $this->virtual_account;
		}
		
		public function setDescription(string $description): Parameter
		{
			$this->description = $description;
			return $this;
		}
		
		public function getDescription(): string
		{
			return $this->description;
		}
		
		public function setInfo(string $info): Parameter
		{
			$this->info = $info;
			return $this;
		}
		
		public function getInfo(): string
		{
			return $this->info;
		}
		
		public function setDatetimeExpired(string $datetime_expired): Parameter
		{
			$this->datetime_expired = $datetime_expired;
			return $this;
		}
		
		public function getDatetimeExpired(): string
		{
			return $this->datetime_expired;
		}
		
		public function setDatetimeCreated(string $datetime_created): Parameter
		{
			$this->datetime_created = $datetime_created;
			return $this;
		}
		
		public function getDatetimeCreated(): string
		{
			return $this->datetime_created;
		}
		
		public function setDatetimeLastUpdated(string $datetime_last_updated): Parameter
		{
			$this->datetime_last_updated = $datetime_last_updated;
			return $this;
		}
		
		public function getDatetimeLastUpdated(): string
		{
			return $this->datetime_last_updated;
		}
		
		public function setDatetimePayment(string $datetime_payment): Parameter
		{
			$this->datetime_payment = $datetime_payment;
			return $this;
		}
		
		public function getDatetimePayment(): string
		{
			return $this->datetime_payment;
		}
		
		public function setPaymentNtb(string $payment_ntb): Parameter
		{
			$this->payment_ntb = $payment_ntb;
			return $this;
		}
		
		public function getPaymentNtb(): string
		{
			return $this->payment_ntb;
		}
		
		public function setPaymentAmount(int $payment_amount): Parameter
		{
			$this->payment_amount = $payment_amount;
			return $this;
		}
		
		public function getPaymentAmount(): int
		{
			return $this->payment_amount;
		}
		
		public function setVaStatus(int $va_status): Parameter
		{
			$this->va_status = $va_status;
			return $this;
		}
		
		public function getVaStatus(): int
		{
			return $this->va_status;
		}
		
	}