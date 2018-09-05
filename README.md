# spgateway
使用智付通金流範例

## 使用說明
1.  需要有智付通的帳號
2.  填入帳號內的商品編號(MerchantID)、Hash Key、HashIV ,可在智付通管理後台找到  
3.  自定義細部設定，交易與付款結果會回傳至ReturnURL 、NotifyURL 對應的網址
4.  送出可連結至智付通付款介面

## 注意事項
*  環境設定   此範例串接採用正式環境
```
 <form name='Pay2go' method='post' action='https://core.spgateway.com/MPG/mpg_gateway'>
``` 
 若為測試環境請設定為
```
<form name='Pay2go' method='post' action='https://ccore.spgateway.com/MPG/mpg_gateway'>
```
*  正式網站: https://www.spgateway.com/  測試網站: https://cwww.spgateway.com/ 兩者的帳號密碼為獨立的
