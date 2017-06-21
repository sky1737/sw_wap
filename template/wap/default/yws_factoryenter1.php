<?php if (!defined('TWIKER_PATH')) exit('deny access!'); ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta CONTENT="zxjBigPower">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>厂家入驻-中国“新零售”领导品牌</title>
    <link rel="stylesheet" href="<?php echo TPL_URL; ?>css/yws_lz/base.css">
    <link rel="stylesheet" href="<?php echo TPL_URL; ?>css/yws_lz/yws-cjrz2.css">
    <link rel="stylesheet" href="<?php echo TPL_URL; ?>js/yws_lz/lib/css/jquery.fullPage.css">
    <script src="<?php echo TPL_URL; ?>js/yws_lz/lib/js/jquery-3.0.0.min.js"></script>
    <script src="<?php echo TPL_URL; ?>js/yws_lz/lib/js/jquery.fullPage.min.js"></script>
    <script src="<?php echo TPL_URL; ?>js/yws_lz/base.js"></script>
    <script src="<?php echo TPL_URL; ?>js/yws_lz/yws_cjrz2.js"></script>
</head>
<body>
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
                        <p>通过官方网站或直接致电本公司提出申请，并给出如下信息：所在地区、联系人、联系电话等。</p>
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
      <div class="con-con-box">
          <h3></h3>
          <div class="showbox" style="font-size: 12px;">
              <ul>
                  <li>
                      <label>
                          <input type="radio" name="type" value="1" id="type1" checked>
                          国内商家
                      </label>
                  </li>
              </ul>
              <ul>
                  <li>
                      <label><input type="radio" name="type" value="2" id="type2">
                          国外商家</label>
                  </li>
              </ul>
              <form action="./yws_factoryenter.php" name="type1" method="get" id="cjrzfrom1">
                  <input type="hidden" value="type" name="contry">
                  <ul id="select1">
                      <li>
                          <label> <input type="checkbox" name="mold1" value="list" id="common" checked>
                              &nbsp;&nbsp;&nbsp;经营服饰、珠宝首饰、饰品、运动户外、数码、家用电器、家装/家具/家纺、居家日用、汽车用品及配件、母婴类目的商品;</label>
                      </li>
                      <li>
                          <label>
                              <input type="checkbox" name="mold2" value="list2" id="foot">
                              &nbsp;&nbsp;&nbsp;经营食品；
                          </label>
                      </li>
                      <li>
                          <label>
                              <input type="checkbox" name="mold3" value="list2,list3" id="bjfoot">
                              &nbsp;&nbsp;&nbsp;经营保健食品;
                          </label>
                      </li>
                      <li>
                          <label>
                              <input type="checkbox" name="mold4" value=" list2,list4" id="liquor">
                              &nbsp;&nbsp;&nbsp;经营酒类;
                          </label>
                      </li>
                      <li>
                          <label>
                              <input type="checkbox" name="mold5" value="list5" id="cosmetic">
                              &nbsp;&nbsp;&nbsp;经营化妆品;
                          </label>
                      </li>
                  </ul>
                  <input class="finish" id="finish1" type="submit" value="下一步">
              </form>

              <form action="./yws_factoryenter.php" name="type2" method="get" id="cjrzfrom2">
                  <input type="hidden" value="type" name="contry">
                  <ul id="select2">
                      <li>
                          <label>
                              <input type="checkbox" name="mold2" value="list2,list6" id="foot2">
                              &nbsp;&nbsp;&nbsp;经营进口食品；
                          </label>
                      </li>
                      <li>
                          <label>
                              <input type="checkbox" name="mold3" value="list6,list2,list3,list7" id="bjfoot2">
                              &nbsp;&nbsp;&nbsp;经营进口保健食品;
                          </label>
                      </li>
                      <li>
                          <label>
                              <input type="checkbox" name="mold4" value="list6,list2,list4,list6" id="liquor2">
                              &nbsp;&nbsp;&nbsp;经营进口酒类;
                          </label>
                      </li>
                      <li>
                          <label>
                              <input type="checkbox" name="mold5" value="list6,list5,list8" id="cosmetic2">
                              &nbsp;&nbsp;&nbsp;经营进口化妆品;
                          </label>
                      </li>
                  </ul>
                  <input class="finish" id="finish2" type="submit" value="下一步">
              </form>
          </div>
      </div>
    </div>
</div>
</body>
</html>