<?php
    require 'mongodb.class.php';


    $db = new Mongo_DB();
    $collectionName = 'person';
    $person1 = array(
        'name' => 'Person',
        'pass' => 'password'
    );

    //$db->selectDB('person');

    //插入多条数据。
    // for ($i=0; $i < 5; $i++) {
    //     $person = $person1;
    //     $person['name'] .= $i;
    //     $db->insert($collectionName, $person);
    // }
    //插入一条数据。
    //$db->insert($collectionName, $person1);

    //查询查看是否成功插入。
    $db->select($collectionName);

    //更新数据
    $updatePerson = array('pass' => 'password2');
    $where = array('name' => 'Person2');
    //$db->update($collectionName, $where, $updatePerson);

    //默认使用set更新
    $setPerson = array(
        //'set' => array(
            'pass' => 'password3',
            'safepass' => 'password2',
            'age' => 20
        //)
    );
    // $db->update($collectionName, $where, $setPerson);
    // $db->select($collectionName);

    //使用inc更新数值(数值必须为数字)
    $incPerson = array(
        'age' => array('inc', 4)
    );
    // $db->update($collectionName, $where, $incPerson);
    // $db->select($collectionName);
