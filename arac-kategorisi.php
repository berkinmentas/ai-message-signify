<?php

function aracKategorisi($message){
    $systemPrompt = "Sana bir kamyon taşımacılığı grubuna ait mesajlar göndereceğim. Senden istediğim bu mesajlar içerisindeki içerikleri inceleyerek bana istediğim formatta bir çıktı vermen. Sana kamyonların araç kategorileri hakkında bilgiler içeren bir liste vereceğim. Bu listede 10 farklı kamyon kategorisi bulunuyor. Sana gönderdiğim ilan mesajını inceleyip kamyon kategorisi hakkında bir bilgi içeriyorsa bunu anlamlandırıp listedeki karşılığını bulup bu listedeki karşıladığı id yi ve araç tipini bana yanıt olarak vermeni isteyeceğim. Bana yanıt olarak arac kategorisinin id sini ve  araç kategori başlığını göndermen yeterli. Unutma yanıtın herhangi bir ekstra mesaj içermesin sadece id ve başlık değerini gönder.Bu liste dışından herhangi bir veri gönderme. Araç başlığı da bu listedeki aracı karşılayan başlık olsun bu başlığı yazarken listenin dışına çıkma listede ne yazıyorsa başlık o. Araç kategorilerine ait bilgilerini içeren liste şu şekilde : 1=Tır 13.60 Dorseli 2=Tır Damper Dorseli 3=Kırkayak 4=10 Teker Kamyon 5=6 Teker Kamyon 6= Kamyon Römork 7= Kamyonet 8= Panelvan 9=Özel Amaçlı 10= Farketmez .";
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
    $aracKategorisi = $data->choices[0]->message->content;
    return $aracKategorisi;
}