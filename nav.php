<div class='nav-content'>
    <div class='nav-backdrop d-none'>
    </div>
    <div class="l-navbar shadow-lg"
         id="nav-bar">
        <nav class="nav">
            <div>
                <a href="#"
                   class="nav_logo"> <img src='img/logo.png'></i>
                    <span class="nav_logo-name">Client Manager</span>
                </a>
                <?php
                if ($logged_in) {
                ?>
                <div class='nav_title'>
                    <?php
                        $ans = "Dziś jest ";
                        $weekDay = date('w');
                        switch ($weekDay) {
                            case '0':
                                $ans .= " niedziela";
                                break;
                            case '1':
                                $ans .= " Poniedziałek";
                                break;
                            case '2':
                                $ans .= " wtorek";
                                break;
                            case '3':
                                $ans .= " środa";
                                break;
                            case '4':
                                $ans .= " czwartek";
                                break;
                            case '5':
                                $ans .= " piątek";
                                break;
                            case '6':
                                $ans .= " sobota";
                                break;
                        }
                        $ans .= " " . date('d');
                        $monthName = date('m');
                        switch ($monthName) {
                            case '1':
                                $ans .= " styczeń";
                                break;
                            case '2':
                                $ans .= " luty";
                                break;
                            case '3':
                                $ans .= " marzec";
                                break;
                            case '4':
                                $ans .= " kwiecień";
                                break;
                            case '5':
                                $ans .= " maj";
                                break;
                            case '6':
                                $ans .= " czerwiec";
                                break;
                            case '7':
                                $ans .= " lipiec";
                                break;
                            case '8':
                                $ans .= " sierpień";
                                break;
                            case '9':
                                $ans .= " wrzesień";
                                break;
                            case '10':
                                $ans .= " październik";
                                break;
                            case '11':
                                $ans .= " listopad";
                                break;
                            case '12':
                                $ans .= " grudzień";
                                break;
                        }
                        $ans .= " i ";
                        $sql = "SELECT count(meet_date) as 'today', (SELECT meet_time FROM `meet` WHERE `user_id`={$_SESSION['user']} AND `meet_date`='" . Date("Y-m-d") . "' AND `meet_time`>='" . Date("H:i") . "' ORDER BY `meet_time` ASC LIMIT 1) as 'closest' FROM `meet` WHERE `user_id`={$_SESSION['user']} AND `meet_date`='" . Date("Y-m-d") . "' AND `meet_time`>='" . Date("H:i") . "' ";
                        $select = $conn_data->query($sql);
                        $select->execute();
                        $meet = $select->fetch();
                        switch ($meet['today']) {
                            case 0:
                                $ans .= "na dziś nie masz żadnych spotkań";
                                break;
                            case 1:
                                $ans .= "na dzisiaj masz zaplanowane tylko jedno spotkanie najbliższe o " . $meet['closest'];
                                break;
                            case 2:
                            case 3:
                            case 4:
                                $ans .= "na dzisiaj masz zaplanowane " . $meet['today'] . " spotkania, najblizsze o" . $meet['closest'];
                                break;
                            default:
                                $ans .= "na dzisiaj masz zaplanowane " . $meet['today'] . " spotkań, najblizsze o" . $meet['closest'];
                                break;
                        }
                        echo $ans;
                        ?>
                </div>
                <?php
                }
                ?>
                <div class="nav_list">
                    <?php
                    if ($logged_in) {
                        $det = new accountDetails();
                        $details = $det->accountData();
                        if ($details['group_id'] != null) {
                            $group_det = $det->groupUsers();
                        }
                        //zalogowany
                        $select = $conn_data->query("select * from `preference_user` where `preference_id`=4 AND `user_id`={$_SESSION['user']} ");
                        $select->execute();
                        $row_demo = $select->fetch();
                        if (!empty($row_demo) && $row_demo['preference_value'] == 1) {
                            $demo = "OR `user_id`=1 ";
                        } else {
                            $demo = "";
                        }

                        $select = $conn_data->prepare("select * from `preference_user` where `preference_id`=1 AND `user_id`={$_SESSION['user']} ");
                        $select->execute();
                        if ($select->rowCount() == 0) {
                            $custom_order = "ORDER BY `nav_id` ";
                        } else {
                            $row_order = $select->fetch();
                            $arr_order = explode(" | ", $row_order['preference_value']);
                            $custom_order = "ORDER BY ";
                            $custom_order .= "case `nav_id`";
                            $i = 1;
                            foreach ($arr_order as $b) {
                                $custom_order .= " when '" . $b . "' then " . $i . " ";
                                $i++;
                            }
                            $custom_order .= "else 99 end";
                        }
                        if ($details['privilege'] == '0') {
                            $lock = 'text-danger';
                        } else {
                            $lock = null;
                        }
                        $select = $conn_form->prepare("SELECT * FROM `nav` WHERE `nav_privilege`<=1 AND (`nav_show_force`=1 OR `nav_show_force` IS NULL) AND `nav_position`=1  {$custom_order};");
                    } else {
                        $select = $conn_form->prepare("SELECT * FROM `nav` WHERE `nav_privilege`=0 AND (`nav_show_force`=0 OR `nav_show_force` IS NULL) AND `nav_position`=1 ;");
                    }
                    $select->execute();

                    $allow = 0;
                    if (!isset($_GET['site'])) {
                        $site = "Ładowanie...";
                    } else {
                        $site = "Strona nie istnieje :(";
                    }
                    $i = 0;
                    $first = null;
                    if ($select->rowCount() > 0) {
                        while ($row = $select->fetch()) {
                            if ($first == null && $row['nav_href'] != "#") {
                                $first = $row;
                            }
                            if (!isset($_GET['site'])  && $row['nav_show'] == 1 && $row['nav_href'] != "#") {
                                if (isset($_GET['back'])) {
                    ?>
                    <script>
                    window.location.href = 'index.php?site=<?= $_GET['back'] ?>&content=<?= $_GET['content'] ?>';
                    </script>
                    <?php
                                } else {
                                ?>
                    <script>
                    window.location.href = 'index.php?site=<?= $row['nav_href'] ?>';
                    </script>
                    <?php
                                }
                            }
                            if ($row['nav_show'] == 1) {
                                if (isset($_GET['site'])) {
                                    $site_array = explode("/", $_GET['site']);
                                    if ($site_array[0] == $row['nav_href']) {
                                        $active = 'active';
                                    } else {
                                        $active = '';
                                    }
                                    if ($row['nav_method'] != null) {
                                        $method = "";
                                        $arr = json_decode($row['nav_method']);
                                        foreach ($arr as $key => $value) {
                                            $method .= "data-" . $key . "='" . $value . "' ";
                                        }
                                    } else {
                                        $method = "";
                                    }
                                    if ($row['nav_href'] == '#') {
                                        $href = "#";
                                    } else {
                                        $href = "?site=" . $row['nav_href'];
                                    }
                                    if ($row['nav_hide'] == '1') {
                                        $hide = "d-none";
                                    } else {
                                        $hide = "";
                                    }
                                    $icon = $row['nav_icon'];
                                    $title = $row['nav_title'];
                                    include('view/nav-element.php');
                                }
                            }
                            //rozłożyć element
                            if (isset($_GET['site'])) {
                                if (count($site_array) == 1) {
                                    if ($site_array[0] == $row['nav_href']) {
                                        //strona dozwolona
                                        $allow = 1;
                                        $catalog = $row['nav_catalog'];
                                        $site = $row['nav_title'];
                                    }
                                } else {
                                    $select_group = $conn_form->prepare("SELECT `nav_href`,`nav_title` FROM `nav_group` WHERE `nav_href`= ?  LIMIT 1");
                                    $select_group->execute([$site_array[1]]);
                                    if ($select_group->rowCount() > 0) {
                                        $group_det = $select_group->fetch();
                                        $allow = 1;
                                        $site = $group_det['nav_title'];
                                        $catalog = null;
                                    } else {
                                    }
                                }
                            }
                        }
                        if (!isset($_SESSION['back-to'])) {
                            $dnone = "d-none";
                            ?>
                    <?php
                        }
                    } else {
                    }
                    if ($allow == 0) {
                        if (isset($_GET['content'])) {
                            $back = "back=" . $_GET['site'] . "&content=" . $_GET['content'];
                        ?>
                    <script>
                    window.location.href = 'index.php?site=<?= $first['nav_href'] ?>&<?= $back ?>';
                    </script>
                    <?php
                        }
                    }
                    if ($logged_in) {
                        ?>
                </div>
            </div>
            <div style='display:flex;justify-content:space-between'>
                <?php
                        if (isset($_GET['site'])) {
                            if (count($site_array) == 1) {
                                if ($site_array[0] == 'profile' || $_GET['site'] == 'destroy' || $_GET['site'] == 'archive') {
                                    //strona dozwolona
                                    $active = 'active';
                                    $allow = 1;
                                    switch ($_GET['site']) {
                                        case 'profile':
                                            $site = 'Profil';
                                            break;
                                        case 'archive':
                                            $site = 'Archiwum';
                                            break;
                                        case 'destroy':
                                            $site = 'Wylogowywanie';
                                            break;
                                    }
                                }
                            }

                ?>
                <div>
                    <a href="#"
                       data-group='8'
                       data-bs-toggle="dropdown"
                       class="nav_link <?= $active ?>">
                        <i class="<?= $lock ?> fas fa-cog"></i>
                        <span class="nav_name"><?= $details['username'] ?></span>
                    </a>
                    <ul class="dropdown-menu "
                        aria-labelledby="dropdownMenuLink">
                        <?php
                            $menuGroup = $conn_form->query("SELECT * FROM `nav_group` WHERE `nav_id`=8 AND `nav_show` = 1");
                            while ($rowGroup = $menuGroup->fetch()) {
                            ?>
                        <li><a class="dropdown-item"
                               href="index.php?site=profile/<?= $rowGroup['nav_href'] ?>"><i
                                   class="pe-2 fas <?= $rowGroup['nav_icon'] ?>"></i><?= $rowGroup['nav_title'] ?></a>
                        </li>
                        <?php
                            }
                            if ($_SESSION['user'] == 1) {
                            ?>
                        <li><a class="dropdown-item"
                               href="index.php?site=profile/admin"><i class="pe-2 fas fa-candy-cane"></i>Admin</a></li>
                        <?php
                            }
                            ?>
                        <li><a class="dropdown-item"
                               href="#"
                               data-chat><i class="pe-2 fas fa-comments"></i>Chat z administracją</a></li>
                    </ul>
                </div>
                <span class='d-flex flex-row'>
                    <a href="?site=archive"
                       class="nav_link">
                        <i class="fas fa-archive"></i>
                    </a>
                    <a href="?site=destroy"
                       class="nav_link">
                        <i class="fas fa-power-off ?>"></i>

                    </a>
                </span>



                <?php


                        }
                    }

            ?>

        </nav>
    </div>
    <header class="header d-grid py-2 w-100"
            id="header">
        <div class='d-flex justify-content-between'>


            <span class='col-1'></span>
            <H2 class='col-1 m-0 p-0 text-nowrap d-flex align-self-center justify-content-center'
                id='pageTitle'><?= $site ?></H2>
            <div class="col-1"
                 id="header-toggle">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </header>
</div>