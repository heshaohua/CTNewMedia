<?php include 'template/header.php';?>
<link rel="stylesheet" href="../source/css/style.css">
<style>
.proinfo_1 img{max-width: 95%;}
</style>
<!--content section start-->
<div>
    <ul class="breadcrumb">
        <li>
            <a href="index.php">Home</a>
        </li>
        <li>
            <a href="#">文章详细内容</a>
        </li>
    </ul>
</div>
<div class="row">
    <div class="box col-md-12">
        <div class="box-inner">
            <div class="box-header well">
                <h2><i class="glyphicon glyphicon-edit"></i> 文章详细内容</h2>
            </div>
            <div class="box-content row" style="padding:20px;">
               <div id="con" style="width:380px;border:1px rgb(39, 42, 48) solid;margin:0px auto;">
                    <div class="tour_div" style="width:380px;padding:10px;">

                        <div class="tour_title tc0" style="font-size:20px;"><?php echo $content['title'];?></div>
                        <div style="border: 1px #ECECEC dashed;background-color: #fff;padding: 5px;font-size: 12px;color: #FF5705;margin-top: 5px;margin-bottom: 10px;line-height:20px;height:50px;">
                                 
                            <div style="width:35%;float:left;height:20px;">阅读： <?php echo $content['visitcount'];?></div>
                            <div style="width:30%;float:left;height:20px;">分享：<?php echo intval($content['sharenum']);?></div> 
                            <div style="width:30%;float:left;height:20px;">发布：<?php echo date('Y-m-d',strtotime($content['addtime']));?></div>
                            <div style="width:35%;float:left;height:20px;">全部：&yen;<?php echo $content['money'];?></div>
                            <div style="width:30%;float:left;height:20px;">剩余：&yen;<?php echo $content['leftmoney'];?></div> 
                            <div style="width:30%;float:left;height:20px;">人均：&yen;<?php echo $content['minprice'];?> ~ &yen;<?php echo $content['maxprice'];?></div>           
                        </div>
                        <div style="width:100%;font-size:12px;font-weight: bold;">
                            分钱区域：<?php echo $areadata;?>
                        </div>
                        <div class="proinfo_1">
                            <?php echo $content['content'];?>
                        </div>  
                    </div>  

                </div>  
            </div>
        </div>
    </div>
</div>

<?php include 'template/footer.php';?>