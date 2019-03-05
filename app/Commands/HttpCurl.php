<?php
/**
 * @Created by PhpStorm.
 * @author chenli <491126240@qq.com>
 * @Date: 2019/2/22
 * @Time: 8:39
 */

namespace App\Commands;


/**
 * HTTP操作类
 *
 * @author  chenli <491126240@qq.com>
 */

class HttpCurl{

    public static $http_code = null;

    /**
     * 初始化CURL
     * @return resource
     */
    protected static function curlInit() {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        return $ch;
    }

    /**
     * 取得CURL请求结果
     *
     * @param resource $ch        curl资源
     * @param string   $url       指定URL
     * @param mixed    $data      提交的数据（数组或查询字符串，如果是传文件，必需用数组，文件名值前面加@）
     * @param string   $cookie    COOKIE字符中
     * @param string   $referer   指定来源
     * @param string   $userAgent 指定用户标识（浏览器）
     * @param int      $timeout   超时时间
     * @param string   $host      域名HOST
     * @param array    $headers   文件头信息
     *
     * @return mixed
     */
    protected static function curlResult($ch, $url, $data = null, $cookie = null, $referer = null, $userAgent = null, $timeout = 3, $host = null ,$headers = null) {

        curl_setopt_array($ch,
            array(
                CURLOPT_URL => $url,
                CURLOPT_COOKIE => $cookie,
                CURLOPT_REFERER => $referer,
                CURLOPT_USERAGENT => $userAgent,
                CURLOPT_HTTPHEADER =>array('API-RemoteIP:' . self::getRealClientIp()),
                CURLOPT_TIMEOUT => $timeout
            )
        );
        if (!empty($headers) && !empty($host)) {
            $headers[] = "Host: {$host}";
        } elseif (!empty($host)) {
            $headers = array("Host: {$host}");
        }
        $host && curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $data && curl_setopt($ch, CURLOPT_POST, 1);
        $data && curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($ch);

        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        self::$http_code = $http_code;

        if ($result === false) { //出错
            $msg = curl_error($ch);
            $code = curl_errno($ch);
            return self::error($msg, $code);
            trigger_error("[{$code}] {$msg}", E_USER_WARNING);
        }

        curl_close($ch);

        return json_decode($result);
    }

    /**
     * 记录或读取错误信息
     *
     * @param string $msg  错误消息
     * @param string $code 错误码
     *
     * @return array
     */
    public static function error($msg = null, $code = null) {
        static $error_msg = '', $error_code = 0;
        if ($msg !== null && $code !== null) {
            $error_msg = $msg;
            $error_code = $code;
        }
        return array('errmsg' => $error_msg, 'errno' => $error_code);
    }

    /**
     * 通过GET取得一条数据
     *
     * @param string $url       指定URL
     * @param string $cookie    COOKIE字符中
     * @param string $referer   指定来源
     * @param string $userAgent 指定用户标识（浏览器）
     * @param int    $time_out  超时时间
     * @param string $host      域名HOST
     * @param array  $headers   文件头信息
     *
     * @return mixed
     */
    static public function get($url, $cookie = null, $referer = null, $userAgent = null, $time_out = 10, $host = null ,$headers = null) {
        $ch = self::curlInit();
        return self::curlResult($ch, $url, null, $cookie, $referer, $userAgent, $time_out, $host ,$headers);
    }

    /**
     * 通过POST提交一条数据(二进制)
     *
     * @param string $url       指定URL
     * @param mixed  $data      提交的数据（数组或查询字符串，如果是传文件，必需用数组，文件名值前面加@）
     * @param string $cookie    COOKIE字符中
     * @param string $referer   指定来源
     * @param string $userAgent 指定用户标识（浏览器）
     * @param int    $time_out  超时时间
     * @param string $host      域名HOST
     * @param array  $headers   文件头信息
     *
     * @return mixed
     */
    static public function postBin($url, $data, $cookie = null, $referer = null, $userAgent = null, $time_out = 3, $host = null ,$headers = null) {
        $ch = self::curlInit();
        curl_setopt($ch, CURLOPT_POST, 1);
        return self::curlResult($ch, $url, $data, $cookie, $referer, $userAgent, $time_out, $host ,$headers);
    }

    /**
     * 通过POST提交一条数据(urlencode)
     *
     * @param string $url       指定URL
     * @param mixed  $data      提交的数据（数组或查询字符串）
     * @param string $cookie    COOKIE字符中
     * @param string $referer   指定来源
     * @param string $userAgent 指定用户标识（浏览器）
     * @param int    $time_out  超时时间
     * @param string $host      域名HOST
     * @param array  $headers   文件头信息
     *
     * @return mixed
     */
    static public function post($url, $data, $cookie = null, $referer = null, $userAgent = null, $time_out = 3, $host = null ,$headers = null) {
        is_array($data) && $data = http_build_query($data);
        return self::postBin($url, $data, $cookie, $referer, $userAgent, $time_out, $host ,$headers);
    }

    /**
     * 通过DELETE提交一条数据
     *
     * @param string $url       指定URL
     * @param mixed  $data      提交的数据（数组或查询字符串）
     * @param string $cookie    COOKIE字符中
     * @param string $referer   指定来源
     * @param string $userAgent 指定用户标识（浏览器）
     * @param int    $time_out  超时设置
     *
     * @return mixed
     */
    static public function delete($url, $data, $cookie = null, $referer = null, $userAgent = null, $time_out = 10) {
        $ch = self::curlInit();
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        is_array($data) && $data = http_build_query($data);
        return self::curlResult($ch, $url, $data, $cookie, $referer, $userAgent, $time_out);
    }


    /**
     * 获取真实的客户端ip地址
     * This function is copied from login.sina.com.cn/module/libmisc.php/get_ip()
     *
     * @param boolean $to_long 可选。是否返回一个unsigned int表示的ip地址
     *
     * @return string|float		客户端ip。如果to_long为真，则返回一个unsigned int表示的ip地址；否则，返回字符串表示。
     */
    public static function getRealClientIp($to_long = false) {
        $ser =  request()->getServerParams();
        $real_ip = $ser['remote_addr'];
        return $to_long ? self::ip2long($real_ip) : $real_ip;
    }

    /**
     * 修正过的ip2long
     * 可去除ip地址中的前导0。32位php兼容，若超出127.255.255.255，则会返回一个float
     * for example: 02.168.010.010 => 2.168.10.10
     * 处理方法有很多种，目前先采用这种分段取绝对值取整的方法吧……
     *
     * @param string $ip ip address
     *
     * @return float 使用unsigned int表示的ip。如果ip地址转换失败，则会返回0
     */
    public static function ip2long($ip) {
        $ip_chunks = explode('.', $ip, 4);
        foreach ($ip_chunks as $i => $v) {
            $ip_chunks[$i] = abs(intval($v));
        }
        return sprintf('%u', ip2long(implode('.', $ip_chunks)));
    }

}