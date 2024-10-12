<?php
/**
 * 请求头设置
 */
function set_header($cookie) {
    return array(
        'Host: zhxg.njtech.edu.cn',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:89.0) Gecko/20100101 Firefox/89.0',
        'Origin: http://zhxg.njtech.edu.cn',
        'Referer: http://zhxg.njtech.edu.cn',
        'Content-Type: application/x-www-form-urlencoded',
        'cookie: '.$cookie,
    );
}
function http_post($url, $post_data,$get_header=false,$cookie) {
    $postdata = http_build_query($post_data);
    $options = array(
        'http' => array(
            'method'        => 'POST',
            'header'        => set_header($cookie),
            'content'       => $postdata,
        )
    );
    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    //var_dump($http_response_header);
    if($get_header) {
        // 需要返回header
        return array(
            'header'    => $http_response_header,
            'body'      => $result,
        );
    } else {
        return $result;
    }
}
    $arr = array();
    //var_dump($_COOKIE);exit;
    $cookie = isset($_COOKIE['JSESSIONID'])?$_COOKIE['JSESSIONID']:"";
    $route = isset($_COOKIE['route'])?$_COOKIE['route']:"";
    $result = http_post("http://zhxg.njtech.edu.cn/xsfw/sys/emapfunauth/getRefreshVerifyCode.do",$arr,true,'JSESSIONID='.$cookie.";route=".$route);
    $id = substr($result['header'][7], 23,32);

    header($result['header'][6]);setcookie("JSESSIONID",$id);
    echo json_encode(array(
        "pic" => $result['body'],
        "header" => $result['header']
    ));