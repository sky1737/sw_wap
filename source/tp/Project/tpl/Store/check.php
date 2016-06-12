<include file="Public:header"/>
<style type="text/css">
    table, th, td {
        font: 400 12px/18px "microsoft yahei", Arial;
        color: #434343;
    }

    .ui-box {
        margin-bottom: 15px;
    }

    table {
        border-collapse: collapse;
        border-spacing: 0;
    }

    .ui-table {
        width: 100%;
        font-size: 12px;
        text-align: left;
        margin-bottom: 0;
        border: 1px solid #e5e5e5;
    }

    .ui-table.ui-table-list {
        border: none;
    }

    thead {
        display: table-header-group;
        vertical-align: middle;
        border-color: inherit;
    }

    caption, th, td {
        font-weight: normal;
        vertical-align: middle;
    }

    .ui-table th {
        background: #f8f8f8;
    }

    .ui-table th, .ui-table td {
        padding: 10px;
        border-bottom: 1px solid #e5e5e5;
        vertical-align: top;
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
        line-height: 18px;
    }

    .ui-table th.ui-table-text-right, .ui-table th.text-right, .ui-table th.ui-table-opts, .ui-table td.ui-table-text-right, .ui-table td.text-right, .ui-table td.ui-table-opts {
        text-align: right;
        padding-right: 8px;
        padding-left: 0px;
    }

    .ui-table tbody tr:last-of-type {
        border-bottom: none;
    }

    .c-gray {
        color: #999;
    }

    .ui-money, .ui-money-income, .ui-money-outlay {
        font-weight: bold;
        color: #333;
    }

    .ui-money-income {
        color: #55BD47;
    }

    .ui-money-outlay {
        color: #f00;
    }
