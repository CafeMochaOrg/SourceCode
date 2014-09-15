<?php
ob_start();
///////////wp list table for company/////////
if (!class_exists('WP_List_Table')) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class My_List_Table_company extends WP_List_Table {

    function __construct() {
        global $status, $page;

//Set parent defaults
        parent::__construct(array(
            'singular' => 'movie', //singular name of the listed records
            'plural' => 'movies', //plural name of the listed records
            'ajax' => false        //does this table support ajax?
        ));
    }

//    function usort_reorder($a, $b) {
//        // If no sort, default to title
//        $orderby = (!empty($_GET['orderby']) ) ? $_GET['orderby'] : 'post_title';
//        // If no order, default to asc
//        $order = (!empty($_GET['order']) ) ? $_GET['order'] : 'asc';
//        // Determine sort order
//        $result = strcmp($a[$orderby], $b[$orderby]);
//        // Send final sort direction to usort
//        return ( $order === 'asc' ) ? $result : -$result;
//    }
//    function get_sortable_columns() {
//        $sortable_columns = array(
//            'post_title' => array('post_title', false),
//            'start_date' => array('start_date', false),
//            'isbn' => array('isbn', false)
//        );
//        return $sortable_columns;
//    }

    function get_columns() {
        $columns = array(
//checkbox//
//  'cb' => '<input type="checkbox" />',
//endcheckbox//
            'Branche' => 'Branche',
            'function'=>'Functie',
            'Bedrijfsnaam' => 'Bedrijfsnaam',
            'Straat' => 'Straat',
            'Postcode' => 'Postcode',
            'Woonplaats' => 'Woonplaats',
            'email' => 'E-mail',
            'view_detail' => 'View Details',
            'delete' => 'Delete'
        );
        return $columns;
    }

//manupulate data//
    function column_booktitle($item) {
        $actions = array(
            'edit' => sprintf('<a href="?page=%s&action=%s&book=%s">Edit</a>', $_REQUEST['page'], 'edit', $item['ID']),
            'delete' => sprintf('<a href="?page=%s&action=%s&book=%s">Delete</a>', $_REQUEST['page'], 'delete', $item['ID']),
        );
        return sprintf('%1$s <span style="color:silver">(id:%2$s)</span>%3$s',
                /* $1%s */ $item['title'],
                /* $2%s */ $item['ID'],
                /* $3%s */ $this->row_actions($actions)
        );
    }

//end manupulate//
//Bulk actions//
//    function get_bulk_actions() {
//        $actions = array('deactivate' => 'Deactivate');
//        return $actions;
//    }
//
//    function process_bulk_action() {
//        //Detect when a bulk action is being triggered...
//        if ('active' === $this->current_action()) {
//            $msg = '';
//            $cb = $_GET['movie'];
//            if (count($cb) == 0) {
//                $msg = 'Please select manufacturer to active in the front';
//            } else {
//                foreach ($cb as $val) {
//                    $id = $val;
//                    global $wpdb;
//                    $query = $wpdb->query("UPDATE wp_posts set post_status='publish'  WHERE ID='$id'");
//                }
//                $msg = count($cb) . " Item Successfully Activated";
//            }
//            set_transient('del_msg', $msg, 30);
//            $redirect_url = get_bloginfo('url') . '/wp-admin/admin.php?page=all-manufacturer';
//            wp_redirect($redirect_url);
//            exit;
//        }
//
//        if ('deactivate' === $this->current_action()) {
//            $msg = '';
//            $cb = $_GET['movie'];
//            if (count($cb) == 0) {
//                $msg = 'Please select manufacturer to deactivate in the front';
//            } else {
//                foreach ($cb as $val) {
//                    $id = $val;
//                    global $wpdb;
//                    $query = $wpdb->query("UPDATE wp_posts set post_status='draft'  WHERE ID='$id'");
//                }
//                $msg = count($cb) . " Item Successfully Deactivate";
//            }
//            set_transient('del_msg', $msg, 30);
//            $redirect_url = get_bloginfo('url') . '/wp-admin/admin.php?page=all-manufacturer';
//            wp_redirect($redirect_url);
//            exit;
//        }
//    }
//end bulk//
//checkbox(get_coloms function related->up)//
//    function column_cb($item) {
//        return sprintf(
//                        '<input type="checkbox" name="%1$s[]" value="%2$s" />',
//                        /* $1%s */ $this->_args['singular'], //Let's simply repurpose the table's singular label ("movie")
//                        /* $2%s */ $item['ID']                //The value of the checkbox should be the record's id
//        );
//    }
//endcheckbox//
    function prepare_items($data) {
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);
//$this->process_bulk_action();
//usort($this->items = $data, array(&$this, 'usort_reorder'));
//($this->items=$data, array(&$this, 'get_bulk_actions'));
        $this->items = $data;
//pagenation//
        $per_page = 10;
        $current_page = $this->get_pagenum();
        $total_items = count($this->items = $data);
// only ncessary because we have sample data
        $this->found_data = array_slice($this->items = $data, (($current_page - 1) * $per_page), $per_page);
        $this->set_pagination_args(array(
            'total_items' => $total_items, //WE have to calculate the total number of items
            'per_page' => $per_page //WE have to determine how many items to show on a page
        ));
        $this->items = $this->found_data;
//end pagenation//
    }

    function column_default($item, $column_name) {
        switch ($column_name) {
            case 'function':
            case 'Branche':
            case 'Bedrijfsnaam':
            case 'Straat':
            case 'Postcode':
            case 'Woonplaats':
            case 'email':
            case 'view_detail':
            case 'delete':


                return $item[$column_name];
            default:
                return print_r($item, true); //Show the whole array for troubleshooting purposes
        }
    }

}

