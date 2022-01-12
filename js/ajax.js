$(".close-alert").click(function () {
    var btn = $(this);
    $.ajax({
        type: "POST",
        url: "ajax/remove-this-alert.php",
        data: {
            id: btn.data("id"),
        },
        success: function (_zmienna) {

            btn.closest("div").remove()
        }
    });
});

$(".remove-saved-device").click(function(){
    var btn = $(this);
    $.ajax({
        type: "POST",
        url: "ajax/remove-saved-device.php",
        data: {
            id:$(this).attr('data-id')
        },
        dataType: 'json',
        success: function (e) {
         if(e==1){
            customAlert('success', 'OK!', "[Poprawnie wylogowano zapisane urządzenie]");
            btn.closest("li").remove();
         }else{
            customAlert('danger', 'Bład!', "Wystąpił błąd przy usuwaniu zapisanego urządzenia");
         }
        }
    })
})


$(".show-alert").click(function(){
    var btn = $(this);
    $.ajax({
        type: "POST",
        url: "ajax/remove-cookie.php",
        data: {
            id:$(this).attr('data-id')
        },
        dataType: 'json',
        success: function (e) {
         if(e==1){
            customAlert('success', 'OK!', "Poprawnie usunieto zabronienie");
            $(this).closest("tr").remove();
         }else{
            customAlert('danger', 'Bład!', "Wystąpił błąd przy usuwaniu zapisanego urządzenia");
         }
        }
    })
})


// * * EVENT HANDLERS AJAX * * //

$(document).on("click", "button.match-content", function () {


    matchType = $(this).attr("data-match-type"),
        foreignId = $(this).attr("data-foreign-id"),
        mainId = $(this).attr("data-main-id"),
        content = $(this).attr("data-content")


    $.ajax({
        type: "POST",
        url: "ajax/event/match-content.php",
        data: {
            matchType: matchType,
            foreignId: foreignId,
            mainId: mainId,
            content: content
        },
        dataType: 'html',
        success: function (modal) {
            console.log(modal);
            if (modal != 0) {
                customAlert('success', 'Sukces!', modal);
    
                $(".modal-body .content").find("tr[data-match-id='" + foreignId + "']").hide();
                // tooltip.hide()
            } else {
                customAlert('danger', 'Błąd!', "Wystąpił błąd przy ustawianiu statusu propozycji");
            }
        }
    })
 
})



var modalsArray = [];
modalsArray['modalx0'] = new bootstrap.Modal(document.getElementById('myModalx0'))
if ($("button[data-content-get]").length) {
    modal($("button[data-content-get]"), modalsArray['modalx0'], "myModalx0");
}

$(document).on('click', "[data-toggle='modal']", function () {
    var button = $(this);
    var el =  $(".modals-container .modal:last-child")
        var asd = el.attr("id");

        zIndex = el.css('z-index')
       

        var modalId = asd.split("x");
        var nextModalName = modalId[0] + "x" + (modalId[1] * 1 + 1)

        var elem = document.querySelector('#myModalx0');

        // Get HTML content
       
            var html = '<div class="modal fade" id="' + nextModalName + '" tabindex="-1" aria-labelledby="tytul" style="display: none;" aria-hidden="true">' + elem.innerHTML + "</div>";
            $("body .modals-container").append(html)
    

            var myModalEl = document.getElementById(nextModalName);
        modalsArray[nextModalName] = new bootstrap.Modal(myModalEl)



        myModalEl.addEventListener('hidden.bs.modal', function () {
            $(this).remove();
        })

        modal(button, modalsArray[nextModalName], nextModalName);



})

