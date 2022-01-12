document.addEventListener("DOMContentLoaded", function(event) {
const showNavbar = (toggleId, navId, bodyId, headerId) =>{
const toggle = document.getElementById(toggleId),
nav = document.getElementById(navId),
bodypd = document.getElementById(bodyId),
headerpd = document.getElementById(headerId)

// Validate that all variables exist
if(toggle && nav && bodypd && headerpd){
    console.log('istnieje')
toggle.addEventListener('click', ()=>{
// show navbar
nav.classList.toggle('show')
// change icon
toggle.classList.toggle('open')
// add padding to body
bodypd.classList.toggle('body-pd')
// add padding to header
headerpd.classList.toggle('body-pd')
$(".nav-backdrop").toggleClass("d-none");
$("body").on('click',".nav-backdrop",function(){
    $(this).addClass("d-none");
    $(nav).removeClass('show')
    $("#header-toggle").removeClass("open")
})

})
}
}

showNavbar('header-toggle','nav-bar','body-pd','header')

/*===== LINK ACTIVE =====*/ 

// Your code to run since DOM is loaded and ready
});
