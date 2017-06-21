
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta content="zxjBigPower">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>分公司申请-中国“新零售”领导品牌</title>
    <link rel="stylesheet" href="<?php echo TPL_URL; ?>css/yws_lz/base.css">
    <link rel="stylesheet" href="<?php echo TPL_URL; ?>css/yws_lz/yws_fgssq.css">
    <link rel="stylesheet" href="<?php echo TPL_URL; ?>js/yws_lz/lib/css/jquery.fullPage.css">
    <script src="<?php echo TPL_URL; ?>js/yws_lz/lib/js/jquery-3.0.0.min.js"></script>
    <script src="<?php echo TPL_URL; ?>js/yws_lz/lib/js/jquery.fullPage.min.js"></script>
    <script src="<?php echo TPL_URL; ?>js/yws_lz/base.js"></script>
    <script src="<?php echo TPL_URL; ?>js/yws_lz/yws_fgssq.js"></script>
</head>
<body>
<div id="alertBox">
    <div id="newzeroselltips" >
        <div id="msg">您上传的图片过大，请重新上传</div>
        <div  id="iknow">知道了</div>
    </div>
</div>
<div id="dowebok">
    <div class="section section1">
        <div class="showbox">
            <div class="con">
                <h3><span>申请条件</span></h3>
                <ul id="sqtj">
                    <li>
                        <span>1</span><p>可以独立承担民事责任的法人机构</p>
                    </li>
                    <li>
                        <span>2</span><p>具备持续的业务开展能力，与某个行业或某地区各行业客户有着良好的业务沟通渠道</p>
                    </li>
                    <li>
                        <span>3</span><p>了解和熟悉实体店操作、B2B概念和服务运作流程</p>
                    </li>
                    <li>
                        <span>4</span><p>可以独立开据合法的服务性票据</p>
                    </li>
                    <li>
                        <span>5</span><p>企业申请需提交营业执照复印件、税务登记证复印件和公司简介</p>
                    </li>
                </ul>
                <div class="next scaleBig">
                    <i></i>
                    <i></i>
                    <i></i>
                </div>
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
                        <p>本公司负责当地的区域经理将与您进行洽谈和协商，使您了解相关的加盟政策和价格体系。</p>
                        <span>2</span>
                    </li>
                    <li class="clearfix">
                        <p>在符合本公司代理加盟条件的情况下，认同本公司代理加盟政策和价格体系的基础上，与本公司签署正式的代理加盟协议及其他相关文件。</p>
                        <span>3</span>
                    </li>
                    <li class="clearfix">
                        <p>本公司将依据协议的签定情况，给成为本公司的代理加盟商发放相关服务授权证书。</p>
                        <span>4</span>
                    </li>
                    <li class="clearfix">
                        <p>本公司将为代理加盟商提供相关培训与交流。</p>
                        <span>5</span>
                    </li>
                    <li class="clearfix">
                        <p>代理加盟商在协议约定的区域开展业务，对业务负责。</p>
                        <span>6</span>
                    </li>
                </ul>
                <div class="next scaleBig">
                    <i></i>
                    <i></i>
                    <i></i>
                </div>
            </div>
        </div>
    </div>
    <div class="section section3">
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
                        <div class="annotation">支持png、gif、jpg且文件小于3M，图片请加盖公章</div>
                        <div class="next-page" id="nextPage">下一步</div>
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
                                <input type="text" name="phone" id="tel" maxlength="11">&nbsp;&nbsp;&nbsp; </p>
                        </li>
                        <div id="notnull" style="font-size: 13px;color:red;"></div>
                        <input type="hidden" value="分公司申请" name="yws_title">
                        <div class="prev-page" id="endPage">上一步</div>
<!--                        <div class="finish">提交</div>-->
                        <input type="submit" class="finish" value="提交">
                    </ul>

                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>