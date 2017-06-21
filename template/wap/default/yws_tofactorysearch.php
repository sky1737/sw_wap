<ul>
    <?php if($stores):?>
        <?php foreach($stores as $val):?>
            <?php if($val['s_sid']==91 || $val['s_sid']==212):?>
                <?php continue;?>
            <?php endif;?>
            <li>
                <a href="./category2.php?storeid=<?= $val['s_sid']?>" >
                    <img src="<?= $val['logo']?>" alt="">
                    <p><?= $val['name']?></p>
                    <span style="color: #ccc;">认证时间：<?= date('Y-m-d H:i',$val['date_added'])?></span>
                </a>
            </li>
        <?php endforeach;?>
    <?php else:?>
        <li style="text-align: center;line-height: 80px">
            暂无您所搜索的厂家
        </li>
    <?php endif;?>
</ul>