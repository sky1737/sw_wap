<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta content="zxjBigPower">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>新零售申请-中国“新零售”领导品牌</title>
    <link rel="stylesheet" href="<?php echo TPL_URL; ?>css/yws_lz/base.css">
    <link rel="stylesheet" href="<?php echo TPL_URL; ?>css/yws_lz/yws_newzerosell.css">
    <link rel="stylesheet" href="<?php echo TPL_URL; ?>js/yws_lz/lib/css/jquery.fullPage.css">
    <script src="<?php echo TPL_URL; ?>js/yws_lz/lib/js/jquery-3.0.0.min.js"></script>
    <script src="<?php echo TPL_URL; ?>js/yws_lz/lib/js/jquery.fullPage.min.js"></script>
    <script src="<?php echo TPL_URL; ?>js/yws_lz/base.js"></script>
    <script src="<?php echo TPL_URL; ?>js/yws_lz/yws_newzerosell.js"></script>
</head>
<body>
<div id="newzeroselltips" style="display: none; height: 120px; width: 60%; border-radius: 10px; background: rgba(255, 255, 255, 0.95); position: absolute; top: 190px; left: 20%; z-index: 1000;">
    <div id="msg" style="margin:0 auto;width:100%;height: 50px;text-align: center;padding-top: 25px;font-size: 14px;color: red;">您上传的图片过大，请重新上传</div>
    <div style="width: 90px;height: 36px;margin: 0 auto;margin-top: 20px;font-size: 12px;border-radius:7px;background:#99bbf5;color: white;text-align: center;line-height: 36px" id="iknow">知道了</div>
</div>
<div id="dowebok" style="background-color: red">
    <div class="section section1">
        <div class="showbox">
            <div class="con">
                <h3><span>申请条件</span></h3>
                <ul id="sqtj">
                    <li>
                        <span>1</span><p>具有民事行为能力、能独立承担民事责任的个人或个体工商户</p>
                    </li>
                    <li>
                        <span>2</span><p>拥有一个较强的销售团队或者忠诚度较高的客户群或者在当地有良好的团体客户渠道</p>
                    </li>
                    <li>
                        <span>3</span><p>了解和熟悉实体店操作、B2B概念和服务运作流程</p>
                    </li>
                </ul>
                <div class="next scaleBig"><i class="iconfont">&#xe609</i></div>
            </div>
        </div>
    </div>
    <div class="section section2">
        <div class="showbox">
            <div class="con">
                <h3><span>申请步骤</span></h3>
                <ul id="sqbz">
                    <li class="clearfix">
                        <p>通过官方网站或直接致电本公司提出申请，并给出如下信息：所在地区、联系人、联系电话等。 </p>
                        <span>1</span>
                    </li>
                    <li class="clearfix">
                        <p>本公司负责当地的区域经理将与您进行洽谈和协商，使您了解新零售的合作政策和价格体系。</p>
                        <span>2</span>
                    </li>
                    <li class="clearfix">
                        <p>在符合本公司申请条件的情况下，认同本公司合作政策和价格体系的基础上，与本公司签定合作协议。</p>
                        <span>3</span>
                    </li>
                </ul>
                <div class="next scaleBig"><i class="iconfont">&#xe609</i></div>
            </div>
        </div>
    </div>
    <div class="section section3">
        <div class="showbox">
            <form action="./yws_newzerosell.php" name="newzerofile" id="newzerofile" method="post" enctype="multipart/form-data">
                <div class="con con-first">
                    <h3></h3>
                    <ul>
                        <li>
                            <p>个人身份证(正面)</p>
                            <div class="con-box clearfix">
                                <div class="img-box">
                                </div>
                                <a href="#" class="inputa">
                                    +<input type="file" name="idcard" id="yyzz" accept="image/png,image/gif,image/bmp,image/jpg" required>
                                </a>
                                <div class="filebox">
                                    <div class="filebox-box" >
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <p>个人身份证(反面)</p>
                            <div class="con-box clearfix">
                                <div class="img-box">
                                </div>
                                <a href="#" class="inputa">
                                    +<input type="file" name="persional2" id="mgcz" value="" accept="image/png,image/gif,image/bmp,image/jpg" required>
                                </a>
                                <div class="filebox">
                                    <div class="filebox-box" >
                                    </div>
                                </div>
                            </div>
                        </li>
                        <div class="annotation">支持png、gif、jpg且文件小于3M，图片请加盖公章</div>
                        <div class="next-page" id="nextPage">下一步</div>
                    </ul>

                </div>
                <div class="con con-next">
                    <ul>
                        <li>
                            <p>银行账户资料</p>
                            <div class="con-box clearfix">
                                <div class="img-box"></div>
                                <a href="#">+<input type="file" name="bank" id="sfz" value="" accept="image/png,image/gif,image/bmp,image/jpg" required>
                                </a>
                                <div class="filebox">
                                    <div class="filebox-box"></div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <p>营业执照</p>
                            <div class="con-box clearfix">
                                <div class="img-box"></div>
                                <a href="#">
                                    +
                                    <input type="file" name="license" id="swdj" value="" accept="image/png,image/gif,image/bmp,image/jpg" required>

                                </a>
                                <div class="filebox">
                                    <div class="filebox-box">

                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <p>一寸免冠彩照</p>
                            <div class="con-box clearfix">
                                <div class="img-box"></div>
                                <a href="#">+<input type="file" name="persional1" id="zhzm" value="" accept="image/png,image/gif,image/bmp,image/jpg" required>
                                </a>
                                <div class="filebox">
                                    <div class="filebox-box">
                                    </div>
                                </div>
                            </div>
                        </li>
                        <div class="annotation">支持png、gif、jpg且文件小于3M，图片请加盖公章</div>
                        <div class="prev-page" id="prevPage">上一步</div>
                        <div class="next-page" id="nextPage1">下一步</div>
                    </ul>

                </div>
                <div class="con con-end">
                    <ul>
                        <h6>基本信息</h6>
                        <li>
                            <p><span>所在地区</span>
                                <input type="text" name="area" id="szdq" >&nbsp;&nbsp;&nbsp; </p>
                        </li>
                        <li>
                            <p><span>联系人</span>
                                <input type="text" name="linkman" id="lxr" >&nbsp;&nbsp;&nbsp; </p>
                        </li>
                        <li>
                            <p><span>联系电话</span>
                                <input type="text" name="phone" id="tel" maxlength="11" >&nbsp;&nbsp;&nbsp; </p>
                        </li>
                        <div id="notnull" style="font-size: 13px;color:red;"></div>
                        <div class="prev-page" id="endPage">上一步</div>
                        <input type="submit" class="finish" value="提交">
<!--                        <div class="finish">提交</div>-->
                    </ul>
                </div>
            </form>

        </div>
</div>
</body>
</html>