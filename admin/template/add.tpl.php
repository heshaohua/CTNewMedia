<?php include 'template/header.php';?>
<!--content section start-->
<div>
    <ul class="breadcrumb">
        <li>
            <a href="index.php">Home</a>
        </li>
        <li>
            <a href="#">新文章</a>
        </li>
    </ul>
</div>
<div class="row">
    <div class="box col-md-12">
        <div class="box-inner">
            <div class="box-header well">
                <h2><i class="glyphicon glyphicon-edit"></i> 编辑文章内容</h2>
            </div>
            <div class="box-content row" style="padding:20px;">
                <form role="form" action="add.php" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="title">标题</label>
                        <input type="text" class="form-control" id="title" name="title">
                    </div>
                    <div class="form-group">
                        <label for="remark">状态</label>
                        <select name="status">
                            <option value="0">未发布</option>
                            <option value="1">发布</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="categoryid">分类</label>
                        <select name="categoryid">
                            <?php foreach($allcategory as $cat):?>
                            <option value="<?php echo $cat['id'];?>"><?php echo $cat['name'];?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="city">有效区域(城市)  <span style="font-size:12px;color:grey;">!多个城市以竖线（‘|’）分隔，例如（成都|德阳|绵阳  ）<span></label><br>
                        <textarea name="city" id="city" cols="100" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="money">总金额</label>
                        <input type="text" class="form-control" id="money" name="money">
                    </div>
                     <div class="form-group">
                        <label for="minprice">最低点击单价</label>
                        <input type="text" class="form-control" id="minprice" name="minprice">
                    </div>
                    <div class="form-group">
                        <label for="maxprice">最高点击单价</label>
                        <input type="text" class="form-control" id="maxprice" name="maxprice">
                    </div>
                    <div class="form-group">
                        <label for="remark">内容</label>
                        <script id="container" name="content" type="text/plain">
                        </script>
                        <!-- 配置文件 -->
                        <script type="text/javascript" src="ueditor/ueditor.config.js"></script>
                        <!-- 编辑器源码文件 -->
                        <script type="text/javascript" src="ueditor/ueditor.all.js"></script>
                        <!-- 实例化编辑器 -->
                        <script type="text/javascript">
                            var ue = UE.getEditor('container',{
                                initialFrameHeight:280,
                                initialFrameWidth:900,
                                
                            });
                        </script>
                    </div>
                    <!--
                    <div class="form-group">
                        <label for="remark">上传缩略图</label>
                        <input type="file" name="slpic">
                        <p style="font-size:12px;color:grey;">如果没上传，则以文章内第一张图为缩略图</p>
                    </div>
                    -->
                    <button type="submit" class="btn btn-primary btn-sm" name="addpost">Click to Submit</button>
                </form>    
            </div>
        </div>
    </div>
</div>

<?php include 'template/footer.php';?>