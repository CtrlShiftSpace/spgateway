<?php

	//AES加密函式
	function create_mpg_aes_encrypt ($parameter = "" , $key = "", $iv = "") { 
		$return_str = ''; 
		if (!empty($parameter)) { 
		 	//將參數經過 URL ENCODED QUERY STRING 
		 	$return_str = http_build_query($parameter); 
		}
		return trim(bin2hex(openssl_encrypt(addpadding($return_str), 'aes-256-cbc', $key, OPENSSL_RAW_DATA|OPENSSL_ZERO_PADDING, $iv))); 
 	}

 	//編號補字
	function addpadding($string, $blocksize = 32) { 
		$len = strlen($string); 
		$pad = $blocksize - ($len % $blocksize); 
		$string .= str_repeat(chr($pad), $pad); 
		return $string; 
 	}

	$trade_info_arr = array(

		//商店代碼
		'MerchantID' => '你的商店代號',
		//回傳格式 JSON|String
		'RespondType' => 'JSON',
		//時間戳記
		'TimeStamp' => time(),
		//智付通版本號
		'Version' => '1.4',
		//交易編號(自訂)
		'MerchantOrderNo' => '你的交易編號',
		//價格
		'Amt' => 40,
		//商品敘述
		'ItemDesc' => 'UnitTest',
		//付款人信箱
		'Email' => 'test000000@gmail.com',
		//登入類型(是否需要登入才可使用 1:是 0:否)
		'LoginType' => 0,

		////*以下為選填，未送出仍不影響運作*////

		//交易限制時間(以秒為單位,<60以60秒計算,>900以900秒計算,0為不啟用)
		'TradeLimit' => 0,
		//繳費有效期限(格式為date('Ymd') ex.20140620)
		//預設為起算後7天,最大為180天
		'ExpireDate' => '',
		//交易完成後導回商店頁面網址
		'ReturnURL' => '',
		//回傳支付結果網址
		'NotifyURL' => '',
		//商店取號網址
		'CustomerURL' => '',
		//支付取消返回商店網址
		'ClientBackURL' => '',
		// 付款人電子信箱是否開放修改
		// 1:可修改|0:不可修改
		// 預設為可修改
		'EmailModify' => 0,
		//商店備註
		'OrderComment' => 0,
		// 是否啟用信用卡一次付清
		// 1:啟用|0:不啟用
		// 預設不啟用
		'CREDIT' => 0,
		// 是否啟用Google Pay支付
		// 1:啟用|0:不啟用
		// 預設不啟用
		'ANDROIDPAY' => 0,
		// 是否啟用Samsung Pay支付
		// 1:啟用|0:不啟用
		// 預設不啟用
		'SAMSUNGPAY' => 0,
		// 是否啟用信用卡分期付款
		// 1:開啟所有分期類別,不可帶其它數值|
		// 多類別:以 . 隔開 ex. 3.6.9
		// 項目號碼
		// ------------------
		// |3 = 分 3 期功能
 		// |6 = 分 6 期功能
 		// |12 = 分 12 期功能
 		// |18 = 分 18 期功能
 		// |24 = 分 24 期功能
 		// |30 = 分 30 期功能
 		// ------------------
 		// 0:不啟用
 		// 預設不啟用
		'InstFlag' => 0,
		// 是否啟用信用卡紅利
		// 1:啟用|0:不啟用
		// 預設不啟用
		'CreditRed' => 0,
		// 是否啟用銀聯卡支付
		// 1:啟用|0:不啟用
		// 預設不啟用
		'UNIONPAY' => 0,
		// 是否啟用WEBATM支付
		// 1:啟用|0:不啟用
		// 預設不啟用
		'WEBATM' => 0,
		// 是否啟用ATM 轉帳支付
		// 1:啟用|0:不啟用
		// 預設不啟用
		'VACC' => 0,
		// 是否啟用超商代碼繳費支付
		// 1:啟用|0:不啟用
		// 訂單金額小於 30 元或超過 2 萬元不會顯示此支付
		// 預設不啟用
		'CVS' => 0,
		// 是否啟用超商條碼繳費支付
		// 訂單金額小於 20 元或超過 4 萬元不會顯示此支付
		// 1:啟用|0:不啟用
		// 預設不啟用
		'BARCODE' => 0,
		// 是否啟用Pay2go支付
		// 1:啟用|0:不啟用
		// 預設不啟用
		'P2G' => 0,
		//物流啟用
		//使用前，須先登入智付通會員專區啟用物流並設定退貨門市與取貨人相關資訊
		// 1: 啟用超商取貨不付款 
 		// 2: 啟用超商取貨付款 
 		// 3: 超商取貨不付款及超商取貨付款 
 		// 0: 不啟用
 		// 訂單金額小於 30 元或大於 2 萬元時不會顯示此支付
 		// 預設不啟用
		'CVSCOM' => 0

	);

	//設定Hash Key
	$mer_key = '你的Hash Key';
	//設定Hash IV
	$mer_iv = '你的Hash IV';

	//交易資料經 AES 加密後取得 TradeInfo
	$tradeInfo = create_mpg_aes_encrypt($trade_info_arr, $mer_key, $mer_iv);

	$tradeSha_str = "HashKey=$mer_key&$tradeInfo&HashIV=$mer_iv";
	//SHA256加密
	$tradeSha  = strtoupper(hash("sha256", $tradeSha_str));

