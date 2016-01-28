<?php
namespace LaneWeChat\Core;
/**
 * 微信Access_Token的获取与过期检查
 * Created by Lane.
 * User: lane
 * Date: 13-12-29
 * Time: 下午5:54
 * Mail: lixuan868686@163.com
 * Website: http://www.lanecn.com
 */
class JsapiTicket{

    /**
     * 获取微信Access_Token
     */
    public static function getJsapiTicket(){
        //检测本地是否已经拥有access_token，并且检测access_token是否过期
        $JsapiTicket = self::_checkJsapiTicket();
        if($JsapiTicket === false){
            $JsapiTicket = self::_getJsapiTicket();
        }
        return $JsapiTicket['ticket'];
    }

    /**
     * @descrpition 从微信服务器获取js sdk api_ticket
     * @return Ambigous|bool
     */
    private static function _getJsapiTicket(){
        $accessToken = AccessToken::getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=".$accessToken;
        $JsapiTicket = Curl::callWebServer($url, '', 'GET');
        if(!isset($JsapiTicket['ticket'])){
            return Msg::returnErrMsg(MsgConstant::ERROR_GET_ACCESS_TOKEN, '获取js ticket失败');
        }

        $JsapiTicket['time'] = time();
        $JsapiTicketJson = json_encode($JsapiTicket);
        //存入数据库
        $db = new mysql;
        $db->connect(DBHOST, DBUSER, DBPASSWORD, DBNAME);
        $sql = "update jsapiticket set ticket='".$JsapiTicketJson."' where id=1";
        $db->query($sql);
        return $JsapiTicket;
    }

    /**
     * @descrpition 检测微信ACCESS_TOKEN是否过期
     *              -10是预留的网络延迟时间
     * @return bool
     */
    private static function _checkJsapiTicket(){
        //获取access_token。是上面的获取方法获取到后存起来的。
        $db = new mysql;
        $db->connect(DBHOST, DBUSER, DBPASSWORD, DBNAME);
        $tempJsapiTicket = $db->fetch_first("select * from jsapiticket");
        $JsapiTicket['value'] = $tempJsapiTicket['ticket'];
        if(!empty($JsapiTicket['value'])){
            $JsapiTicket = json_decode($JsapiTicket['value'], true);
            if(time() - $JsapiTicket['time'] < $JsapiTicket['expires_in']-20){
                return $JsapiTicket;
            }
        }
        return false;
    }

    public static function getSignPackage($url) {
        $jsapiTicket = self::getJsapiTicket();
        
        $timestamp = time();
        $nonceStr = self::createNonceStr();

        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

        $signature = sha1($string);

        $signPackage = array(
          "appId"     => WECHAT_APPID,
          "nonceStr"  => $nonceStr,
          "timestamp" => $timestamp,
          "url"       => $url,
          "signature" => $signature,
          "rawString" => $string
        );
        return $signPackage; 
    }

    private static function createNonceStr($length = 16) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
          $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }
}
?>