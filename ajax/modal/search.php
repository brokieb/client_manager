<div class="alert alert-info" role="alert">
                        Wyszukiwarka przeszukuje wszystkie twoje bazy danych, program jest nastawiony na proste wyszukiwanie więc wpisując słowo "wynajem" wyszukiwarka znajdzie wszystkie spotkania/klientów/nieruchomości w których wybraliśmy opcję "wynajem". Jeżeli chcesz określić dokładnie bazę z której chcesz szukać musisz na nowo wpisać wyszukiwaną opcję żeby odświeżyć wyniki. </br>Po kliknięciu w wyszukany wynik przejdziemy do szczegółów o danej pozycji 
                    </div>
                    <div class='row d-flex p-1'>
                        <input class="form-control" type="search" placeholder="Zacznij wpisywać... " aria-label="Search" autocomplete="off">
                    </div>
                    <span class='row'>
                        <div class="btn-group w-100 p-2" role="group" aria-label="Basic radio toggle button group">
                            <input type="radio" class="btn-check" name="source" id="all-build-client-meet" autocomplete="off" checked="checked">
                            <label class="btn btn-outline-primary" for="all-build-client-meet"><i class="pe-2 fas fa-certificate"></i>Wszystko</label>

                            <input type="radio" class="btn-check" name="source" id="build" autocomplete="off">
                            <label class="btn btn-outline-primary" for="build"><i class="pe-2 fas fa-home"></i>Nieruchomości</label>

                            <input type="radio" class="btn-check" name="source" id="client" autocomplete="off">
                            <label class="btn btn-outline-primary" for="client"><i class="pe-2 fas fa-users"></i>Klienci</label>

                            <input type="radio" class="btn-check" name="source" id="meet" autocomplete="off">
                            <label class="btn btn-outline-primary" for="meet"><i class="pe-2 fas fa-calendar-alt"></i>Spotkania</label>
                        </div>
                        <table class='table table-striped m-0'>
                            <tbody class='search-content'>

                            </tbody>
                        </table>
                    </span>