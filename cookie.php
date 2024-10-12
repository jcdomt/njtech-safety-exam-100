<?php

function set_header() {
    return array(
        'Host: zhxg.njtech.edu.cn',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:89.0) Gecko/20100101 Firefox/89.0',
        'Origin: http://zhxg.njtech.edu.cn',
        'Referer: http://zhxg.njtech.edu.cn',
        'Content-Type: application/x-www-form-urlencoded'
    );
}
function http_post($url, $post_data,$get_header=false) {
    $postdata = http_build_query($post_data);
    $options = array(
        'http' => array(
            'method'        => 'POST',
            'header'        => set_header(),
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


$result = http_post("http://zhxg.njtech.edu.cn/xsfw/sys/emapfunauth/pages/funauth-login.do",array(),true);


$id = substr($result['header'][7], 23,32);
setcookie("JSESSIONID",$id);
$id = substr($result['header'][6], 18,32);
setcookie("route",$id);
setcookie("_WEU","");