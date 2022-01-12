
var nextPage = [{
    title: 'Intro',
    text: `To wszystko co przygotowałem dla Ciebie na tej stronie, mogę przejść dalej?`,
    buttons: [
      {
        action() {
          window.location.href = wwa;
          //przejście na następną stronę
        },
        text: 'Tak!'
      }
    ],
    id: 'creating'
  }
]
var lastPage = [{
    title: 'Intro',
    text: `Wszystkie funkcjonalności na tej stronie zostały już wyjaśnione, jeżeli masz pomysł na rozwój aplikacji lub chciałbyś funkcję dostosow`,
    buttons: [
      {
        action() {
          return this.next();
        },
        text: 'Tak!'
      }
    ],
    id: 'creating'
  }
]
finishCourse = [{
    title: 'Zakończyłeś kurs',
    text: `Przeszedłeś przez cały kurs, jezeli dalej jest coś dla Ciebie niejasne lub masz pomysł na funkcjonalność która usprawniłaby Ci pracę - proszę o kontakt na chacie`,
    buttons: [
      {
        action() {
          return this.next();
        },
        text: 'Tak!'
      }
    ],
    id: 'creating'
  }
]
preview = [{
  title: 'Intro',
  text: `Witaj! jaki kurs chcesz zobaczyć? Jeżeli nie masz jeszcze żadnych klientów/ spotkań włacz opcje Dane demonstracyjne na stronie Profil, będzie Ci łatwiej zrozumieć funkcje `,
  buttons: [
    {
      action() {
        $.cookie("tour",true)
        window.location.replace("index.php?site=add-client");
        return this.next();
      },
      text: 'Pełen kurs!'
    },{
      action() {
        var a = document.URL
        console.log(a)
        var part = a.substring(
          a.lastIndexOf("site=") + 5,
          a.lastIndexOf("#")
      );
      console.log(eval(part.replace("-","")))
        this.addSteps(eval(part.replace("-","")));
        this.addSteps(lastPage);
        return this.next();
      },
      text: 'Tylko ta strona'
    }

  ],
  id: 'creating'
}]
addclient = [{
    title: 'Intro',
    text: `Witaj, na tej stronie możesz dodawać szczegóły i wymagania klienta który poszukuje róznego rodzaju nieruchomości`,
    buttons: [
      {
        action() {
          return this.next();
        },
        text: 'Tak!'
      }
    ],
    id: 'creating'
  },{
    title: 'Szczczegóły pola tekstowego',
    text: `Przy podawaniu szczegółów przy polu tekstowym w nawiasie jest informacja jakie dane powinniśmy tutaj wprowadzić`,
    attachTo: {
      element: '.form-control',
      on: 'bottom'
    },
    buttons: [
      {
        action() {
          return this.back();
        },
        classes: 'shepherd-button-secondary',
        text: 'Back'
      },
      {
        action() {
          return this.next();
        },
        text: 'Next'
      }
    ],
    id: 'creating'
  },{
    title: 'Pola checkbox',
    text: `Pola ustawione obok siebie są polami checkbox (możemy wybrać więcej niż jedną opcję )`,
    attachTo: {
      element: '.border .justify-content-around',
      on: 'bottom'
    },
    buttons: [
      {
        action() {
          return this.back();
        },
        classes: 'shepherd-button-secondary',
        text: 'Back'
      },
      {
        action() {
          return this.next();
        },
        text: 'Next'
      }
    ],
    id: 'creating'
  },{
    title: 'Dodanie własnego checkbox',
    text: `Klikając na przycisk INNE POLE możemy dodać własny checkbox który będzie dostępny tylko przy tym formularzy, przy następnym dodawaniu klientów / mieszkań pola te nie będą uwzględnione. Jeżeli często za każdym razem dodajesz dokładnie to samo pole, napisz do mnie wiadomość dodam je dla Ciebie na stałe.`,
    attachTo: {
      element: '.checkbox-new',
      on: 'bottom'
    },
    buttons: [
      {
        action() {
          return this.back();
        },
        classes: 'shepherd-button-secondary',
        text: 'Back'
      },
      {
        action() {
          return this.next();
        },
        text: 'Next'
      }
    ],
    id: 'creating'
  },{
    title: 'Uwagi o kliencie',
    text: `Bardzo ważnym polem w wszelkich formularzach na stronie jest uzupełnianie uwag dotyczących klienta - możemy tutaj umieścić cenne uwagi które dostaliśmy od klienta`,
    attachTo: {
      element: 'textarea',
      on: 'bottom'
    },
    buttons: [
      {
        action() {
          return this.back();
        },
        classes: 'shepherd-button-secondary',
        text: 'Back'
      },
      {
        action() {
          return this.next();
        },
        text: 'Next'
      }
    ],
    id: 'creating'
  }
]

