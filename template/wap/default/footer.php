<?php
if (empty($noFooterLinks) && empty($noFooterCopy)) { ?>
    <div class="js-footer">
        <div class="footer">
            <div class="copyright">
                <?php
                if (empty($noFooterLinks)) { ?>
                    <div class="ft-links">
                        <a href="<?php echo './'; //$now_store['url']; ?>">店铺主页</a>
                        <a href="<?php echo './my.php'; //$now_store['ucenter_url']; ?>">会员中心</a>
                        <?php echo $now_store['physical_count'] ? '<a href="' . $now_store['physical_url'] . '">线下门店</a>' : ''; ?>
                    </div>
                    <?php
                }
                if (0 && empty($noFooterCopy)) { ?>
                    <div class="ft-copyright">
                        <a href="<?php echo $config['wap_site_url']; ?>" target="_blank">由&nbsp;<span
                                class="company"><?php echo $config['site_name']; ?></span>&nbsp;提供技术支持</a>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <?php } ?>
<script charset="utf-8" src="http://wpa.b.qq.com/cgi/wpa.php"></script>
<script type="text/javascript">
    BizQQWPA.addCustom({aty: '0', a: '0', nameAccount: 800191661, selector: 'BizQQWPA'});
</script>
<div class="wx_aside" id="quckArea">
    <a href="javascript:void(0);" id="quckIco2" class="btn_more">更多</a>
    <div class="wx_aside_item" id="quckMenu">
        <a href="./index.php" class="item_index">首页</a>
        <a href="./category.php" class="item_fav">商品分类</a>
        <a href="./cart.php" class="item_cart" id>购物车</a>
<!--        <div id="BizQQWPA"  class="item_uc" >QQ客服</div>-->
        <a href="#" class="item_uc" id="BizQQWPA">QQ客服</a>
        <!-- WPA Button Begin -->
     <!--   <script charset="utf-8" type="text/javascript" src="http://wpa.b.qq.com/cgi/wpa.php?key=XzgwMDE5MTY2MV80MjY1OTZfODAwMTkxNjYxXw"></script>-->
        <!-- WPA Button End -->
        <?php // <a href="./weidian.php" class="item_cart">微店列表</a> ?>
        <a href="./my.php" class="item_uc">个人中心</a>
    </div>
</div>
<?php
//include display('lottery'); ?>
