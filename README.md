# sign

## 安装

 安装包文件
  ```shell
  composer require "entere/sign:dev-master"
  ```


## 使用

php 实例：

```php

<?php
require_once("./src/Sign.php");

use Entere\Sign\Sign;

$params = ['timestamp'=>'1439279383630','screen_name'=>'entere','format'=>'json'];
$signSecret = 'f827182b1051075e601c73ac1ae329fa';
print_r(Sign::getSign($params,$signSecret));

?>

```


Laravel5 实例：


```php

<?php 

namespace App\Http\Controllers;

use Entere\Sign\Sign;

class WelcomeController extends Controller {
    
    public function index()
    {
        $params = ['timestamp'=>'1439279383630','screen_name'=>'entere','format'=>'json'];
        $signSecret = 'f827182b1051075e601c73ac1ae329fa';
        print_r(Sign::getSign($params,$signSecret));
    }
}

?>

```




## 说明
客户端与服务端的数据交互，大部分应用都采用的 RESTful API 的方式，那么如何确保 API 接口的安全性呢？URL 签名的方式可以确保请求的过程中参数不被修改。

## 约定签名机制

### 1、服务端给客户端分发签名密钥signSecret(请勿泄漏)：

```php
$signSecret = "f827182b1051075e601c73ac1ae329fa";
```

### 2、计算签名
例如 API 请求参数如下:

```json
{
    "timestamp":"1439279383630",
    "screen_name":"entere",
    "format":"json"
}
```

**1. 按参数名进行升序排列** 

timestamp, screen_name, format 其中不包括空值参数

排序后的参数为:

```json
{
    "format":"json"
    "screen_name":"entere",
    "timestamp":"1438279283630",
    
}
```
**2. 构造签名串** 

以 url 参数的方式 构建签名串，格式如下：
    
    key1=value1&key2=value2&key3=value3……

应用到上述示例得到签名串为：

    format=json&screen_name=entere&timestamp=1438279283630

然后把 $signSecret 字符串附加到以上签名串后，最终的 $str 的值是这样： 
    
    format=json&screen_name=entere&timestamp=1438279283630f827182b1051075e601c73ac1ae329fa
    
    

**3. 计算签名** 

对上面的$str 进行md5 签名：

    md5(format=json&screen_name=entere&timestamp=1438279283630f827182b1051075e601c73ac1ae329fa)

并把值转成小写：

    874f7bcbb08cf72afca63c68b2209bb4

**4. 添加签名** 

将计算的签名值以sign参数名，附加到URL请求中。一个典型的API请求如下所示

    https://xxx.com/xxx?format=json&screen_name=entere&timestamp=1438279283630&sign=874f7bcbb08cf72afca63c68b2209bb4


### 3、API比对参数

服务端 API 拿到参数后，以同样的方式签名（去掉 $sign 参数），能过比对 $sign 的值即可判断请求是否被篡改。




## License

MIT