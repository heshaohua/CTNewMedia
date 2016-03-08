<?php include 'template/header.php';?>
<!--content section start-->
<div>
    <ul class="breadcrumb">
        <li>
            <a href="index.php">Home</a>
        </li>
        <li>
            <a href="#">修改文章</a>
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
                <?php if(!empty($msg)):?>
                    <p style="color:green;padding-left:40px;font-size:16px;font-family:inherit;font-weight:bold;">
                        <i class="glyphicon glyphicon-info-sign"></i>&nbsp;&nbsp;<?php echo $msg;?>
                    </p>
                <?php endif;?>    
                <form role="form" action="edit.php" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="title">标题</label>
                        <input type="hidden" name="articleid" value="<?php echo $data['articleid']?>">
                        <input type="text" class="form-control" id="title" name="title" value="<?php echo $data['title'];?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="remark">状态</label>
                        <select name="status">
                            <option value="0" <?php if($data['status']==0):?>selected<?php endif;?>>未发布</option>
                            <option value="1" <?php if($data['status']==1):?>selected<?php endif;?>>发布</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="categoryid">分类</label>
                        <select name="categoryid">

                            <?php foreach($allcategory as $cat):?>
                            <option value="<?php echo $cat['id'];?>" <?php if($data['categoryid']==$cat['id']){echo 'selected';}?>><?php echo $cat['name'];?></option>
                            <?php endforeach;?>

                        </select>
                    </div>
                    <div class="form-group">
                        <label for="city">有效区域(城市)  
                        <span style="font-size:12px;color:red;display:none;" id="citytips">
                            如果需要具体到县级请在输入框中输入，多个城市以竖线（‘|’）分隔，如 涪城区|游仙区|江油市 .  为空默认全市范围
                        </span></label>
                        <br>
                        <div id="city">
                            <select class="prov" name="province"></select>
                            <select class="city" disabled="disabled" name="city" onchange="selectcity()"></select>
                        </div>
                        <textarea name="district" id="district" rows="3" cols="89" style="display:none;margin-top:10px;"><?php echo $data['district'];?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="money">总金额</label>
                        <input type="text" class="form-control" id="money" name="money" value="<?php echo $data['money'];?>">
                    </div>
                    <div class="form-group">
                        <label for="minprice">最低点击单价</label>
                        <input type="text" class="form-control" id="minprice" name="minprice" value="<?php echo $data['minprice'];?>">
                    </div>
                    <div class="form-group">
                        <label for="maxprice">最高点击单价</label>
                        <input type="text" class="form-control" id="maxprice" name="maxprice" value="<?php echo $data['maxprice'];?>">
                    </div>

                    <div class="form-group">
                        <label for="leftmoney">剩余金额</label>
                        <input type="text" class="form-control" id="leftmoney" name="leftmoney" value="<?php echo $data['leftmoney'];?>">
                    </div>
                    <div class="form-group">
                        <label for="visitcount">阅读数</label>
                        <input type="text" class="form-control" id="visitcount" name="visitcount" value="<?php echo $data['visitcount'];?>">
                    </div>
                    <div class="form-group">
                        <label for="sharenum">分享数</label>
                        <input type="text" class="form-control" id="sharenum" name="sharenum" value="<?php echo $data['sharenum'];?>">
                    </div>
                    <div class="form-group">
                        <label for="clicknum">点击数</label>
                        <input type="text" class="form-control" id="clicknum" name="clicknum" value="<?php echo $data['clicknum'];?>">
                    </div>
                    <div class="form-group">
                        <label for="priceperclick">人均</label>
                        <input type="text" class="form-control" id="priceperclick" name="priceperclick" value="<?php echo $data['priceperclick'];?>">
                    </div>
                    
                    
                    <div class="form-group">
                        <label for="remark">内容</label>
                        <script id="container" name="content" type="text/plain">
                        <?php echo $data['content'];?>
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
                    <button type="submit" class="btn btn-primary btn-sm" name="savepost">Click to Save</button>
                </form>   
            </div>
        </div>
    </div>
</div>
<script src="js/jquery.cityselect.js"></script>
<script>
$("#city").citySelect({   
    url:"js/city.min.js",   
    prov:"<?php echo $data['province'];?>", //省份  
    city:"<?php echo $data['tempcity'];?>", //城市  
});
$(function(){
    if($(".city").length>0&&$(".city")[0].value!='全省'){
        $("#citytips").show();
        $("#district").show();
    }else{
        $("#citytips").hide();
        $("#district").value='';
        $("#district").hide();
    }
});

function selectcity(){
    var city = $(".city")[0].value;
    if(city=='全省'){
        $("#citytips").hide();
        $("#district").value='';
        $("#district").hide();
    }else{
        $("#citytips").show();
        $("#district").value='';
        $("#district").show();
    }
}
</script>
<?php include 'template/footer.php';?>