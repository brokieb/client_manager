<h3>Jesteś na specjalnej stronie przygotowanej do obsługi chatu z administracją, kliknij w prawym dolnym rogu żeby otworzyć chat i rozpocząć rozmowę </h3>
<button class='teste'>asd</button>
<script>
                // Here is an example showing how you could do it using PHP
               
                $(".teste").click(function(){

                    var Tawk_API = Tawk_API || {};
                Tawk_API.visitor = {
                    name: '<?=$details['username']?>',
                    email: '<?=$details['email']?>',
                    hash: '<?=hash_hmac("sha256", $details['email'], "83571b51844615aa2c239b0849297682398a4653")?>'
                };
                // Tawk_API = Tawk_API || {}, Tawk_LoadStart = new Date();
                
                (function() {
                    var s1 = document.createElement("script"),
                        s0 = document.getElementsByTagName("script")[0];
                    s1.async = true;
                    s1.src = 'https://embed.tawk.to/60cc39fe65b7290ac63695bc/1f8es52fr';
                    s1.charset = 'UTF-8';
                    s1.setAttribute('crossorigin', '*');
                    s0.parentNode.insertBefore(s1, s0);
                })();
});
            </script>