?>

<html>
	<form name='Pay2go' method='post' action='https://core.spgateway.com/MPG/mpg_gateway'>
		MerchantID：
		<input type='text' name='MerchantID' value="<?php echo $trade_info_arr['MerchantID']; ?>" >
		<br>

		版本：
		<input type='text' name='Version' value="<?php echo $trade_info_arr['Version']; ?>" >
		<br>

		回傳格式:
		<input type='text' name='RespondType' value="<?php echo $trade_info_arr['RespondType']; ?>" >
		<br>

		TradeInfo:
		<input type='text' name='TradeInfo' value="<?php echo $tradeInfo ?>" >
		<br>

		TradeSha:
		<input type='text' name='TradeSha' value="<?php echo $tradeSha ?>" >
		<br>

		時間戳記:
		<input type='text' name='TimeStamp' value="<?php echo $trade_info_arr['TimeStamp']; ?>" >
		<br>

		商店訂單編號:
		<input type='text' name='MerchantOrderNo' value="<?php echo $trade_info_arr['MerchantOrderNo']; ?>" >
		<br>

		訂單金額:
		<input type='text' name='Amt' value="<?php echo $trade_info_arr['Amt']; ?>" >
		<br>

		商品敘述:
		<input type='text' name='ItemDesc' value="<?php echo $trade_info_arr['ItemDesc']; ?>" >
		<br>

		Email:
		<input type='text' name='Email' value="<?php echo $trade_info_arr['Email']; ?>" >
		<br>

		智付通會員:
		<input type='text' name='LoginType' value="<?php echo $trade_info_arr['LoginType']; ?>" >
		<br>

		<!--交易限制時間:-->
		<input type='hidden' name='TradeLimit' value="<?php echo $trade_info_arr['TradeLimit']; ?>" >
		<!--繳費有效期限:-->
		<input type='hidden' name='ExpireDate' value="<?php echo $trade_info_arr['ExpireDate']; ?>" >
		<!--返回商店網址:-->
		<input type='hidden' name='ReturnURL' value="<?php echo $trade_info_arr['ReturnURL']; ?>" >
		<!--支付通知網址:-->
		<input type='hidden' name='NotifyURL' value="<?php echo $trade_info_arr['NotifyURL']; ?>" >
		<!--商店取號網址:-->
		<input type='hidden' name='CustomerURL' value="<?php echo $trade_info_arr['CustomerURL']; ?>" >
		<!--支付取消返回商店網址:-->
		<input type='hidden' name='ClientBackURL' value="<?php echo $trade_info_arr['ClientBackURL']; ?>" >
		<!--付款人電子信箱是否開放修改:-->
		<input type='hidden' name='EmailModify' value="<?php echo $trade_info_arr['EmailModify']; ?>" >
		<!-- 商店備註:-->
		<input type='hidden' name='OrderComment' value="<?php echo $trade_info_arr['OrderComment']; ?>" >
		<!--信用卡一次付清啟用:-->
		<input type='hidden' name='CREDIT' value="<?php echo $trade_info_arr['CREDIT']; ?>" >
		<!--Google Pay啟用:-->
		<input type='hidden' name='ANDROIDPAY' value="<?php echo $trade_info_arr['ANDROIDPAY']; ?>" >
		<!--SAMSUNG PAY啟用:-->
		<input type='hidden' name='SAMSUNGPAY' value="<?php echo $trade_info_arr['SAMSUNGPAY']; ?>" >
		<!--信用卡分期付款啟用:-->
		<input type='hidden' name='InstFlag' value="<?php echo $trade_info_arr['InstFlag']; ?>" >
		<!--信用卡紅利啟用:-->
		<input type='hidden' name='CreditRed' value="<?php echo $trade_info_arr['CreditRed']; ?>" >
		<!--銀聯卡啟用:-->
		<input type='hidden' name='UNIONPAY' value="<?php echo $trade_info_arr['UNIONPAY']; ?>" >
		<!--WEBATM啟用:-->
		<input type='hidden' name='WEBATM' value="<?php echo $trade_info_arr['WEBATM']; ?>" >
		<!--ATM轉帳啟用:-->
		<input type='hidden' name='VACC' value="<?php echo $trade_info_arr['VACC']; ?>" >
		<!--CVS啟用:-->
		<input type='hidden' name='CVS' value="<?php echo $trade_info_arr['CVS']; ?>" >
		<!--BARCODE啟用:-->
		<input type='hidden' name='BARCODE' value="<?php echo $trade_info_arr['BARCODE']; ?>" >
		<!--Pay2go 電子錢包啟用 :-->
		<input type='hidden' name='P2G' value="<?php echo $trade_info_arr['P2G']; ?>" >
		<!--物流啟用:-->
		<input type='hidden' name='CVSCOM' value="<?php echo $trade_info_arr['CVSCOM']; ?>" >



		<br>

		<input type='submit' value='Submit'>
	</form>
</html>