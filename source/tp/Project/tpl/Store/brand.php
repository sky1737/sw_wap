<include file="Public:header"/><style type="text/css">    .c-gray {        color: #999;    }    .table-list tfoot tr {        height: 40px;    }    .green {        color: green;    }    a, a:hover {        text-decoration: none;    }</style><if condition="$withdrawal_count gt 0">    <script type="text/javascript">        $(function () {            $('#nav_12 > dd > #leftmenu_Store_withdraw', parent.document).html('提现记录 <label style="color:red">(' + {pigcms{$withdrawal_count} + ')</label>')        })    </script>    <else/>    <script type="text/javascript">        $(function () {            $('#nav_12 > dd > #leftmenu_Store_withdraw', parent.document).html('提现记录');        })    </script></if><script type="text/javascript">    $(function () {        $('.status-enable > .cb-enable').click(function () {            if (!$(this).hasClass('selected') && parseFloat($(this).data('status')) == 1) {                var url = window.location.href;                var brand_id = $(this).data('id');                $.post("<?php echo U('Store/brand_status'); ?>", {'status': 1, 'brand_id': brand_id}, function (data) {                    window.location.href = url;                })            }            if (parseFloat($(this).data('status')) == 0) {                $(this).removeClass('selected');            }            return false;        })        $('.status-disable > .cb-disable').click(function () {            if (!$(this).hasClass('selected') && parseFloat($(this).data('status')) == 1) {                var url = window.location.href;                var brand_id = $(this).data('id');                if (!$(this).hasClass('selected')) {                    $.post("<?php echo U('Store/brand_status'); ?>", {                        'status': 0,                        'brand_id': brand_id                    }, function (data) {                        window.location.href = url;                    })                }            }            return false;        })    })</script><div class="mainbox">    <div id="nav" class="mainnav_title">        <ul>            <a href="{pigcms{:U('Store/brand')}" class="on">品牌列表</a>|            <a href="javascript:void(0);"               onclick="window.top.artiframe('{pigcms{:U('Store/brand_add')}','添加品牌',480,310,true,false,false,addbtn,'add',true);">添加品牌</a>        </ul>    </div>    <table class="search_table" width="100%">        <tr>            <td>                <form action="{pigcms{:U('Store/brand')}" method="get">                    <input type="hidden" name="c" value="Store"/>                    <input type="hidden" name="a" value="brand"/>                    筛选:                    <select name="type_id">                        <option value="0">品牌类别</option>                        <volist name="all_brandtypes" id="all_brandtypes">                            <option                            <if condition="$Think.get.type_id eq $all_brandtypes['type_id']">selected</if>                            value="{pigcms{$all_brandtypes['type_id']}">{pigcms{$all_brandtypes.type_name}</option>                        </volist>                    </select>                    <input type="submit" value="查询" class="button"/>                </form>            </td>        </tr>    </table>    <div class="table-list">        <table width="100%" cellspacing="0">            <thead>            <tr>                <th>删除 | 修改</th>                <th>编号</th>                <th>名称</th>                <th>描述</th>                <th>状态</th>                <th>排序</th>                <th class="textcenter" width="100">操作</th>            </tr>            </thead>            <tbody>            <if condition="is_array($brands)">                <volist name="brands" id="brand">                    <tr>                        <td><a url="<?php echo U('Store/brand_del', array('id' => $brand['brand_id'])); ?>"                               class="delete_row"><img src="{pigcms{$static_path}images/icon_delete.png" width="18"                                                       title="删除" alt="删除"/></a> | <a href="javascript:void(0);"                                                                                      onclick="window.top.artiframe('{pigcms{:U('Store/brand_edit', array('id' => $brand['brand_id']))}','修改品牌 - {pigcms{$brand.name}',480,<if condition="$brand['pic']">390                            <else/>                            310            </if>            ,true,false,false,editbtn,'edit',true);"><img src="{pigcms{$static_path}images/icon_edit.png" width="18"                                                          title="修改" alt="修改"/></a></td>            <td>{pigcms{$brand.brand_id}</td>            <td><a href="javascript:void(0);"                   onclick="window.top.artiframe('{pigcms{:U('Store/brand_edit', array('id' => $category['cat_id']))}','修改品牌 - {pigcms{$brand.name}',480,<if condition="$brand['pic']">390                <else/>                310</if>,true,false,false,editbtn,'edit',true);"> <span                    style="font-weight: bold;">{pigcms{$brand.name}</span></a></td>            <td>{pigcms{$brand.desc}</td>            <td>                <if condition="$brand['status'] eq 1"><span class="green">启用</span>                    <else/>                    <span class="red">禁用</span></if>            </td>            <td>{pigcms{$brand.order_by}</td>            <td>                <span class="cb-enable status-enable"><label class="cb-enable <if condition="$brand['status'] eq 1">selected</if>                    " data-id="<?php echo $brand['brand_id']; ?>"><span>启用</span><input type="radio" name="status"                                                                                        value="1" <if                        condition="$brand['brand_id'] eq 1">checked="checked"                    </if> /></label></span>                <span class="cb-disable status-disable"><label class="cb-disable <if condition="$brand['status'] eq 0">selected</if>                    " data-id="<?php echo $brand['brand_id']; ?>"><span>禁用</span><input type="radio" name="status"                                                                                        value="0" <if                        condition="$brand['brand_id'] eq 0">checked="checked"                    </if>/></label></span>            </td>            </tr>            </volist>            </if>            </tbody>            <tfoot>            <if condition="is_array($brands)">                <tr>                    <td class="textcenter pagebar" colspan="7">{pigcms{$page}</td>                </tr>                <else/>                <tr>                    <td class="textcenter red" colspan="7">列表为空！</td>                </tr>            </if>            </tfoot>        </table>    </div></div><include file="Public:footer"/>