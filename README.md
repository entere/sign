# 安装

 安装包文件

  ```shell
  composer require "entere/sign:1.0.0"
  ```

# 使用

php 实例：

```php

<?php
require_once("./src/Sign.php");

use Entere\Sign\Sign;

$params = [
    'access_key'=>'7576762362',
    'timestamp'=>'1439279383630',
    'screen_name'=>'entere',
    'format'=>'json'
];
$secret = 'f827182b1051075e601c73ac1ae329fa';
$result = Sign::generateSign($params,$secret);
return $result;

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
        $params = [
            'access_key'=>'7576762362',
            'timestamp'=>'1439279383630',
            'screen_name'=>'entere',
            'format'=>'json'
        ];
        $secret = 'f827182b1051075e601c73ac1ae329fa';
        $result = Sign::generateSign($params,$secret);
        return $result;

    }
}

?>

```




# 说明
客户端与服务端的数据交互，大部分应用都采用的 RESTful API 的方式，那么如何确保 API 接口的安全性呢？URL 签名的方式可以确保请求的过程中参数不被修改。

签名的机制是由开发者在 API 客户端计算出系列参数组合的哈希值，将产生的信息添加到 URL 请求的 sign 参数。

例如 API 请求参数如下:

```json
{
    "access_key":"7576762362",
    "timestamp":"1439279383630",
    "screen_name":"entere",
    "format":"json"
}
```

1、按参数名进行升序排列 

access_key, timestamp, screen_name, format 其中不包括空值参数

排序后的参数为:

```json
{
    "access_key":"7576762362",
    "format":"json",
    "screen_name":"entere",
    "timestamp":"1438279283630",
    
}
```

2、构造签名串 

以secret字符串开头，追加排序后参数名称和值，格式：
    
    secretkey1value1key2value2...
    
    
假设 secret的值为 `f827182b1051075e601c73ac1ae329fa` 应用到上述示例得到签名串为：

    f827182b1051075e601c73ac1ae329faaccess_key7576762362formatjsonscreen_nameenteretimestamp1438279283630



3、计算签名 

对上面的签名串进行 md5 签名：

    md5(f827182b1051075e601c73ac1ae329faaccess_key7576762362formatjsonscreen_nameenteretimestamp1438279283630)

并把值转成小写：

    927c0fc11caaf98840ed7773b348685c

4、添加签名 

将计算的签名值以 sign 参数名，附加到 URL 请求中。一个典型的 API 请求如下所示

    https://xxx.com/xxx?access_key=7576762362&format=json&screen_name=entere&timestamp=1438279283630&sign=927c0fc11caaf98840ed7773b348685c


5、服务器验证

验证请求者的身份：简单判断 access_key。

防止重放攻击：服务器端首先验证时间戳 timestamp 是否有效，比如是服务器时间戳 5 分钟之前的请求视为无效。

保护传输中的数据：服务端收到请求时，将基于相同签名方法（去掉 sign 参数）重新计算哈希，并将其与请求中包括的哈希值进行匹配。如果哈希值不匹配，服务器将返回 401（未授权被拒绝）错误码。




# License

MIT