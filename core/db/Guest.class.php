<?php

/**
 * db_Guest
 */
class core_db_Guest extends core_db_DbBase
{
    private $table = "mywd_guest";

    public function __construct()
    {
        parent::__construct($this->table);
    }

    /**
     * 添加guest
     * @param $data
     * @return bool
     */
    public function addGuest($data)
    {
        try {
            if (empty($data)) {
                throw new Exception("缺少必要参数");
            }

            //判断数据必选项
            $this->useConfig("common", "main");
            $rs = $this->insertData($data);
            if ($rs === false) {
                throw new Exception("数据写入失败");
            }
            return true;
        } catch (Exception $e) {
            $this->log($e);
            return false;
        }
    }
}