function company_info() {
    global $wpdb;
    if ($_GET['id']) {
        $edit_id = $_GET['id'];
        if (isset($_POST['edit_company'])) {
            $date = date('Y-m-d');
            $function = $_POST['function'];
            $Branche = $_POST['branch'];
            $Bedrijfsnaam = $_POST['Bedrijfsnaam'];
            $Straat = $_POST['Straat'];
            $Postcode = $_POST['Postcode'];
            $Woonplaats = $_POST['Woonplaats'];
            $email = $_POST['email'];
            if ($Branche == '')
                $error_message = '';
            else if ($Bedrijfsnaam == '')
                $error_message = '';
            else if (empty($Straat))
                $error_message = '';
            else if (empty($Postcode))
                $error_message = '';
            else if ($Woonplaats == '')
                $error_message = '';
            else if ($email == "")
                $error_message = '';
            if ($error_message != '') {
                set_transient('error_msg', $error_message, 30);
                //set_transient('register', $register);
                wp_redirect(get_bloginfo('url') . '/wp-admin/admin.php?page=company_info');
                exit;
            } else {
                $wpdb->get_results("update wp_company set function='$function',Branche='$Branche', Bedrijfsnaam='$Bedrijfsnaam', Straat='$Straat', Postcode='$Postcode', Woonplaats='$Woonplaats', email='$email', date='$date' where id='$edit_id'");
                $success_message = '';
                set_transient('msg', $success_message, 30);
                wp_redirect(get_bloginfo('url') . '/wp-admin/admin.php?page=company_info');
                exit();
            }
        }
        $m = get_transient('msg');
        $mm = get_transient('error_msg');
        delete_transient('msg');
        delete_transient('error_msg');
        $comp = $wpdb->get_results("select * from wp_company where id='$edit_id'");
        ?>
        <div>
            <h1><strong>Edit Company Details</strong></h1>
            <?php if ($mm) { ?>
                <div class="success_message">
                    <?php
                    echo $mm = get_transient('msgs');

                    delete_transient('msgs');
                    ?></div>
            <?php }if ($mm1) { ?>
                <div class="error_message">
                    <?php
                    echo $mm1 = get_transient('error_msgs');

                    delete_transient('error_msgs');
                    ?></div>
            <?php } ?>
            <form method="post">
                <table class="widefat" cellspacing="0" style="width:450px;">
                    <tr>
                        <th>Functie</th>
                        <th>:</th>
                        <th><select name="function" id="function">
                                <option value="">Selecteer Functie</option>
                                <option <?php if ($comp[0]->function == 'Accountmanager') {
            echo 'selected="selected"';
        } ?> value="Accountmanager"> Accountmanager </option>
                                <option <?php if ($comp[0]->function == 'Administrateur') {
            echo 'selected="selected"';
        } ?> value="Administrateur"> Administrateur </option>
                                <option <?php if ($comp[0]->function == 'Applicatiebeheerder') {
            echo 'selected="selected"';
        } ?> value="Applicatiebeheerder"> Applicatiebeheerder </option>
                                <option <?php if ($comp[0]->function == 'Assistent Accountant') {
            echo 'selected="selected"';
        } ?> value="Assistent Accountant"> Assistent Accountant </option>
                                <option <?php if ($comp[0]->function == 'Assistent Controller') {
            echo 'selected="selected"';
        } ?> value="Assistent Controller"> Assistent Controller </option>
                                <option <?php if ($comp[0]->function == 'Assistent Filiaalmanager') {
            echo 'selected="selected"';
        } ?> value="Assistent Filiaalmanager"> Assistent Filiaalmanager </option>
                                <option <?php if ($comp[0]->function == 'Belastingadviseur') {
            echo 'selected="selected"';
        } ?> value="Belastingadviseur"> Belastingadviseur </option>
                                <option <?php if ($comp[0]->function == 'Besturingstechnicus') {
            echo 'selected="selected"';
        } ?> value="Besturingstechnicus"> Besturingstechnicus </option>
                                <option <?php if ($comp[0]->function == 'Business Analist') {
            echo 'selected="selected"';
        } ?> value="Business Analist"> Business Analist </option>
                                <option <?php if ($comp[0]->function == 'Business Controller') {
            echo 'selected="selected"';
        } ?> value="Business Controller"> Business Controller </option>
                                <option <?php if ($comp[0]->function == 'Callcenter Medewerker') {
            echo 'selected="selected"';
        } ?> value="Callcenter Medewerker"> Callcenter Medewerker </option>
                                <option <?php if ($comp[0]->function == 'Commercieel Medewerker Binnendienst') {
            echo 'selected="selected"';
        } ?> value="Commercieel Medewerker Binnendienst"> Commercieel Medewerker Binnendienst </option>
                                <option <?php if ($comp[0]->function == 'Controleleider') {
            echo 'selected="selected"';
        } ?> value="Controleleider"> Controleleider </option>
                                <option <?php if ($comp[0]->function == 'Controller') {
            echo 'selected="selected"';
        } ?> value="Controller"> Controller </option>
                                <option <?php if ($comp[0]->function == 'Constructeur') {
            echo 'selected="selected"';
        } ?> value="Constructeur"> Constructeur </option>
                                <option <?php if ($comp[0]->function == 'Drogisterijmanager') {
            echo 'selected="selected"';
        } ?> value="Drogisterijmanager"> Drogisterijmanager </option>
                                <option <?php if ($comp[0]->function == 'Electrical Engineer') {
            echo 'selected="selected"';
        } ?> value="Electrical Engineer"> Electrical Engineer </option>
                                <option <?php if ($comp[0]->function == 'Embedded Software Engineer') {
            echo 'selected="selected"';
        } ?> value="Embedded Software Engineer"> Embedded Software Engineer </option>
                                <option <?php if ($comp[0]->function == 'Ervaren Magazijnmedewerker') {
            echo 'selected="selected"';
        } ?> value="Ervaren Magazijnmedewerker"> Ervaren Magazijnmedewerker </option>
                                <option <?php if ($comp[0]->function == 'Field Service Engineer') {
            echo 'selected="selected"';
        } ?> value="Field Service Engineer"> Field Service Engineer </option>
                                <option <?php if ($comp[0]->function == 'Financial Accountant') {
            echo 'selected="selected"';
        } ?> value="Financial Accountant"> Financial Accountant </option>
                                <option <?php if ($comp[0]->function == 'Financial Controller') {
            echo 'selected="selected"';
        } ?> value="Financial Controller"> Financial Controller </option>
                                <option <?php if ($comp[0]->function == 'Grafische Vormgever') {
            echo 'selected="selected"';
        } ?> value="Grafische Vormgever"> Grafische Vormgever </option>
                                <option <?php if ($comp[0]->function == 'Hardware Engineer') {
            echo 'selected="selected"';
        } ?> value="Hardware Engineer"> Hardware Engineer </option>
                                <option <?php if ($comp[0]->function == 'Inkoper') {
            echo 'selected="selected"';
        } ?> value="Inkoper"> Inkoper </option>
                                <option <?php if ($comp[0]->function == 'Inkoop Assistent') {
            echo 'selected="selected"';
        } ?> value="Inkoop Assistent"> Inkoop Assistent </option>
                                <option <?php if ($comp[0]->function == 'Inkoop Manager') {
            echo 'selected="selected"';
        } ?> value="Inkoop Manager"> Inkoop Manager </option>
                                <option <?php if ($comp[0]->function == 'Junior International Sales Manager') {
            echo 'selected="selected"';
        } ?> value="Junior International Sales Manager"> Junior International Sales Manager </option>
                                <option <?php if ($comp[0]->function == 'Kassamedewerker') {
            echo 'selected="selected"';
        } ?> value="Kassamedewerker"> Kassamedewerker </option>
                                <option <?php if ($comp[0]->function == 'Lead Engineer') {
            echo 'selected="selected"';
        } ?> value="Lead Engineer"> Lead Engineer </option>
                                <option <?php if ($comp[0]->function == 'Maintenance Engineer') {
            echo 'selected="selected"';
        } ?> value="Maintenance Engineer"> Maintenance Engineer </option>
                                <option <?php if ($comp[0]->function == 'Manager Operations') {
            echo 'selected="selected"';
        } ?> value="Manager Operations"> Manager Operations </option>
                                <option <?php if ($comp[0]->function == 'Manufacturing Engineer') {
            echo 'selected="selected"';
        } ?> value="Manufacturing Engineer"> Manufacturing Engineer </option>
                                <option <?php if ($comp[0]->function == 'Marketing Manager') {
            echo 'selected="selected"';
        } ?> value="Marketing Manager"> Marketing Manager </option>
                                <option <?php if ($comp[0]->function == 'Mechanical Engineer') {
            echo 'selected="selected"';
        } ?> value="Mechanical Engineer"> Mechanical Engineer </option>
                                <option <?php if ($comp[0]->function == 'Medewerker Klantenservice') {
            echo 'selected="selected"';
        } ?> value="Medewerker Klantenservice"> Medewerker Klantenservice </option>
                                <option <?php if ($comp[0]->function == 'Medewerker Kwaliteitsdienst') {
            echo 'selected="selected"';
        } ?> value="Medewerker Kwaliteitsdienst"> Medewerker Kwaliteitsdienst </option>
                                <option <?php if ($comp[0]->function == 'Medewerker Technische Dienst') {
            echo 'selected="selected"';
        } ?> value="Medewerker Technische Dienst"> Medewerker Technische Dienst </option>
                                <option <?php if ($comp[0]->function == 'Meewerkend Voorman') {
            echo 'selected="selected"';
        } ?> value="Meewerkend Voorman"> Meewerkend Voorman </option>
                                <option <?php if ($comp[0]->function == 'Online Marketeer') {
            echo 'selected="selected"';
        } ?> value="Online Marketeer"> Online Marketeer </option>
                                <option <?php if ($comp[0]->function == 'Online Marketing Manager') {
            echo 'selected="selected"';
        } ?> value="Online Marketing Manager"> Online Marketing Manager </option>
                                <option <?php if ($comp[0]->function == 'Process Engineer') {
            echo 'selected="selected"';
        } ?> value="Process Engineer"> Process Engineer </option>
                                <option <?php if ($comp[0]->function == 'Product Manager') {
            echo 'selected="selected"';
        } ?> value="Product Manager"> Product Manager </option>
                                <option <?php if ($comp[0]->function == 'Project Controller') {
            echo 'selected="selected"';
        } ?> value="Project Controller"> Project Controller </option>
                                <option <?php if ($comp[0]->function == 'Project Engineer') {
            echo 'selected="selected"';
        } ?> value="Project Engineer"> Project Engineer </option>
                                <option <?php if ($comp[0]->function == 'Project Manager') {
            echo 'selected="selected"';
        } ?> value="Project Manager"> Project Manager </option>
                                <option <?php if ($comp[0]->function == 'Projectleider') {
            echo 'selected="selected"';
        } ?> value="Projectleider"> Projectleider </option>
                                <option <?php if ($comp[0]->function == 'Projectmanager') {
            echo 'selected="selected"';
        } ?> value="Projectmanager"> Projectmanager </option>
                                <option <?php if ($comp[0]->function == 'Quality Engineer') {
            echo 'selected="selected"';
        } ?> value="Quality Engineer"> Quality Engineer </option>
                                <option <?php if ($comp[0]->function == 'Reachtruckchauffeur') {
            echo 'selected="selected"';
        } ?> value="Reachtruckchauffeur"> Reachtruckchauffeur </option>
                                <option <?php if ($comp[0]->function == 'Recruitment Consultant') {
            echo 'selected="selected"';
        } ?> value="Recruitment Consultant"> Recruitment Consultant </option>
                                <option <?php if ($comp[0]->function == 'Salarisadministrateur') {
            echo 'selected="selected"';
        } ?> value="Salarisadministrateur"> Salarisadministrateur </option>
                                <option <?php if ($comp[0]->function == 'Sales Engineer') {
            echo 'selected="selected"';
        } ?> value="Sales Engineer"> Sales Engineer </option>
                                <option <?php if ($comp[0]->function == 'Sales Manager') {
            echo 'selected="selected"';
        } ?> value="Sales Manager"> Sales Manager </option>
                                <option <?php if ($comp[0]->function == 'Sales Representative') {
            echo 'selected="selected"';
        } ?> value="Sales Representative"> Sales Representative </option>
                                <option <?php if ($comp[0]->function == 'Secretaresse') {
            echo 'selected="selected"';
        } ?> value="Secretaresse"> Secretaresse </option>
                                <option <?php if ($comp[0]->function == 'Senior Software Engineer') {
            echo 'selected="selected"';
        } ?> value="Senior Software Engineer"> Senior Software Engineer </option>
                                <option <?php if ($comp[0]->function == 'Senior Systeembeheerder') {
            echo 'selected="selected"';
        } ?> value="Senior Systeembeheerder"> Senior Systeembeheerder </option>
                                <option <?php if ($comp[0]->function == 'Service Coördinator') {
            echo 'selected="selected"';
        } ?> value="Service Coördinator"> Service Coordinator </option>
                                <option <?php if ($comp[0]->function == 'Service Monteur') {
            echo 'selected="selected"';
        } ?> value="Service Monteur"> Service Monteur </option>
                                <option <?php if ($comp[0]->function == 'Software Engineer') {
            echo 'selected="selected"';
        } ?> value="Software Engineer"> Software Engineer </option>
                                <option <?php if ($comp[0]->function == 'Systeembeheerder') {
            echo 'selected="selected"';
        } ?> value="Systeembeheerder"> Systeembeheerder </option>
                                <option <?php if ($comp[0]->function == 'Test Engineer') {
            echo 'selected="selected"';
        } ?> value="Test Engineer"> Test Engineer </option>
                                <option <?php if ($comp[0]->function == 'Technisch Tekenaar') {
            echo 'selected="selected"';
        } ?> value="Technisch Tekenaar"> Technisch Tekenaar </option>
                                <option <?php if ($comp[0]->function == 'Verkoopmedewerker') {
            echo 'selected="selected"';
        } ?> value="Verkoopmedewerker"> Verkoopmedewerker </option>
                                <option <?php if ($comp[0]->function == 'Verpleegkundige Thuiszorg') {
            echo 'selected="selected"';
        } ?> value="Verpleegkundige Thuiszorg"> Verpleegkundige Thuiszorg </option>
                                <option <?php if ($comp[0]->function == 'Verzorgende') {
            echo 'selected="selected"';
        } ?> value="Verzorgende"> Verzorgende </option>
                                <option <?php if ($comp[0]->function == 'Vulploegmedewerker') {
            echo 'selected="selected"';
        } ?> value="Vulploegmedewerker"> Vulploegmedewerker </option>
                                <option <?php if ($comp[0]->function == 'Werkvoorbereider') {
            echo 'selected="selected"';
        } ?> value="Werkvoorbereider"> Werkvoorbereider </option>
                                <option <?php if ($comp[0]->function == 'Winkelmedewerker') {
            echo 'selected="selected"';
        } ?> value="Winkelmedewerker"> Winkelmedewerker </option>
                            </select></th>
                    </tr>
                    <tr>
                        <th>Branche</th>
                        <th>:</th>
                        <th><select name="branch" id="branch">
                                <option value="">Selecteer Branche</option>
                                <option <?php if ($comp[0]->Branche == 'Accountancy/Belastingadvies') {
            echo 'selected="selected"';
        } ?> value="Accountancy/Belastingadvies">Accountancy/Belastingadvies</option>
                                <option <?php if ($comp[0]->Branche == 'Agrarisch') {
            echo 'selected="selected"';
        } ?> value="Agrarisch">Agrarisch</option>
                                <option <?php if ($comp[0]->Branche == 'Automatisering/IT') {
            echo 'selected="selected"';
        } ?> value="Automatisering/IT">Automatisering/IT</option>
                                <option <?php if ($comp[0]->Branche == 'Automotive') {
            echo 'selected="selected"';
        } ?> value="Automotive">Automotive</option>
                                <option <?php if ($comp[0]->Branche == 'Banken/financiele diensten') {
            echo 'selected="selected"';
        } ?> value="Banken/financiele diensten">Banken/financiele diensten</option>
                                <option <?php if ($comp[0]->Branche == 'Bouw/installatie/vastgoed') {
            echo 'selected="selected"';
        } ?> value="Bouw/installatie/vastgoed">Bouw/installatie/vastgoed</option>
                                <option <?php if ($comp[0]->Branche == 'Chemische producten') {
            echo 'selected="selected"';
        } ?> value="Chemische producten">Chemische producten</option>
                                <option <?php if ($comp[0]->Branche == 'Detailhandel/winkel') {
            echo 'selected="selected"';
        } ?> value="Detailhandel/winkel">Detailhandel/winkel</option>
                                <option <?php if ($comp[0]->Branche == 'Drukkerijen / Uitgeverijen') {
            echo 'selected="selected"';
        } ?> value="Drukkerijen / Uitgeverijen">Drukkerijen / Uitgeverijen</option>
                                <option <?php if ($comp[0]->Branche == 'Facilitaire dienstverlening') {
            echo 'selected="selected"';
        } ?> value="Facilitaire dienstverlening">Facilitaire dienstverlening</option>
                                <option <?php if ($comp[0]->Branche == 'Farmacie') {
            echo 'selected="selected"';
        } ?> value="Farmacie">Farmacie</option>
                                <option <?php if ($comp[0]->Branche == 'Gezondheids/welzijn sector') {
            echo 'selected="selected"';
        } ?> value="Gezondheids/welzijn sector">Gezondheids/welzijn sector</option>
                                <option <?php if ($comp[0]->Branche == 'Handel/groothandel') {
            echo 'selected="selected"';
        } ?> value="Handel/groothandel">Handel/groothandel</option>
                                <option <?php if ($comp[0]->Branche == 'Horeca/recreatie/reizen') {
            echo 'selected="selected"';
        } ?> value="Horeca/recreatie/reizen">Horeca/recreatie/reizen</option>
                                <option <?php if ($comp[0]->Branche == 'Juridisch') {
            echo 'selected="selected"';
        } ?> value="Juridisch">Juridisch</option>
                                <option <?php if ($comp[0]->Branche == 'Makelaardij / Vastgoed') {
            echo 'selected="selected"';
        } ?> value="Makelaardij / Vastgoed">Makelaardij / Vastgoed</option>
                                <option <?php if ($comp[0]->Branche == 'Maritiem') {
            echo 'selected="selected"';
        } ?> value="Maritiem">Maritiem</option>
                                <option <?php if ($comp[0]->Branche == 'Onderwijs/opleiding') {
            echo 'selected="selected"';
        } ?> value="Onderwijs/opleiding">Onderwijs/opleiding</option>
                                <option <?php if ($comp[0]->Branche == 'Overheid/non-profit') {
            echo 'selected="selected"';
        } ?> value="Overheid/non-profit">Overheid/non-profit</option>
                                <option <?php if ($comp[0]->Branche == 'Reclame/communicatie/media') {
            echo 'selected="selected"';
        } ?> value="Reclame/communicatie/media">Reclame/communicatie/media</option>
                                <option <?php if ($comp[0]->Branche == 'Techniek/industrie') {
            echo 'selected="selected"';
        } ?> value="Techniek/industrie">Techniek/industrie</option>
                                <option <?php if ($comp[0]->Branche == 'Telecommunicatie') {
            echo 'selected="selected"';
        } ?> value="Telecommunicatie">Telecommunicatie</option>
                                <option <?php if ($comp[0]->Branche == 'Transport/logistiek/luchtvaart') {
            echo 'selected="selected"';
        } ?> value="Transport/logistiek/luchtvaart">Transport/logistiek/luchtvaart</option>
                                <option <?php if ($comp[0]->Branche == 'Uitzend/detachering/W&S') {
            echo 'selected="selected"';
        } ?> value="Uitzend/detachering/W&S">Uitzend/detachering/W&S</option>
                                <option <?php if ($comp[0]->Branche == 'Verhuur') {
            echo 'selected="selected"';
        } ?> value="Verhuur">Verhuur</option>
                                <option <?php if ($comp[0]->Branche == 'Verzekeringen/assurantiën') {
            echo 'selected="selected"';
        } ?> value="Verzekeringen/assurantiën">Verzekeringen/assurantien</option>
                                <option <?php if ($comp[0]->Branche == 'Zakelijke dienstverlening') {
            echo 'selected="selected"';
        } ?> value="Zakelijke dienstverlening">Zakelijke dienstverlening</option>
                            </select></th>
                    </tr>
                    <tr>
                        <th>Bedrijfsnaam</th>
                        <th>:</th>
                        <th><input type="text" name="Bedrijfsnaam" value="<?php echo $comp[0]->Bedrijfsnaam; ?>" required="required"/></th>
                    </tr>
                    <tr>
                        <th>Straat</th>
                        <th>:</th>
                        <th><input type="text" name="Straat" value="<?php echo $comp[0]->Straat; ?>" required="required"/></th>
                    </tr>
                    <tr>
                        <th>Postcode</th>
                        <th>:</th>
                        <th><input type="text" name="Postcode" value="<?php echo $comp[0]->Postcode; ?>" required="required"/></th>
                    </tr>
                    <tr>
                        <th>Woonplaats</th>
                        <th>:</th>
                        <th><input type="text" name="Woonplaats" value="<?php echo $comp[0]->Woonplaats; ?>" required="required" /></th>
                    </tr>
                    <tr>
                        <th>E-mail</th>
                        <th>:</th>
                        <th><input type="text" name="email" value="<?php echo $comp[0]->email; ?>" required="required"/></th>
                    </tr>
                    <tr>
                        <th><input type="submit" value="save" name="edit_company" /></th>
                    </tr>
                </table>
            </form>
        </div> 
        <?php
    } else {
        if (isset($_POST['add_company'])) {
            $date = date('Y-m-d');
            $function = $_POST['function'];
            $Branche = $_POST['branch'];
            $Bedrijfsnaam = $_POST['Bedrijfsnaam'];
            $Straat = $_POST['Straat'];
            $Postcode = $_POST['Postcode'];
            $Woonplaats = $_POST['Woonplaats'];
            $email = $_POST['email'];
            if ($function == '')
                $error_message = '';
            else if ($Branche == '')
                $error_message = '';
            else if ($Bedrijfsnaam == '')
                $error_message = '';
            else if (empty($Straat))
                $error_message = '';
            else if (empty($Postcode))
                $error_message = '';
            else if ($Woonplaats == '')
                $error_message = '';
            else if ($email == "")
                $error_message = '';
            if ($error_message != '') {
                set_transient('error_msg', $error_message, 30);
                //set_transient('register', $register);
                wp_redirect(get_bloginfo('url') . '/wp-admin/admin.php?page=company_info');
                exit;
            } else {
                $in_data = array('function' => $function, 'Branche' => $Branche, 'Bedrijfsnaam' => $Bedrijfsnaam, 'Straat' => $Straat, 'Postcode' => $Postcode, 'Woonplaats' => $Woonplaats, 'email' => $email, 'status' => '0', 'date' => $date);
                $wpdb->insert('wp_company', $in_data);
                $success_message = '';
                set_transient('msg', $success_message, 30);
                wp_redirect(get_bloginfo('url') . '/wp-admin/admin.php?page=company_info');
                exit();
            }
        }
        $m = get_transient('msg');
        $mm = get_transient('error_msg');
        delete_transient('msg');
        delete_transient('error_msg');
        ?>
        <div>
            <h1><strong>Add Company</strong></h1>
        <?php if ($mm) { ?>
                <div class="success_message">
            <?php
            echo $mm = get_transient('msgs');

            delete_transient('msgs');
            ?></div>
        <?php }if ($mm1) { ?>
                <div class="error_message">
            <?php
            echo $mm1 = get_transient('error_msgs');

            delete_transient('error_msgs');
            ?></div>
        <?php } ?>
            <form method="post">
                <table class="widefat" cellspacing="0" style="width:450px;">
                    <tr>
                        <th>Functie</th>
                        <th>:</th>
                        <th><select name="function" id="function">
                                <option value="">Selecteer Functie</option>
                                <option value="Accountmanager"> Accountmanager </option>
                                <option value="Administrateur"> Administrateur </option>
                                <option value="Applicatiebeheerder"> Applicatiebeheerder </option>
                                <option value="Assistent Accountant"> Assistent Accountant </option>
                                <option value="Assistent Controller"> Assistent Controller </option>
                                <option value="Assistent Filiaalmanager"> Assistent Filiaalmanager </option>
                                <option value="Belastingadviseur"> Belastingadviseur </option>
                                <option value="Besturingstechnicus"> Besturingstechnicus </option>
                                <option value="Business Analist"> Business Analist </option>
                                <option value="Business Controller"> Business Controller </option>
                                <option value="Callcenter Medewerker"> Callcenter Medewerker </option>
                                <option value="Commercieel Medewerker Binnendienst"> Commercieel Medewerker Binnendienst </option>
                                <option value="Controleleider"> Controleleider </option>
                                <option value="Controller"> Controller </option>
                                <option value="Constructeur"> Constructeur </option>
                                <option value="Drogisterijmanager"> Drogisterijmanager </option>
                                <option value="Electrical Engineer"> Electrical Engineer </option>
                                <option value="Embedded Software Engineer"> Embedded Software Engineer </option>
                                <option value="Ervaren Magazijnmedewerker"> Ervaren Magazijnmedewerker </option>
                                <option value="Field Service Engineer"> Field Service Engineer </option>
                                <option value="Financial Accountant"> Financial Accountant </option>
                                <option value="Financial Controller"> Financial Controller </option>
                                <option value="Grafische Vormgever"> Grafische Vormgever </option>
                                <option value="Hardware Engineer"> Hardware Engineer </option>
                                <option value="Inkoper"> Inkoper </option>
                                <option value="Inkoop Assistent"> Inkoop Assistent </option>
                                <option value="Inkoop Manager"> Inkoop Manager </option>
                                <option value="Junior International Sales Manager"> Junior International Sales Manager </option>
                                <option value="Kassamedewerker"> Kassamedewerker </option>
                                <option value="Kassamedewerker"> Kassamedewerker </option>
                                <option value="Lead Engineer"> Lead Engineer </option>
                                <option value="Maintenance Engineer"> Maintenance Engineer </option>
                                <option value="Manager Operations"> Manager Operations </option>
                                <option value="Manufacturing Engineer"> Manufacturing Engineer </option>
                                <option value="Marketing Manager"> Marketing Manager </option>
                                <option value="Mechanical Engineer"> Mechanical Engineer </option>
                                <option value="Medewerker Klantenservice"> Medewerker Klantenservice </option>
                                <option value="Medewerker Kwaliteitsdienst"> Medewerker Kwaliteitsdienst </option>
                                <option value="Medewerker Technische Dienst"> Medewerker Technische Dienst </option>
                                <option value="Meewerkend Voorman"> Meewerkend Voorman </option>
                                <option value="Online Marketeer"> Online Marketeer </option>
                                <option value="Online Marketing Manager"> Online Marketing Manager </option>
                                <option value="Process Engineer"> Process Engineer </option>
                                <option value="Product Manager"> Product Manager </option>
                                <option value="Project Controller"> Project Controller </option>
                                <option value="Project Engineer"> Project Engineer </option>
                                <option value="Project Manager"> Project Manager </option>
                                <option value="Projectleider"> Projectleider </option>
                                <option value="Projectmanager"> Projectmanager </option>
                                <option value="Quality Engineer"> Quality Engineer </option>
                                <option value="Reachtruckchauffeur"> Reachtruckchauffeur </option>
                                <option value="Recruitment Consultant"> Recruitment Consultant </option>
                                <option value="Salarisadministrateur"> Salarisadministrateur </option>
                                <option value="Sales Engineer"> Sales Engineer </option>
                                <option value="Sales Manager"> Sales Manager </option>
                                <option value="Sales Representative"> Sales Representative </option>
                                <option value="Secretaresse"> Secretaresse </option>
                                <option value="Senior Software Engineer"> Senior Software Engineer </option>
                                <option value="Senior Systeembeheerder"> Senior Systeembeheerder </option>
                                <option value="Service Coördinator"> Service Coordinator </option>
                                <option value="Service Monteur"> Service Monteur </option>
                                <option value="Software Engineer"> Software Engineer </option>
                                <option value="Software Engineer"> Software Engineer </option>
                                <option value="Systeembeheerder"> Systeembeheerder </option>
                                <option value="Test Engineer"> Test Engineer </option>
                                <option value="Technisch Tekenaar"> Technisch Tekenaar </option>
                                <option value="Verkoopmedewerker"> Verkoopmedewerker </option>
                                <option value="Verpleegkundige Thuiszorg"> Verpleegkundige Thuiszorg </option>
                                <option value="Verzorgende"> Verzorgende </option>
                                <option value="Vulploegmedewerker"> Vulploegmedewerker </option>
                                <option value="Vulploegmedewerker"> Vulploegmedewerker </option>
                                <option value="Werkvoorbereider"> Werkvoorbereider </option>
                                <option value="Winkelmedewerker"> Winkelmedewerker </option>
                            </select></th>
                    </tr>
                    <tr>
                        <th>Branche</th>
                        <th>:</th>
                        <th><select name="branch" id="branch">
                                <option value="">Selecteer Branche</option>
                                <option value="Accountancy/Belastingadvies">Accountancy/Belastingadvies</option>
                                <option value="Agrarisch">Agrarisch</option>
                                <option value="Automatisering/IT">Automatisering/IT</option>
                                <option value="Automotive">Automotive</option>
                                <option value="Banken/financiele diensten">Banken/financiele diensten</option>
                                <option value="Bouw/installatie/vastgoed">Bouw/installatie/vastgoed</option>
                                <option value="Chemische producten">Chemische producten</option>
                                <option value="Detailhandel/winkel">Detailhandel/winkel</option>
                                <option value="Drukkerijen / Uitgeverijen">Drukkerijen / Uitgeverijen</option>
                                <option value="Facilitaire dienstverlening">Facilitaire dienstverlening</option>
                                <option value="Farmacie">Farmacie</option>
                                <option value="Gezondheids/welzijn sector">Gezondheids/welzijn sector</option>
                                <option value="Handel/groothandel">Handel/groothandel</option>
                                <option value="Horeca/recreatie/reizen">Horeca/recreatie/reizen</option>
                                <option value="Juridisch">Juridisch</option>
                                <option value="Makelaardij / Vastgoed">Makelaardij / Vastgoed</option>
                                <option value="Maritiem">Maritiem</option>
                                <option value="Onderwijs/opleiding">Onderwijs/opleiding</option>
                                <option value="Overheid/non-profit">Overheid/non-profit</option>
                                <option value="Reclame/communicatie/media">Reclame/communicatie/media</option>
                                <option value="Techniek/industrie">Techniek/industrie</option>
                                <option value="Telecommunicatie">Telecommunicatie</option>
                                <option value="Transport/logistiek/luchtvaart">Transport/logistiek/luchtvaart</option>
                                <option value="Uitzend/detachering/W&S">Uitzend/detachering/W&S</option>
                                <option value="Verhuur">Verhuur</option>
                                <option value="Verzekeringen/assurantiën">Verzekeringen/assurantien</option>
                                <option value="Zakelijke dienstverlening">Zakelijke dienstverlening</option>
                            </select></th>
                    </tr>
                    <tr>
                        <th>Bedrijfsnaam</th>
                        <th>:</th>
                        <th><input type="text" name="Bedrijfsnaam" value="" required="required"/></th>
                    </tr>
                    <tr>
                        <th>Straat</th>
                        <th>:</th>
                        <th><input type="text" name="Straat" value="" required="required"/></th>
                    </tr>
                    <tr>
                        <th>Postcode</th>
                        <th>:</th>
                        <th><input type="text" name="Postcode" value="" required="required"/></th>
                    </tr>
                    <tr>
                        <th>Woonplaats</th>
                        <th>:</th>
                        <th><input type="text" name="Woonplaats" value="" required="required" /></th>
                    </tr>
                    <tr>
                        <th>E-mail</th>
                        <th>:</th>
                        <th><input type="text" name="email" value="" required="required"/></th>
                    </tr>
                    <tr>
                        <th><input type="submit" value="save" name="add_company" /></th>
                    </tr>
                </table>
            </form>
        </div> 
        <?php
        if ($_GET['delete_id']) {
            $delete_id = $_GET['delete_id'];
            $wpdb->get_results("delete From wp_company where id='$delete_id'");
            wp_redirect(get_bloginfo('url') . '/wp-admin/admin.php?page=company_info');
            exit();
        }
        $company = $wpdb->get_results("select * from wp_company");
        $data_arr = array();
        foreach ($company as $key => $val) {
            $id = $val->id;
            $Branche = $val->Branche;
            $function = $val->function;
            $Bedrijfsnaam = $val->Bedrijfsnaam;
            $Straat = $val->Straat;
            $Postcode = $val->Postcode;
            $Woonplaats = $val->Woonplaats;
            $email = $val->email;
            $status = $val->status;
            $date = $val->date;
            $data = array('ID' => $id, 'function' => $function, 'Branche' => $Branche, 'Bedrijfsnaam' => $Bedrijfsnaam, 'Straat' => $Straat, 'Postcode' => $Postcode, 'Woonplaats' => $Woonplaats, 'email' => $email, 'view_detail' => '<a href=' . get_bloginfo('url') . '/wp-admin/admin.php?page=company_info&id=' . $val->id . '>view details</a>', 'delete' => '<a href=' . get_bloginfo('url') . '/wp-admin/admin.php?page=company_info&delete_id=' . $val->id . '>delete</a>');
            $data_arr[$key] = $data;
        }

        $myListTable = new My_List_Table_company();
        echo '<h1><strong>List Of Company</strong></h1>';
        if (isset($_GET['export'])) {
            csv_d();
        }
        $myListTable->prepare_items($data_arr);
//$myListTable->search_box('search', 'search_id');
        ?>
        <?php
        echo $export_msg = get_transient('exp_msg');
        delete_transient('exp_msg');
        ?>
        <!-- Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions -->
        <?php
        echo $msg = get_transient('del_msg');
        delete_transient('del_msg');
//wp_redirect(get_permalink());
        ?>
        <form id="movies-filter" method="get">
            <!-- For plugins, we also need to ensure that the form posts back to our current page -->
            <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
        <?php $myListTable->display(); ?>
        </form>
        <?php
        //echo '</div>';
    }
}
?>