profile= [{
  title: 'Własne ustawienia',
  text: `Wszelkie ustawienia, konfiguracje połączeń, statystyki, rozliczenia są/będą dostępne w tej zakładce`,
  buttons: [
    {
      action() {
        return this.back();
      },
      classes: 'shepherd-button-secondary',
      text: 'Back'
    },
    {
      action() {
        return this.next();
      },
      text: 'mainProfile'
    }
  ],
  id: 'creating'
},{
  title: 'Własne pola',
  text: `Bardzo ważnym jest żeby uzuepłnić regiony w których pracujemy, dzięki temu przy dodawaniu nieruchomości lub mieszkań będziemy mogli oznaczyć regiony które danego klienta interesują`,
  attachTo: {
    element: 'textarea',
    on: 'bottom'
  },
  buttons: [
    {
      action() {
        return this.back();
      },
      classes: 'shepherd-button-secondary',
      text: 'Back'
    },
    {
      action() {
        return this.next();
      },
      text: 'Next'
    }
  ],
  id: 'regions'
},{
  title: 'Uporządkowanie bocznego menu',
  text: `Możemy ustalić kolejność pól w pasku bocznym, strona którą ustawimy jako pierwszą będzie uruchamiała się jako pierwsza odrazu po zalogowaniu. Możemy na samej górze ustawić pola <i class="pe-1 fas fa-backward"></i> lub <i class="pe-1 fas fa-search"></i> nie będą one brane pod uwagę przy domyślnym uruchamianiu, chociaż dalej będą na samej górze `,
  attachTo: {
    element: '#sortable',
    on: 'bottom'
  },
  buttons: [
    {
      action() {
        return this.back();
      },
      classes: 'shepherd-button-secondary',
      text: 'Back'
    },
    {
      action() {
        return this.next();
      },
      text: 'Next'
    }
  ],
  id: 'sort-navigation'
},{
  title: 'Statystyki z korzystania',
  text: `Są tu wyświetlane wszelkie informacje o twoim koncie oraz co ważne szczegóły o aktywnym abonamencie`,
  attachTo: {
    element: '#account-details',
    on: 'bottom'
  },
  buttons: [
    {
      action() {
        return this.back();
      },
      classes: 'shepherd-button-secondary',
      text: 'Back'
    },
    {
      action() {
        return this.next();
      },
      text: 'Next'
    }
  ],
  id: 'account-details'
},{
  title: 'Połączenie z kalendarzem Google',
  text: `W aplikacji możemy łączyć swoje konto z kalendarzem Google w celu synchronizacji dodawanych spotkań, aktualnie aplikacja nie została jeszcze potwierdzona przez Google więc wyświetli się ostrzeżenie że połączenie może być niebezpieczne - pomimo to, możemy połączyć konto`,
  attachTo: {
    element: '#login-google',
    on: 'bottom'
  },
  buttons: [
    {
      action() {
        return this.back();
      },
      classes: 'shepherd-button-secondary',
      text: 'Back'
    },
    {
      action() {
        return this.next();
      },
      text: 'Next'
    }
  ],
  id: 'login-google'
},{
  title: 'Przełaczniki',
  text: `Tutaj możesz właczyc rózne usługi/dodatkowe moduły dostępne w aplikacji. `,
  attachTo: {
    element: '#modules',
    on: 'bottom'
  },
  buttons: [
    {
      action() {
        return this.back();
      },
      classes: 'shepherd-button-secondary',
      text: 'Back'
    },
    {
      action() {
        return this.next();
      },
      text: 'Next'
    }
  ],
  id: 'regions'
}]

