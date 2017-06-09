ddv-exception
===================

Installation - 安装
------------

```bash
composer require ddvphp/ddv-exception
```

Usage - 使用
-----

### 1、设置捕获异常

```php

\DdvPhp\DdvException::setHandler(function (array $r, $e) {
  var_dump($r);  
});


```

### 2、抛出异常

```php

throw new \DdvPhp\DdvException\Error("测试一个异常", 'TEST_A_EXCEPTION');

```

### 3、抛出自定义继承异常

```php

class UserError extends \DdvPhp\DdvException\Error
{
  // 魔术方法
  public function __construct( $message = 'Unknown Error' , $errorId = 'UNKNOWN_ERROR' , $code = '400', $errorData = array() )
  {
    parent::__construct( $message , $errorId , $code, $errorData );
  }
}

throw new UserError("测试一个异常", 'TEST_A_EXCEPTION');

```
