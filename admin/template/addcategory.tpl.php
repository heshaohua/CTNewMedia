<?php include 'template/header.php';?>
<!--content section start-->
<div>
    <ul class="breadcrumb">
        <li>
            <a href="index.php">Home</a>
        </li>
        <li>
            <a href="#">添加分类</a>
        </li>
    </ul>
</div>
<div class="row">
    <div class="box col-md-12">
        <div class="box-inner">
            <div class="box-header well">
                <h2><i class="glyphicon glyphicon-edit"></i> 添加分类</h2>
            </div>
            <div class="box-content row" style="padding:20px;">
                <form role="form" action="category.php?action=addcategory" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="catname">分类名</label>
                        <input type="text" class="form-control" id="catname" name="catname">
                    </div>
                    <div class="form-group">
                        <label for="ordernum">显示顺序</label>
                        <input type="text" class="form-control" id="ordernum" name="ordernum">
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm" name="addcategory">Click to Submit</button>
                </form>    
            </div>
        </div>
    </div>
</div>

<?php include 'template/footer.php';?>