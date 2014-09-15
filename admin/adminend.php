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
            'cat_name' => 'Category Name',
            'cat_desc' => 'Description',
            'img' => 'Image',
            'delete' => 'Delete',
            'active' => 'Active'
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
            case 'cat_name':
            case 'cat_desc':
            case 'img':
            case 'delete':
            case 'active':


                return $item[$column_name];
            default:
                return print_r($item, true); //Show the whole array for troubleshooting purposes
        }
    }

}

function manage_category() {
    global $wpdb;

    if ($_GET['delete_id']) {
        $delete_id = $_GET['delete_id'];
        $wpdb->get_results("delete From wp_category where id='$delete_id'");
        wp_redirect(get_bloginfo('url') . '/wp-admin/admin.php?page=manage_category');
        exit();
    }
    if ($_GET['active_id']) {
        $active_id = $_GET['active_id'];
        $company = $wpdb->get_results("select * from wp_category where id='$active_id'");
        $cat_name = $company[0]->cat_name;
        $cat_desc = $company[0]->cat_desc;
        $cat_img = $company[0]->cat_img;
        $term_id = wp_insert_term(
                $cat_name, // the term 
                'category', // the taxonomy
                array(
            'description' => $cat_desc,
            'slug' => $cat_name,
            'parent' => 0
                )
        );

        update_option('z_taxonomy_image' . $term_id['term_id'], $cat_img);
        $wpdb->get_results("delete From wp_category where id='$active_id'");
        wp_redirect(get_bloginfo('url') . '/wp-admin/admin.php?page=manage_category');
        exit();
    }
    $company = $wpdb->get_results("select * from wp_category");
    $data_arr = array();
    foreach ($company as $key => $val) {
        $id = $val->id;
        $cat_name = $val->cat_name;
        $cat_desc = $val->cat_desc;
        $cat_img = $val->cat_img;
        $data = array('ID' => $id, 'cat_name' => $cat_name, 'cat_desc' => $cat_desc, 'img' => '<img src=' . $cat_img . ' height="100" width="100">', 'delete' => '<a href=' . get_bloginfo('url') . '/wp-admin/admin.php?page=manage_category&delete_id=' . $val->id . '>delete</a>', 'active' => '<a href=' . get_bloginfo('url') . '/wp-admin/admin.php?page=manage_category&active_id=' . $val->id . '>Active</a>');
        $data_arr[$key] = $data;
    }

    $myListTable = new My_List_Table_company();
    echo '<h1><strong>Categories</strong></h1>';
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
?>