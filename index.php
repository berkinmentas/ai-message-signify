<?php
include 'kasa-tipi.php';
include 'arac-kategorisi.php';

$text= 'Yükleme:  Adıyaman Besni Teslim: Ağrı/Tutak 3 adet * Standart Tenteli 40 ayak Yük: Kalıp Malzemesi Ebatlar: Kasa içi Ağırlık: 25000kg Ücret:  18000 + KDV Hazır yükler Teşekkürler Bayram Uzundere 05355278842';

$systemPrompt = "Benim için bir ilan düzenleyici olarak görev almanı isteyeceğim. Bir gruba gelen mesajları sana göndereceğim. Bu mesajları okuyarak bunlardan anlamlı ve sabit tipte bir ilan mesajı oluşturacaksın. Bu mesajlar bir kamyonla taşıma gruplarına ait. Kullanıcılar bu gruba yapacakları seferlere ait bilgiler içeren mesajları atıyorlar. Örneğin Balıkesir - Bursa arasında bir sefer. Burada kalkış noktası Balıkesir varış noktası Bursa olacak. Bu mesajda araca ait bilgiler de içerebilir. Örneğin Tır 13.60 Dorseli veya Kamyonet gibi. Bunun yanında tıra ait kasa veya dorse bilgisi de içerebilir. Örneğin açık-kapalı veya tenteli gibi. Bu tırın dorsesini tarif eder. Mesaj içeriğinde iletişim numarası gibi numaralar da içerebilir. Bu bir cep numarası olabilir. Veya kullanıcıya ait bir mail adresi de olabilir. Vereceğin yanıtlar için örnek bir yapı vereceğim. Bu yapıyı kullanarak cevap olarak bir JSON dönmeni isteyeceğim. Oluşturmak için gerekli bilgileri yapılar halinde sana vereceğim. Gerekli bilgileri bu yapılardan da karşılayarak bana bir JSON oluşturmanı istiyorum. Yanıt olarak Sadece bu JSON verisini göndermelisin. Json kalıbı bu şekilde olacak NeredenIl= NeredenIl NeredenIlId= NeredenIlId NeredenIlce= NeredenIlce NeredenIlceId= NeredenIlceId NereyeIl= NereyeIl NereyeIlId= NereyeIlId NereyeIlce= NereyeIlce NereyeIlceId= NereyeIlceId Araç_Cins= Araç_Cins Arac_Kategori_ID= Arac_Kategori_ID Kasa_Tipi= Kasa_Tipi Kasa_Tipi_ID= Kasa_Tipi_ID Telefon= Telefon Fiyat= Fiyat KdvDahilmi= KdvDahilmi . Arac kategorisi id si yerine koyacağın id yi ve bu Arac cinsini sana hazır olarak veriyorum. Oluşturacağın json verisinde Arac Kategorisi id si şu olacak ARAC_KATEGORİ_ID ve aracın cinsi  ARAC_CINSI olarak gönderdiğim değer olacak. Json da kullanacağın kasa tipi için de kullanacağın id yi ve kasa tipini sana hazır olarak veriyorum. Kasa tipi için kullanacağın id şu olacak KASA_TİPİ_ID kasa tipi değeri için kullanacağın değer şu olacak KASA_TIPI. Bunlar haricindeki bilgileri sana ileteceğim mesaj içeriğinden doldurman gerekecek. Mesajda + KDV gibi bir ifade mevcutsa bu kdv fiyata dahil değil anlamına geliyor. Unutma sana verdiğim json kalıbının dışında bir veri istemiyorum. Sana gönderdiğim verileri bu kalıpta yerleştirerek yanıt olarak sadece oluşturduğun JSON değerini vermelisin.";

$textMessage= json_encode($text);
$kasaTipi = kasaTipi($text);
$aracKategorisi = aracKategorisi($text);

$message2 = 'KAMYON_KATEGORI_ID='.$aracKategorisi.'KASA_TIPI_ID='.$kasaTipi.'Diğer verileri alacağın mesaj ='.$text;
$message = $aracKategorisi.'içerisinden id değeri ARAC_KATEGORI_ID ye eşit başlık değeri ise ARAC_CINS değerine eşit'.$kasaTipi.'içerisindeki değerlerden id KASA_TIPI_ID değerine eşit başlık ise KASA_TIPI değerinin alacağı veri.'.'Diğer verileri alacağın mesaj ='.$text;
echo 'Kasa Tipi :'.$kasaTipi;
echo '<pre>';
echo 'Kamyon Kat. :'.$aracKategorisi;
echo '<pre>';
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
print_r($data->choices[0]->message->content);
