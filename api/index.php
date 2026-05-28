<?php
require '../config.php';
set_time_limit(60);
ini_set('memory_limit', '256M');
header('Content-Type: application/json');

$useragent = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/116.0.0.0 Safari/537.36";

if(isset($_GET['url'])) {
    $link = $_GET['url'];
    $link = removeAfter($link, '?');
    $link = str_replace('?', '', $link);
    $realFileName = getFileNameFromURL($link);
    $realFileName = str_replace('.'.getExtension($realFileName), '', $realFileName);
    $custom = 0;
    if(isset($_GET['custom'])) {
        $custom = $_GET['custom'];
    }
    if(isFind($link, 'istockphoto') || isFind($link, 'gettyimages')) {
        if(isFind($link, 'istockphoto')) {
            $url = 'https://istock.7xm.xyz/get.php';
        }
        else if(isFind($link, 'gettyimages')) {
            $url = 'https://gettyimage.7xm.xyz/get.php';
        }
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "url=$link");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/x-www-form-urlencoded'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        $file = randomString().".txt";
        file_put_contents($file, $result);
        $download = getLineWithString($file, 'Download Image');
        $download = getBetweenString($download, '<a href="', '" class=');
        $download = "https://".getDomain($url)."/$download";
        $fname = downloadFile($download);
        $ex = getExtension($fname);
        header('Content-Type: '.getContentType($fname));
        header('Content-Disposition: inline; filename="'.$realFileName.'.'.$ex.'"');
        header('Content-Length: '.filesize($fname));
        readfile($fname);
        unlink($file);
        unlink($fname);
    }
    elseif(isFind($link, 'stock.adobe.com')) {
        if(!file_exists('cookies')) {
            mkdir('cookies');
        }
        if(!file_exists('cookies/adobe/cookies.txt')) {
            file_put_contents('cookies/adobe/cookies.txt', '1');
        }
        #$max_credits = 720;
        $server = file_get_contents('cookies/adobe/cookies.txt');
        $cookie = "cookies/adobe/cookies".$server.".txt";
        if(!file_exists($cookie)) {
            die;
        }
        $url = urldecode($link);
        if(strpos($url, "?") !== false) {
    		if(strpos($url, "&asset_id=") !== false) {
    			$explo = explode("&asset_id=", $url);
    			$imageid = $explo[1];
    		}
    		elseif(strpos($url, "?asset_id=") !== false) {
    			$explo = explode("?asset_id=", $url);
    			$imageid = end(explode("/",$explo[0]));
    		}
    		elseif(strpos($url, "?k=")!==false){
    			$explo = explode("?k=", $url);
    			$imageid = $explo[1];
    		}
    		else {
    			$explo = explode("?", $url);
    			$explo = explode("/", $explo[0]);
    			$imageid = end($explo);
    		}
        }
        else {
    		$explo = explode("/", $url);
    		$imageid = end($explo);
        }
    	$url = "https://stock.adobe.com/id/".$imageid;
    	$geturl = "https://stock.adobe.com/Ajax/MediaData/".$imageid."?full=1";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $geturl);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $headers = [
            'Referer: https://stock.adobe.com',
            'User-Agent: '.$useragent,
            'upgrade-insecure-requests: 1'
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);                                                                                            
        $rs = curl_exec($ch);
        curl_close($ch);
        $json = json_decode($rs);
        $is_standard = $json->is_standard;
        $is_premium = $json->is_premium;
        $is_video = $json->is_video;
        if($is_premium || $is_video) {
            die;
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $headers = [
            'Referer: https://stock.adobe.com/',
            'User-Agent: '.$useragent,
            'Connection: keep-alive'
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);                                                                                            
        $rs = curl_exec($ch);
        curl_close($ch);
        $html = new DOMDocument();
        @$html->loadHTML($rs);
        $balance = 0;
        $xpath = new DOMXPath($html);
        $els = $xpath->query("//span[@data-t='quota-images-standard' or @data-t='quota-cct-pro-unlimited-label'] or @data-t='quota-by-license-badge-text-1']");
        if($els->length > 0) {
            $el = $els->item(0);
            $balance = $el->nodeValue;
        }
        $start = '<script type="application/json" id="js-page-config">';
        $end = '</script>';
        $getjs = getBetweenString($rs, $start, $end);
        $json = json_decode($getjs);
        $csrf = $json->stockPortal->reduxState->portal->page->csrfToken;
        $xRequestId = $json->stockPortal->reduxState->portal->page->xRequestId;
        
        $loginUrl = 'https://stock.adobe.com/uk/Ajax/GetDownload/'.$imageid.'/10';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $loginUrl);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $headers = [
            'referer: '.$url,
            'User-Agent: '.$useragent,
            'x-csrf-token: '.$csrf,
            'x-request-id: '.$xRequestId,
            'x-requested-with: XMLHttpRequest',
            'sec-fetch-site: same-origin',
            'sec-fetch-mode: cors',
            'sec-fetch-dest: empty',
            'origin: https://stock.adobe.com',
            'Connection: keep-alive',
            'content-length: 0'
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $content = curl_exec($ch);
        curl_close($ch);
        $jsons = json_decode($content);
        if(!empty($jsons->download_url)) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $jsons->download_url);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
            curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_NOBODY, 1);
            $headers = [
                'Referer: '.$url,
                'User-Agent: '.$useragent,
                'Connection: keep-alive'
            ];
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);                                                                                            
            $rs = curl_exec($ch);
            $download_url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
            curl_close($ch);
        }
        if(!empty($download_url) && strpos($download_url, "amazonaws.com") !== false) {
            $explo = explode("filename%3D", $download_url);
            if(strpos($explo[1], "&") !== false) {
                $expl = explode("&", $explo[1]);
                $filename = str_replace("%22", "", $expl[0]);
            }
            else {
                $filename = str_replace("%22", "", $explo[1]);
            }
            if(strpos($download_url, 'filename%3D') === false) {
                $filename = basename(trim(strtok($download_url, '?')));
            }
            /*if($balance <= 1) {
                file_put_contents('cookies/adobe/cookies.txt', ($server + 1));
            }*/
            $size = getFileSize($download_url);
            $file = downloadFile($download_url);
            rename($file, "files/adobe/$filename");
            header('Content-Type: application/json');
            $rsArray = array('status' => true, 'imageid' => $imageid, 'download' => $download_url, 'name' => $filename, 'size' => $size, 'balance' => $balance);
            echo json_encode($rsArray);
            die;
        }
        else
        {
            header('Content-Type: application/json');
            $rsArray = array('status' => false, 'imageid' => $imageid, 'msg' => 'error', 'details' => $jsons);
            echo json_encode($rsArray);
            die;
        }
        
    }
    elseif(isFind($link, 'lovepik.com')) {
        $cookie = 'cookies/lovepik.txt';
        $id = getNumbersEx($link);
        $url = 'https://lovepik.com/download/getDownloadUrl';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
        $payload = "pid=$id&type=3&byso=&token=";
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/x-www-form-urlencoded; charset=UTF-8', 'User-Agent:Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.0.0 Safari/537.36'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        $dl = json_decode($result)->url;
        /*$fname = downloadFile($dl);
        $ex = getExtension($fname);
        header('Content-Type: '.getContentType($fname));
        header('Content-Disposition: inline; filename="'.$realFileName.'.'.$ex.'"');
        header('Content-Length: '.filesize($fname));
        readfile($fname);
        unlink($fname);
        die();*/
        $filename = basename($dl);
        $filename = removeAfter($filename, '?');
        $filename = str_replace('?', '', $filename);
        $size = getFileSize($dl);
        header('Content-Type: application/json');
        echo json_encode(array('status' => true, 'name' => $filename, 'size' => $size, 'download' => $dl));
    }
    # elseif(isFind($link, 'freepik.com')) {
        # if(isFind($link, 'premium')) {
            # $id = $link;
            # if(isFind($link, '.htm') && isFind($link, '_')) {
                # $id = removeBefore($link, '_');
                # $id = getStringNumbers($id);
            # }
            # $csrf = '91f46ae2c74249bc34e7393c85507939';
            # $cookie = 'cookies/freepik.txt';
            # $url = "https://www.freepik.com/xhr/download-url/$id";
            # $ch = curl_init($url);
            # curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
            # curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
            # curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept:application/json, text/plain, */*', 'User-Agent:Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/101.0.4951.54 Safari/537.36', 'x-requested-with:XMLHttpRequest', "x-csrf-token:$csrf"));
            # curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            # $result = curl_exec($ch);
            # curl_close($ch);
            # $res = json_decode($result);
            # if($res->success) {
                # $name = $res->filename;
                # $download = $res->url;
                # $size = getFileSize($download);
                # header('Content-Type: application/json');
                # echo json_encode(array('status' => true, 'name' => $name, 'size' => $size, 'download' => $download));
            # }
        # }
    # }
    elseif(isFind($link, 'storyblocks.com')) {
        if($custom == '0') {
            $info = file_get_contents("https://realfreedomnews.com/n1stock/storyblocks.php?act=getinfo&url=$link");
            $res = json_decode($info, true);
            if($res['status']) {
                $id = $res['id'];
                $thumbnail = $res['thumbnail'];
                $types = $res['types'];
                $title = $res['title'];
                $description = $res['description'];
                header('Content-Type: application/json');
                echo json_encode(array('status' => true, 'id' => $id, 'thumbnail' => $thumbnail, 'types' => $types, 'title' => $title, 'description' => $description));
            }
        }
        elseif($custom != '0') {
            $iid = explode(':', $custom)[0];
            $itype = explode(':', $custom)[1];
            $download = file_get_contents("https://realfreedomnews.com/n1stock/storyblocks.php?act=download&url=$link&id=$iid&type=$itype");
            $res = json_decode($download);
            if($res->status) {
                $download = $res->url;
                $size = getFileSize($download);
                $name = $res->filename;
                header('Content-Type: application/json');
                echo json_encode(array('status' => true, 'name' => $name, 'size' => $size, 'download' => $download));
            }
        }
    }
    /*elseif(isFind($link, 'freepik.com')) {
        /*$expl = explode("/", $link);
        if(isFind($link, 'premium')) {
            $id = $link;
            if(isFind($link, '.htm') && isFind($link, '_')) {
                $id = removeBefore($link, '_');
                $id = getStringNumbers($id);
            }
            $current_hour = date("H");
            $server = 1;
            if($current_hour >= 6){ $server = 2; };

            $url = "https://realfreedomnews.com/n1stock/freepik.php?server=1&partner=yasinnetwork&stockid=$id&referurl=$link";
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch);
            curl_close($ch);
            $res = json_decode($result);
            if($res->status == "Success") {
                $count = $res->count;
                $name = $res->filename;
                $download = $res->url;
                $size = getFileSize($download);
                header('Content-Type: application/json');
                echo json_encode(array('status' => true, 'name' => $name, 'size' => $size, 'download' => $download));
            }
        }
        if(strpos($expl[3], 'premium')!==false) {
            $json = json_decode(file_get_contents("https://dl.n1stock.site/dl.php?key=asd@123&url=$link"));
            $download = $json->download;
            $filename = $json->name;
            $size = $json->size;
            header('Content-Type: application/json');
            echo json_encode(array('status' => true, 'name' => $filename, 'size' => $size, 'download' => $download));
        }
        vaghti mikhay faght az api vps estefade koni az inja be bad ro roshan kon 
        $api = file_get_contents("http://157.90.198.73/freepik.php?url=$link");
        $js = json_decode($api);
        if($js->status && isLink($js->url)) {
            $download = $js->url;
            $size = getFileSize($download);
            $name = getFileNameFromURL($download);
            header('Content-Type: application/json');
            echo json_encode(array('status' => true, 'name' => $name, 'size' => $size, 'download' => $download));
        }
    }*/
    elseif(isFind($link, 'radiojavan.com') || isFind($link, 'rj.app') || isFind($link, 'rjplay.co') || isFind($link, 'rjvan.me')) {
        $key = 'WMO4-LG6A-4V6K-YBBS';
        $result = file_get_contents("https://citroapi.ir/radiojavan/?q=$link&key=$key");
        $result = json_decode($result);
        if($result->status) {
            $download = $result->result;
            $filename = basename($download);
            $filename = removeAfter($filename, '?');
            $filename = str_replace('?', '', $filename);
            $size = getFileSize($download);
            header('Content-Type: application/json');
            echo json_encode(array('status' => true, 'name' => $filename, 'size' => $size, 'download' => $download));
        }
    }
    elseif(isFind($link, 'pornhub.com')) {
        $id = $_GET['id'];
        if(isFind($id, 'viewkey=')) {
            $id = removeBefore($id, 'viewkey=');
        }
        $status = false;
        $result = [];
        $thumb = 'failed';
        $data = file_get_contents("https://appsdev.cyou/xv-ph-rt/api/?site_id=pornhub&video_id=$id");
        $info = json_decode($data, true);
        if(isset($info['mp4'])) {
            foreach($info['mp4'] as $quality=>$link) {
                $result[] = [$quality => $link];
            }
        }
        if(isset($info['thumb'])) {
            $thumb = $info['thumb'];
            $status = true;
        }
        echo json_encode(['status' => $status, 'thumbnail' => $thumb, 'result' => $result]);
    }

    /*elseif(isFind($link, 'elements.envato.com')) {
        $rs = file_get_contents("http://144.76.212.210:8686/Envato.php/?key=asd@123&url=$link");
        $json = json_decode($rs);
        $download = $json->download;
        $license = $json->license;
        
        $filename = basename($download);
        $size = getFileSize($download);
        header('Content-Type: application/json');
        echo json_encode(array('status' => true, 'name' => $filename, 'size' => $size, 'download' => $download, 'license' => $license));
    }*/
    elseif(isFind($link, 'vecteezy.com')) {
        $data = json_decode(file_get_contents("https://dl.n1stock.site/dl.php?key=asd@123&url=$link"));
        $download = $data->download;
        $filename = $data->name;
        $size = $data->size;
        header('Content-Type: application/json');
        echo json_encode(array('status' => true, 'name' => $filename, 'size' => $size, 'download' => $download));
    }
    elseif(isFind($link, 'motionarray.com')) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $link);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $headers = [
            'User-Agent: '.$useragent
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        curl_close($ch);
        $csrf = getBetweenString($result, 'name="_token" content="', '"');
        
        $file_id = explode('-', $link);
        $file_id = $file_id[(sizeof($file_id) - 1)];
        $file_id = str_replace('/', '', $file_id);
        
        $cookie = "cookies/motionarray/cookies.txt";
        
        $url = "https://motionarray.com/account/download/$file_id/?device_token=$csrf";
    	$ch = curl_init($url);
    	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    	curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
    	curl_setopt($ch, CURLOPT_HTTPHEADER, array("User-Agent:$useragent", "x-csrf-token:$csrf"));
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    	$result = curl_exec($ch);
    	curl_close($ch);
    	
    	$data = json_decode($result);
    	$download = $data->signed_url;
    	
    	$filename = getFileNameFromURL($download);
    	$size = getFileSize($download);
    	
        header('Content-Type: application/json');
        echo json_encode(array('status' => true, 'name' => $filename, 'size' => $size, 'download' => $download));
    }
    elseif(isFind($link, '123rf.com')) {
        $imageid = (int) explode("_", $link)[1];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $link);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $headers = [
            'Referer: '.$link,
            'User-Agent: '.$useragent,
            'upgrade-insecure-requests: 1'
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        $redirect_url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
        curl_close($ch);
        $hostname = parse_url($redirect_url, PHP_URL_HOST);
        $getjs = getBetweenString($result, '<script id="__NEXT_DATA__" type="application/json">', '</script>');
        $json = json_decode($getjs, true);
        # $xsrf_token = $json["props"]["cookies"]["XSRF-TOKEN"];
        $arrPriceLic = $json["props"]["pageProps"]["pricing"]["standardLicenses"]; 
        array_pop($arrPriceLic);
        $lfile = end($arrPriceLic);
        $res = $lfile["downloadResolution"];
        $format = $lfile["extension"];
        $datapost = [
            "imageid" => $imageid,
            "dlPlanType" => 1,
            "res" => $res,
            "format" => $format,
        ];
        $datapost = json_encode($datapost);
        $geturl = "https://".$hostname."/download-node/post/details_download";
        
        if(!file_exists('cookies')) {
            mkdir('cookies');
        }
        if(!file_exists('cookies/123rf/cookies.txt')) {
            file_put_contents('cookies/123rf/cookies.txt', '1');
        }
        $max_credits = 10;
        $server = file_get_contents('cookies/123rf/cookies.txt');
        $cookie = "cookies/123rf/cookies".$server.".txt";
        if(!file_exists($cookie)) {
            die();
        }
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $geturl);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $datapost);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $headers = [
            'Host: '.$hostname,
            'Referer: '.$redirect_url,
            'Origin: https://'.$hostname,
            'Content-Type: application/json',
            'Content-Length: '.strlen($datapost),
            'User-Agent: '.$useragent,
            # 'X-XSRF-TOKEN: '.$xsrf_token,
            'Request-Type: search',
            'x-datadog-origin: rum',
            'x-datadog-parent-id: '.genRandomNumber(18, false),
            'x-datadog-sampling-priority: 1',
            'x-datadog-trace-id: '.genRandomNumber(19, false),
            'Connection: keep-alive'
        ];
        if(isset($json["props"]["cookies"]["XSRF-TOKEN"])){
            $headers["X-XSRF-TOKEN"] = $json["props"]["cookies"]["XSRF-TOKEN"];
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);                                                                                            
        $result = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if($httpcode == 200) {
            $filename = "123rf_$imageid.$format";
            file_put_contents("files/123rf/$filename", $result);
            $download_url = "https://y4siiiin.com/n1stock/api/files/123rf/$filename";
            $size = getFileSize($download_url);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://".$hostname."/apicore/members/balance");
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
            curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
            $headers = [
                'Referer: '.$redirect_url,
                'User-Agent: '.$useragent,
                'upgrade-insecure-requests: 1'
            ];
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);                                                                                            
            $result = curl_exec($ch);
            curl_close($ch);
            $sub = json_decode($result)->subs;
            if($sub <= 1) {
                file_put_contents('cookies/123rf/cookies.txt', ($server + 1));
            }
            header('Content-Type: application/json');
            echo json_encode(array('status' => true, 'name' => $filename, 'size' => $size, 'download' => $download_url));
        }
    }
    elseif(isFind($link, 'shutterstock.com')) {
        $id = getNumbersEx($realFileName);
        if(isFind($link, '-')) {
            if(isFind($link, '/video/') && isFind($link, 'clip-')) {
                $id = removeBefore($link, 'clip-');
                $id = removeAfter($id, '-');
                $id = str_replace('-', '', $id);
                die();
            }
            elseif(isFind($link, '/music/') && isFind($link, 'track-')) {
                $id = removeBefore($link, 'track-');
                $id = removeAfter($id, '-');
                $id = str_replace('-', '', $id);
                die();
            }
            else {
                $id = explode('-', $link);
                $id = $id[(sizeof($id) - 1)];
            }
        }
        $data = json_decode(file_get_contents("http://n1stock.203-55-176-98.xvps.link/shutter.php?secret=49c8ffc23cbe81693333cb97c6c0d215&act=download&id=$lid&type=shutterstock_photo"));
        if($data->status) {
            $dl = $data->url;
            $name = $data->filename;
            $name = 'shutterstock_'.$data->id.'.'.getExtension($name);
            $size = getFileSize($dl);
            $file = downloadFile($dl);
            rename($file, "files/shutter/$name");
            header('Content-Type: application/json');
            echo json_encode(array('status' => true, 'name' => $name, 'size' => $size, 'download' => $dl));
        }
        /*$data = json_decode(file_get_contents('https://dmosmm.com/Api/?User=n1stock&Pass=gjvpFSW6Th4XHCI9&Links=["'.$link.'"]&Method=Download'));
        if($data->success) {
            $dl = json_decode($data->downloadLink)[0];
            $credit = $data->credit;
            
            if(isNumberDivisible($credit, 10)) {
                $res = mysqli_query($db, "SELECT * FROM `users` WHERE `admin` > '1'");
                $rows = mysqli_num_rows($res);
                if($rows > 0) {
            		while($row = mysqli_fetch_assoc($res)) {
            		    sendMessage($row['id'], "#ALERT\n<b>Shutterstock API</b>'s credit is <b>$credit</b>");
            		}
                }
            }
            $name = getFileNameFromURL($dl);
            $size = getFileSize($dl);
            $file = downloadFile($dl);
            rename($file, "files/shutter/$name");
            header('Content-Type: application/json');
            echo json_encode(array('status' => true, 'name' => $name, 'size' => $size, 'download' => $dl));
        }
        else {
            if(!empty($id)) {
                if(!file_exists('cookies')) {
                    mkdir('cookies');
                }
                if(!file_exists('cookies/shutterstock/cookies.txt')) {
                    file_put_contents('cookies/shutterstock/cookies.txt', '1');
                }
                $max_credits = 10;
                $server = file_get_contents('cookies/shutterstock/cookies.txt');
                $cookie = "cookies/shutterstock/cookies".$server.".txt";
                if(!file_exists($cookie)) {
                    die();
                }
                $country = "US";
                $ch = curl_init();
            	curl_setopt($ch, CURLOPT_URL, "https://www.shutterstock.com/sstk-oauth/access-token");
            	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            	curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
            	curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
            	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
            	curl_setopt($ch, CURLOPT_HEADER, 0); 
            	$headers = array('Referer: https://www.shutterstock.com/home', 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 'accept: application/json', 'content-type: application/json');
            	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);                                                                                            
            	$result = curl_exec($ch);
            	curl_close($ch);
            	$json = json_decode($result);
            	if($json->access_token) {
            		$ch = curl_init();
            		curl_setopt($ch, CURLOPT_URL, "https://www.shutterstock.com/studioapi/user/subscriptions");
            		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
            		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
            		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            		curl_setopt($ch, CURLOPT_HEADER, 0); */
            		//$headers = array('Referer: https://www.shutterstock.com/home', 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7');
            		/*curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            		$rs = curl_exec($ch);
            		curl_close($ch);
            		$jso = json_decode($rs);
            		$credits_remaining = 0;
            		foreach ($jso->data as $key) {
            		    if($key->attributes->status == "active" && $key->attributes->current_licenses[0]->redeemable_for == "standard") {
            			    $subcre = $key->attributes->current_allotments[0]->credits_remaining;
            				$credits_remaining = $credits_remaining + $subcre;
            				$subscription_id = $key->id;
            			}
            			else {
            				$credits_remaining = 0;
            			}
            		}
            		foreach ($jso->data as $key) {
            			if($key->attributes->status == "active" && $key->attributes->current_licenses[0]->redeemable_for == "standard" && $key->attributes->current_allotments[0]->credits_remaining >= 1) {
            				$subscription_id = $key->id;
            			}
            		}
            		if($subscription_id) {
            			$ch = curl_init();
            			curl_setopt($ch, CURLOPT_URL, "https://www.shutterstock.com/studioapi/images/".$id);
            			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            			curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
            			curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
            			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
            			curl_setopt($ch, CURLOPT_HEADER, 0); */
            			//$headers = array('Referer: https://www.shutterstock.com/home', 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7');
            			/*curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);                                                                                            
            			$rs = curl_exec($ch);
            			curl_close($ch);
            			$jsons = json_decode($rs);
            			if(array_key_exists("vector_eps", $jsons->data->attributes->sizes)) {
            				$content_format = "eps";
            				$content_size = "vector";
            			}
            			else {
            				$content_format = "jpg";
            				$content_size = "huge";
            			}
            			$postdata = '{"country_code":"'.$country.'","required_cookies":"","content":[{"content_format":"'.$content_format.'","content_id":"'.$id.'","content_size":"'.$content_size.'","content_type":"photo","license_name":"standard","show_modal":true}]}';
            			$ch = curl_init();
            			curl_setopt($ch, CURLOPT_URL, "https://www.shutterstock.com/studioapi/licensees/current/redownload");
            			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            			curl_setopt($ch, CURLOPT_POST, 1);
            			curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
            			curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
            			curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
            			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
            			curl_setopt($ch, CURLOPT_HEADER, 0); 
            			$headers = array('Referer: '.$referurl, 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 'accept: application/json', 'content-type: application/json', 'authorization: Bearer '.$json->access_token, 'x-end-user-country: '.$country, 'x-shutterstock-user-token: '.$json->user_token);
            			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);                                                                                            
            			$rss = curl_exec($ch);
            			curl_close($ch);
            			$jsonss = json_decode($rss);
            			if(!empty($jsonss->meta->licensed_content[0]->download_url) && $jsonss->meta->licensed_content[0]->is_redownload) {
            				$download_url = $jsonss->meta->licensed_content[0]->download_url;
            			}
            			else {
            				$postdata = '{"country_code":"'.$country.'","required_cookies":"","content":[{"content_format":"'.$content_format.'","content_id":"'.$id.'","content_size":"'.$content_size.'","content_type":"photo","license_name":"standard","subscription_id":"'.$subscription_id.'","show_modal":true}]}';
            				$ch = curl_init();
            				curl_setopt($ch, CURLOPT_URL, "https://www.shutterstock.com/studioapi/licensees/current/relicense");
            				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            				curl_setopt($ch, CURLOPT_POST, 1);
            				curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
            				curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
            				curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
            				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
            				curl_setopt($ch, CURLOPT_HEADER, 0); 
            				$headers = array('Referer: '.$referurl, 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 'accept: application/json', 'content-type: application/json', 'authorization: Bearer '.$json->access_token, 'x-end-user-country: '.$country, 'x-shutterstock-user-token: '.$json->user_token);
            				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            				$rss = curl_exec($ch);
            				curl_close($ch);
            				$jsonss = json_decode($rss);
            				$download_url = $jsonss->meta->licensed_content[0]->download_url;
            			}
            			if(!empty($download_url)) {
            			    $filename = basename($download_url);*/
            			    /*$filename = basename($download_url);
            				$fname = downloadFile($download_url);
                            $ex = getExtension($fname);
                            if(strtolower($ex) == 'eps') {
                                $zip = str_replace('.'.$ex, '', $fname).".zip";
                                zipFile($zip, array($fname));
                                unlink($fname);
                                $fname = $zip;
                            }
                            header('Content-Type: '.getContentType($fname));
                            header('Content-Disposition: inline; filename="'.$fname.'"');
                            header('Content-Length: '.filesize($fname));
                            readfile($fname);
                            unlink($fname);
                            */
                            
                            /*$download_url = removeAfter($download_url, '?');
                            $download_url = str_replace('?', '', $download_url);
                            
                            
                            $size = getFileSize($download_url);
                            $files = 'files';
                            if(!file_exists($files)) {
                                mkdir($files);
                            }
                            $name = downloadFile($download_url);
                            rename($name, "$files/shutter/$name");
                            header('Content-Type: application/json');
                            echo json_encode(array('status' => true, 'name' => $filename, 'size' => $size, 'download' => $download_url));
                            if($credits_remaining <= 1) {
                                file_put_contents('cookies/cookies.txt', ($server + 1));
                            }
            			}
                    }
                }
            }
        }*/
    }
    else {
        die();
    }
}
else {
    echo json_encode(array('status' => false));
}