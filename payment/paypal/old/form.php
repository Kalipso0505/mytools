<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE html>
<html>
	<head>
		<title></title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	</head>
	<body>
		<div><img src="https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif" align="left" style="margin-right:7px;"></div>
		<form method=post action="https://api-3t.sandbox.paypal.com/nvp"> 
			<input type="submit" name="METHOD" value="SetExpressCheckout" />
			<input type="hidden" name="VERSION" value="74.0" /> 
			<input type="hidden" name="USER" value="alexan_1319546114_biz_api1.vengamobile.de" /> 
			<input type="hidden" name="PWD" value="1319546142"/> 
			<input type="hidden" name="SIGNATURE" value="AhBXbbDmtabjD3BtlxGTGw6vGnC8AY69f8UCN43cIGvKPLNO5sJHzcpk"/> 
			
			<input type="hidden" name="PAYMENTREQUEST_0_AMT" value="19.95" /> 
			<input type="hidden" name="PAYMENTREQUEST_0_CURRENCYCODE" value="EUR" /> 
			<input type="hidden" name="PAYMENTREQUEST_0_PAYMENTACTION" value="Sale" /> 
			
			<input type="hidden" name="RETURNURL" value="http://bobmobile.de/" /> 
			<input type="hidden" name="CANCELURL" value="http://www.google.de" /> 
			
		</form>
		
	</body>
</html>
