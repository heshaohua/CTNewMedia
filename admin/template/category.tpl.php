<?php include 'template/header.php';?>
<!--content section start-->
<div>
    <ul class="breadcrumb">
        <li>
            <a href="index.php">Home</a>
        </li>
        <li>
            <a href="#">分类列表</a>
        </li>
    </ul>
</div>
<div class="row">
    <div class="box col-md-12">
        <div class="box-inner">
            <div class="box-header well">
                <h2><i class="glyphicon glyphicon-align-justify"></i> 分类列表</h2>
            </div>
            <div class="box-content row" style="padding:20px;">
            <p class="left">
                            <a href="category.php?action=addcategory" class="btn btn-large btn-primary"><i class="icon-chevron-left icon-white"></i> 添加分类</a> 
                        </p>
            </p> 
            <div class="box-content row" style="padding:20px;">
                <table id="articlelist" class="table table-striped table-bordered bootstrap-datatable  responsive order-column">
				    <thead>
				    <tr>
				        <th width="80">id</th>
				        <th>name</th>
                        <th>ordernum</th>
				        <th width="300">operations</th>
				    </tr>
				    </thead>
				</table>
				<tbody></tbody>
            </div>    
        </div>
    </div>
</div>
<!--content section end-->   
<script>
		$(document).ready(function() {
                $('#articlelist').dataTable( {
                        "sDom": "<'row-fluid'<'col-md-6'l><'col-md-6'f>r>t<'row-fluid'<'span12'i><'span12 center'p>>",
						"sPaginationType": "bootstrap",
						"oLanguage": {
						"sLengthMenu": "_MENU_ records per page"
						},
                        "bProcessing": true,
                        "bServerSide": true,
                        "bSort":false,
					    "sAjaxSource": "getcatList.php"
                });
        }); 

        function deletearticle(id){
            if(confirm("确定删除？")){
                $.get("category.php?action=deletecategory&id="+id,function(data){
                    console.log(data);
                    if(data=='success'){
                        $('#deleteitem'+id).parent().parent().remove();  
                    }
                })    
            }
        }
</script>
<?php include 'template/footer.php';?>