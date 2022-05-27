
$i = 1;
echo color('yellow', "[+] "). 'Masukan List Account :  ';
$listadd = trim(fgets(STDIN));
echo color('yellow', "[+] "). 'Masukan Password :  ';
$passer = trim(fgets(STDIN));
$list = explode("\n", str_replace("\r", "", file_get_contents($listadd)));
foreach ($list as $key => $akun) {
    echo"
========================== ACCOUNT KE [$i] ==============================\n";
    
    $parsing = explode('|',$akun);
    $email = $parsing[0];
    $pass = $passer;
    $parsing = explode("@",$email);
    $usermail = $parsing[0];
    $domain = $parsing[1];
    $headers = array();
    $headers[] = 'Authority: api.pltplace.io';
    $headers[] = 'Accept: application/json, text/plain, */*';
    $headers[] = 'Accept-Language: en-US,en;q=0.9';
    $headers[] = 'Content-Type: application/json;charset=UTF-8';
    $headers[] = 'Origin: https://pltplace.io';
    $headers[] = 'Referer: https://pltplace.io/';
    $headers[] = 'Sec-Ch-Ua: \" Not A;Brand\";v=\"99\", \"Chromium\";v=\"101\", \"Google Chrome\";v=\"101\"';
    $headers[] = 'Sec-Ch-Ua-Mobile: ?0';
    $headers[] = 'Sec-Ch-Ua-Platform: \"Windows\"';
    $headers[] = 'Sec-Fetch-Dest: empty';
    $headers[] = 'Sec-Fetch-Mode: cors';
    $headers[] = 'Sec-Fetch-Site: same-site';
    $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/101.0.4951.67 Safari/537.36';
    $login  = curl('https://api.pltplace.io/api/v1/account/login',"{\"email\":\"$email\",\"password\":\"$pass\"}",$headers);
    $account = curl("https://api.pltplace.io/api/v1/account",null,$headers);
    if(strpos($account[1],'"status":"inactive"')){
        $datalogin = json_decode($account[1]);
        $username = $datalogin->data->name;
        $mailer = $datalogin->data->email;
        $status = $datalogin->data->status;
        echo color('green',"[" . date("H:i:s") . "] ")."Username => $username\n";
        echo color('green',"[" . date("H:i:s") . "] ")."Email => $mailer\n";
        echo color('green',"[" . date("H:i:s") . "] ")."Status Account => $status\n";
        $active = curlaktivasi('https://api.pltplace.io/api/v1/account/email/activate',$headers);
        $headersx = array();
        $headersx[] = 'Authority: pltplace.io';
        $headersx[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9';
        $headersx[] = 'Accept-Language: en-US,en;q=0.9';
        $headersx[] = 'Sec-Ch-Ua: \" Not A;Brand\";v=\"99\", \"Chromium\";v=\"101\", \"Google Chrome\";v=\"101\"';
        $headersx[] = 'Sec-Ch-Ua-Mobile: ?0';
        $headersx[] = 'Sec-Ch-Ua-Platform: \"Windows\"';
        $headersx[] = 'Sec-Fetch-Dest: document';
        $headersx[] = 'Sec-Fetch-Mode: navigate';
        $headersx[] = 'Sec-Fetch-Site: none';
        $headersx[] = 'Sec-Fetch-User: ?1';
        $headersx[] = 'Upgrade-Insecure-Requests: 1';
        $headersx[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/101.0.4951.67 Safari/537.36';
        cek:
        $email = curlmail("https://tempmail.plus/api/mails?email=$usermail%40$domain&first_id=0&epin=",null,$headersx);
        $validatemail = json_decode($email[1])->first_id;
        if(!$validatemail == 0){
            $headerss = array();
            $headerss[] = 'Sec-Ch-Ua: \" Not A;Brand\";v=\"99\", \"Chromium\";v=\"101\", \"Google Chrome\";v=\"101\"';
            $headerss[] = 'Accept: application/json, text/javascript, */*; q=0.01';
            $headerss[] = 'Referer: https://tempmail.plus/en/';
            $headerss[] = 'X-Requested-With: XMLHttpRequest';
            $headerss[] = 'Sec-Ch-Ua-Mobile: ?0';
            $headerss[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/101.0.4951.67 Safari/537.36';
            $headerss[] = 'Sec-Ch-Ua-Platform: \"Windows\"';
            $cekinbox = curlmail("https://tempmail.plus/api/mails/$validatemail?email=$usermail%40$domain&epin=",null,$headerss);
            $resultmail = json_decode($cekinbox[1])->text;
            if(empty($resultmail)){
                echo color('green',"[" . date("H:i:s") . "] ")."Kode Belom Ditemukan \n";
                goto cek;
            } 
            $code = explode("アカウント登録を完了するために、下記のリンクへアクセスをお願いします。",$resultmail);
            $codexx = explode("本リンクの有効期限は 3 日となります。期限を過ぎると再度メール認証の手続きが必要となりますのでご注意ください。",$code[1]);
            $codexxx = explode("\n",$codexx[0]);
            echo color('green',"[" . date("H:i:s") . "] ")."Link Didapatkan => $codexxx[2]\n";
            $token = explode("=",$codexxx[2]);
            sleep(2);
            repeat:
            $logins  = curl("$codexxx[2]",null,$headers);
            $accounts = curl("https://api.pltplace.io/api/v1/account",null,$headers);
            $active = curl("https://api.pltplace.io/api/v1/account/email/activate?activate_code=$token[1]",null,$headers);
            if(strpos($accounts[1],'"status":"active"')){
                echo color('green',"[" . date("H:i:s") . "] ")."Account Succesfully Aktivasi\n";
                unlink('cookie.txt');
                unlink('mail.txt');

            }else{
                echo color('red',"[" . date("H:i:s") . "] ")."Account Gagal Aktivasi\n";
                $home = curl("https://pltplace.io/email-tips?from=home",null,$headers);
                $accounts = curl("https://api.pltplace.io/api/v1/account",null,$headers);
                goto repeat;
            }
            
        }else{
            echo color('red',"[" . date("H:i:s") . "] ")."Belom Menemukan ID Inbox\n";
            goto cek;
        }
    }else{
        $accountsbabi = curl("https://api.pltplace.io/api/v1/account",null,$headers);
        if(strpos($accountsbabi[1],'"status":"active"')){
            $datalogin = json_decode($accountsbabi[1]);
            $mailer = $datalogin->data->email;
            echo color('green',"[" . date("H:i:s") . "] ")."Username => $mailer\n";
            echo color('green',"[" . date("H:i:s") . "] ")."Account Sudah Aktif Skip\n";

        }else{
            echo color('red',"[" . date("H:i:s") . "] ")."Account Error Skip\n";
            save("$akun\n","accountgagallogin.txt");
        }
        
    }
    $i++;
    
}

function curlmail($url,$post,$headerss,$follow=false,$method=null)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_COOKIEJAR, "mail.txt");
     	curl_setopt($ch, CURLOPT_COOKIEFILE, "mail.txt");
		if ($follow == true) curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		if ($method !== null) curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
		if ($headerss !== null) curl_setopt($ch, CURLOPT_HTTPHEADER, $headerss);
		if ($post !== null) curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		$result = curl_exec($ch);
		$header = substr($result, 0, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
		$body = substr($result, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
		preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $result, $matches);
		$cookies = array();
		foreach($matches[1] as $item) {
		  parse_str($item, $cookie);
		  $cookies = array_merge($cookies, $cookie);
		}
		return array (
		$header,
		$body,
		$cookies
		);
	}
function curl($url,$post,$headerss,$follow=false,$method=null)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_COOKIEJAR, "cookie.txt");
     	curl_setopt($ch, CURLOPT_COOKIEFILE, "cookie.txt");
		if ($follow == true) curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		if ($method !== null) curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
		if ($headerss !== null) curl_setopt($ch, CURLOPT_HTTPHEADER, $headerss);
		if ($post !== null) curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		$result = curl_exec($ch);
		$header = substr($result, 0, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
		$body = substr($result, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
		preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $result, $matches);
		$cookies = array();
		foreach($matches[1] as $item) {
		  parse_str($item, $cookie);
		  $cookies = array_merge($cookies, $cookie);
		}
		return array (
		$header,
		$body,
		$cookies
		);
	}
function curlaktivasi($url,$headerss)
	{
		$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_COOKIEJAR, "cookie.txt");
     	curl_setopt($ch, CURLOPT_COOKIEFILE, "cookie.txt");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headerss);
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        return $result;
	}
function color($color = "default" , $text)
    {
        $arrayColor = array(
            'red'       => '1;31',
            'green'     => '1;32',
            'yellow'    => '1;33',
            'blue'      => '1;34',
        );  
        return "\033[".$arrayColor[$color]."m".$text."\033[0m";
    }
function save($data, $file) 
	{
		$handle = fopen($file, 'a+');
		fwrite($handle, $data);
		fclose($handle);
	}
