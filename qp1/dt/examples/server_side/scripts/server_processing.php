<?php
/*
 * DataTables example server-side processing script.
 *
 * Please note that this script is intentionally extremely simply to show how
 * server-side processing can be implemented, and probably shouldn't be used as
 * the basis for a large complex system. It is suitable for simple use cases as
 * for learning.
 *
 * See http://datatables.net/usage/server-side for full details on the server-
 * side processing requirements of DataTables.
 *
 * @license MIT - http://datatables.net/license_mit
 */
 
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Easy set variables
 */
 
// DB table to use
$table = 'wp_users';
 
// Table's primary key
$primaryKey = 'id';
 
// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
    array( 'db' => 'user_login', 'dt' => 0 ),
    array( 'db' => 'user_email',  'dt' => 1 ),
    array( 'db' => 'ID',   'dt' => 2 ),
    array( 'db' => 'phone',     'dt' => 3 ),
    array(
        'db'        => 'user_registered',
        'dt'        => 4,
        'formatter' => function( $d, $row ) {
            return date( 'jS M y', strtotime($d));
        }
    )
);
 
// SQL server connection information
$sql_details = array(
    'user' => 'tradmin_qezyplay',
    'pass' => '&(qezy@word)&',
    'db'   => 'tradmin_newqezy',
    'host' => '50.62.170.42'
);
 
 
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */
 
require( 'dt/ssp.class.php' );
 
echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
);

?>