function modal(button, modal, id) {
    modal.show(); 



    
    $("#"+id).css("z-index",(zIndex*1)+2)
$(".modal-backdrop:last").css('z-index',(zIndex*1)+1)




    var thisModal = document.getElementById(id)
    var title = button.attr('data-title')
    var id = button.attr('data-id') //
    var modalId = button.attr('data-modal') 
    if(button.attr('data-call')){
        var phone = button.attr('data-call') //
    }else{
        var phone = null;
    }
    if(button.attr('data-content')){
        var content = button.attr('data-content')
    }else{
        var content = null;
    }
    if(button.attr('data-page')){
        var page = button.attr('data-page')
    }else{
        var page = null;
    }


    $.ajax({
        type: "POST",
        url: "ajax/modal-init.php",
        data: {
            modalId: modalId,
            content: content
        },
        dataType: 'json',
        success: function (modal) {
          console.log(modal);
             $.ajax({
                type: "POST",
                url: "ajax/" + modal['modal'][0]['modal_site'] + ".php",
                data: {
                    id: id,
                    content: content,
                    page: page
                },
                success: function (zawartosc) {
                    var modalTitle = thisModal.querySelector('.modal-title')
                    var modalBody = thisModal.querySelector('.modal-body .content')
                    var modalFooter = thisModal.querySelector('.modal-footer')
                    thisModal.querySelector('form').setAttribute('method', modal['modal'][0]['modal_method'])
                    thisModal.querySelector('form').setAttribute('id', modal['modal'][0]['modal_content'])
                    thisModal.querySelector('form').classList.add("modal-"+modal['modal'][0]['modal_size'])
                    switch(modal['modal'][0]['modal_size']){
                        case 'sm':
                            break;
                        case null:
                            
                            break;
                         default:
                            thisModal.querySelector('form').classList.add("modal-dialog-scrollable")
                             break;
                    }
                    $(modalFooter).find("[data-id='null']").each(function(){
                        $(this).attr('data-id',id)
                    })
                    $(modalFooter).find("[data-page='null']").each(function(){
                        $(this).attr('data-page',content)
                    })
                    if (modal['group'] != null) {
                        if ($(thisModal).find(".modal-menu").length > 0) {
                            $(thisModal).find(".modal-menu").remove();
                        }
                        
                        $(".modal-header").after('<div class="modal-menu m-0 p-1 row"></div>');
                        var modalMenu = thisModal.querySelector(".modal-menu");
                        var menuButton = "<div class='btn-group' role='group'>";
                        menuButton += "<a href='#' data-group-id='0' class='btn btn-sm btn-info active col'><i class='fas fa-info-circle px-2'></i>Podsumowanie</a>";
                        modal['group'].forEach(function (z) {
                            menuButton += "<a href='#' data-group-name='" + z['group_name'] + "' data-group-id='" + z['group_id'] + "' class='btn btn-sm btn-info col'><i class='fas " + z['group_icon'] + " px-2'></i>" + z['group_title'] + "</a>";
                        })
                        menuButton += "</div>";
                        modalMenu.innerHTML = menuButton;
                    } else {
                        
                        if ($(thisModal).find(".modal-menu").length > 0) {
                            $(thisModal).find(".modal-menu").remove();
                        }
                    }
                    modalTitle.textContent = title;
                    modalBody.innerHTML = zawartosc;

                    
                    // if ($(thisModal).find(".content:not([data-group=0])").length > 0) {
                    //     $(thisModal).find(".content:not([data-group=0])").remove();
                    //     $(thisModal).find(".content[data-group=0]").removeClass("d-none")
                    // }




                //     do{

                   

                   
    
                // }while($(thisModal).find('form .modal-body').height()/2<0);


               

                    $(modalBody).find(".checks").each(function () { //to jest to wyświetlania edytorów/wyświetlaczy klientów/mieszkań/spotkań
                        if ($(this).find("*").length <= 1) {
                            $(this).closest(".checks-main").remove();
                        }
                    })


                    $(modalBody).find(".print-title").each(function(){
                        $(this).css('background',"red")
                        if($(this).siblings("table").find("tbody tr").length==0){
                            $(this).closest(".col-6").remove();
                        }
                    })

                    var clip = new ClipboardJS('.clipboard');
                    clip.on('success', function (e) {
                        e.clearSelection();
                    });

                    // * * USTAWIENIE JAKIE PRZYCISKI MAJĄ BYĆ WYŚWIETLONE W MODALU * * //

                    if($(modalBody).find("div[deny=true]").length>0){
                        $(modalFooter).text("Brak uprawnień")
                    }else{

                  
                    if (modal['modal'][0]['modal_call'] == 1) { //Przycisk do dzwonienia
                        $(modalFooter).find("a#call").show();
                        $(modalFooter).find("a#call").attr('href', 'tel:' + phone)
                    } else {
                        $(modalFooter).find("a#call").hide();
                    }
                    if (modal['modal'][0]['modal_print'] == 1) {
                        $(modalFooter).find('button#print').show();
                    } else {
                        $(modalFooter).find('button#print').hide();
                    }
                    if(modal['modal'][0]['modal_action'] == 1){
                        $(modalFooter).find(".content-actions").show();
                        //odpalenie funkcjonalności 
                        $(modalFooter).find(".content-actions button").on("click","")

                    }else{
                        $(modalFooter).find(".content-actions").hide();
                    }

                    
                    switch (modal['modal'][0]['modal_submit']) {
                        case 'submit': //Przycisk do akceptowania
                            $(modalFooter).find('#save').show();
                            break;
                        case 'button': //Przycisk bez odświeżania, nie odświeża strony
                            $(modalFooter).find('#prevent').show();
                            $(thisModal).find("form").submit(function(e){
                                e.preventDefault();
                            })
                            break;
                        case 'send': //przycisk do wysyłania
                            $(modalFooter).find('#send').show();
                            break;
                            case 'confirm':
                                $(modalFooter).find('#confirm').show();
                                
                                break;
                        default:
                            $(modalFooter).find('#save').hide();
                            $(modalFooter).find('#prevent').hide();
                            $(modalFooter).find('#send').hide();
                            break;
                       
                    }

                    if (modal['modal'][0]['modal_moderate'] == 1 && $("body").attr("data-s")==1) { //Przyciski do usuwania/archiwizowania
                        $(modalFooter).find('.moderate').show();
                        $(modalFooter).find('.moderate button').attr('data-id', id)
                    } else {
                        $(modalFooter).find('.moderate').hide();
                    }
                }


                    // * * MOŻLIWOŚĆ PRZECHODZENIA POMIĘDZY GRUPAMI * * //

                    $(thisModal).find(".modal-menu [data-group-id]").on('click', function () {
                        $(thisModal).find("[data-group-id]").removeClass('active')
                        $(this).addClass('active');
                        var modalGroupId = $(this).attr('data-group-id');
                        var modalGroupName = $(this).attr('data-group-name');
                        $(thisModal).find(".modal-body content:not(.d-none)").addClass("d-none");
                        if ($(thisModal).find('.content[data-group=' + modalGroupId + ']').length > 0) { //grupa została już utworzona, tylko ją wyświetlamy
            
                            $(thisModal).find("[data-group]:not(.d-none)").addClass("d-none")
                            $(thisModal).find(".d-none[data-group=" + modalGroupId + "]").removeClass("d-none")
                        } else {//grupa nie istnieje, pobieramy zawartość
                            $.ajax({
                                type: "POST",
                                url: "ajax/modal-group/" + modalGroupName + ".php",
                                data: {
                                    id: modalGroupId,
                                    contentId: id,
                                    contentName: content

                                },
                                dataType: 'html',
                                success: function (wynik) {
                          
                                    $(thisModal).find("[data-group]:not(.d-none)").addClass("d-none");
                                    $(thisModal).find(".modal-body").append("<div class='content' data-group='" + modalGroupId + "'>" + wynik + "</div>")

                                    $(thisModal).find('[data-bs-tooltip="tooltip"]').tooltip();
                                }
                            })
                        }


                    })


                    // * * USTAWIANIE ODPOWIEDNIO WIDOCZNYCH PÓŁ FORMULARZA * * //

                    var ids = [];
                    $(thisModal).find("input[type=radio]:checked").each(function () {
                        let idd = $(this).attr('data-toggle-id')
                        ids.push(idd);
                        $(this).closest("[data-actual]").attr("data-actual",idd)
                    })

                    $(thisModal).find("[data-show-id]").each(function (_x, _y) {
                 
                        if ($(this).data('show-id') != "") {
                            var thisid = $(this).attr('data-show-id');
                            var i = 0;
                           
                            if(thisid==modal['modal'][0]['modal_content']){
                                i = 1;
                             
                            }else{
                                thisid.split(",").forEach(function (x) {
                                    if (ids.indexOf(x) >= 0) {
                                        i++;
                         
                                    }
    
                                })
                            }

                            
                            if (i == 0) {
                                $(this).addClass("d-none")
                            }
                        }
                    })

                    // * * POLE NUMERU TELEFONU * * //

if($(thisModal).find("input[type=tel]").length>0){
                    var input = thisModal.querySelector("input[type=tel]");
                    var errorMsg = thisModal.querySelector("#invalid-" + $(input).attr("id") + "");
        
                    var errorMap = ["Nieprawidłowy numer", "Nieprawidłowy kod kraju", "Numer jest za krótki", "Numer jest za długi", "Numer jest nieprawidłowy"]
                    var iti = window.intlTelInput(input, {
                        preferredCountries: ['pl'],
                        utilsScript:
                            "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
                        customContainer: "input-group"

                    });

                    input.addEventListener("countrychange", function() {
                        // do something with iti.getSelectedCountryData()
                        var place = $(this).attr('placeholder');
                        $(this).closest(".input-group").prev().html("Telefon ( "+place+" )");
                      });
           
                    $(".iti").removeClass("iti")
                    $(".iti__flag-container").addClass("btn btn-outline-secondary")






                    var reset = function () {
                        input.classList.remove("error");
                        errorMsg.innerHTML = "";
                        $(errorMsg).hide()
                    };

                    // on blur: validate
                    input.addEventListener('blur', function () {
                        reset();
                        if (input.value.trim()) {
                            if (iti.isValidNumber()) {
                            } else {
                                input.classList.add("error");
                                var errorCode = iti.getValidationError();
                                errorMsg.innerHTML = errorMap[errorCode];
                                $(errorMsg).show()
                            }
                        }
                    });


                    $(thisModal).on("submit", "form:not(.formReady)", function (e) {
                       
                        e.preventDefault();
                        var form = $(this).closest("form");
                        form.find(".d-none").each(function () {
                            $(this).remove();
                        })
                        var i = 0;
                        form.find("input[required=required]").each(function () {
                            if ($(this).val() == "") {
                                if (i == 0) {
                                    $(this).focus();
                                    i++;
                                }
             
                                $("#invalid-" + $(this).attr("id")).text("To pole nie może pozostać puste !")
                                $("#invalid-" + $(this).attr("id")).show();
                            }else{
                                $("#invalid-" + $(this).attr("id")).hide();
                            }    
                 })
                     console.log("eszke!!",$(form).find("[data-show-one=1]").is(":visible"));
                 
                 if(!$(".invalid-feedback").is(':visible')){
    form.find("input[type=tel]").val(iti.getNumber())
    form.addClass("formReady")
     form.submit();
}else{
  
}
                    })



                    // on keyup / change flag: reset
                    input.addEventListener('change', reset);
                    input.addEventListener('keyup', reset);

                }





                    // * * FUNKJE MODALI * * //

                    $('[data-bs-tooltip="tooltip"]').tooltip();

                    $(".select").select2({// ustawienie customowych selectów
                        matcher: function(params, data) {
                            var original_matcher = $.fn.select2.defaults.defaults.matcher;
                            var result = original_matcher(params, data);
                            if (result && data.children && result.children && data.children.length != result.children.length && data.text.toLowerCase().includes(params.term.toLowerCase()) ) {
                                 result.children = data.children;
                            }
                            return result;
                        },
                        width: 'resolve' 
                     });



                    function IsEmail(email) {
                        var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                        if(!regex.test(email)) {
                          return false;
                        }else{
                          return true;
                        }
                      }



                    // * * FUNKCJONALNOSCI DLA DANYCH MODALI * * //

                    if ($(thisModal.querySelector("#back-to")).length) { //powrót do zapisanej pozycji w ciasteczkach

                        if ($(thisModal.querySelector("form")).find(".back-to-content") !== null) {
                            $(thisModal.querySelector("form")).change(function () {
                                var where = $(this).find('input[type=radio]:checked').attr('data-site');
                                var ans = where.split("|");
                                $(this).closest('form').attr('action', 'index.php');
                                $(this).closest('form').find('input[name=site]').val(ans[0])
                            });
                        }
                    } else if ($(thisModal.querySelector("#search-engine")).length) { //wyszukiwarka

                        var typingTimer; //timer identifier
                        var doneTypingInterval = 1000; //time in ms, 5 second for example
                        var input = $('#search-engine input');

                        //on keyup, start the countdown
                        input.on('keyup', function () {
                            clearTimeout(typingTimer);
          
                            if (input.val().length >= 3) {
                                typingTimer = setTimeout(doneTyping, doneTypingInterval);
                            }
                        });

                        //on keydown, clear the countdown 
                        input.on('keydown', function () {
                            clearTimeout(typingTimer);
                        });


                        input.closest("#search-engine").serialize();
                        //user is "finished typing," do something
                        function doneTyping() {

                            $.ajax({
                                type: "POST",
                                url: "ajax/search-engine.php",
                                data: {
                                    query: input.val(),
                                    source: input.closest("#search-engine input[name=source]:checked").attr('id')

                                },
                                success: function (zawartosc) {

                                    $("#search-engine .search-content").html(zawartosc)

                                }
                            });


                        }

                    } else if ($(thisModal.querySelector("#custom-value")).length) { //dodanie customowego checkboxa
            
                        $($(thisModal).find("#prevent")).one("click", function () {
                            var customInput = $(thisModal.querySelector("#new-checkbox-input")).val();

                            var custom = $(button).parents(".border").find(".checkbox:first-child").html()
                            $(".checkbox-new[data-custom-button-id='" + id + "']").before(
                                $(custom).attr({
                                    'id': "*" + customInput + "*",
                                    'value': "*" + customInput + "*",
                                    'for': "*" + customInput + "*",
                                    'checked': 'checked'
                                }).text("* " + customInput + " *"))





                        })
                    }else if($(thisModal.querySelector("#add-user-group")).length) {

                        function checkFeedbacks(emaildiv,$mode = 0){
                            var i = 0;
                            emaildiv.find(".feedback:visible").each(function(){
                                i++;
                            })
                            if(i==0){

                        var html =   emaildiv.last();
                       var email = emaildiv.find("input");
                       if(IsEmail(email.val())==true){ //email prawdziwy
                        var splited = email.val().split("@");
                        if(splited[1]==$(thisModal).find("#add-company-emails").attr('data-email')){//domena dozwolona

                            $.ajax({
                                type: "POST",
                                url: "ajax/check-user-exist.php",
                                dataType: "json",
                                data: {
                                    email: splited[0]
                                },
                                success: function (e) {
var i = 0;
$(thisModal).find(".add-email input:not(:last-child)").each(function(){

    if($(this).val()==email.val()){
        i++;
    }
    
})

if(i>1){
    html.find('.invalid-feedback').text("Ten email został już użyty powyżej!")
    html.find('.invalid-feedback').show()
}else{

    if(e==1){//konto już istnieje, wyślę zaproszenie
        html.find('.valid-feedback').text("Konto istnieje, zostaje wysłany email z zaproszeniem")
        html.find('.valid-feedback').show()
    }else if(e==2){
        html.find('.invalid-feedback').text("Użytkownik z tym adresem został już zaproszony")
        html.find('.invalid-feedback').show()
    }else{//konto zostanie utworzone
        
        html.find('.valid-feedback').text("Takiego adresu nie mamy w bazie, zostanie utworzone konto")
        html.find('.valid-feedback').show()
    }
    var i =0;
$(thisModal).find(".add-email input").each(function(){
if($(this).val()==""){
i++;
}
})
    if($(thisModal).find(".invalid-feedback:visible").length==0&&i==0&&$mode==0){
        
        $(thisModal).find("#add-company-emails .add-email").last().after("<div class='add-email input-content'>"+html.html()+"</div>")
        $(thisModal).find("#add-company-emails .add-email").last().find('.btn').attr('disabled',false)
        $(thisModal).find("#add-company-emails .add-email").last().find(".feedback").hide();
    }

}
                                }
                            });


                            
                        }else{//domena niedozwolona
                            html.find('.invalid-feedback').text("Taka domena nie jest akceptowalna")
                                        html.find('.invalid-feedback').show()
                        }
                    }else{//błędny email
                        html.find('.invalid-feedback').text("To nies jest prawidłowy adres email")
                        html.find('.invalid-feedback').show()
                       }

                            }
                            if($mode==1&&$(thisModal).find(".invalid-feedback:visible").length==0){
                                return 1;                            }

                        }



                        $(thisModal).on('click','.add-another-email',function(){
                            $(this).closest("#add-company-emails").find(".add-email").each(function(){
                                checkFeedbacks($(this))
                            })
                           
                        })
                        $(thisModal).on('click','.remove-company-email',function(){
                            
                            $(this).closest(".add-email").remove();
                        })
                        $(thisModal).on('input','.add-email input',function(){
                            if($(this).closest(".add-email").find(".feedback").is(":visible")){
                                $(this).closest(".add-email").find(".feedback").hide()
                            }
                        })
                        
                        $(thisModal).on('submit','form:not(.checked)',function(e){
                            e.preventDefault();
                            var form = $(this).closest("form");
                            var i =0;
                            $(this).find(".modal-content .add-email").each(function(){
                                checkFeedbacks($(this),1)
                                
                            })
                            if(i==0){
                            
                                form.addClass('checked')
                                form.submit();
                            }
                        })
                       
                    }



                },
                error:function(e){
                }

            })






        }
    })



}


