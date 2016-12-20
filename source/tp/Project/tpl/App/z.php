<include file="Public:header"/>
<style type="text/css">
    .green {
        color: green;
    }

    .red {
        color: red;
    }
</style>
<div class="mainbox">
    <div id="nav" class="mainnav_title">
        <ul>
            <a href="{pigcms{:U('App/z')}" class="on">众筹列表</a>|
            <a href="javascript:void(0);"
               onclick="window.top.artiframe('{pigcms{:U('App/z_edit')}','添加众筹',1024,600,true,false,false,addbtn,'add',true);">添加众筹</a>
        </ul>
    </div>
    <form name="myform" id="myform" action="" method="post">
        <div class="table-list">
            <table width="100%" cellspacing="0">
                <thead>
                <tr>
                    <th>编号</th>
                    <th>名称</th>
                    <th>目标款</th>
                    <th>已筹款</th>
                    <th>收益比例</th>
                    <th>消费金比例</th>
                    <th>分佣比例</th>
                    <th>封闭时长</th>
                    <th>状态</th>
                    <th>排序</th>
                    <th class="textcenter">操作</th>
                </tr>
                </thead>
                <!--SELECT  `id`,  `image`,  `title`,  `target`,  `achieved`,  `profit_rate`,  `gift_rate`,  `commission_rate`,  `days`,  `status`,  LEFT(`detials`, 256),  `add_time` FROM `yunws`.`tp_app_z` LIMIT 1000;-->
                <tbody>
                <if condition="is_array($list)">
                    <volist name="list" id="item">
                        <tr>
                            <td>{pigcms{$item.zid}</td>
                            <td>{pigcms{$item.title}</td>
                            <td><?php printf('%.2f', $item['target']); ?></td>
                            <td><?php printf('%.2f', $item['achieved']); ?></td>
                            <td><?php printf('%.2f', $item['profit_rate'] / 100.00); ?>%</td>
                            <td><?php printf('%.2f', $item['gift_rate'] / 100.00); ?>%</td>
                            <td><?php printf('%.2f', $item['commission_rate'] / 100.00); ?>%</td>
                            <td>{pigcms{$item.days}天</td>
                            <td><?php echo ($item['status'] ? '<span class="green">启用' : '<span class="red">禁用') . '</span>'; ?></td>
                            <td>{pigcms{$item.sort}</td>
                            <td class="textcenter">
                                <a href="javascript:void(0);"
                                   onclick="window.top.artiframe('{pigcms{:U('App/z_edit',array('zid'=>$item['zid']))}','编辑众筹信息',1024,600,true,false,false,editbtn,'add',true);">编辑</a>
                                |
                                <a href="javascript:void(0);" class="delete_row" parameter="zid={pigcms{$item.zid}"
                                   url="{pigcms{:U('App/z_del')}">删除</a></td>
                        </tr>
                    </volist>
                    <tr>
                        <td class="textcenter pagebar" colspan="11">{pigcms{$page}</td>
                    </tr>
                    <else/>
                    <tr>
                        <td class="textcenter red" colspan="11">列表为空！</td>
                    </tr>
                </if>
                </tbody>
            </table>
        </div>
    </form>
</div>
<include file="Public:footer"/>