addbuild = [{
  title: 'Pola wpływające na dalszą część formularza',
  text: `W forularzach znajdują się pola jednokrotnego wyboru, może być takie pole również jako takie które wpłynie na dalszą część formularza (<i id="form-editor" class="ps-2 fas fa-stream"</i>) dlatego zwróć uwagę przy dodawaniu danych do formularza żeby takie pole zaznaczyć na początku, ponieważ jeśli uzupełnisz pole które będzie dostępne tylko np. dla działki i zmienisz wybór <i id="form-editor" class="ps-2 fas fa-stream" na Dom, uzupełnione pole MOŻE nie być uzupełnione`,
  attachTo: {
    element: '#form-editor:nth-child(1)',
    on: 'bottom'
  },
  buttons: [
    {
      action() {
        return this.back();
      },
      classes: 'shepherd-button-secondary',
      text: 'Back'
    },
    {
      action() {
        $("#form-editor:first").click();
        return this.next();

      },
      text: 'Next'
    }
  ],
  id: 'account-details'
},{
  title: 'Pola z niestandardową maską',
  text: `W formularzach występują również pola które mają specyficzny format, w tym miejscu aplikacja nie pozwoli nam na wpisanie danych w innym formacie, jeżeli przy wpisywaniu pola nie wyświetlają się - sprawdź czy na pewno znak który wpisujesz powinien się tutaj znaleźć `,
  attachTo: {
    element: '#build_kw',
    on: 'bottom'
  },
  buttons: [
    {
      action() {
        return this.back();
      },
      classes: 'shepherd-button-secondary',
      text: 'Back'
    },
    {
      action() {
        return this.next();
      },
      text: 'Next'
    }
  ],
  id: 'account-details'
}
,{
  title: 'INNE POLE',
  text: `Tak jak w innych formularzach tutaj też można dodać własne pole które będzie widoczne tylko na tej stronie. `,
  attachTo: {
    element: '.checkbox-new',
    on: 'bottom'
  },
  buttons: [
    {
      action() {
        return this.back();
      },
      classes: 'shepherd-button-secondary',
      text: 'Back'
    },
    {
      action() {
        return this.next();
      },
      text: 'Next'
    }
  ],
  id: 'account-details'
}
]


addmeet = [{
  title: 'Typ spotkania',
  text: `Tak jak w poprzednich formularzach tutaj też twój wybór zalezy od reszty, zostało założone że spotkania takie jak prezentacja, um. przedwstępna, akt moga być tworzone tylko z użytkownikami z bazy. Przed dodaniem spotkania upewnij się że klient z którym chcesz stworzyć spotkanie istnieje w bazie`,
  attachTo: {
    element: '[for="prezentacja"]',
    on: 'bottom'
  },
  buttons: [
    {
      action() {
        return this.back();
      },
      classes: 'shepherd-button-secondary',
      text: 'Back'
    },
    {
      action() {
        $('[for="prezentacja"]').click()
        return this.next();

      },
      text: 'Next'
    }
  ],
  id: 'account-details'
},{
  title: 'Wybór klienta',
  text: `W tym miejscu wybieramy z jakim klientem tworzone jest spotkanie`,
  attachTo: {
    element: 'select',
    on: 'bottom'
  },
  buttons: [
    {
      action() {
        return this.back();
      },
      classes: 'shepherd-button-secondary',
      text: 'Back'
    },
    {
      action() {
        return this.next();

      },
      text: 'Next'
    }
  ],
  id: 'account-details'
},{
  title: 'Synchronizacja z Google',
  text: `Jeżeli twoje konto połaczone jest z Google Calendar automatycznie po dodaniu spotkania, zobaczysz je w swoim kalendarzu Google `,
  buttons: [
    {
      action() {
        return this.back();
      },
      classes: 'shepherd-button-secondary',
      text: 'Back'
    },
    {
      action() {
        return this.next();

      },
      text: 'Next'
    }
  ],
  id: 'account-details'
}]

