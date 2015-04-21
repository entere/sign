# sign

## 安装

 安装包文件
  ```shell
  composer require "entere/sign:dev-master"
  ```

## 使用


实例：


```php
<?php namespace App\Http\Controllers;

use Entere\Sign\Sign;;

class WelcomeController extends Controller {
    
    public function index()
    {
    	$params = ['name'=>'entere','age'=>4,'ip'=>'127.0.0.1'];
    	$key = 'entere';
        var_dump(Sign::getSign($params,$key));
    }
}
```

## License

MIT