## 简介
使用PHP连接MongoDB数据库，并实现简单的数据库中CRUD操作

### 使用
1.  给PHP添加MongoDB拓展
2.  新建`config.php`配置文件
```
<?php
    $config = array(
        'hostname'  =>  '192.168.19.129',
        'username'  =>  '',
        'password'  =>  '',
        'hostport'  =>  '',
        'database'  =>  'mongo'
    );
```
其中，
    1. `hostname`为数据库的地址，本机为`localhost`或者`127.0.0.1`，不填则默认连接本机数据库。
    2. `username`为登陆数据库的用户名，MongoDB默认为空。
    3. `password`为登陆数据库的密码，MongoDB默认为空。
    4. `hostport`为MongoDB监听端口号，MongoDB默认为27017。
    5. `database`为需要连接的数据库名。

3.  引入文件`mongodb.class.php`
```
<?php
        require 'mongodb.class.php';
        require 'config.php';
        $db = new Mongo_DB($config);
```

### 函数说明及其使用
*   `__construct`
    >构造函数。

    需要传入数据库配置信息。配置信息为空时，默认连接本地MongoDB。


*   `connect`
    >连接数据库。在构造函数中调用。

    允许用户在不同使用情况下切换本机或其他数据库。

*   `selectDB`
    >选择数据库。

    允许用户在不同使用情况下切换数据库。

*   `selectCollection`
    >选择集合。

    允许用户在不同使用情况下切换集合。

*   `select`
    >在集合中查找所有符合条件的数据

    传入参数说明：
    1.  `$collectionName`。集合名称，指定查询的集合。
    2.  `$where`。查询条件，指定需要查询的数据的条件，默认为空，即查询集合中所有数据。
    3.  `$dynamic`。是否在遍历时更新数据，默认为`false`，即查询结果集后，在遍历结果集的过程中，若数据发生变化，结果集的数据仍旧保持不变。

    返回结果：
    返回所有符合搜索条件的结果集数组。

*   `findOne`
    >在集合中查找出第一条符合条件的数据

    传入参数说明：
    1.  `$collectionName`。集合名称，指定查询的集合。
    2.  `$where`。查询条件，指定需要查询的数据的条件，默认为空，即查询集合中所有数据。
    3.  `$dynamic`。是否在遍历时更新数据，默认为`false`，即查询结果集后，在遍历结果集的过程中，若数据发生变化，结果集的数据仍旧保持不变。

    返回结果：
    返回第一条符合搜索条件的结果。

*   `insert`
    >在集合中插入一条新数据

    传入参数说明：
    1.  `$collectionName`。集合名称，指定插入数据的集合。
    2.  `$dataArray`。需要插入的数据数组。

    返回结果：
    返回一个结果集，包含成功`ok = 1`或者错误信息`err,errmsg`;
    若在此过程出错，则返回空。

*   `update`
    >在集合中查找符合条件的数据并更新

    传入参数说明：
    1.  `$collectionName`。集合名称，指定更新数据的集合。
    2.  `$where`。更新条件。
    3.  `$newData`。需要更新的数据数组。
    4.  `$options`。包含此次更新的所有参数，如`$multiple`，`$upsert`等。

    返回结果：
    返回一个结果集，包含成功`ok = 1`或者错误信息`err,errmsg`。

*   `updateAll`
    >在集合中查找符合条件的数据并更新所有符合条件的数据

    传入参数说明：
    1.  `$collectionName`。集合名称，指定更新数据的集合。
    2.  `$where`。更新条件。
    3.  `$newData`。需要更新的数据数组。

    返回结果：
    返回一个结果集，包含成功`ok = 1`或者错误信息`err,errmsg`。

*   `delete`
    >在集合中查找符合条件的数据并删除符合条件的数据

    传入参数说明：
    1.  `$collectionName`。集合名称，指定删除数据的集合。
    2.  `$where`。删除条件。

    返回结果：
    返回一个结果集，包含成功`ok = 1`或者错误信息`err,errmsg`。


### 辅助函数
*   `P`函数
    >输出执行的时间和信息。

*   `E`函数
    >输出执行遇到错误的时间和信息。

*   `Array2String`函数
    >将一个数组组拼接成string，方便后续输出。
