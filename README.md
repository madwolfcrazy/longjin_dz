# 一个测试项目 为Discuz!X 开发 RESTful API 接口

项目采用php slim 框架
我目前主要使用的DX版本为2.5 GBK 编码

### 有空就填坑... ...
#### 填坑... ...
##### 坑...

## /protect/config/index.php 

配置文件示例

```
<?php
# protect/config/index.php

return [
    'db' => [
        'host' => 'xxx',
        'driver' => 'mysql',
        'username' =>'longjing_dev',
        'password' =>'longjing_dev',
        'database' => 'dzx_25',
        'charset'=>'utf8',
        'collation'=>'utf8_unicode_ci',
        'prefix'=>'dzx25_',
    ],
    'debugging' => true,
    'url_pre'  => 'http://xxx.com/DZX2.5/',
    'jwt_secret' => '(*UJ(bk<S-F7>(^T(*a(^T(O)(*&YR*',
    'logined_scope' => [
        'comment_create'
    ],
];
```
