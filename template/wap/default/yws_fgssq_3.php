<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="author:zxjBigPower">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>中国“新零售”领导品牌</title>
    <link rel="stylesheet" href="<?php echo TPL_URL; ?>css/yws_lz/base.css">
    <link rel="stylesheet" href="<?php echo TPL_URL; ?>css/yws_lz/yws_fgssq_3.css">
    <script src="<?php echo TPL_URL; ?>js/yws_lz/lib/js/jquery-3.0.0.min.js"></script>
    <script src="<?php echo TPL_URL; ?>js/yws_lz/base.js"></script>
    <script src="<?php echo TPL_URL; ?>js/yws_lz/yws_fgssq_3.js"></script>
</head>
<body>
<div class="showbox">
    <form action="./yws_branchcompany.php" method="post" name="compnay" id="company" enctype="multipart/form-data">
        <div class="con con-first">
            <h3></h3>
            <ul>
                <li>
                    <p>营业执照</p>
                    <div class="con-box clearfix">
                        <div class="img-box">
                        </div>
                        <a href="#">+<input type="file" name="license_pic" id="yyzz" accept="image/png,image/gif,image/bmp,image/jpg" required>
                        </a>
                        <div class="filebox">
                            <div class="filebox-box">
                            </div>
                        </div>
                    </div>
                </li>
                <li>
                    <p>税务登记证</p>
                    <div class="con-box clearfix">
                        <div class="img-box"></div>
                        <a href="#">
                            +
                            <input type="file" name="tax_pic" id="swdj" accept="image/png,image/gif,image/bmp,image/jpg" required>
                        </a>
                        <div class="filebox">
                            <div class="filebox-box">

                            </div>
                        </div>
                    </div>
                </li>
                <div class="annotation">注:请加盖公章。</div>
                <div class="next-page" id="nextPage">下一页&nbsp;<i>&gt;</i></div>
            </ul>

        </div>
        <div class="con con-next">
            <ul>
                <li>
                    <p>法人身份证</p>
                    <div class="con-box clearfix">
                        <div class="img-box"></div>
                        <a href="#">+<input type="file" name="idcard_pic" id="sfz" accept="image/png,image/gif,image/bmp,image/jpg" required>
                        </a>
                        <div class="filebox">
                            <div class="filebox-box"></div>
                        </div>
                    </div>
                </li>
                <li>
                    <p>银行账户证明</p>
                    <div class="con-box clearfix">
                        <div class="img-box"></div>
                        <a href="#">+<input type="file" name="bank_pic" id="zhzm" accept="image/png,image/gif,image/bmp,image/jpg" required>
                        </a>
                        <div class="filebox">
                            <div class="filebox-box">
                            </div>
                        </div>
                    </div>
                </li>
                <li>
                    <p>法人一寸免冠照</p>
                    <div class="con-box clearfix">
                        <div class="img-box"></div>
                        <a href="#">
                            +
                            <input type="file" name="peo_pic" id="mgcz" accept="image/png,image/gif,image/bmp,image/jpg" required>
                        </a>
                        <div class="filebox">
                            <div class="filebox-box">
                            </div>
                        </div>
                    </div>
                </li>
                <div class="annotation">注:请加盖公章。</div>
                <div class="prev-page" id="prevPage"><i>&lt;</i>&nbsp;上一页</div>
                <div class="next-page" id="nextPage1">下一页&nbsp;<i>&gt;</i></div>
            </ul>

        </div>
        <div class="con con-end">
            <ul>
                <h6>基本信息</h6>
                <li>
                    <p><span>所在地区</span>
                        <input type="text" name="area" id="szdq" required>&nbsp;&nbsp;&nbsp; </p>
                </li>
                <li>
                    <p><span>联系人</span>
                        <input type="text" name="linkman" id="lxr" required>&nbsp;&nbsp;&nbsp; </p>
                </li>
                <li>
                    <p><span>联系电话</span>
                        <input type="tel" name="phone" id="tel" required>&nbsp;&nbsp;&nbsp; </p>
                </li>
                <div class="prev-page" id="endPage"><i>&lt;</i>&nbsp;上一页</div>
                <div id="notnull"></div>
                <input type="submit" class="finish" value="提交">
            </ul>
        </div>
    </form>
</div>

</body>
</html>