buildlist = [{
  title: 'możliwości w tabeli',
  text: `Każdy klient jest opisany indywidualnym ID - po tym najłatwiej rozpoznasz dana pozycję.`,
  attachTo: {
    element: 'tr[data-id]',
    on: 'bottom'
  },
  buttons: [
    {
      action() {
        return this.back();
      },
      classes: 'shepherd-button-secondary',
      text: 'Back'
    },
    {
      action() {
        return this.next();

      },
      text: 'Next'
    }
  ],
  id: 'account-details'
},{
  title: 'Wskazówki dojazdu',
  text: `Kliknięcie w adres przy danej pozycji uruchomi nam nawigację Google - możemy wtedy łatwo sprawdzić gdzie dana miejscowość dokładnie znajduje się.`,
  attachTo: {
    element: '[data-tbl-title=address]',
    on: 'bottom'
  },
  buttons: [
    {
      action() {
        return this.back();
      },
      classes: 'shepherd-button-secondary',
      text: 'Back'
    },
    {
      action() {
        return this.next();

      },
      text: 'Next'
    }
  ],
  id: 'account-details'
},{
  title: 'Połaczenie telefoniczne',
  text: `Kliknięcie w numer telefonu pozwoli nam na wykonanie polaczenie telefonicznego bez potrzeby kopiowania / przepisywania numeru telefonu`,
  attachTo: {
    element: '[data-tbl-title=phone]',
    on: 'bottom'
  },
  buttons: [
    {
      action() {
        return this.back();
      },
      classes: 'shepherd-button-secondary',
      text: 'Back'
    },
    {
      action() {
        $("[data-bs-original-title='Edycja']").click()
        return this.next();

      },
      text: 'Next'
    }
  ],
  id: 'account-details'
},{
  title: 'Identyczne pola',
  text: `Znajduje się tutaj formularz identyczny do tego służacego do dodawania pozycji`,
  buttons: [
    {
      action() {
        return this.back();
      },
      classes: 'shepherd-button-secondary',
      text: 'Back'
    },
    {
      action() {
        return this.next();

      },
      text: 'Next'
    }
  ],
  id: 'account-details'
},{
  title: 'Pamiętaj o zapisaniu',
  text: `W panelu dolnym znajduja sie funkcje które sa aktualnie dostępne dla tego okna. Dla przykładu w oknie edycji możemy zadzwonić, zapisać zmiany w formularzu lub usunć/zarchiwizować dana pozycje`,
  attachTo: {
    element: '.modal-footer',
    on: 'top'
  },
  buttons: [
    {
      action() {
        return this.back();
      },
      classes: 'shepherd-button-secondary',
      text: 'Back'
    },
    {
      action() {
        $("#close").click()
        return this.next();

      },
      text: 'Next'
    }
  ],
  id: 'account-details'
},{
  title: 'Przyciski funkcyjne',
  text: `Do każdej tabeli moga być przydzielone rozne funkcje, zwracaj uwagę jakie przyciski sa dostepne w danej tabeli. Teraz przejdziemy do szczegółów o pozycji.`,
  attachTo: {
    element: '[data-bs-original-title="Informacje"]',
    on: 'top'
  },
  buttons: [
    {
      action() {
        return this.back();
      },
      classes: 'shepherd-button-secondary',
      text: 'Back'
    },
    {
      action() {
        $("[data-bs-original-title='Informacje']").click()
        return this.next();

      },
      text: 'Next'
    }
  ],
  id: 'account-details'
},{
  title: 'Informacje',
  text: `W tym panelu możemy dowiedzieć się wszelkich szczegółów na temat tej pozycji.`,
  buttons: [
    {
      action() {
        return this.back();
      },
      classes: 'shepherd-button-secondary',
      text: 'Back'
    },
    {
      action() {
        return this.next();

      },
      text: 'Next'
    }
  ],
  id: 'account-details'
},{
  title: 'Kopiowanie elementów',
  text: `Kliknięcie w przycisk schowka możemy łatwo skopiować całe pole bez potrzeby zaznaczania.`,
  attachTo: {
    element: '.clipboard',
    on: 'top'
  },
  buttons: [
    {
      action() {
        return this.back();
      },
      classes: 'shepherd-button-secondary',
      text: 'Back'
    },
    {
      action() {
        $("#close").click()
        return this.next();

      },
      text: 'Next'
    }
  ],
  id: 'account-details'
},{
  title: 'Drukowanie',
  text: `Klikajac w ten przycisk mozemy wywolac funkcje drukowania podsumowania danej pozycji`,
  attachTo: {
    element: "[data-bs-original-title='Drukuj']",
    on: 'top'
  },
  buttons: [
    {
      action() {
        return this.back();
      },
      classes: 'shepherd-button-secondary',
      text: 'Back'
    },
    {
      action() {
        return this.next();
      },
      text: 'Next'
    }
  ],
  id: 'account-details'
},{
  title: 'Wyszukiwanie',
  text: `Do każdej strony implementowany jest moduł wyszukiwarki.`,
  attachTo: {
    element: '[aria-controls="adv-search"]',
    on: 'top'
  },
  buttons: [
    {
      action() {
        return this.back();
      },
      classes: 'shepherd-button-secondary',
      text: 'Back'
    },{
      action() {
        $('[aria-controls="adv-search"]').click()
        return this.next();

      },
      text: 'Next'
    }
  ],
  id: 'account-details'
},{
  title: 'Wyszukiwanie zaawansowane',
  text: `Można wyszukiwać tutaj po wielu parametrach, zaznaczajac dla przykladu mieszkanie i Dom będziemy szukać pozycje które maja jednoczesnie zaznaczone mieszkanie i dom - warunek ten jest niewykonalny dlatego przy wyszukiwaniu musimy uważać na to co wprowadzamy`,
  attachTo: {
    element: '.checkbox',
    on: 'top'
  },
  buttons: [
    {
      action() {
        return this.back();
      },
      classes: 'shepherd-button-secondary',
      text: 'Back'
    },
    {
      action() {
        $("#load-more").click()
        return this.next();

      },
      text: 'Next'
    }
  ],
  id: 'account-details'
},{
  title: 'Dużo danych',
  text: `Im bardziej twoja baza klientów będzie rozwijana może się okazać że klientow jest tak dużo że ciężko będzie wrócić do klienta który jest 500 na liście, zasymulujmy to...`,
  attachTo: {
    element: "tbody tr:last-child",
    on: 'top'
  },
  buttons: [
    {
      action() {
        return this.back();
      },
      classes: 'shepherd-button-secondary',
      text: 'Back'
    },
    {
      action() {
        $("#load-more").click()
        return this.next();

      },
      text: 'Next'
    }
  ],
  id: 'account-details'
},{
  title: 'Powrót ',
  text: `Przejdźmy jeszcze niżej...`,
  attachTo: {
    element: "tbody tr:last-child",
    on: 'top'
  },
  buttons: [
    {
      action() {
        return this.back();
      },
      classes: 'shepherd-button-secondary',
      text: 'Back'
    },
    {
      action() {
        $("#load-more").click()
        return this.next();

      },
      text: 'Next'
    }
  ],
  id: 'account-details'
},{
  title: 'Powrót ',
  text: `jak widać, otrzymaliśmy stosowny komunikat że system zapamiętał twoja pozycje na stronie, teraz w kazdym momencie mozezs wrocic do ostatniej pozycji na liscie `,
  attachTo: {
    element: "tbody tr:last-child",
    on: 'top'
  },
  buttons: [
    {
      action() {
        return this.back();
      },
      classes: 'shepherd-button-secondary',
      text: 'Back'
    },
    {
      action() {
        $("#header-toggle").click()
        return this.next();

      },
      text: 'Next'
    }
  ],
  id: 'account-details'
},{
  title: 'Cofanie',
  text: `Klikając w przycisk cofania możemy otworzyc omawianą funkcje cofania `,
  attachTo: {
    element: "[data-site='modal/back-to']",
    on: 'auto'
  },
  buttons: [
    {
      action() {
        return this.back();
      },
      classes: 'shepherd-button-secondary',
      text: 'Back'
    },
    {
      action() {
        return this.next();
      },
      text: 'Next'
    }
  ],
  id: 'account-details'
},{
  title: 'Powrót do zapisanych pozycji',
  text: `jak widać, pojawiły się tutaj pozycje do których możemy wrócić. Teraz po zaznaczeniu strony która nas interesuje i kliknięciu wyślij zostaniemy wróceni do ostatnio przegladanej pozycji (Narazie nie klikaj)`,
  buttons: [
    {
      action() {
        return this.back();
      },
      classes: 'shepherd-button-secondary',
      text: 'Back'
    },
    {
      action() {
        $("#close").click();
        return this.next();
        $("[data-title='Wyszukiwarka']").click();
      },
      text: 'Next'
    }
  ],
  id: 'account-details'
},{
  title: 'Szybkie wyszukiwanie',
  text: `Przed wpisywaniem czegokolwiek wybierz w której bazie chcesz szukać, zaznaczenie pierwszej opcji wymusi wyszukiwanie po całej bazie bez wyjatkow`,

  buttons: [
    {
      action() {
        return this.back();
      },
      classes: 'shepherd-button-secondary',
      text: 'Back'
    },
    {
      action() {
        return this.next();

      },
      text: 'Next'
    }
  ],
  id: 'account-details'
},{
  title: 'szybkie Wyszukiwanie',
  text: `Druga opcja, zarazem szybsza jest szybka wyszukiwarka ktora nie wymaga wchodzenia w odpowiednia podstrone, jest to przycisk <i class="fas fa-search"></i> znajdujacy się po lewej stronie`,

  buttons: [
    {
      action() {
        return this.back();
      },
      classes: 'shepherd-button-secondary',
      text: 'Back'
    },
    {
      action() {
        return this.next();

      },
      text: 'Next'
    }
  ],
  id: 'account-details'
},{
  title: 'Wpisywanie w wyszukiwarce',
  text: `Wyszukiwarka zaczyna wyszukiwać kilka sekund po zaprzestaniu pisania, dlatego jeśli zmienisz parametr wyszukiwań musisz usunac i ponownie wpisac ta sama litere lub wpisac calkowicie coś nowego. Po wyszukaniu możemy kliknac w dany wynik zeby przejsc do podsumowania o nim`,
  attachTo: {
    element: ".modal-body input",
    on: 'top'
  },
  buttons: [
    {
      action() {
        return this.back();
      },
      classes: 'shepherd-button-secondary',
      text: 'Back'
    },
    {
      action() {
        return this.next();

      },
      text: 'Next'
    }
  ],
  id: 'account-details'
}]