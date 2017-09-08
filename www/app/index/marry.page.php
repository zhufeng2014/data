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

    /**
     * 获取微信签名
     */
    public function pageGetWxConfig()
    {
        $token = $this->getWxToken();
        $ticket = $this->getWxJsapiTicket($token);
        $configData = $this->createSha($ticket);
        $configData['appId'] = WXAPPID;
        echo core_lib_Comm::json($configData);
        exit;
    }

    /**
     * 获取微信令牌
     */
    public function getWxToken()
    {
        session_start();
        $token = $_SESSION['token'];
        $tokenExpire = $_SESSION['tokenExpire'];
        if (!($token && $tokenExpire) || $tokenExpire < time()) {
            $tokenUrl = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . WXAPPID . '&secret=' . WXAPPSECRET;
            $tokenRS = file_get_contents($tokenUrl);
            $tokens = json_decode($tokenRS, true);
            $token = $tokens['access_token'];
            $_SESSION['token'] = $token;
            $_SESSION['tokenExpire'] = time() + 7000;
        }
        return $token;
    }

    /**
     * 获取jsapi ticket
     */
    public function getWxJsapiTicket($token){
        if (!$token) {
            return false;
        }

        session_start();
        $ticket = $_SESSION['ticket'];
        $ticketExpire = $_SESSION['ticketExpire'];
        if (!($token && $ticketExpire) || $ticketExpire < time()) {
            $ticketUrl = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token='.$token.'&type=jsapi';
            $ticketRS = file_get_contents($ticketUrl);
            $tickets = json_decode($ticketRS, true);
            $ticket = $tickets['ticket'];
            $_SESSION['ticket'] = $ticket;
            $_SESSION['ticketExpire'] = time() + 7000;
        }
        return $ticket;
    }

    /**
     * 创建加密串
     */
    public function createSha($ticket)
    {
        $timestamp = time();
        $wxnonceStr = "wxmarryticket";
        $wxticket = $ticket;
        $wxOri = sprintf("jsapi_ticket=%s&noncestr=%s×tamp=%s&url=%s",
            $wxticket, $wxnonceStr, $timestamp,
            'http://ly1314520.com/marry'
        );
        $wxSha1 = sha1($wxOri);
        $data['timestamp'] = $timestamp;
        $data['nonceStr'] = $wxnonceStr;
        $data['signature'] = $wxSha1;
        return $data;
    }
}
