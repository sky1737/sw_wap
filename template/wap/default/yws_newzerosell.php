<?php if (!defined('TWIKER_PATH')) exit('deny access!'); ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta CONTENT="zxjBigPower">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>新零售申请</title>
    <link rel="stylesheet" href="<?php echo TPL_URL; ?>css/yws_lz/base.css">
    <link rel="stylesheet" href="<?php echo TPL_URL; ?>css/yws_lz/yws_newzerosell.css">
    <link rel="stylesheet" href="<?php echo TPL_URL; ?>js/yws_lz/lib/css/jquery.fullPage.css">
    <script src="<?php echo TPL_URL; ?>js/yws_lz/lib/js/jquery-3.0.0.min.js"></script>
    <script src="<?php echo TPL_URL; ?>js/yws_lz/lib/js/jquery.fullPage.min.js"></script>
    <script src="<?php echo TPL_URL; ?>js/yws_lz/base.js"></script>
    <script src="<?php echo TPL_URL; ?>js/yws_lz/yws_newzerosell.js"></script>
</head>
<body>
<div id="dowebok">
    <div class="section section1">
        <div class="showbox">
            <div class="con">
                <h3>申请条件</h3>
                <p>1.具有民事行为能力、能独立承担民事责任的个人或个体工商</p>
                <p>2.拥有一个较强的销售团队或者忠诚度较高的客户群或者在当地有良好的团体客户渠道</p>
                <p>3.了解和熟悉实体店操作、B2B概念和服务运作流程</p>
            </div>
        </div>
        <div class="next">下一页</div>
    </div>
    <div class="section section2">
        <div class="showbox">
            <div class="con">
                <h3>申请步骤</h3>
                <p>1.通过官方网站或直接致电本公司提出申请，并给出如下信息：所在地区、联系人、联系电话等。</p>
                <p> 2.本公司负责当地的区域经理将与您进行洽谈和协商，使您了解新零售的合作政策和价格体系。</p>
                <p> 3.在符合本公司申请条件的情况下，认同本公司合作政策和价格体系的基础上，与本公司签定合作协议。</p>
            </div>
        </div>
        <div class="next">下一页</div>
    </div>
    <div class="section section3">
        <div class="showbox">
            <form action="#" enctype="multipart/form-data">
                <div class="con con-first">
                    <h3>提交资料</h3>
                    <ul>
                        <li>
                            <p>1、个人身份证:&nbsp;&nbsp; <i>*</i></p>
                            <form action="" name="idcard" id="idcard" enctype="multipart/form-data">
                                <div class="con-box clearfix">
                                    <span> 示例：</span>
                                    <div class="img-box"><img src="<?php echo TPL_URL; ?>css/images/yws_lz/fgssq/yyzz.jpg" alt=""></div>
                                    <a href="#">
                                        上传图片
                                        <input type="file" name="yyzz" id="yyzz" accept="image/png,image/gif" onchange="uploadpic(this,'idcard')">
                                    </a>
                                    <div class="filebox">
                                        <div class="filebox-box">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </li>
                        <li>
                            <p>2、个体工商户营业执照:&nbsp;&nbsp; <i>*</i></p>
                            <div class="con-box clearfix">
                                <span> 示例：</span>
                                <div class="img-box"><img src="<?php echo TPL_URL; ?>css/images/yws_lz/fgssq/swdj.jpg" alt=""></div>
                                <a href="#">
                                    上传图片
                                    <input type="file" name="swdj" id="swdj" accept="image/png,image/gif">
                                </a>
                                <div class="filebox">
                                    <div class="filebox-box">

                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <p>3、银行账户资料:&nbsp;&nbsp; <i>*</i></p>
                            <div class="con-box clearfix">
                                <span> 示例：</span>
                                <div class="img-box"><img src="<?php echo TPL_URL; ?>css/images/yws_lz/fgssq/zhzm.png" alt=""></div>
                                <a href="#">
                                    上传图片
                                    <input type="file" name="sfz" id="sfz" accept="image/png,image/gif">
                                </a>
                                <div class="filebox">
                                    <div class="filebox-box">

                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <div class="next-page" id="nextPage">下一页&nbsp;<i>&gt;</i></div>
                </div>
                <div class="con con-next">
                    <ul>
                        <li>
                            <p>4、一寸免冠彩照两张:&nbsp;&nbsp; <i>*</i></p>
                            <div class="con-box clearfix">
                                <span> 示例：</span>
                                <div class="img-box"><img src="<?php echo TPL_URL; ?>css/images/yws_lz/fgssq/sfz.jpg" alt=""></div>
                                <a href="#">
                                    上传图片
                                    <input type="file" name="zhzm" id="zhzm" accept="image/png,image/gif">
                                </a>
                                <div class="filebox">
                                    <div class="filebox-box">
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="con-box clearfix">
                                <span> 示例：</span>
                                <div class="img-box"><img src="<?php echo TPL_URL; ?>css/images/yws_lz/fgssq/mgcz.jpg" alt=""></div>
                                <a href="#">
                                    上传图片
                                    <input type="file" name="mgcz" id="mgcz" accept="image/png,image/gif">
                                </a>
                                <div class="filebox">
                                    <div class="filebox-box">
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <div class="prev-page" id="prevPage"><i>&lt;</i>&nbsp;上一页</div>
                    <div class="next-page" id="nextPage1">下一页&nbsp;<i>&gt;</i></div>
                </div>
                <div class="con con-end">
                    <ul>
                        <li>
                            <p>6、所在地区: <i>*</i><br>
                                <input type="text" name="szdq" id="szdq">&nbsp;&nbsp;&nbsp; </p>
                        </li>
                        <li>
                            <p>7、联系人: <i>*</i><br>
                                <input type="text" name="lxr" id="lxr">&nbsp;&nbsp;&nbsp; </p>
                        </li>
                        <li>
                            <p>8、联系电话: <i>*</i><br>
                                <input type="tel" name="tel" id="tel">&nbsp;&nbsp;&nbsp; </p>
                        </li>
                    </ul>
                    <div class="prev-page" id="endPage"><i>&lt;</i>&nbsp;上一页</div>
                </div>
            </form>
            <div class="finish">完成</div>
        </div>
    </div>
</div>
</body>
</html>