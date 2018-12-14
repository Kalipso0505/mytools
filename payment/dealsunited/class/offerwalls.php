<?php

/**
 * this interfaces must be implemented by all offerwalls
 * @author Alexander Achim
 */
interface iOfferwall {
	/**
	 * this function concats the urlstring used in the iframe-src
	 * @access public
	 */
	public function getIFrameUrl();
	
	/**
	 * called in postback; checks securitytoken and calls store of data
	 */
	public function evaluatePostback($DB);
}
?>
