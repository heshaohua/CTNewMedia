<?php include 'template/header.php';?>
<!--content section start-->
<div>
    <ul class="breadcrumb">
        <li>
            <a href="index.php">Home</a>
        </li>
        <li>
            <a href="#">修改分类</a>
        </li>
    </ul>
</div>
<div class="row">
    <div class="box col-md-12">
        <div class="box-inner">
            <div class="box-header well">
                <h2><i class="glyphicon glyphicon-edit"></i> 修改分类</h2>
            </div>
            <div class="box-content row" style="padding:20px;">
                <form role="form" action="category.php?action=editcategory" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="catname">分类名</label>
                        <input type="text" class="form-control" id="catname" name="catname" value="<?php echo $categoryinfo['name'];?>">
                        <input type="hidden" class="form-control" id="catid" name="catid" value="<?php echo $categoryinfo['id'];?>">
                    </div>
                    <div class="form-group">
                        <label for="ordernum">显示顺序</label>
                        <input type="text" class="form-control" id="ordernum" name="ordernum" value="<?php echo $categoryinfo['ordernum'];?>">
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm" name="savedata">Click to Submit</button>
                </form>    
            </div>
        </div>
    </div>
</div>

<?php include 'template/footer.php';?>