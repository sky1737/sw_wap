<div>
	<div class="page-settlement">
		<div class="ui-box settlement-info">
			<div class="account-info"> <img src="<?php echo $user_session['avatar']; ?>" class="logo" />
				<div class="account-info-meta">
					<div class="info-item">
						<label>用户昵称：</label>
						<span><?php echo $user_session['nickname']; ?></span>
					</div>
					<div class="info-item">
						<label>收款账户：</label>
						<span><?php echo empty($card) ? '' : $card['card_user']; ?></span>
					</div>
					<div class="info-item">
						<label>银行卡号：</label>
						<span><?php echo empty($card) ? '未填写' : $card['card_no']; ?></span>
						<?php if (!empty($card)) { ?>
						<a href="javascript:;" class="js-setup-account">修改提现账号</a>
						<?php } ?>
					</div>
				</div>
			</div>
			<div class="balance">
				<div class="balance-info" style="border-left: none;">
					<div class="balance-title">7天收入<span>（截至昨日）</span></div>
					<div class="balance-content"> <span class="money"><?php echo $day_7_income; ?></span> <span class="unit">元</span> <a href="#income_details" class="pull-right inoutdetail">收支明细</a> </div>
				</div>
				<div class="balance-info">
					<div class="balance-title">7天积分<span>（截至昨日）</span></div>
					<div class="balance-content"> <span class="money"><?php echo $day_7_point; ?></span> <span class="unit"></span> </div>
				</div>
				<div class="balance-info">
					<div class="balance-title">账户余额 </div>
					<div class="balance-content"> <span class="money"><?php echo $user_session['balance']; ?></span> <span class="unit">元</span> 
						<!--<a href="javascript:;" class="ui-btn ui-btn-primary pull-right ui-btn-disabled js-goto" data-goto="renzheng" disabled="disabled">提现</a>--> 
						<a href="<?php
                        if ($user_session['balance'] <= 0) {
                        	echo 'javascript:;';
                        } else {
                        	echo '#'.(empty($user_session['bank_no'])?'income_setting':'income_apply');
                        } ?>" class="ui-btn ui-btn-primary pull-right <?php echo $user_session['balance'] <= 0 ? 'ui-btn-disabled' : 'js-goto'; ?>" data-goto="renzheng">提现</a> </div>
				</div>
				<div style="clear: both"></div>
			</div>
		</div>
		
		<!--<div class="ui-box ui-title">
            <h3>支付方式</h3>
            <a href="#">设置</a>
        </div>

        <div class="ui-box">
            <ul class="pay-mode-list clearfix">
                <li class="pay-mode-item">
                    <div class="pay-mode-icon pay-mode-icon-wxpay"></div>
                    <div class="pay-mode-meta">
                        <h4 class="pay-mode-name">
                            <i class="pay-mode-enable"></i>
                            微信支付 - 代销
                        </h4>

                        <p class="pay-mode-description">
                            微店不收取任何交易、提现手续费（微信支付产生的交易手续费由微店全部补贴）
                        </p>
                    </div>
                </li>
                <li class="pay-mode-item">
                    <div class="pay-mode-icon pay-mode-icon-unionpay"></div>
                    <div class="pay-mode-meta">
                        <h4 class="pay-mode-name">
                            <i class="pay-mode-enable"></i>
                            银行卡支付 - 联动U付
                        </h4>

                        <p class="pay-mode-description">
                            微店不收取任何交易、提现手续费（银行扣取的交易手续费，由微店全额补贴）
                        </p>
                    </div>
                </li>
                <li class="pay-mode-item">
                    <div class="pay-mode-icon pay-mode-icon-bdpay"></div>
                    <div class="pay-mode-meta">
                        <h4 class="pay-mode-name">
                            <i class="pay-mode-enable"></i>
                            银行卡支付 - 百度钱包
                        </h4>

                        <p class="pay-mode-description">
                            微店不收取任何交易、提现手续费（银行扣取的交易手续费，由微店全额补贴）
                        </p>
                    </div>
                </li>
                <li class="pay-mode-item">
                    <div class="pay-mode-icon pay-mode-icon-alipay"></div>
                    <div class="pay-mode-meta">
                        <h4 class="pay-mode-name">
                            <i class="pay-mode-disable"></i>
                            支付宝支付
                        </h4>

                        <p class="pay-mode-description">
                            支付宝实时扣除每笔交易手续费2.5%～2%，每次提现支付宝将扣除0.5%（单笔最低手续费1元，最高25元）
                        </p>
                    </div>
                </li>
            </ul>
        </div>--> 
		
	</div>
</div>
