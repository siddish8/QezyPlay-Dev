<?php

 include('header.php');


?>

<article>
<table id="example" class="display" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Username</th>
                <th>Email</th>
                <th>id</th>
                <th>regd.</th>
                
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>First name</th>
                <th>Last name</th>
                <th>Position</th>
                <th>Office</th>
                
            </tr>
        </tfoot>
    </table>
<script>
jQuery(document).ready(function() {
    jQuery('#example').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": "server.php"
    } );
} );
</script>
</article>
<?php 
 include('footer.php');
?>

