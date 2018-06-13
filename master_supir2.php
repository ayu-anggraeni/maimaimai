<!DOCTYPE html>
<?php  include "header.php"; ?>
<html>
    <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">

      <title>Master Supir</title>

      <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet"/>
      <link href="../bootstrap/css/style2.css" rel="stylesheet"/> 
      

      <!-- include javascript and css files for the EditableGrid library -->
    <script src="../bootstrap/editablegrid-master/editablegrid.js"></script>
    <!-- [DO NOT DEPLOY] --> <script src="../bootstrap/editablegrid-master/editablegrid_renderers.js" ></script>
    <!-- [DO NOT DEPLOY] --> <script src="../bootstrap/editablegrid-master/editablegrid_editors.js" ></script>
    <!-- [DO NOT DEPLOY] --> <script src="../bootstrap/editablegrid-master/editablegrid_validators.js" ></script>
    <!-- [DO NOT DEPLOY] --> <script src="../bootstrap/editablegrid-master/editablegrid_utils.js" ></script>

    <script type="text/javascript" src="../bootstrap/js/jquery.min.js"></script>
      <script type="text/javascript" src="../bootstrap/js/bootstrap.min.js"></script>
      <link rel="stylesheet" href="../bootstrap/css/jquery.mCustomScrollbar.min.css">
      <script src="../bootstrap/js/jquery.mCustomScrollbar.concat.min.js"></script>

      


      <style type="text/css">
        table.table-hover tbody tr:hover {
    /*background-color: #fb9692; */
    background-color: #fb9692;
}
input.form-fixer {
    padding: 1px;
}


      </style>
    </head>


<?php
  include_once($_SERVER['DOCUMENT_ROOT'].'/maibus/fungsi/database_function.php');
  $db = openDb();
  //$data = getList($db, 'tb_supir');
  $data = getListLimit($db, 'tb_supir', 0, 10, "ORDER BY supir_nama");
?>


<body>
<div class="wrapper">
<!-- Sidebar Holder -->
 <?php include "sidebarholder.php"; ?>
<!-- Page Content Holder -->
<div id="content">
<?php include "nav.php"; ?>
<div class="overlay"></div>



<script type="text/javascript">



</script>


<input type="hidden" name='session_user_hak' id='session_user_hak' value=<?php echo $_SESSION['user_hak']; ?>>


    


<div id="tablecontent"> 
    <table id="mainTable" class="table table-hover" style="table-layout: fixed;
  width: 100%">
  <thead><tr><th width="5%">No</th><th>Nama Supir</th><th>Telp.</th><th>Bus</th><th></th></tr></thead>
  <tbody>
    <?php
    $no=0;
      foreach ($data as $key => $val) {
        $no++;
        $a = getTbBusUnit($db, $val['bus_unit_id']);
        $b = getTbBusExt($db, $a[0]['bus_id']);
        $bus = $b[0]['bus_nama_tarif']." - ".$val['bus_unit_id'];
        echo "<tr id='R$no'>";
        echo "<td>$no</td>";
        echo "<td>".$val['supir_nama']."</td>";
        echo "<td>".$val['supir_telp']."</td>";
        echo "<td>".$val['bus_unit_id']."</td>";
       // echo "<td> <input class='form-control input-sm form-fixer' disabled type='text' name='supirR$no' id='supirR$no' value=". $val['supir_id'] ."> </td>";
        echo "<td><div id='sR$no'></div> <input type='hidden' name='supirR$no' id='supirR$no' value=". $val['supir_id'] ."> <input type='hidden' name='busR$no' id='busR$no'>  </td>";
        echo "</tr>";
      }

    ?>
  </tbody>
  <tfoot>
    <tr>
      <td colspan="5" style="text-align: right;"><button onclick="edit();" class="btn btn-primary">Save</button></td>
     
        
    </tr>
  </tfoot>
</table>

</div>




