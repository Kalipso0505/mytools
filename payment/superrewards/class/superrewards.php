<?php

/**
 * Description of superrewards
 *
 * @author alexander.achim
 */
class superrewards {

	protected $postBackUrl = 'http://www.domain.com/postback.cgi?app=mygreatapp&new=25&total=1000&uid=1234567&oid=123';
	protected $iframeUrl = 'http://www.superrewards-offers.com/super/offers';
	protected $sKey = '';
	protected $sShopId = '';
	protected $iUserId = '';

	public function __construct() {
	}
	
	public function init($iUserId, $sKey, $sShopId) {
		$this->iUserId = $iUserId;
		$this->sKey = $sKey;
		$this->sShopId = $sShopId;		
	}

	public function getIFrameUrl() {
		return $this->iframeUrl . '?h=' . $this->sShopId . '&uid=' . $this->iUserId . '&hdh=meinhash';
	}

	public function evaluatePostback() {
//		echo '<pre>'.var_dump($_REQUEST, 1).'</pre>';
		$sig = md5($_REQUEST['id'] . ':' . $_REQUEST['new'] . ':' . $_REQUEST['uid'] . ':' . $this->sKey);

		if (trim($sig) == trim($_REQUEST['sig'])) {
			echo (integer) $this->updateDB($_POST);
		}
	}

	public function updateDB($values) {
		$sSql = "INSERT INTO `jag_accounts`.`store_superrewards_callback` (`id` ,`new` ,`total` ,`uid` ,`oid` ,`sig`)
VALUES (" . $_REQUEST['id'] . ", " . $_REQUEST['new'] . ", " . $_REQUEST['total'] . ", " . $_REQUEST['uid'] . ", " . $_REQUEST['oid'] . ", '" . $_REQUEST['sig'] . "');";

		$link = mysql_connect('localhost', 'root', '');
		$db = mysql_select_db('jag_accounts', $link);
		$result = mysql_query($sSql, $link);
		if (mysql_errno($link)) {
			throw new \Exception('MySQL error ' . mysql_errno() . ': ' . mysql_error() . "\nWhen executing:" . $query);
		}
		mysql_close($link);
		return $result;
	}

}

?>
