<?php
	namespace BSISMARTBILLING;
	
	use GuzzleHttp\Client as GuzzleClient;
	
	class Configurator
	{
		
		private static Configurator $defaultConfiguration;
		protected string $baseUrl = '';
		protected string $type = '';
		protected string $clientId = '';
		protected string $clientSecret = '';
		protected GuzzleClient $client;
		
		public function __construct()
		{
			$this->client = new GuzzleClient();
		}
		
		/**
		 * Gets the default configuration instance
		 *
		 */
		public static function getDefaultConfiguration(): Configurator
		{
			self::$defaultConfiguration = new Configurator();
			return self::$defaultConfiguration;
		}
		
		public function setUrl(string $url): Configurator
		{
			$this->baseUrl = $url;
			return $this;
		}
		
		public function getUrl(): string
		{
			return $this->baseUrl;
		}
		
		public function getClientId(): string
		{
			return $this->clientId;
		}
		
		public function setClientId(string $clientId): Configurator
		{
			$this->clientId = $clientId;
			return $this;
		}
		
		public function getClientSecret(): string
		{
			return $this->clientSecret;
		}
		
		public function setClientSecret(string $clientSecret): Configurator
		{
			$this->clientSecret = $clientSecret;
			return $this;
		}
	}