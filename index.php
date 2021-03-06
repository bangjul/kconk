<?php
require __DIR__ . '/vendor/autoload.php';

use \LINE\LINEBot;
use \LINE\LINEBot\HTTPClient\CurlHTTPClient;
use \LINE\LINEBot\MessageBuilder\MultiMessageBuilder;
use \LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use \LINE\LINEBot\MessageBuilder\StickerMessageBuilder;
use \LINE\LINEBot\SignatureValidator as SignatureValidator;

// set false for production
$pass_signature = true;

// set LINE channel_access_token and channel_secret
$channel_access_token = "fmp1m7KxFGnh2tzSiJlnjfzMobnD7y0jdtHtJOQw3crOGso6W5p3Ou5pZUcO93iRsLHsquhltl5JfCzuksRUHj9sjom9lY0xtfGPFti0PjWZ6cqiQBpD7O8d8t/CnWpFu+tUB0y+Em14iEj5G3CLNAdB04t89/1O/w1cDnyilFU=";
$channel_secret = "40414e27c17d10738853dfc06935db19";

// inisiasi objek bot
$httpClient = new CurlHTTPClient($channel_access_token);
$bot = new LINEBot($httpClient, ['channelSecret' => $channel_secret]);

$configs =  [
    'settings' => ['displayErrorDetails' => true],
];
$app = new Slim\App($configs);

// buat route untuk url homepage
$app->get('/', function($req, $res)
{
  echo "Welcome at Slim Framework";
});

