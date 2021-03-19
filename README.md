# _Ajax-Upload_
##  _Upload file with php and ajax_ 
### _Ajax筆記_

- _Ajax的contentType編碼預設為`application/x-www-form-urlencoded` 
將contentType設定成false後使用Formdata來傳送，指定Content-Type以編碼`mutipart/form-data`來傳送_


### _踩雷筆記_
- Warning: Cannot modify header information – headers already sent by （路徑）
- 原因：在header方法前如果有輸出內容將會報錯．因為php.ini中的output_buffering有設定緩衝大小，其實可以利用ini_set()方法去設定output_buffering的值。
```sh
output_buffering=OFF  //預設為關閉
output_buffering=ON   //開啟緩衝快取且為最大值
output_buffering=3    //開啟大小為3 bytes的緩衝快取
``` 
