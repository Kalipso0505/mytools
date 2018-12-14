<?php
/**
 * Description of paypal
 *
 * @author alexander.achim
 */

//Berechtigung	API-Signatur
//API-Benutzername	alexan_1319546245_pre_api1.vengamobile.de
//API-Passwort	5HFLLV6GVM3BD83X
//Unterschrift
//Datum des Antrags	25. Okt 2011 15:22:07 MESZ

class paypal {
	// first
	private $paypalUri = "https://api-3t.sandbox.paypal.com/nvp";
	private $user = "alexan_1319546114_biz_api1.vengamobile.de";
	private $pwd = "1319546142";
	private $subscription = "AhBXbbDmtabjD3BtlxGTGw6vGnC8AY69f8UCN43cIGvKPLNO5sJHzcpk";
	private $methode = "SetExpressCheckout";
	private $version = "74.0";
	private $amounts = array();
	private $siteConfirm = "http://success.paypal.dev";
	private $siteCancel = "http://fail.paypal.dev";

	private $paypallOptions = array();
	private $ident = array();
	private $uri = array();

	// secound
	private $paypalLogin = "https://www.sandbox.paypal.com/cgi-bin/webscr";
	private $cmd = "_express-checkout";

	public function __construct() {
		$this->ident['USER'] = $this->user;
		$this->ident['PWD'] = $this->pwd;
		$this->ident['SIGNATURE'] = $this->subscription;
		$this->paypallOptions['METHOD'] = $this->methode;
		$this->paypallOptions['VERSION'] = $this->version;
		$this->uri['RETURNURL'] = $this->siteConfirm;
		$this->uri['CANCELURL'] = $this->siteCancel;
	}

	//********* PUBLIC *********************************************************
	public function addAmount($value, $currency) {
		$amount = array('value'=>$value, 'currency'=>$currency);
		$this->amounts[] = $amount;
	}

	public function requestAuthentication() {
		$postParams = $this->getInitialPostArray();
		$result = $this->curlRequest($this->paypalUri, $postParams);
		return $this->evaluateShopIdent($result);
	}

	public function requestPerformPayment() {
		$postParams = $this->getPaymentPostArray();
		$result = $this->curlRequest($this->paypalUri, $postParams);
		echo '<pre> bestätigung'.print_r($result, 1).'</pre>';
		$result = $this->evaluatePaymentIdent($result);
		$postParams = $this->processPayments($result);
		echo '<pre>params: '.print_r($postParams, 1).'</pre>';
		$result = $this->curlRequest($this->paypalUri, $postParams);
		echo '<pre> bezahlt'.print_r($result, 1).'</pre>';

		return $result;
	}

	//********* PRIVATE ********************************************************
	private function getInitialPostArray() {
		return array_merge($this->ceateAmountArray(), $this->ident, $this->paypallOptions, $this->uri);
	}

	private function curlRequest($uri, $params) {
		$cws = curl_init();
		curl_setopt($cws, CURLOPT_URL, $uri);
		curl_setopt($cws, CURLOPT_HEADER, 0);

        curl_setopt($cws, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($cws, CURLOPT_SSL_VERIFYHOST, 0);

		curl_setopt($cws, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($cws, CURLOPT_POST, 1);
		curl_setopt($cws, CURLOPT_POSTFIELDS, http_build_query($params));

		curl_setopt($cws, CURLOPT_VERBOSE, 1);
		$result = curl_exec($cws);
		$info = curl_getinfo($cws);
		curl_close($cws);

		return $this->decodeResponse($result);
	}

	private function ceateAmountArray() {
		$amounts = array();
		$cnt = 0;
		if (is_array($this->amounts)) {
			foreach ($this->amounts as $value) {
				$amounts["PAYMENTREQUEST_".$cnt."_AMT"] = $value['value'];
				$amounts["PAYMENTREQUEST_".$cnt."_CURRENCYCODE"] = $value['currency'];
				$amounts["PAYMENTREQUEST_".$cnt."_PAYMENTACTION"] = "Sale";
				++$cnt;
			}
		} else {
			throw new Exception('nichts zum Abuchen angegeben');
		}
		return $amounts;
	}

	private function format4html($uristring){
		return '<pre>Params:<br />'.  str_replace('&','<br />', urldecode($uristring)).'</pre>';
	}

	private function decodeResponse($response) {
		$values = urldecode($response);
		$values = explode('&', $values);
		foreach ($values as $value) {
			$line = explode('=', $value);
			$result[$line[0]] = $line[1];
		}
		return $result;
	}

	private function evaluateShopIdent($response){
		if($response['ACK'] != 'Success') {
			die("Fehler bei der Authentifizierung bei Paypal");
		}
		$parsedTime = str_replace(array('Z','T'), array('',' '), $response['TIMESTAMP']);
		$response['TIMESTAMP'] = DateTime::createFromFormat('Y-m-j H:i:s', $parsedTime);
		$response['loginurl'] = $this->paypalLogin.'?token='.$response['TOKEN'].'&cmd='.$this->cmd;
		return $response;
	}

	private function getPaymentPostArray() {
		$result = array_merge($this->ceateAmountArray(), $this->ident, $this->paypallOptions);
		$result['METHOD'] = 'GetExpressCheckoutDetails';
		$result['TOKEN'] = $_GET['token'];
		return $result;
	}

	private function evaluatePaymentIdent($response) {
		if($response['ACK'] != 'Success') {
			die("Fehler bei der Abfrage der Bestellungsdetails");
		}
		$parsedTime = str_replace(array('Z','T'), array('',' '), $response['TIMESTAMP']);
		$response['TIMESTAMP'] = DateTime::createFromFormat('Y-m-j H:i:s', $parsedTime);
		return $response;
	}

	private function processPayments($paymentIdentResponse) {
		$result = array_merge($this->ceateAmountArray(), $this->ident, $this->paypallOptions);
		$result['METHOD'] = 'DoExpressCheckoutPayment';
		$result['TOKEN'] = $paymentIdentResponse['TOKEN'];
		$result['PAYERID'] = $paymentIdentResponse['PAYERID'];

		$cnt = 0;
		while (isset($paymentIdentResponse['PAYMENTREQUEST_'.$cnt.'_AMT'])) {
			$result['PAYMENTREQUEST_'.$cnt.'_AMT'] = $paymentIdentResponse['PAYMENTREQUEST_'.$cnt.'_AMT'];
//			$result['PAYMENTREQUEST_'.$cnt.'_PAYMENTACTION'] = 'Authorization';
			$result['PAYMENTREQUEST_'.$cnt.'_CURRENCYCODE'] = $paymentIdentResponse['CURRENCYCODE'];
			++$cnt;
		}

		return $result;
	}
}

?>
