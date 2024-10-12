<?php
/**
 * 请求头设置
 */
function set_json_header($cookie) {
    return array(
        'Host: zhxg.njtech.edu.cn',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:89.0) Gecko/20100101 Firefox/89.0',
        'Origin: http://zhxg.njtech.edu.cn',
        'Referer: http://zhxg.njtech.edu.cn',
        'Content-Type: application/json',
        'cookie: '.$cookie,
    );
}
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


$main = '{"WJDM":"'.$_POST['id'].'","KSSJ":"2023-08-28 15:05:10","fwzid":"00D039B0AD81832CE0630912A8C093F5","fwxid":"entranceEducation","SFZDTJ":"0","answers":"';
$cookie = $_POST['cookie'];
$arr = array(
    "WJDM" => $_POST['id']
);
$arr = ["data"=>json_encode($arr)];
$result = http_post("http://zhxg.njtech.edu.cn/xsfw/sys/yxapp/modules/onlineAnswer/queryPaperAllQuestionList.do",$arr,false,$cookie); 

$answer = "[";
$result = json_decode($result,true);
$data = $result['data']['allQuestions'];
for($i=0;$i<count($data);$i++) {
    $t = '{\"TMDM\":\"'.$data[$i]['TMDM'].'\",\"DAXX\":\"';
    $tt = '';
    for($j=0;$j<count($data[$i]['xxArray']);$j++) { 
        if($data[$i]['xxArray'][$j]['SFSDA_DISPLAY'] == "是") {
            if ($tt != '') $tt.=",";
            $tt .= $data[$i]['xxArray'][$j]['XXDM'];
            
        }
    }
    $t .= $tt;
    $t .= '\"}';
    $answer .= $t;
    if ($i != count($data)-1) $answer .= ",";
}
$answer .= "]";
$main .= $answer . '"}';
$arr = ["data"=>$main];
$result = http_post("http://zhxg.njtech.edu.cn/xsfw/sys/yxapp/modules/onlineAnswer/submitAnswer.do",$arr,false,$cookie); 
echo $result;