// buat route untuk webhook
$app->post('/webhook', function ($request, $response) use ($bot, $pass_signature)
{
    // get request body and line signature header
    $body        = file_get_contents('php://input');
    $signature = isset($_SERVER['HTTP_X_LINE_SIGNATURE']) ? $_SERVER['HTTP_X_LINE_SIGNATURE'] : '';

    // log body and signature
    file_put_contents('php://stderr', 'Body: '.$body);

    if($pass_signature === false)
    {
        // is LINE_SIGNATURE exists in request header?
        if(empty($signature)){
            return $response->withStatus(400, 'Signature not set');
        }

        // is this request comes from LINE?
        if(! SignatureValidator::validateSignature($body, $channel_secret, $signature)){
            return $response->withStatus(400, 'Invalid signature');
        }
    }

    // kode aplikasi nanti disini
    $data = json_decode($body, true);
    if(is_array($data['events'])){
        foreach ($data['events'] as $event)
        {
            if ($event['type'] == 'message')
            {
                if(
                     $event['source']['type'] == 'group' or
                     $event['source']['type'] == 'room'
                   ){
                    //message from group / room
                    if($event['source']['userId'])
                    {

                        $userId     = $event['source']['userId'];
                        $getprofile = $bot->getProfile($userId);
                        $profile    = $getprofile->getJSONDecodedBody();
                        $greetings  = new TextMessageBuilder("Halo, ".$profile['displayName']);
                        $textMessageBuilder = new TextMessageBuilder($event['message']['text']);

                        $result = $bot->replyMessage($event['replyToken'], $greetings , $textMessageBuilder);
                        return $res->withJson($result->getJSONDecodedBody(), $result->getHTTPStatus());

                    } 
                    else {
                            // send same message as reply to user
                            $result = $bot->replyText($event['replyToken'], $event['message']['text']);
                            return $res->withJson($result->getJSONDecodedBody(), $result->getHTTPStatus());
                        }              
                   } 
                   else {
                    //message from single user
                    if($event['message']['type'] == 'text')
                        {
                            if($event['message']['text'] == "Info" || $event['message']['text'] == "info" || $event['message']['text'] == "INFO")
                            {
                                
                                $textMessageBuilder = new TextMessageBuilder('ada yang bisa kconk bantu?   contoh : jadwal salat hari ini.    untuk list kota di madura ketik : list kota ');
                                $result = $bot->replyMessage($event['replyToken'], $textMessageBuilder);
                                // $textMessageBuilder = new TextMessageBuilder($event['message']['text']);
                                // $result = $bot->replyMessage($event['replyToken'], $textMessageBuilder);

                                return $response->withJson($result->getJSONDecodedBody(), $result->getHTTPStatus());

                            }
                            else if($event['message']['text'] == 'list kota' || $event['message']['text'] == 'List kota')
                            {
                                
                                $textMessageBuilder = new TextMessageBuilder('Bangkalan, Sampang, Pamekasan, Sumenep');
                                $result = $bot->replyMessage($event['replyToken'], $textMessageBuilder);
                                // $textMessageBuilder = new TextMessageBuilder($event['message']['text']);
                                // $result = $bot->replyMessage($event['replyToken'], $textMessageBuilder);

                                return $response->withJson($result->getJSONDecodedBody(), $result->getHTTPStatus());

                            }
                            else if($event['message']['text'] == 'jadwal salat hari ini' || $event['message']['text'] == 'Jadwal salat hari ini')
                            {
                                
                                $textMessageBuilder = new TextMessageBuilder('di kota apa? (contoh : sampang)');
                                $result = $bot->replyMessage($event['replyToken'], $textMessageBuilder);
                                // $textMessageBuilder = new TextMessageBuilder($event['message']['text']);
                                // $result = $bot->replyMessage($event['replyToken'], $textMessageBuilder);

                                return $response->withJson($result->getJSONDecodedBody(), $result->getHTTPStatus());

                            }
                            else if($event['message']['text'] == 'bangkalan' || $event['message']['text'] == 'Bangkalan' || $event['message']['text'] == 'Bkl')
                            {
                                
                                $textMessageBuilder = new TextMessageBuilder('Assalamualaikum Wr.Wb.   ini nih jadwal salat di Bangkalan   jangan sampai kelewatan ya!   subuh : 03.58 WIB   Dzuhur : 11.21 WIB   Ashar : 14.28 WIB   Maghrib : 17.26 WIB   Isya : 18.35 WIB');
                                $result = $bot->replyMessage($event['replyToken'], $textMessageBuilder);
                                // $textMessageBuilder = new TextMessageBuilder($event['message']['text']);
                                // $result = $bot->replyMessage($event['replyToken'], $textMessageBuilder);

                                return $response->withJson($result->getJSONDecodedBody(), $result->getHTTPStatus());

                            }
                            else if($event['message']['text'] == 'sampang' || $event['message']['text'] == 'Sampang' || $event['message']['text'] == 'Spg')
                            {
                                
                                $textMessageBuilder = new TextMessageBuilder('Assalamualaikum Wr.Wb.   ini nih jadwal salat di Sampang   jangan sampai kelewatan ya!   subuh : 03.57 WIB   Dzuhur : 11.19 WIB   Ashar : 14.27 WIB   Maghrib : 17.24 WIB   Isya : 18.33 WIB');
                                $result = $bot->replyMessage($event['replyToken'], $textMessageBuilder);
                                // $textMessageBuilder = new TextMessageBuilder($event['message']['text']);
                                // $result = $bot->replyMessage($event['replyToken'], $textMessageBuilder);

                                return $response->withJson($result->getJSONDecodedBody(), $result->getHTTPStatus());

                            }
                            else if($event['message']['text'] == 'pamekasan' || $event['message']['text'] == 'Pamekasan' || $event['message']['text'] == 'Pmk')
                            {
                                
                                $textMessageBuilder = new TextMessageBuilder('Assalamualaikum Wr.Wb.   ini nih jadwal salat di Pamekasan   jangan sampai kelewatan ya!   subuh : 03.56 WIB   Dzuhur : 11.18 WIB   Ashar : 14.26 WIB   Maghrib : 17.23 WIB   Isya : 18.32 WIB');
                                $result = $bot->replyMessage($event['replyToken'], $textMessageBuilder);
                                // $textMessageBuilder = new TextMessageBuilder($event['message']['text']);
                                // $result = $bot->replyMessage($event['replyToken'], $textMessageBuilder);

                                return $response->withJson($result->getJSONDecodedBody(), $result->getHTTPStatus());

                            }
                            else if($event['message']['text'] == 'sumenep' || $event['message']['text'] == 'Sumenep' || $event['message']['text'] == 'Smp')
                            {
                                
                                $textMessageBuilder = new TextMessageBuilder('Assalamualaikum Wr.Wb.   ini nih jadwal salat di Sumenep   jangan sampai kelewatan ya!   subuh : 03.54 WIB   Dzuhur : 11.16 WIB   Ashar : 14.24 WIB   Maghrib : 17.21 WIB   Isya : 18.31 WIB');
                                $result = $bot->replyMessage($event['replyToken'], $textMessageBuilder);
                                // $textMessageBuilder = new TextMessageBuilder($event['message']['text']);
                                // $result = $bot->replyMessage($event['replyToken'], $textMessageBuilder);

                                return $response->withJson($result->getJSONDecodedBody(), $result->getHTTPStatus());

                            }
                            else if($event['message']['text'] == 'Terimakasih' || $event['message']['text'] == 'terimakasih' || $event['message']['text'] == 'sekelangkong' || $event['message']['text'] == 'Thanks')
                            {
                                
                                $textMessageBuilder = new TextMessageBuilder('sama-sama '.$profile['displayName']);
                                $result = $bot->replyMessage($event['replyToken'], $textMessageBuilder);
                                // $textMessageBuilder = new TextMessageBuilder($event['message']['text']);
                                // $result = $bot->replyMessage($event['replyToken'], $textMessageBuilder);

                                return $response->withJson($result->getJSONDecodedBody(), $result->getHTTPStatus());

                            }
                            else if($event['message']['text'] == "Hallo" || $event['message']['text'] == "Hy" || $event['message']['text'] == "hallo" || $event['message']['text'] == "Halo" || $event['message']['text'] == "halo" || $event['message']['text'] == "Hi" || $event['message']['text'] == "Hii" || $event['message']['text'] == "hi" || $event['message']['text'] == "conk")
                            {
                                $userId     = $event['source']['userId'];
                                $getprofile = $bot->getProfile($userId);
                                $profile    = $getprofile->getJSONDecodedBody();
                                $textMessageBuilder = new TextMessageBuilder('hallo tretan '.$profile['displayName']);
                                $result = $bot->replyMessage($event['replyToken'], $textMessageBuilder);
                                // $textMessageBuilder = new TextMessageBuilder($event['message']['text']);
                                // $result = $bot->replyMessage($event['replyToken'], $textMessageBuilder);

                                return $response->withJson($result->getJSONDecodedBody(), $result->getHTTPStatus());

                            }
                            else if($event['message']['text'] == "Siapa nama kamu?")
                            {
                                $userId     = $event['source']['userId'];
                                $getprofile = $bot->getProfile($userId);
                                $profile    = $getprofile->getJSONDecodedBody();
                                $textMessageBuilder = new TextMessageBuilder('hallo tretan '.$profile['displayName']. ' nama saya Kconk dari Madura');
                                $result = $bot->replyMessage($event['replyToken'], $textMessageBuilder);
                                // $textMessageBuilder = new TextMessageBuilder($event['message']['text']);
                                // $result = $bot->replyMessage($event['replyToken'], $textMessageBuilder);

                                return $response->withJson($result->getJSONDecodedBody(), $result->getHTTPStatus());

                            }
                            else
                            {
                                // send same message as reply to user
                                //$result = $bot->replyText($event['replyToken'], $event['message']['text']);

                                // or we can use replyMessage() instead to send reply message
                                $textMessageBuilder = new TextMessageBuilder('itu artinya apa ya tretan? maaf kconk belum di ajari kata itu. Ketik "info" untuk cara penggunaan');
                                $result = $bot->replyMessage($event['replyToken'], $textMessageBuilder);
                                // $textMessageBuilder = new TextMessageBuilder($event['message']['text']);
                                // $result = $bot->replyMessage($event['replyToken'], $textMessageBuilder);

                                return $response->withJson($result->getJSONDecodedBody(), $result->getHTTPStatus());

                            }
                            
                        }
                        if(
                            $event['message']['type'] == 'image' or
                            $event['message']['type'] == 'video' or
                            $event['message']['type'] == 'audio' or
                            $event['message']['type'] == 'file'
                        ){
                            $basePath  = $request->getUri()->getBaseUrl();
                            $contentURL  = $basePath."/content/".$event['message']['id'];
                            $contentType = ucfirst($event['message']['type']);
                            $result = $bot->replyText($event['replyToken'],
                                $contentType. " yang kamu kirim bisa diakses dari link:  " . $contentURL);

                            return $res->withJson($result->getJSONDecodedBody(), $result->getHTTPStatus());
                        }
                   }

                
            }
        }
    }

});


