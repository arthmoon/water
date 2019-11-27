<?php

namespace console\controllers;

use common\models\Topic;
use yii\console\Controller;

class ParseController extends Controller
{
    var $_boardId  = 44660102;

    /**
     * @param int $offset
     * @param int $q
     * @return bool|false|mixed|string|string[]|null
     */
    private function getBoard(int $offset = 0, int $q = 40)
    {
        $url  = "https://vk.com/board{$this->_boardId}?offset={$offset}&q={$q}";
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => "",
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => "POST",
            CURLOPT_HTTPHEADER     => array(
                "Accept: */*",
                "Accept-Encoding: gzip, deflate",
                "Cache-Control: no-cache",
                "Connection: keep-alive",
                "Content-Length: 0",
                "Cookie: remixlang=0; remixstid=1928918562_88a9e5a2ee66cbbac4; remixaudio_show_alert_today=0; remixff=00; remixlhk=b016936df9dcb8c70c",
                "Host: vk.com",
                "Postman-Token: 5fcfb973-fa2b-4222-9acf-b9b49bd5adda,869f2467-d54c-4677-88f8-2d92110adfd9",
                "User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.89 Safari/537.36",
                "cache-control: no-cache"
            ),
        ));

        $response = curl_exec($curl);
        $err      = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return false;
        }

        $response = iconv('windows-1251', 'UTF-8', $response);
        $response = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $response);
        $response = str_replace('windows-1251', "UTF-8", $response);

        return $response;
    }

    /**
     *
     */
    public function actionBoard()
    {
        libxml_use_internal_errors(true);

        $offset = 0;
        $limit  = 40;

        do {
            $response = $this->getBoard($offset, $limit);

            $dom = new \DOMDocument();
            $dom->loadHTML($response);
            $xpath = new \DOMXPath($dom);
            $nodes = $xpath->query("//div[contains(@class, 'blst_row')]");
            $topicsCount = count($nodes);
            foreach ($nodes as $i => $node) {
                $a      = $xpath->query(".//a[@class='blst_title']", $node);
                $status = count($xpath->query(".//div[@class='blst_closed']", $node)) > 0 ? 'closed' : 'opened';
                $href   = $a[0]->getAttribute('href') ?? '';
                $id     = explode('_', $href)[1];
                $title  = $a[0]->nodeValue ?? '';

                $count  = $xpath->query(".//div[@class='blst_other']", $node);
                $count  = substr($count[0]->nodeValue, 0, strpos($count[0]->nodeValue, '|'));
                $count  = preg_replace('/[^0-9]/', '', $count);

                $topic  = Topic::findOne($id);
                if (!$topic) {
                    $topic         = new Topic();
                    $topic->id     = $id;
                    $topic->offset = (int)$count;
                }
                $topic->href   = $href;
                $topic->title  = $title;
                $topic->status = $status;

                $topic->save(false);
            }
            $offset+= $limit;
        } while ($topicsCount >= $limit);
    }
}
