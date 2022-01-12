<div class="modal fade" id="myModalx0" tabindex="-1" aria-labelledby="tytul" aria-hidden="true">
    <form method='POST' class="modal-dialog modal-dialog-centered" action='index.php?site=<?= $_GET['site'] ?>'>
        <div class="modal-content">
            <div class="modal-header">
                <div class='d-flex flex-column'>

                    <h5 class="modal-title" id="tytul">Ładowanie... </h5>
                    <h6 class="modal-agent" id="tytul"></h5>
                </div>
                <span>
                    <!-- <button type="button" class='btn btn-outline' aria-label="toggleWindow" ><i class="far fa-window-restore"></i></button> -->
                    <button type="button" class='btn btn-outline' data-bs-dismiss="modal"><i class="fas fa-times"></i></button>
                </span>
            </div>
            <div class="modal-body">
                <div class='content' data-group='0'>
                    <div class="spinner-border text-light" role="status">
                        <div class='d-flex justify-content-center'>
                            <span class='visually-hidden'>Loading...</span>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer d-flex justify-content-between">
                <div class="btn-group content-actions" style="display:none" >
                    <button type="button" class="btn btn-danger dropdown-toggle " data-bs-toggle="dropdown" aria-expanded="false">
                        Akcje
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" data-toggle="modal" data-modal-type='confirmation' data-modal='11' data-content='success' data-title="Potwierdź działanie" data-page="null" data-id="null" href="#">Zakończone pozytywnie, archiwizuj</a>
                        </li>
                        <li>
                            <a class="dropdown-item" data-toggle="modal" data-modal-type='confirmation' data-modal='11' data-content='loss' data-title="Potwierdź działanie" data-page="null" data-id="null" href="#">nie udało się, archiwizuj</a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item" data-toggle="modal" data-modal-type='confirmation' data-modal='11' data-content='remove' data-title="Potwierdź działanie" data-page="null" data-id="null" href="#">Usuń bezpowrotnie</a>
                        </li>
                    </ul>
                </div>
                <button style="display:none" id='print' type="button" class="btn btn-success print"><i class="fas fa-print px-2"></i>Drukuj</button>
                <a style="display:none" id='call' href='' class='btn btn-info call-client'><i class="fas fa-phone px-2"></i>Zadzwoń do klienta</a>
                <button style="display:none" id='send' type="submit" class="btn btn-success"><i class="fas fa-paper-plane px-2"></i>Wyślij</button>
                <button style="display:none" id='save' type="submit" class="btn btn-success"><i class="fas fa-save px-2"></i>Zapisz</button>
                <button style="display:none" id='prevent' type="button" class="btn btn-success" data-bs-dismiss="modal"><i class="fas fa-check px-2"></i>Akceptuj</button>
                <div id='confirm' style="display:none;width:100%;">
                    <div class='d-flex justify-content-around'> 
                        <button type="button" class='btn btn-danger' data-bs-dismiss="modal"><i class="fas pe-2 fa-times"></i>Nie</button>
                        <button type="submit" class='btn btn-success'><i class="fas pe-2 fa-check"></i>Tak</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>