</style>
<div class="ui-box">


    <div class="mainnav_title" id="nav">
        <a onclick="javascript:slidowns(1)"
        <if condition="$is_check neq '2'"> class="on"</if>
        href="{pigcms{:U('Store/check', array('id' => $store_id))}">未对账订单列表</a>|

        <a onclick="javascript:slidowns(2)"
        <if condition="$is_check eq '2'">class="on"</if>
        href="{pigcms{:U('Store/check', array('id' => $store_id,'types' => 'checked'))}">已对账订单列表</a>
    </div>


    <table class="ui-table ui-table-list" style="padding: 0px;">
        <thead class="js-list-header-region tableFloatingHeaderOriginal">
        <tr class="widget-list-header">
            <th class="cell-15">订单id</th>
            <th class="cell-20">订单no</th>
            <th class="cell-10 text-right">未对账金额(元)</th>
            <th class="cell-10 text-right">
                <if condition="$is_check eq '2'">已</if>
                提成比例
            </th>
            <th class="cell-10 text-right">应对账金额(元)</th>
            <th class="cell-10 text-right">可分润</th>
            <th class="cell-25">当前状态</th>
            <th class="cell-25">操作</th>
        </tr>
        </thead>
        <tbody class="js-list-body-region">
        <if condition="$orders neq ''">
            <volist name="orders" id="order">
                <tr class="widget-list-item">
                    <td><!--pigcms{$record['add_time']|date='Y-m-d H:i:s', ###-->{pigcms{$order['order_id']}</td>
                    <td>
                        <span>{pigcms{$order['order_no']}</span>
                        <br>
                        <span class="c-gray"><!--pigcms{$record['order_no']--></span>
                    </td>
                    <td class="text-right ui-money ui-money-income"> ￥{pigcms{$order['un_check_account']}元</td>
                    <td class="text-right ui-money ui-money-outlay">
                        <if condition="$is_check eq '2'">{pigcms{$order['sales_ratio']}
                            <else/>
                            {pigcms{$info['sys_sales_ratio']}
                        </if>
                    </td>
                    <td class="text-right">￥{pigcms{$order['should_check_account']}元</td>
                    <td class="text-right">￥{pigcms{$order['un_check_account']-$order['should_check_account']}元</td>
                    <td class="text-right qrcz_status">
                        <if condition="$order.is_check eq 2">
                            <font class="date-quick-pick">已对账</font>
                            <else/>
                            未对账
                        </if>

                    </td>
                    <td class="zt">
                        <if condition="$is_check eq '1'">
                            <input class="button qrcz" type="button" value="对账" store_id="{pigcms{$order['store_id']}"
                                   data-order-id="{pigcms{$order['order_id']}"
                                   data-order-no="{pigcms{$order['order_no']}"><br>
                            <else/>
                            已对账
                        </if>
                    </td>
                </tr>


            </volist>
            <tr>
                <td colspan="8" align="right" class="pagebar">{pigcms{$page}</td>
            </tr>
            <else/>
            <td colspan="8" align="center" class="red">没有记录</td>
        </if>
        </tbody>
    </table>


    <div>

        <if condition="$orders neq ''">
            <if condition="$is_check eq '1'">
                <table class="ui-table">
                    <tr>
                        <td class="pagebar" colspan="6">本页对账情况</td>

                    </tr>
                    <tr>
                        <td>本页合计未对账金额：</td>
                        <td>￥{pigcms{$info['page_uncheck_account']}元</td>
                        <td>本店合计应对账金额：</td>
                        <td>￥{pigcms{$info['page_should_check_account']}元</td>
                        <td>提成比例：</td>
                        <td>{pigcms{$info['sys_sales_ratio']}</td>
                    </tr>

                    <tr>
                        <td>本页可分润：</td>
                        <td>￥{pigcms{$info['page_liyun_account']}元</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </table>
            </if>
        </if>


        <if condition="$orders neq ''">
            <if condition="$is_check eq '2'">

                <table class="ui-table">
                    <tr>
                        <td class="pagebar" colspan="6">本页<font class="ui-money-income" style="font-weight:700">已</font>对账情况
                        </td>
                    </tr>
                    <tr>
                        <td>本页合计已对账金额：</td>
                        <td>￥{pigcms{$info['page_uncheck_account']}元</td>
                        <td>本店合计应对账金额：</td>
                        <td>￥{pigcms{$info['had_page_should_check_account']}元</td>
                        <td>现本页可分润：</td>
                        <td>￥{pigcms{$info['had_page_liyun_account']}元</td>
                    </tr>
                </table>

                <table class="ui-table">
                    <tr>
                        <td class="pagebar" colspan="6">按系统现：<font class="ui-money-outlay">{pigcms{$info['sys_sales_ratio']}</font>
                            提成标准：本页现对账情况
                        </td>
                    </tr>
                    <tr>
                        <td>本页合计已对账金额：</td>
                        <td>￥{pigcms{$info['page_uncheck_account']}元</td>
                        <td>本店合计现应对账金额：</td>
                        <td>￥{pigcms{$info['page_should_check_account']}元</td>
                        <td>现系统提成比例：</td>
                        <td>{pigcms{$info['sys_sales_ratio']}</td>
                    </tr>

                    <tr>

                        <td>现本页可分润：</td>
                        <td>￥{pigcms{$info['page_liyun_account']}元</td>
                        <td></td>
                        <td></td>
                    </tr>
                </table>
            </if>
        </if>
    </div>


</div>
<script>
    $(function () {
        var check_url = "<?php echo U('Store/check'); ?>";
        $(".qrcz").live("click", function () {
            var order_id = $(this).attr("data-order-id");
            var order_no = $(this).attr("data-order-no");
            var store_id = $(this).attr("store_id");

            var index = $(".qrcz").index($(this));
            if (!order_id || !order_no || !store_id) {
                alert('缺失参数,请联系系统管理员！');
                return false;
            }

            $(this).removeClass("qrcz");
            $(this).attr("disabled", true);
            $(".zt").eq(index).html("<font color='#c00'>正在操作中...！</font>");


            if (window.confirm('此操作不可逆，确认已出账么？')) {
                $.post(
                    check_url,
                    {'is_check': 2, 'order_id': order_id, 'order_no': order_no, 'store_id': store_id},
                    function (datas) {
                        if (datas.error == '0') {
                            $(".zt").eq(index).html("<font class='qrcz' color='#c00'>" + datas.message + "</font>");
                            $(".qrcz_status").eq(index).html("<font color='#c00'>" + datas.message + "</font>");


                            $(".zt").eq(index).closest("tr").css("background", "#e5e5e5")
                            $(".aui_close").click();
                        } else {
                            $(".zt").eq(index).html("<font class='qrcz' color='#c00'>已对账记录失败</font>");
                            $(".zt").eq(index).closest("tr").css("background", "#e5e5e5")
                            $(".aui_close").click();
                        }
                    },
                    'json'
                )
            }
        })

    })
</script>
<include file="Public:footer"/>