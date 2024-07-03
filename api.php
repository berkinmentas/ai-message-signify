<?php
// İlgili dosyaları dahil et
include 'kasa-tipi.php';
include 'arac-kategorisi.php';
include 'il-ilce.php';

// Post verisini al
$text = isset($_POST['textInput']) ? $_POST['textInput'] : '';

// İl-ilçe bilgilerini içeren JSON dosyasını oku
$mernisJson = file_get_contents('il-ilce-mernis.json');

$neredenIlId = '';
$neredenIlceId = '';
$nereyeIlId = '';
$nereyeIlceId = '';
$apiKey= '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';

$systemPrompt = "Benim için bir ilan düzenleyici olarak görev almanı isteyeceğim. Bir gruba gelen mesajları sana göndereceğim. Bu mesajları okuyarak bunlardan anlamlı ve sabit tipte bir ilan mesajı oluşturacaksın. Bu mesajlar bir kamyonla taşıma gruplarına ait. Kullanıcılar bu gruba yapacakları seferlere ait bilgiler içeren mesajları atıyorlar. Örneğin Balıkesir - Bursa arasında bir sefer. Burada kalkış noktası Balıkesir varış noktası Bursa olacak. Bu mesajda araca ait bilgiler de içerebilir. Örneğin Tır 13.60 Dorseli veya Kamyonet gibi. Bunun yanında tıra ait kasa veya dorse bilgisi de içerebilir. Örneğin açık-kapalı veya tenteli gibi. Bu tırın dorsesini tarif eder. Mesaj içeriğinde iletişim numarası gibi numaralar da içerebilir. Bu bir cep numarası olabilir. Veya kullanıcıya ait bir mail adresi de olabilir. Vereceğin yanıtlar için örnek bir yapı vereceğim. Bu yapıyı kullanarak cevap olarak bir JSON dönmeni isteyeceğim. Oluşturmak için gerekli bilgileri yapılar halinde sana vereceğim. Gerekli bilgileri bu yapılardan da karşılayarak bana bir JSON oluşturmanı istiyorum. Yanıt olarak Sadece bu JSON verisini göndermelisin. Json kalıbı bu şekilde olacak NeredenIl= NeredenIl NeredenIlId= NeredenIlId NeredenIlce= NeredenIlce NeredenIlceId= NeredenIlceId NereyeIl= NereyeIl NereyeIlId= NereyeIlId NereyeIlce= NereyeIlce NereyeIlceId= NereyeIlceId Araç_Cins= Araç_Cins Arac_Kategori_ID= Arac_Kategori_ID Kasa_Tipi= Kasa_Tipi Kasa_Tipi_ID= Kasa_Tipi_ID Telefon= Telefon Fiyat= Fiyat KdvDahilmi= KdvDahilmi . Arac kategorisi id si yerine koyacağın id yi ve bu Arac cinsini sana hazır olarak veriyorum. Oluşturacağın json verisinde Arac Kategorisi id si şu olacak ARAC_KATEGORİ_ID ve aracın cinsi  ARAC_CINSI olarak gönderdiğim değer olacak. Json da kullanacağın kasa tipi için de kullanacağın id yi ve kasa tipini sana hazır olarak veriyorum. Kasa tipi için kullanacağın id şu olacak KASA_TİPİ_ID kasa tipi değeri için kullanacağın değer şu olacak KASA_TIPI. Bunlar haricindeki bilgileri sana ileteceğim mesaj içeriğinden doldurman gerekecek. Mesajda + KDV gibi bir ifade mevcutsa bu kdv fiyata dahil değil anlamına geliyor. Unutma sana verdiğim json kalıbının dışında bir veri istemiyorum. Sana gönderdiğim verileri bu kalıpta yerleştirerek yanıt olarak sadece oluşturduğun JSON değerini vermelisin.";

$textMessage= json_encode($text);
$kasaTipi = kasaTipi($text);
$aracKategorisi = aracKategorisi($text);
$ilIlce = ilIlce($text);

$mernis = json_decode($mernisJson, true);
$test = json_decode($ilIlce);

$messageText = 'Kamyona ait araç bilgilerini içeren id ve araç cinsi değerini sana hazır olarak veriyorum bunlar şu şekilde :'.$aracKategorisi.'.Bunun yanında yine kamyona ait kasa tipi bilgilerini içeren KASA_TIPI_ID ve KASA_TIPI değerlerini sana hazır olarak gönderiyorum bu bilgiler bu şekilde :'.$kasaTipi.'. Sana nereden nereye bilgilerini de hazır olarak vereceğim. NeredenIlId:'.$neredenIlId.', NeredenIlceId:'.$neredenIlceId.' NereyeIlId:'.$nereyeIlId.'NereyeIlceId:'.$nereyeIlceId.'. Bu nereden nereye gideceği bilgileri de jsondaki ilgili alanlara yerleştirmelisin. İstediğim JSON değerini oluşturmak için diğer gerekli bilgileri bu metinden eşleştirmeni isteyeceğim. Bu mesajı iyi incele ve boş kalan bilgileri bu mesajı anlamlandırarak doldur ->'.$text;
foreach ($mernis['Liste'] as $item) {
    if (strpos(strtolower($item['IL']), strtolower($test->NeredenIl)) === 0 && strpos(strtolower($item['İLÇE']), strtolower($test->NeredenIlce)) === 0) {
        $neredenIlId = $item["IL KODU"];
        $neredenIlceId = $item["İLÇE MERNİS KODU"];
    }
    if (strpos(strtolower($item['IL']), strtolower($test->NereyeIl)) === 0 && strpos(strtolower($item['İLÇE']), strtolower($test->NereyeIlce)) === 0) {
        $nereyeIlId = $item["IL KODU"];
        $nereyeIlceId = $item["İLÇE MERNİS KODU"];
    }
}

// Curl örneği
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.openai.com/v1/chat/completions');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    "model" => "gpt-3.5-turbo-1106",
    "messages" => [
        [
            "role" => "system",
            "content" => $systemPrompt
        ],
        [
            "role" => "user",
            "content" => $messageText
        ]
    ],
    "temperature" => 1,
    "max_tokens" => 256,
    "top_p" => 1,
    "frequency_penalty" => 0,
    "presence_penalty" => 0
]));

$headers = [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $apiKey
];
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);

// Curl işlemini başarıyla tamamladıktan sonra JSON yanıtını işle
$data = json_decode($result, true);
$dataJson = $data['choices'][0]['message']['content'] ?  $data['choices'][0]['message']['content'] : '';
// JSON yanıtını döndür
header('Content-Type: application/json');
echo $dataJson;

?>
