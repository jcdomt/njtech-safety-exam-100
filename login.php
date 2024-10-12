<?php
/**
 * 请求头设置
 */
function set_json_header($cookie) {
    return array(
        'Host: zhxg.njtech.edu.cn',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:89.0) Gecko/20100101 Firefox/89.1',
        'Origin: http://zhxg.njtech.edu.cn',
        'Referer: http://zhxg.njtech.edu.cn',
        'Content-Type: application/json',
        'cookie: '.$cookie,
    );
}
function set_header($cookie) {
    return array(
        'Host: zhxg.njtech.edu.cn',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:89.0) Gecko/20100101 Firefox/89.1',
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
function getWEU($str) {
    $b = mb_strpos($str,'_WEU=') + mb_strlen('_WEU=');
    $e = mb_strpos($str,'Path=/xsfw/') - $b;
    return mb_substr($str,$b,$e);
}
function http_get($url, $post_data,$get_header=false,$cookie) {
    $options = array(
        'http' => array(
            'method'        => 'GET',
            'header'        => set_header($cookie),
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
function json_post($url, $post_data,$get_header=false,$cookie) {
    $postdata = json_encode($post_data);
    $options = array(
        'http' => array(
            'method'        => 'POST',
            'header'        => set_json_header($cookie),
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

    $username = $_POST['username'];
    $password = $_POST['password'];
    $cookie = isset($_POST['cookie'])?$_POST['cookie']:"";
    $arr = array(
        'userName'       => $username,
        'password'     => $password,
        'isWeekLogin'    => false,
    );
    $result = http_post("http://zhxg.njtech.edu.cn/xsfw/sys/emapfunauth/loginValidate.do",$arr,true,$cookie);
 
    
    $weu = getWEU($result['header'][7]);
    $cookie1 = $cookie.";_WEU=".$weu;
    $result = http_post("http://zhxg.njtech.edu.cn/xsfw/sys/emappagelog/config/xggzptapp.do",array(),true,$cookie1);
    
    $weu = getWEU($result['header'][6]);
    $cookie1 = $cookie.";_WEU=".$weu;
    $result = http_get("http://zhxg.njtech.edu.cn/xsfw/i18n.do?appName=xggzptapp&EMAP_LANG=zh",array(),true,$cookie1);
    
    $weu = getWEU($result['header'][7]);
    $cookie1 = $cookie.";_WEU=".$weu;
    $arr = array(
        "appId" => "4607046226700803",
        "appName" => "yxapp"
    );
    $result = http_post("http://zhxg.njtech.edu.cn/xsfw/sys/swpubapp/indexmenu/getAppConfig.do?appId=4607046226700803&appName=yxapp",$arr,true,$cookie1);
    
    $weu = getWEU($result['header'][6]);
    $cookie1 = $cookie.";_WEU=".$weu;
    $result = http_post("http://zhxg.njtech.edu.cn/xsfw/sys/yxapp/yxindexs.do",array(),true,$cookie1);
    
     $weu = getWEU($result['header'][5]);
     
    $cookie1 = $cookie.";_WEU=".$weu;
    echo json_encode([
        'cookie'=>$cookie1,
        'msg'=>'success'
    ]);

    setcookie("_WEU",$weu);
     
    