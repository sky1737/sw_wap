<div><div class="page-settlement">
        <div class="ui-box applyWithdrawal-region">
            <div class="header">申请提现</div>

            <div class="form-horizontal">
                <fieldset>
                    <div class="control-group">
                        <label class="control-label">可提现金额：</label>
                        <div class="controls">
                            <span class="money"><?php echo $balance; ?></span>
                            <span class="unit">元</span>
                        </div>
                    </div>
                    <div class="control-group bank-group">
                        <label class="control-label"><em class="required">*</em>选择提现银行：</label>
                        <div class="controls js-bank-list-region">
                            <ul>
                                <?php if (!empty($card)) { ?>
                                <li class="bank active" data-id="<?php echo $card['card_id']; ?>">
                                    <span class="bank_name"><?php echo $card['bank']['name']; ?> - <?php echo $card['bank_name']; ?></span>
                                    <div class="dropdown hover dropdown-right">
                                        <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
                                        <span class="txt">管理</span>
                                            <i class="caret"></i>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li><a href="#income_setting" class="js-edit">编辑</a></li>
                                            <!--<li><a href="javascript:;" class="js-delete">删除</a></li>-->
                                        </ul>
                                    </div>
                                    <span class="c-gray account_name"><?php echo $card['card_user']; ?>（****<?php echo substr($card['card_no'], -4); ?>）</span>
                                </li>
                                <?php } ?>
                            </ul>
                        </div>
                        <?php if (empty($card['bank_id'])) { ?>
                        <div class="controls" style="padding-top: 5px;"><a href="#income_setting" class="js-add-bankcard">添加银行卡</a></div>
                        <?php } ?>
                    </div>
                    <div class="control-group">
                        <label class="control-label"><em class="required">*</em>提现金额：</label>
                        <div class="controls">
                            <input class="js-money" type="text" data-balance="<?php echo $balance; ?>" name="money" placeholder="最多可输入<?php echo $balance; ?>">&nbsp;&nbsp;元
                        </div>
                    </div>
                    <div class="control-group period-group">
                        <label class="control-label">提现审核周期：</label>
                        <div class="controls">
						<span>个人认证店铺为3个工作日</span>
                        </div>
                    </div>
                </fieldset>
                <div class="form-actions">
                    <button class="btn btn-primary js-submit" data-loading-text="提现中...">确认提现</button>
                </div>
            </div>
        </div>
    </div></div>