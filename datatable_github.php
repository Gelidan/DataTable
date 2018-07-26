<?php

$title = "Datatables Title";
$columnlist = [ 'Column 1 name', 'Column 2 name', 'Column 3 name'];
$dt = new DataTables();
$dt_table = $dt->drawEmptyTable('userlist', true, $columnlist, $title);

?>
<div class="dashboard">
    <div class="dashboard_item dashboard_item--full">
        <div class="card"><?php echo $dt_table; ?></div>
    </div>
</div>
<script>
    $(document).ready(function () {
        var DT = $("#userlist").DataTable({
            "dom": "Bfltip",
            "order": [[0, "asc"]],
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "api_datatables_github.php",
                "type": "POST",
                "data": {"task": "userlist"},
                "global": false
            },
            "bStateSave": false,
            "responsive": true,
            "lengthMenu": [[10, 50, -1], [10, 50, "Összes"]],
            "fnDrawCallback": function () {
                //Delete action
                $(".delete").on('click', function(e){
                    e.preventDefault();
                    if(confirm('Are you sure?')){
                        $.ajax({
                            "url": "api_delete.php",
                            "type" : "POST",
                            "data": {action: "deleteEntity", table: "table", entity: $(this).attr("data-id")},
                            "global": false
                        }).done(function() {
                            DT.draw();
                        });
                    }else{
                        return false;
                    }
                })
            }
        });
    })
</script>