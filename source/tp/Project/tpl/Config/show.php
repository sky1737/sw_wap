<include file="Public:header"/>
<form id="myform" method="post" frame="true" refresh="true">
    <table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
        <tr>
            <th width="80">URL</th>
            <td>{pigcms{:U('Weixin/index','',false,false,true)}</td>
        </tr>
        <tr>
            <th width="80">Token</th>
            <td>{pigcms{$config['wechat_token']}</td>
        </tr>
    </table>
</form>
<include file="Public:footer"/>