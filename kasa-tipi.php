<?php
function kasaTipi($message){
    $systemPrompt = "Sana bir kamyon taşımacılığı grubuna ait mesajlar göndereceğim. Bu mesaj kamyonun kasasını belirten bilgiler içerecek örneğin açık, kapalı veya tenteli gibi ifadeler. Senden istediğim bu mesajlar içerisindeki içerikleri inceleyerek bana istediğim formatta bir çıktı vermen. Mesajı doğru bir şekilde incele ve verdiğim listeye göre kamyonun kasasına ait bilgiyi mesajdan doğru bir şekilde tarat. Sana kamyonların kasa tipleri hakkında bilgiler içeren bir liste vereceğim. Bu listede 14 farklı kasa tipi bulunuyor. Sana gönderdiğim ilan mesajını inceleyip kasa tipi hakkında bir bilgi içeriyorsa bunu anlamlandırıp verdiğim listeden kasa kategorisini karşılayan id yi ve başlığını bana yanıt olarak vermeni isteyeceğim. Bana yanıt olarak listedeki eşleştiği id ve başlığı göndermen gerekiyor bu listede tanımlandığı gibi gönder ve başka hiçbir içerik bulunmasın sadece id ve başlık örneğin 1 Tenteli gibi. Kasa tiplerine ait bilgilerini içeren liste şu şekilde : 1 = Tenteli, 2 = Açık, 3 = Kapalı, 4 = Soğutuculu (-18 +18), 5 = Oto Taşıyıcı, 6 = Yandan Kapaklı Damperli, 7 = Havuz Kasa (Hafriyat Tipi), 8 = Sal Kasa (Konteyner Dorsesi), 9 = Jumbo Tenteli, 10 = Lowbed, 11 = Tanker Sıvı Yük, 12 = Kurtarıcı, 13 = Vinç Monteli, 14 = Farketmez.";
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
    $kasaTipiId = $data->choices[0]->message->content;

    return $kasaTipiId;
}