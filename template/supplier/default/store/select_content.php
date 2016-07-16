<style type="text/css">
	.alert .close {
		position: relative;
		top: -2px;
		right: -21px;
		line-height: 20px;
	}
</style>
<div>
	<div class="">
		<div class="company-pane">
			<div class="company-line">
				<div class="company">
					<?php /*if($company) { ?>
						<div class="row">
							<div class="company-line-name"><?php echo !empty($company['name']) ? $company['name'] : ''; ?></div>
							<div class="company-line-address">
								<span class="company-line-address-label">地址：</span>
								<span class="company-line-address-info"><?php echo $company['province']; ?>，<?php if(!empty($company['city'])) { ?><?php echo $company['city']; ?>，<?php } ?><?php if(!empty($company['area'])) { ?><?php echo $company['area']; ?>，<?php } ?><?php echo $company['address']; ?></span>
							</div>
							<a href="<?php dourl('account:company'); ?>" class="edit-company">设置</a>
						</div>
					<?php }*/ ?>
					<div class="team-select-pane clearfix">
						<?php
						foreach ($store_return['store_list'] as $store) {
							?>
							<div data-status="<?php echo $store['status'] ?>" class="team-icon mis <?php if($store['status'] !=
								1
							) {
								echo 'store-closed';
							}
							else {
//								if(!empty($store['drp_approve'])) {
//									echo 'drp-approve';
//								}
//								else {
//									echo 'drp-unapprove';
//								}
							} ?>" store-id="<?php echo $store['store_id']; ?>">
								<?php
								if($store['status'] == 4) {
									?>
									<a href="javascript:;" class="open-team" store-id="<?php echo $store['store_id']; ?>"><span>开启</span></a>
									<?php
								}
								else if($store['status'] == 1) {
									if(!empty($store['drp_approve'])) {
										?>
										<?php
										if($store['template_cat_id'] && $store['template_id']) {
											?>
											<a href="<?php dourl('setting:store',
												array('id' => $store['store_id'])); ?>" class="edit-team" store-id="<?php echo $store['store_id']; ?>"><span>修改</span></a>
											<?php
										}
										else {
											?>
											<a href="<?php dourl('store:select_template',
												array('store_id'    => $store['store_id'],
												      'cat_id'      => $cate_info['cat_id'] + 1,
												      'new_page_id' => $new_wei_page_list[$store['store_id']]['page_id'],
												      'page_id'     => $new_wei_page_list[$store['store_id']]['page_id'])); ?>" class="edit-team" store-id="<?php echo $store['store_id']; ?>"><span>修改</span></a>
											<?php
										}
										?>
										<a href="javascript:;" class="delete-team" store-id="<?php echo $store['store_id']; ?>"><span>删除</span></a>
										<?php
									}
								}
								?>
								<div class="team-name-wrap ">
									<div class="team-name" <?php if($store['status'] !=
									1) { ?>style="color: #666" <?php } ?>><?php echo $store['name']; ?></div>
								</div>
								<div class="weixin team-desc">
									<?php
									if($store['status'] == 4) {
										echo '状态：已关闭';
									}
									else if($store['status'] == '2') {
										echo '等待审核';
									}
									else if($store['status'] == '3') {
										echo '审核未通过';
									}
									else if($store['status'] == '5') {
										echo '供货商关闭';
									}
									else if($store['status'] == '0') {
										echo '状态：待审核';
									}
									else {
										if($store['drp_approve']) {
											echo '状态：已认证';
//											if($_SESSION['sync_store']) {
//												echo '状态：正常';
//											}
//											else {
//												echo '微信：';
//												if($store['open_weixin']) {
//													echo '已开通';
//												}
//												else {
//													echo '未开通';
//												}
//											}
										}
										else {
											echo '状态：未认证';
										}
									}
									?>
								</div>
							</div>
							<?php
						}
						if($create_store_status) {
							?>
							<div class="team-icon new-team" onclick="window.location.href='<?php dourl('create'); ?>'">+创建店铺</div>
							<?php
						}
						?>
					</div>
				</div>
			</div>
		</div>
		<div class="js-has-company">
			<div class="js-page-list pagenavi"><?php echo $store_return['page']; ?></div>
		</div>
	</div>
</div>