$app->get('/pushmessage', function($req, $res) use ($bot)
{
    // send push message to user
    $userId = 'Ua643213a694fb82bf08dad6729881fe4';
    //$textMessageBuilder = new TextMessageBuilder('Halo, ini pesan push');
    $stickerMessageBuilder = new StickerMessageBuilder(1, 106);
    //result = $bot->pushMessage($userId, $textMessageBuilder);
    $result2 = $bot->pushMessage($userId, $stickerMessageBuilder);
   
    return $res->withJson($result2->getJSONDecodedBody(), $result2->getHTTPStatus());
});

$app->get('/multicast', function($req, $res) use ($bot)
{
    // list of users
    $userList = [
        'Ua643213a694fb82bf08dad6729881fe4'];

    // send multicast message to user
    $textMessageBuilder = new TextMessageBuilder('Halo, ini pesan multicast');
    $result = $bot->multicast($userList, $textMessageBuilder);
   
    return $res->withJson($result->getJSONDecodedBody(), $result->getHTTPStatus());
});

$app->get('/profile/{userId}', function($req, $res) use ($bot)
{
    // get user profile
    $route  = $req->getAttribute('route');
    $userId = $route->getArgument('userId');
    $result = $bot->getProfile($userId);
             
    return $res->withJson($result->getJSONDecodedBody(), $result->getHTTPStatus());
});

$app->get('/content/{messageId}', function($req, $res) use ($bot)
{
    // get message content
    $route      = $req->getAttribute('route');
    $messageId = $route->getArgument('messageId');
    $result = $bot->getMessageContent($messageId);

    // set response
    $res->write($result->getRawBody());

    return $res->withHeader('Content-Type', $result->getHeader('Content-Type'));
});


$app->run();