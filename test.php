<?php
?>


<script>
    const ilIlceData = <?php echo $ilIlce ?>;
    fetch('il-ilce-mernis.json')
        .then(response => response.json())
        .then(data => {
            let neredenIl = ilIlceData.NeredenIl;
            let neredenIlce = ilIlceData.NeredenIlce;
            let nereyeIl = ilIlceData.NereyeIl;
            let nereyeIlce = ilIlceData.NereyeIlce;
            const neredenResults = data.Liste.filter(item => item["IL"].toLowerCase().startsWith(neredenIl.toLowerCase()) && item["İLÇE"].toLowerCase().startsWith(neredenIlce.toLowerCase()));
            const nereyeResults = data.Liste.filter(item => item["IL"].toLowerCase().startsWith(nereyeIl.toLowerCase()) && item["İLÇE"].toLowerCase().startsWith(nereyeIlce.toLowerCase()));
            let neredenIlKodu = '';
            let neredenIlceMernis = '';
            let nereyeIlKodu = '';
            let nereyeIlceMernis = '';

            if (neredenResults.length > 0 && nereyeResults.length>0) {
                neredenResults.forEach(item => {
                    neredenIlKodu = item["IL KODU"]
                    neredenIlceMernis = item["İLÇE MERNİS KODU"]
                });
                nereyeResults.forEach(item => {
                    nereyeIlKodu = item["IL KODU"]
                    nereyeIlceMernis = item["İLÇE MERNİS KODU"]
                });
            } else {
                console.log("Eşleşen değer bulunamadı.");
            }
        })
        .catch(error => console.error('Error loading JSON:', error));

</script>
