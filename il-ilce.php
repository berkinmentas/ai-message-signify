<?php
$json = file_get_contents('il-ilce-mernis.json');
$data = json_decode($json, true);
print_r($data);
function ilIlce($message){
    $systemPrompt = "Sana bir kamyon taşımacılığı grubuna ait mesajlar göndereceğim. Senden istediğim bu mesajlar içerisindeki içerikleri inceleyerek bana istediğim formatta bir çıktı vermen. Sana göndereceğim metin içerisinde seferlerin yapılacağı konumlar mevcut. Örneğin Balıkesir/Karesi-Bursa/Mudanya. Burada seferin kalkış noktasının Balıkesir ilinin karesi ilçesinden varış noktasının Bursa ilinin Mudanya ilçesi olduğu belirtilmiş. Senden istediğim formatta bir çıktı vermeni istiyorum. İstediğim format json olacak. Bu jsonda NeredenIl-NeredenIlce-NereyeIl-NereyeIlce verileri olacak. Bu verilerin karşılığını göndereceğim mesajın içeriğindeki kalkış ve varış noktalarına göre doldurmanı isteyeceğim. Eğer ilçe belirtilmemişse bunun anlamı merkez olacak. Yanıt olarak sadece oluşturduğun json değerini vermeni istiyorum. Vereceğin yanıtta başka herhangi bir detay olmasın. Sadece oluşturduğun json verisini ver.";
    $apiKey= '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';

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://api.openai.com/v1/chat/completions');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "{\n  \"model\": \"gpt-3.5-turbo-1106\",\n  \"messages\": [\n    {\n      \"role\": \"system\",\n      \"content\": [\n        {\n          \"type\": \"text\",\n          \"text\": \"".$systemPrompt."\"\n        }\n      ]\n    },\n    {\n      \"role\": \"user\",\n      \"content\": [\n        {\n          \"type\": \"text\",\n          \"text\": \"".$message."\"\n        }\n      ]\n    }\n  ],\n  \"temperature\": 1,\n  \"max_tokens\": 256,\n  \"top_p\": 1,\n  \"frequency_penalty\": 0,\n  \"presence_penalty\": 0\n}");


    $headers = array();
    $headers[] = 'Content-Type: application/json';
    $headers[] = 'Authorization: Bearer '.$apiKey;
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }
    curl_close($ch);
    $data = json_decode($result);
    $ilIlce = $data->choices[0]->message->content;
    return $ilIlce;
}