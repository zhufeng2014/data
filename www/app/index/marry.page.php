<?php

class index_marry extends index_base
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 首页
     */
    public function pageIndex($inPath)
    {
        $name = core_lib_Comm::getStr($_GET['name']);
        $phone = core_lib_Comm::getStr($_GET['phone']);
        $num = core_lib_Comm::getStr($_GET['num']);
        $remark = core_lib_Comm::getStr($_GET['remark']);
        $srvGuest = new core_db_Guest();
        $data['name'] = $name;
        $data['phone'] = $phone;
        $data['num'] = $num;
        $data['remark'] = $remark;
        $srvGuest->addGuest($data);
//        return $this->render("index.html");
    }
}
