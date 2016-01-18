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
                        <label for="remark">摘要</label>
                        <textarea id="remark" rows="4" cols="120" style="width:100%;" name="remark"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="remark">状态</label>
                        <select name="status">
                            <option value="0">未发布</option>
                            <option value="1">发布</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tags">TAG(逗号分隔)</label>
                        <input type="text" class="form-control" id="tags" name="tags">
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