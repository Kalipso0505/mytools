<?php

include_once 'offerwalls.php';

/**
 * Description of dunited
 *
 * @author alexander.achim
 */
class dunited implements iOfferwall {

	/**
	 * Via 'Export URL' you can check for all new completed deals of your campaign.
	 * @var string
	 */
	private $exportUrl = "http://www.dealunited.com/public/Api/export/";

	/**
	 * url used in the iframe, which calls the offerwall
	 * @var string
	 */
	private $codeUrl = "https://www.dealunited.com/public/campaignrefi/landing/";

	/**
	 * customvalues, which are send with iframeurl and are passed back in postbackurl by dealunited
	 * @var type
	 */
	private $aIFrameUrlParams = array();

	/**
	 * relevant params from postback-request past by DU
	 * @var array
	 */
	private $aPostbackParams = array (	'iid',	// campaign ID, given bei dunited
										'id',	// our IdUser
										'email',
										'date',	// 	Unix timestamp
										'rev',	// 	revenue (net) in resp. cur (de => €, uk => £) 	decimal 	12.75
										'cur',	// 	country/currency 	string(3) 	de, uk, int
										'opt', 'opt1', 'opt2', 'opt3', 'opt4', 'checksum');

	/**
	 * relevant securityparams from postback-request
	 * @var array
	 */
	private $aSecurityParams = array ('iid', 'id', 'email', 'timestamp', 'rev', 'cur', 'opt', 'opt1', 'opt2', 'opt3', 'opt4');

	/**
	 * Because this shop provides no parameter for the constructors in the class-load-method, params are separatly set by this function
	 * we don't use country as parameter because of the advise from dealunited:
	 * "We advise you not to specify the 'country' parameter when calling the campaign. This way, our system can automatically display the appropriate country, depending from where the user is coming from."
	 *
	 * @access public
	 * @param integer $iUserId our userid from jag_accounts
	 * @param string $sKey provided by superrewards; can be foud in superrewards.ini
	 * @param string $sShopId provided by superrewards; can be foud in superrewards.ini
	 */
	public function init($iShopId, $sGame, $sLanguage, $sSh, $sOh, $sPh) {
		$params['iId']		= $iShopId;
		$params['iIid']		= $sSh;
		$params['opt']		= $sOh;
		$params['opt1']		= $sPh;
		$params['opt2']		= $sGame;
		$params['opt3']		= $sLanguage;
		$this->aIFrameUrlParams = $params;
	}

	/**
	 * Creates a Url for the iframe based on some property:<br />
	 * <ul>
	 * <li>iframeUrl</li>
	 * <li>sShopId</li>
	 * <li>iUserId</li>
	 * <li>aAdditionalPostbackParams</li>
	 * </ul>
	 * @access public
	 * @return string url used 4 the iframe
	 */
	public function getIFrameUrl() {
		return $this->codeUrl .$this->buildDUParams();
	}

	/**
	 * This function is called in Postback and evaluates the Request from superrewards. It provides the returncodes and puts them into DB. 0 = fail, 1 = success
	 * @param object $DB databaseclass from Lupercal
	 * @access public
	 * @return string error / payment : string 4 logging
	 */
	public function evaluatePostback($DB) {
		$md5 = md5($this->concatParams());
		$result = FALSE;
		if (trim($sig) == trim($_GET['checksum'])) {
			$result = $this->updateDB($DB);
		}
		// superrewards expects 0, if postback fails, otherwise 1
		echo (integer) $result;
		return ($result)? 'error' : 'payment';
	}

	/**
	 * inserts requestparams into db
	 * object $oDB databaseclass from Lupercal
	 * @access private
	 * @return type
	 */
	private function updateDB($oDB) {
		$sSql = $oDB->createInsertString('store_dealsunited_callback', $this->getParamsFromRequest());
		$oDB->query_insert($sSql);
		// the returnvalue of query_insert is no indication 4 success; if the Application reaches this point, there was no sqlerror ans success is assumed
		return TRUE;
	}

	/**
	 * reads params defined in aSecurityParams from request and concats them to a string separated by :
	 * @access private
	 * @return string concated values defined in aSecurityParams read from request
	 */
	private function concatParams() {
		$result = '';
		foreach ($this->aSecurityParams as $value) {
			$result .= (empty ($_GET[$value])) ? '' : $_GET[$value];
		}
		return $result;
	}

	/**
	 * loads all params given in aParams from request into an separat array
	 * @access private
	 * @return array values defined in aParams read from request
	 */
	private function getParamsFromRequest() {
		$result = array();
		foreach ($this->aPostbackParams as $value) {
			if(isset($_GET[$value])) {
				$result[$value] = $_GET[$value];
			}
		}
		return $result;
	}

	/**
	 * concats all iFramparameter to url-segments: /key/value/key2/value2/..
	 * @access private
	 * @return string urlsegments
	 */
	private function buildDUParams() {
		$result = '/';
		foreach ($this->aIFrameUrlParams as $key => $value) {
			if(!empty ($value)) {
				$result .= "$key/".urlencode($value).'/';
			}
		}
		return $result;
	}
}

?>
