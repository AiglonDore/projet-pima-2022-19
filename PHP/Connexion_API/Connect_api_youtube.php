<?php
$API_Key    = 'AIzaSyA5SEQPLYmy5cpSJCkHSmIeh_mfsSvVijk'; 
$Channel_ID = 'UCuoKuTCQ9dmPIgOgyLm9HgQ'; 
$username=$_GET["name"];
$Max_Results = 10; 
 
function getChannel($name,$key){
    $apiData = @file_get_contents('https://www.googleapis.com/youtube/v3/channels?key='.$key.'&forUsername='.$name.'&part=id%2Csnippet&type=channel');
    if($apiData){ 
        $videoList = json_decode($apiData); 
        //echo json_encode($videoList);
    }else{ 
        echo 'Invalid API key or channel ID.'; 
    }
    if(!empty($videoList->items)){ 
        foreach($videoList->items as $item){
            if(isset($item->id)){
                $id=$item->id;
                $url2=$item->snippet->customUrl;
            }
        } 
    }
    $Channel_ID=$id;
    $stats = @file_get_contents('https://www.googleapis.com/youtube/v3/channels?part=statistics&id='.$Channel_ID.'&key='.$key.'');
    if($stats){
        $res2=json_decode($stats);
    }
    else{
        return "marche pas";
    }
    $url = "https://www.googleapis.com/youtube/v3/channels?part=snippet&fields=items%2Fsnippet%2Fthumbnails%2Fdefault&id=$Channel_ID&key=AIzaSyA5SEQPLYmy5cpSJCkHSmIeh_mfsSvVijk";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $channelOBJ = json_decode( curl_exec( $ch ),true );
    $thumbnail_url = $channelOBJ["items"][0]["snippet"]["thumbnails"]["default"]["url"];
    $i=(array)get_object_vars($res2); 
    //echo json_encode($i);
    $y=0;
    foreach($i as $c){
        $y+=1;
        if($y==4){
            $cas_spec=(array)$c[0];
            $statistics=(array)($cas_spec['statistics']);
            return (array("name"=>$name,"pop"=>$statistics['viewCount'],"sub"=>$statistics['subscriberCount'],"vc"=>$statistics['videoCount'],"images"=>$thumbnail_url,"url"=>"https://youtube.com/$url2","reseau"=>"youtube"));
    }
    }
    

}
$i=getChannel($username,$API_Key);
echo json_encode($i);
?>