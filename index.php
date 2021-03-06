<?php

/**
 * Copyright 2016 LINE Corporation
 *
 * LINE Corporation licenses this file to you under the Apache License,
 * version 2.0 (the "License"); you may not use this file except in compliance
 * with the License. You may obtain a copy of the License at:
 *
 *   https://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 */

require_once('./LINEBotTiny.php');

$channelAccessToken = 'ssSS+HkyD0XCcBVlsTqA6BS60TDRgOovG2Z0qFW9e2Bt5ZZxdHzo+P0omxcV3NvpwxlnmIZVH8MbCDAnc1BQp/wlrwDjs1ZtWTl4BQLZ2Swgqw2tSJd4opg0C2Id/zPElWZ4tuxPPuVy6QoC/BcLHwdB04t89/1O/w1cDnyilFU=';
$channelSecret = 'c031d6f6f93ba085805259c0071f2505';

$client = new LINEBotTiny($channelAccessToken, $channelSecret);
foreach ($client->parseEvents() as $event) {
    switch ($event['type']) {
        case 'message':
            $message = $event['message'];
            switch ($message['type']) {
                case 'text':
                    // if($message['text']=="hi")
                    // {
                    //     $client->replyMessage(array(
                    //         'replyToken' => $event['replyToken'],
                    //         'messages' => array(
                    //             array(
                    //                 'type' => 'text',
                    //                 'text' => 'Hai Juga, saya Bot'
                    //             )
                    //         )
                    //     ));    
                    // }
                    $prosesTeks = file_get_contents("https://blooming-brook-80964.herokuapp.com/curlWit.php?id=".rawurlencode($message['text']));
                    $decodeTeks = json_decode($prosesTeks, TRUE);
                    if(preg_match('/salam/i', $message['text']))
                    {
                        $client->replyMessage(array(
                            'replyToken' => $event['replyToken'],
                            'messages' => array(
                                array(
                                    'type' => 'text',
                                    'text' => "Wa'alaikumsalam warohmatullah\n"
                                )
                            )
                        ));       
                    }
                    else if($decodeTeks['entities']['amount_of_money'] && $decodeTeks['entities']['intent'])
                    {
                        if($decodeTeks['entities']['intent'][0]["value"]=='berapa')
                        {
                            //ngolah dari api currency fixer.io
                            $url = "http://api.fixer.io/latest?base=".$decodeTeks['entities']['amount_of_money'][0]['unit']."&symbols=IDR";
                            $getCurrency = file_get_contents($url);
                            $decodeCurrency = json_decode($getCurrency,true);

                            //perkalian value dan api fixer
                            $hasil = $decodeTeks['entities']['amount_of_money'][0]["value"] * $decodeCurrency["rates"]["IDR"];

                            $client->replyMessage(array(
                                'replyToken' => $event['replyToken'],
                                'messages' => array(
                                    array(
                                        'type' => 'text',
                                        'text' => "hasilnya ternyata segini ".$hasil
                                    )
                                )
                            )); 
                        }
                    }
                    // else
                    // {
                    //     $arr = array("sorry aku belum ngerti","ah apasih kamu","apatuh", "apasi");
                    //     $randArr = rand(0,count($arr)-1);
                    //     $client->replyMessage(array(
                    //         'replyToken' => $event['replyToken'],
                    //         'messages' => array(
                    //             array(
                    //                 'type' => 'text',
                    //                 'text' => $arr[$randArr]
                    //             )
                    //         )
                    //     ));       
                    // }
                    // $client->replyMessage(array(
                    //     'replyToken' => $event['replyToken'],
                    //     'messages' => array(
                    //         array(
                    //             'type' => 'text',
                    //             'text' => $message['text']
                    //         )
                    //     )
                    // ));
                    break;
                default:
                    error_log("Unsupporeted message type: " . $message['type']);
                    break;
            }
            break;
        default:
            error_log("Unsupporeted event type: " . $event['type']);
            break;
    }
};
