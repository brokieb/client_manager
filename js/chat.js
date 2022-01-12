console.log("JESTEŚMY")
$(document).ready(function() {

    $.ajax({
        type: "POST",
        url: "ajax/init-chat.php",
        dataType: "json",
        success: function(zawartosc) {
console.log("WWW SSAAAA#"+zawartosc['uid']+" "+zawartosc['username'],zawartosc['email'])


var Tawk_API=Tawk_API||{};
Tawk_API.visitor = {
name : 'visitor name',
email : 'visitor@email.com'
};

Tawk_LoadStart=new Date();

(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/60cc39fe65b7290ac63695bc/1f8es52fr';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();

            // document.tidioIdentify = {
            //     distinct_id: zawartosc['uid'], // Unique visitor ID in your system
            //     email:  zawartosc['email'], // visitor email
            //     name:  zawartosc['username']// Visitor na
            // };
            // var tidioScript = document.createElement("script");
            // tidioScript.src = "//code.tidio.co/61bvbibihlliq97g0yhb9mhmytxegrsk.js";
            // document.body.appendChild(tidioScript);
            // console.log(zawartosc);
        }
    });

})