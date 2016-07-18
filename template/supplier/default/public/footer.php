<div class="js-notifications notifications"></div>
<div style="position:absolute;top:0;height:5px;background:rgb(255,252,4);width:0%;z-index:100;"></div>
<?php $show_footer_link = isset($show_footer_link) ? $show_footer_link : true; ?>
<footer class="ui-footer">
    <?php if ($show_footer_link) { ?>
        <a href="<?php echo $config['site_url']; ?>" target="_blank">
            <i>©</i> <?php echo getUrlTopDomain($config['site_url']); ?>
        </a>
    <?php } else { ?>
        <i>©</i> <?php echo getUrlTopDomain($config['site_url']); ?>
    <?php } ?>
</footer>
<div style="display:none;">
<script type="text/javascript">
var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");
document.write(unescape("%3Cspan id='cnzz_stat_icon_1259591443'%3E%3C/span%3E%3Cscript src='" + cnzz_protocol + "s11.cnzz.com/stat.php%3Fid%3D1259591443%26show%3Dpic' type='text/javascript'%3E%3C/script%3E"));
</script>
</div>