</div>
</div>
<script>

  function edit(){
    /*$('#mainTable tr').each(function() {
    console.log($(this).find(".supir-edit").val());    
    });*/
    
    var update = new Array();
    var index = 0;
    //$('input[type="text"].supir-edit').each(function () {
    $('.supir-edit').each(function () {
      var row = $(this).parents('tr'); 
      update[index] = [];
      /*
        0 = nama supir
        1 = telp
        2 = bus_unit_id
        3 = supir_id
      */
      update[index][0] = row.find('td:eq(1)').html();
      update[index][1] = row.find('td:eq(2)').html();
      update[index][2] = $("#busR"+row.find('td:eq(0)').html()).val();
      update[index][3] = row.find('td:eq(4)').find("#supirR"+row.find('td:eq(0)').html()).val();
      $("#supirR"+row.find('td:eq(0)').html()).removeClass('supir-edit');
      index++;
    });
    $.ajax({
      type: "POST",
      url: "master_supir_edit.php",
      data: {"supir": update
      },
      cache: false,
      success: function(response){ 
       console.log(response);
      }
    }); 
    console.log(update);
  }


  $(document).ready(function () {

                $("#sidebar").mCustomScrollbar({
                    theme: "minimal"
                });
                

                $('#sidebarCollapse').on('click', function () {
                    $('#sidebar, #content').toggleClass('active');
                    $('.collapse.in').toggleClass('in');
                    $('a[aria-expanded=true]').attr('aria-expanded', 'false');
                });

                if( $("#session_user_hak").val() ==1){
                  //create our editable grid
var editableGrid = new EditableGrid("DemoGridFull", {
  enableSort: true, // true is the default, set it to false if you don't want sorting to be enabled
  editmode: "absolute", // change this to "fixed" to test out editorzone, and to "static" to get the old-school mode
  editorzoneid: "edition" // will be used only if editmode is set to "fixed"

});


EditableGrid.prototype.initializeGrid = function() 
{
  with (this) {
    modelChanged = function(rowIndex, columnIndex, oldValue, newValue, row) { 
      //console.log("Value for '" + this.getColumnName(columnIndex) + "' in row " + this.getRowId(rowIndex) + " has changed from '" + oldValue + "' to '" + newValue + "'");
      //console.log("#supirasd"+this.getRowId(rowIndex));
      //console.log($("#supir"+this.getRowId(rowIndex)).val());
     // $("#supir"+this.getRowId(rowIndex)).addClass('supir-edit');
      $("#s"+this.getRowId(rowIndex)).addClass('supir-edit');
      $("#bus"+this.getRowId(rowIndex)).val(editableGrid.getValueAt(rowIndex, editableGrid.getColumnIndex("bus")));
      var continent = editableGrid.getValueAt(rowIndex, editableGrid.getColumnIndex("bus"));
      console.log("busunitid " +continent);
    };

    // render the grid (parameters will be ignored if we have attached to an existing HTML table)
    renderGrid("tablecontent", "testgrid", "tableId");
  }
};

EditableGrid.prototype.onloadHTML = function(tableId, busValue) 
{
  // metadata are built in Javascript: we give for each column a name and a type
  this.load({ metadata: [
   { name: "no", datatype: "integer", editable: false },
   { name: "nama", datatype: "string", editable: true },
   { name: "telp", datatype: "string", editable: true },
   //{ name: "continent", datatype: "string", editable: true, values: {"eu": "Europa", "am": "America", "af": "Africa" } },
   { name: "bus", datatype: "string", editable: true, values: busValue}
   
   ]});

  // we attach our grid to an existing table
  this.attachToHTMLTable(_$(tableId));
  this.initializeGrid();
};



                  window.onload = function() { 
    
                    $.ajax({
                      type: "POST",
                      url: "fetch_master_supir_bus.php",
                      data: {
                      },
                      dataType:"JSON",
                      cache: false,
                      success: function(response){ 
                        editableGrid.onloadHTML("mainTable", response); 
                      }
                    }); 
                    
                  } 
                }
            });

</script>
  
</body>
</html>