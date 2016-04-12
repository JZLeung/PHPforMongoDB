<?php
    require 'mongodb.class.php';
    require 'config.php';
    $db = new Mongo_DB($config);

    //testing select database;
    $db->selectDB('test');

    //testing insert data;
    $data = array(
        'name'  =>  'testing',
        'pass'  =>  md5('password')
    );
    //$db->insert('user', $data);
    //$test = Array2String($data);
    //P($test);
    //testing select data;
    $db->select('user');

    //testing findOne data;
    //$data2 = $db->findOne('user');

    //testing update data;
    $update = array(
        'name' => 'testing2',
        'pass' => md5('password2')
    );
    $db->update('user', array('name' => 'testing'), $update, array('upsert' => true ));
    $db->select('user');

    //testing delete data;
    $delete = array(
        'name' => 'testing'
    );
    //$db->delete($delete,'user');
    //$db->select('user');

 ?>
