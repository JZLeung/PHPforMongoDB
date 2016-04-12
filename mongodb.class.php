<?php
include 'function.php';
/**
 * PHP 操作MongoDB数据库
 *
 * @author      JZ.Leung
 * @copyright   JZ.Leung
 */
class Mongo_DB{
    protected $_connection;
    protected $_db;
    protected $_collection;
    protected $configs;

    function __construct($config=''){
        $this->configs = $config;
        $this->connect();
        $this->selectDB($config['database']);
    }
    /**
     * 连接数据库
     *
     * @param
     * @return    void
     * @author
     * @copyright
     */
    public function connect(){
        $config = $this->configs;
        if(empty($config))  $host = '';
        else $host = 'mongodb://'.
            ($config['username']?"{$config['username']}":'').
            ($config['password']?":{$config['password']}@":'').
            $config['hostname'].
            ($config['hostport']?":{$config['hostport']}":'').'/'.
            ($config['database']?"{$config['database']}":'');
        try{
            $this->_connection = new MongoClient($host);
            P("succeessfully to connect to MongoDB");
        }catch (Exception $e){
            E($e);
        }
    }
    /**
     * 选择数据库
     *
     * @param     数据库名称，默认为配置项中设置的数据库
     * @return    void
     * @author
     * @copyright
     */
    public function selectDB($dbName = ''){
        if (!$this->_connection) {
            $this->connect();
        }
        $dbName = $dbName == '' ? $this->configs['database'] : $dbName;
        try {
            $this->_db = $this->_connection->selectDB($dbName);
            P("select $dbName database succeessfully ");
        } catch (Exception $e) {
            E($e);
        }
    }
    /**
     * 选择集合
     *
     * @param     集合名称
     * @return    void
     * @author
     * @copyright
     */
    public function selectCollection($collectionName){
        if (!$this->_db) {
            $this->selectDB();
        }
        try {
            $this->_collection = $this->_db->selectCollection($collectionName);
            //P("select $collectionName database succeessfully");
        } catch (Exception $e) {
            E($e);
        }
    }
    /**
     * 插入数据，并返回新的数据
     *
     * @param       $collectionName 需要将数据插入的集合
     *              $dataArray    需要插入集合中的数据
     * @return      成功：返回插入状态（内含MongoDB中唯一标识符：_id）
     *              失败：返回空数据
     * @author
     * @copyright
     */
    public function insert($collectionName, $dataArray){
        //if (!$this->_collection) {
            $this->selectCollection($collectionName);
        //}
        try {
            $res = $this->_collection->insert($dataArray);
            //print_r($dataArray);
            $id = $dataArray['_id'];
            P("Insert succeessfully; _id is $id");
            return $res;
        } catch (Exception $e) {
            E($e);
            return null;
        }
    }
    /**
     * 查询文档
     *
     * @param       $collectionName     需要查询的集合名称
     *              $where              查询条件
     *              $dynamic            遍历游标时是否动态更新数据
     * @return      成功：返回查询结果集
     *              失败：返回空
     * @author
     * @copyright
     */
    public function select($collectionName,  $where = array(), $dynamic = false, $findOne = false){
        //if (!$this->_collection) {
            $this->selectCollection($collectionName);
        //}
        try {
            if ($dynamic) {
                $cursor = $this->_collection->find($where);//遍历游标时文档更新，游标数据也同步更新
            }else{
                $cursor = $this->_collection->find($where)->snapshot();//忽略遍历游标时的文档更新
            }
            $resultSet = null;
            if ($findOne) {
                P('From findOne');
                foreach ($cursor as $key => $value) {
                    $resultSet[] = $value;
                    $show = Array2String($value);
                    P("$show");
                    break;
                }
            }else {
                P("From select");
                foreach ($cursor as $key => $value) {
                    $resultSet[] = $value;
                    $show = Array2String($value);
                    P("$show");
                }
            }
            return $resultSet ? $resultSet : null;
        } catch (Exception $e) {
            E($e);
            return null;
        }
    }
    /**
     * 读取一条数据
     *
     * @param       $collectionName     需要查询的集合名称
     *              $where              查询条件
     *              $dynamic            遍历游标时是否动态更新数据
     * @return      成功：返回查询结果集
     *              失败：返回空
     * @author
     * @copyright
     */
    public function findOne($collectionName, $where = array(), $dynamic = false){
        return $this->select($collectionName, $where, $dynamic, true);
    }
    /**
     * 更新数据
     *
     * @param
     * @param       $collectionName     需要更新的集合名称
     *              $where              查询条件
     *              $newData            更新的数据
     *              $options            更新的参数($multiple,$upsert等)
     * @return      成功：返回查询结果集
     *              失败：返回空
     * @copyright
     */
    public function update($collectionName, $where, $newData, $options = array()){
        //if (!$this->_collection) {
            $this->selectCollection($collectionName);
        //}
        if (empty($options)) {
            $options = array(
                'upsert' => false,
                'multiple' => false
             );
        }
        try {
            $res = $this->_collection->update($where, array('$set' => $newData), $options);
            $res2 = Array2String($res);
            P("Update succeessfully : $res2");
            return $res;
        } catch (Exception $e) {
            E($e);
        }
    }
    /**
     * 更新所有数据
     *
     * @param
     * @param       $collectionName     需要更新的集合名称
     *              $where              查询条件
     *              $newData            更新的数据
     *              $mutiple            是否所有符合条件的数据都更新
     * @return      成功：返回查询结果集
     *              失败：返回空
     * @copyright
     */
    public function updateAll($collectionName, $where, $newData){
        return $this->update($collectionName, $where, $newData, true);
    }
    /**
     * 删除数据
     *
     * @param
     * @return    void
     * @author
     * @copyright
     */
    public function delete($collectionName, $where){
        $this->selectCollection($collectionName);
        try {
            $res = $this->_collection->remove($where);
            $res = Array2String($res);
            P("Delete succeessfully : $res");
            return $res;
        } catch (Exception $e) {
            E($e);
        }
    }
}
