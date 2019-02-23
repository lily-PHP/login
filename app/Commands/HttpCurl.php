<?php
/**
 * @Created by PhpStorm.
 * @author chenli <491126240@qq.com>
 * @Date: 2019/2/22
 * @Time: 8:39
 */

namespace App\Commands;


class HttpCurl
{
    /*
     * 模拟GET, POST, PUT请求
     * @param string $url  要请求的URL
     * @param string $method  请求方式： GET, POST, PUT
     * @param json $data  请求时携带的数据
     * @return array
     */
    public function requestCURL($url, $method, $data=null,$header = null)
    {
        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
        curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);//SSL验证
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);

        //根据不同请求方式数据提交
        switch($method)
        {
            case "GET":
                curl_setopt($curl, CURLOPT_HTTPGET, true);
                break;
            case "POST":
                curl_setopt($curl, CURLOPT_POST,true);
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                if($header !== null){
                    curl_setopt($curl, CURLOPT_HTTPHEADER,$header);
                }
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
        }

        $res = curl_exec($curl); // 执行操作

        if ($error = curl_error($curl)) {

            //CURL请求失败
            return $error;

        }else{
            //curl请求成功，直接返回接口返回的数据
            // $ret['result'] = json_decode($res);
            // return $res;
            return json_decode($res);
        }

        curl_close($curl); // 关闭CURL会话

    }


}