$("#load-more").click(function () {

    var button = $(this);
    var offset = button.attr('data-offset');
    button.closest("table").attr('data-config')
    $.ajax({
        type: "POST",
        url: "ajax/load-more.php",
        data: {
            view: $(this).attr('data-view'),
            offset: offset,
            config: button.closest("table").attr('data-columns')
        },
        success: function (zawartosc) {
         
            if (zawartosc.indexOf("KONIEC") >= 0) {
                button.text("To już wszystkie elementy");
                button.prop('disabled', true);
            } else {
                button.closest("tfoot").prev().append(zawartosc)
                button.attr('data-offset', offset * 1 + 10)
            }

        }
    });



});

$(".accept-action").click(function () {


    $.ajax({
        type: "POST",
        url: "ajax/accept-action.php",
        data: {
            action: $(this).attr('data-action'),
            id: $(this).attr('data-id'),
            site: $(this).attr('data-site')

        },
        dataType: "html",
        success: function (zawartosc) {

            customAlert(zawartosc.type, zawartosc.title, zawartosc.value);
        }
    });
})

$(".resend-activate-link").click(function () {

    $.ajax({
        type: "POST",
        url: "ajax/resend-activate-link.php",
        dataType: "json",
        success: function (e) {

            customAlert(e['type'], e['subject'], e['value']);
        }
    });

})