<?php
if ( ! defined( 'ABSPATH' ) ) exit;
global $current_user, $wpdb;
$wphrmUserRole = implode(',', $current_user->roles);
$wphrmGeneralSettingsInfo = $this->WPHRMGetSettings('wphrmGeneralSettingsInfo');
$wphrmSalarySlipInfo = $this->WPHRMGetSettings('wphrmSalarySlipInfo');
$wphrmNotificationsSettingsInfo = $this->WPHRMGetSettings('wphrmNotificationsSettingsInfo');
$wphrmUserPermissionInfo = $this->WPHRMGetSettings('wphrmUserPermissionInfo');
$wphrmExpenseReportInfo = $this->WPHRMGetSettings('wphrmExpenseReportInfo');
$wphrmEarningInfo = '';
$wphrmDeductionInfo = '';
$wphrm_messages_General_Settings = $this->WPHRMGetMessage(27);
$wphrm_messages_notification_settings = $this->WPHRMGetMessage(28);
$wphrm_messages_changepassword_settings = $this->WPHRMGetMessage(29);
$wphrm_messages_salary_slip_setting = $this->WPHRMGetMessage(30);
$wphrm_messages_user_permissions_settings = $this->WPHRMGetMessage(31);
$wphrm_messages_settings = $this->WPHRMGetMessage(34);
$wphrmExpenseReportAmount = $this->WPHRMGetMessage(35);
$wphrmMessagesAddEarnings = $this->WPHRMGetMessage(38);
$wphrmMessagesUpdateEarnings = $this->WPHRMGetMessage(39);
$wphrmMessagesAddDeductions = $this->WPHRMGetMessage(40);
$wphrmMessagesUpdateDeductions = $this->WPHRMGetMessage(41);
$wphrmRemoveLebal = $this->WPHRMGetMessage(42);
?>

<!-- BEGIN PAGE HEADER-->
<div class="preloader">
    <span class="preloader-custom-gif"></span>
</div>
<input type="hidden" id="image_url" value="<?php echo esc_attr(plugins_url('assets/images/Remove.png', __FILE__)) ?>">

<div id="deleteModal" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="alert alert-success display-hide" id="WPHRMCustomDelete_success"><i class='fa fa-check-square' aria-hidden='true'></i> <?php echo esc_html($wphrmRemoveLebal); ?>
                <button class="close" data-close="alert"></button>
            </div>
            <div class="alert alert-danger display-hide" id="WPHRMCustomDelete_error">
                <button class="close" data-close="alert"></button>
            </div>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-close"></i></button>
                <h4 class="modal-title"><?php _e('Confirmation', 'wphrm'); ?></h4>
            </div>
            <div class="modal-body" id="info"><p></p></div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn red" id="delete"><i class="fa fa-trash"></i><?php _e('Delete', 'wphrm'); ?></button>
                <button type="button" data-dismiss="modal" aria-hidden="true" class="btn default"><i class="fa fa-times"></i><?php _e('Cancel', 'wphrm'); ?></button>
            </div>
        </div>
    </div>
</div>

<div class="col-md-12">
    <h3 class="page-title"><?php _e('Settings', 'wphrm'); ?></h3>
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li><i class="fa fa-home"></i><?php _e('Home', 'wphrm'); ?><i class="fa fa-angle-right"></i></li>
            <li><?php _e('Settings', 'wphrm'); ?></li>
        </ul>
    </div>
    <div class="row ">
        <div class="col-md-6 col-sm-6">
            <div class="portlet box blue">
                <div class="portlet-title">
                    <div class="caption"><i class="fa fa-cog fa-fw"></i><?php _e('General Settings', 'wphrm'); ?></div>
                    <div class="actions">
                        <a href="javascript:;"  onclick="jQuery('#wphrmGeneralSettingsInfo_form').submit();" data-loading-text="Updating..." class="demo-loading-btn btn btn-sm btn-default ">
                            <i class="fa fa-save"></i><?php _e('Save', 'wphrm'); ?></a>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="alert alert-success display-hide" id="general_settings_success"><i class='fa fa-check-square' aria-hidden='true'></i> <?php echo esc_html($wphrm_messages_General_Settings); ?>
                        <button class="close" data-close="alert"></button>
                    </div>
                    <div class="alert alert-danger display-hide" id="general_settings_error">
                        <button class="close" data-close="alert"></button>
                    </div>
                    <form method="POST"  accept-charset="UTF-8" class="form-horizontal" id="wphrmGeneralSettingsInfo_form" enctype="multipart/form-data">
                        <div class="form-body">
                            <div class="form-group ">
                                <label class="control-label col-md-4"><?php _e('Company Logo', 'wphrm'); ?></label>
                                <div class="col-md-8">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-new thumbnail" style="width: auto; height: 150px;">
                                            <?php if (isset($wphrmGeneralSettingsInfo['wphrm_company_logo']) && $wphrmGeneralSettingsInfo['wphrm_company_logo'] == '') { ?>
                                                <img src="<?php echo esc_attr(plugins_url('assets/images/logo.png', __FILE__)); ?>"  height="150"/>
                                            <?php } else { ?>
                                                <img src="<?php
                                                if (isset($wphrmGeneralSettingsInfo['wphrm_company_logo'])) : echo esc_attr($wphrmGeneralSettingsInfo['wphrm_company_logo']);
                                                endif;
                                                ?>"  height="150"/>
                                                 <?php } ?>
                                        </div>
                                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;">
                                        </div>
                                        <div>
                                            <span class="btn default btn-file">
                                                <span class="fileinput-new"><?php _e('Select Image', 'wphrm'); ?> </span>
                                                <span class="fileinput-exists"><?php _e('Change', 'wphmr'); ?> </span>
                                                <input type="file" name="wphrm_company_logo" id="wphrm_company_logo" />
                                            </span>
                                            <a href="#" class="btn red fileinput-exists" data-dismiss="fileinput"><?php _e('Remove', 'wphrm'); ?></a>
                                        </div><br>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group ">
                                <div class="col-md-4">
                                </div>
                                <div class="col-md-1"><span class="label label-danger span-padding"><?php _e('NOTE', 'wphrm'); ?> !</span></div> <div class="col-md-6 notice-info"><?php _e("Only 'jpeg', 'jpg', 'png' filetypes are allowed and size should be 117px*30px.", 'wphrm'); ?></div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label"><?php _e('Company Full Name', 'wphrm'); ?><span class="required"></span></label>
                                <div class="col-md-8">
                                    <input class="form-control" name="wphrm_company_full_name" type="text" id="wphrm_company_full_name" value="<?php
                                    if (isset($wphrmGeneralSettingsInfo['wphrm_company_full_name'])) : echo esc_attr($wphrmGeneralSettingsInfo['wphrm_company_full_name']);
                                    endif;
                                    ?>" autocapitalize="none"  />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label"><?php _e('Email', 'wphrm'); ?><span class="required"></span></label>
                                <div class="col-md-8">
                                    <input class="form-control"  name="wphrm_company_email" type="text" id="wphrm_company_email" value="<?php
                                    if (isset($wphrmGeneralSettingsInfo['wphrm_company_email'])) : echo esc_attr($wphrmGeneralSettingsInfo['wphrm_company_email']);
                                    endif;
                                    ?>" autocapitalize="none"  />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label"><?php _e('Phone', 'wphrm'); ?><span class="required"></span></label>
                                <div class="col-md-8">
                                    <input class="form-control"  name="wphrm_company_phone" type="text" id="wphrm_company_phone" value="<?php
                                    if (isset($wphrmGeneralSettingsInfo['wphrm_company_phone'])) : echo esc_attr($wphrmGeneralSettingsInfo['wphrm_company_phone']);
                                    endif;
                                    ?>" autocapitalize="none"  />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label"><?php _e('Address', 'wphrm'); ?></label>
                                <div class="col-md-8">
                                    <textarea class="form-control" name="wphrm_company_address" rows="2" id="wphrm_company_address"><?php
                                        if (isset($wphrmGeneralSettingsInfo['wphrm_company_address'])) : echo esc_textarea($wphrmGeneralSettingsInfo['wphrm_company_address']);
                                        endif;
                                        ?></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4"><?php _e('Currency', 'wphrm'); ?></label>
                                <div class="col-md-8">
                                    <select class="bs-select form-control" data-show-subtext="true" name="wphrm_currency" id="wphrm_currency">
                                        <?php
                                        $currency_symbols = $this->currency_symbols;
                                        foreach ($currency_symbols as $key => $currency) {
                                            $selected_currency = explode('-', $wphrmGeneralSettingsInfo['wphrm_currency']);
                                            $match_currency = $selected_currency[1];
                                            ?>
                                            <option value="<?php echo esc_attr($currency) . '-' . esc_attr($key); ?>" <?php
                                            if ($key == $match_currency) {
                                                echo esc_attr(' selected="selected"');
                                            }
                                            ?>><?php echo esc_html($currency) . ' -  ' . esc_html($key); ?></option>
                                                <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="portlet box blue">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-bell"></i> <?php _e('Notifications Settings', 'wphrm'); ?>
                    </div>
                    <div class="actions">
                        <div class="customTooltip-wrap">
                    <span class="demo-loading-btn btn btn-sm btn-default" disabled><i class="fa fa-download" ></i><?php _e('Save', 'wphrm'); ?></span>
                    <div class="customTooltip nt-left-top nt-small">
                        <?php _e(' You can avail this feature in WPHRM<br><a href="?page=wphrm-pro-version">Pro version.</a>', 'wphrm'); ?>
                    </div>
                </div>
                       
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="alert alert-success display-hide" id="wphrm_notifications_settings_success"><i class='fa fa-check-square' aria-hidden='true'></i> <?php echo esc_html($wphrm_messages_notification_settings); ?>
                        <button class="close" data-close="alert"></button>
                    </div>
                    <div class="alert alert-danger display-hide" id="wphrm_notifications_settings_error">
                        <button class="close" data-close="alert"></button>
                    </div>
                    <form method="POST"  accept-charset="UTF-8" class="form-horizontal" id="wphrmNotificationsSettingsInfo_form">   
                        <div id="alert_bank"></div>
                        <div class="form-body">
                            <div class="form-group">
                                <label class="col-md-4 control-label"><?php _e('Notice Board', 'wphrm'); ?>:</label>
                                <div class="col-md-8">
                                    <input  type="checkbox" value="1"   class="make-switch" name="wphrm_notice_notification" <?php
                                    if (isset($wphrmNotificationsSettingsInfo['wphrm_notice_notification']) && $wphrmNotificationsSettingsInfo['wphrm_notice_notification'] == '1') : echo esc_attr('checked');
                                    endif;
                                    ?> data-on-color="success" data-on-text="<?php _e('Yes', 'wphrm'); ?>" data-off-text="<?php _e('No', 'wphrm'); ?>" data-off-color="danger">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label"><?php _e('Leave Application', 'wphrm'); ?>:</label>
                                <div class="col-md-8">
                                    <input  type="checkbox" value="1"   class="make-switch" name="wphrm_leave_notification" <?php
                                    if (isset($wphrmNotificationsSettingsInfo['wphrm_leave_notification']) && $wphrmNotificationsSettingsInfo['wphrm_leave_notification'] == '1') : echo esc_attr('checked');
                                    endif;
                                    ?> data-on-color="success" data-on-text="<?php _e('Yes', 'wphrm'); ?>" data-off-text="<?php _e('No', 'wphrm'); ?>" data-off-color="danger">
                                </div>
                            </div>                           
                    </form>
                </div>
            </div>
        </div>
        <div class="portlet box blue">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-cog fa-fw"></i><?php _e('Salary Slip Fields Settings', 'wphrm'); ?>                    
                </div>
                <div class="actions">
                   <div class="customTooltip-wrap">
                    <span class="demo-loading-btn btn btn-sm btn-default" disabled><i class="fa fa-download" ></i><?php _e('Save', 'wphrm'); ?></span>
                    <div class="customTooltip nt-left-top nt-small">
                        <?php _e(' You can avail this feature in WPHRM <br><a href="?page=wphrm-pro-version">Pro version.</a>', 'wphrm'); ?>
                    </div>
                </div>
                </div>
            </div>
            <div class="portlet-body">
                <div class="alert alert-success display-hide" id="wphrmsalaryslipfield_success"><i class='fa fa-check-square' aria-hidden='true'></i> <?php echo esc_html($wphrmMessagesUpdateEarnings); ?>
                    <button class="close" data-close="alert"></button>
                </div>
                <div class="alert alert-danger display-hide" id="wphrmsalaryslipfield_error">
                    <button class="close" data-close="alert"></button>
                </div>
                <form method="POST"  accept-charset="UTF-8" class="form-horizontal" id="wphrmSalarySlipFieldsInfoForm" enctype="multipart/form-data">
                    <div class="form-body">
                        <h3 class="page-title" style="text-align: center;">  <?php _e('Earnings', 'wphrm'); ?> </h3>
                        <?php
                        $wphrmEarningInfo = $this->WPHRMGetSettings('wphrmEarningInfo');
                        if (!empty($wphrmEarningInfo)) {
                            $i = 1;
                            foreach ($wphrmEarningInfo['earningLebal'] as $wphrmEarningsettingInfo) {
                                ?>
                                <div class="form-group  <?php echo 'removefiled' . esc_attr($i) . 'earningLebal'; ?>">
                                    <div class="col-md-8">
                                        <input class="form-control form-control-inline" name="earninglebal[]" id="earninglebal" value="<?php
                                        if (isset($wphrmEarningsettingInfo)): echo trim(esc_attr($wphrmEarningsettingInfo));
                                        endif;
                                        ?>"  placeholder="<?php _e('Earming Label', 'wphmr'); ?>"/>
                                    </div>
                                    <div class="col-md-2">
                                        <a   onclick="deleteEarningAndDedutions('<?php echo esc_js($i) . 'earningLebal'; ?>');" data-loading-text="Updating..."  class="btn red">
                                            <i class='fa fa-trash' aria-hidden='true'></i></a>

                                    </div>
                                </div>

                                <?php
                                $i++;
                            }
                        }
                        ?>
                        <div id="earninglebalinsertBefore"></div>                             
                        <button type="button" class="btn btn-sm green form-control-inline" id="addearninglebal" style="text-align: center;">
                            <i class="fa fa-plus"></i><?php _e('Add More Earnings', 'wphrm'); ?>
                        </button>
                    </div>
                    <div class="form-body">
                        <h3 class="page-title" style="text-align: center;">  <?php _e('Deductions', 'wphrm'); ?> </h3>
                        <?php
                        $wphrmDeductionInfo = $this->WPHRMGetSettings('wphrmDeductionInfo');
                        if (!empty($wphrmDeductionInfo) && isset($wphrmDeductionInfo['deductionlebal']) && !empty($wphrmDeductionInfo['deductionlebal'])) {
                            $i = 1;
                            foreach ($wphrmDeductionInfo['deductionlebal'] as $wphrmDedutionsettingInfo) {
                                ?>                         
                                <div class="form-group <?php echo 'removefiled' . esc_attr($i) . 'deductionlebal'; ?>">
                                    <div class="col-md-8">
                                        <input class="form-control form-control-inline" name="deductionlebal[]" id="deductionlebal" value="<?php
                                        if (isset($wphrmDedutionsettingInfo)): echo trim(esc_attr($wphrmDedutionsettingInfo));
                                        endif;
                                        ?>"  placeholder="Earming Label"/>
                                    </div>
                                    <div class="col-md-2">
                                        <a   onclick="deleteEarningAndDedutions('<?php echo esc_js($i) . 'deductionlebal'; ?>');" data-loading-text="Updating..."  class="btn red">
                                            <i class='fa fa-trash' aria-hidden='true'></i></a>

                                    </div>
                                </div>

                                <?php
                                $i++;
                            }
                        }
                        ?>
                        <div id="deductionlebalinsertBefore"></div>                             
                        <button type="button" class="btn btn-sm green form-control-inline" id="adddeductionlebal" style="text-align: center;">
                            <i class="fa fa-plus"></i><?php _e('Add More Deductions', 'wphrm'); ?>
                        </button>                      
                    </div>
                </form>
            </div>
        </div>            
        <div class="portlet box blue">
            <div class="portlet-title">
                <div class="caption"><i class="fa fa-cog fa-fw"></i><?php _e('Salary Details Fields Settings', 'wphrm'); ?></div>
                <div class="actions">
                  <div class="customTooltip-wrap">
                    <span class="demo-loading-btn btn btn-sm btn-default" disabled><i class="fa fa-download" ></i><?php _e('Save', 'wphrm'); ?></span>
                    <div class="customTooltip nt-left-top nt-small">
                        <?php _e(' You can avail this feature in WPHRM <br><a href="?page=wphrm-pro-version">Pro version.</a>', 'wphrm'); ?>
                    </div>
                </div>
                </div>
            </div>
            <div class="portlet-body">
                <div class="alert alert-success display-hide" id="wphrmsalaryfield_success"><i class='fa fa-check-square' aria-hidden='true'></i> <?php echo esc_html($wphrmMessagesUpdateEarnings); ?>
                    <button class="close" data-close="alert"></button>
                </div>
                <div class="alert alert-danger display-hide" id="wphrmsalaryfield_error">
                    <button class="close" data-close="alert"></button>
                </div>
                <form method="POST"  accept-charset="UTF-8" class="form-horizontal" id="wphrmSalaryDetailsFieldsSettingsForm" enctype="multipart/form-data">
                    <div class="form-body">
                        <h3 class="page-title" style="text-align: center;"> <?php _e('Salary Fields', 'wphrm'); ?></h3>
                        <?php
                        $wphrmsalarydetailfieldskeyInfo = $this->WPHRMGetSettings('salarydetailfieldskey');
                        if (!empty($wphrmsalarydetailfieldskeyInfo)) {
                            $i = 1;
                            foreach ($wphrmsalarydetailfieldskeyInfo['salarydetailfieldlabel'] as $wphrmsalarydetailSettings) {
                                ?>                      
                                <div class="form-group <?php echo 'removefiled' . esc_attr($i) . 'salarydetailfieldlabel'; ?>">
                                    <div class="col-md-8">
                                        <input class="form-control form-control-inline" name="salary-fields-lebal[]" id="salary-fields-lebal" value="<?php
                                        if (isset($wphrmsalarydetailSettings)): echo trim(esc_attr($wphrmsalarydetailSettings));
                                        endif;
                                        ?>"  placeholder="Salary Field Label', 'wphrm'); ?>"/>
                                    </div>
                                    <div class="col-md-2">
                                        <a   onclick="deleteEarningAndDedutions('<?php echo esc_js($i) . 'salarydetailfieldlabel'; ?>');" data-loading-text="Updating..." class="btn red">
                                            <i class='fa fa-trash' aria-hidden='true'></i></a>                                        
                                    </div>
                                </div>
                                <?php
                                $i++;
                            }
                        }
                        ?>
                        <div id="salary-fields-lebal-Before"></div>                             
                        <button type="button" class="btn btn-sm green form-control-inline" id="add-salary-fields-lebal" style="text-align: center;">
                            <i class="fa fa-plus"></i><?php _e('Add More Salary Fields', 'wphrm'); ?>
                        </button>
                    </div>                 
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-sm-6">
        <div class="portlet box yellow">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-key"></i><?php _e('Change Password', 'wphrm'); ?>
                </div>
                <div class="actions">
                    <div class="customTooltip-wrap">
                    <span class="demo-loading-btn btn btn-sm btn-default" disabled><i class="fa fa-download" ></i><?php _e('Save', 'wphrm'); ?></span>
                    <div class="customTooltip customTooltip1 nt-left-top nt-small-left">
                        <?php _e(' You can avail this feature in WPHRM <br><a href="?page=wphrm-pro-version">Pro version.</a>', 'wphrm'); ?>
                    </div>
                </div>
                </div>
            </div>
            <div class="portlet-body">
                <div class="alert alert-success display-hide" id="wphrmChangePasswordInfo_success"><i class='fa fa-check-square' aria-hidden='true'></i> <?php echo esc_html($wphrm_messages_changepassword_settings); ?>
                    <button class="close" data-close="alert"></button>
                </div>
                <div class="alert alert-danger display-hide" id="wphrmChangePasswordInfo_error">
                    <button class="close" data-close="alert"></button>
                </div>
                <form method="POST"  accept-charset="UTF-8" class="form-horizontal" id="wphrmChangePasswordInfo_form">   
                    <div id="alert_bank"></div>
                    <div class="form-body">
                        <div class="form-group">
                            <label class="col-md-4 control-label"><?php _e('Current Password', 'wphrm'); ?></label>
                            <div class="col-md-8">
                                <input class="form-control" name="wphrm_current_password" type="password" id="wphrm_employee_bank_account_name" autocapitalize="none"  />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label"><?php _e('New Password', 'wphrm'); ?></label>
                            <div class="col-md-8">
                                <input class="form-control" name="wphrm_new_password" type="password" id="wphrm_new_password" autocapitalize="none"  />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label"><?php _e('Confirm Password', 'wphrm'); ?></label>
                            <div class="col-md-8">
                                <input class="form-control" name="wphrm_conform_password" type="password" id="wphrm_conform_password" autocapitalize="none"  />
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="portlet box yellow">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-money"></i><?php _e('Salary Slip Layout Settings', 'wphrm'); ?>
                </div>
                <div class="actions">
                  <div class="customTooltip-wrap">
                    <span class="demo-loading-btn btn btn-sm btn-default" disabled><i class="fa fa-download" ></i><?php _e('Save', 'wphrm'); ?></span>
                    <div class="customTooltip customTooltip1 nt-left-top nt-small-left">
                        <?php _e(' You can avail this feature in WPHRM <br><a href="?page=wphrm-pro-version">Pro version.</a>', 'wphrm'); ?>
                    </div>
                </div>
                </div>
            </div>
            <div class="portlet-body">
                <div class="alert alert-success display-hide" id="wphrmSalarySlipInfo_success"><i class='fa fa-check-square' aria-hidden='true'></i> <?php echo esc_html($wphrm_messages_salary_slip_setting); ?>
                    <button class="close" data-close="alert"></button>
                </div>
                <div class="alert alert-danger display-hide" id="wphrmSalarySlipInfo_error">
                    <button class="close" data-close="alert"></button>
                </div>
                <div class="form-body">
                    <form method="POST"  accept-charset="UTF-8" class="form-horizontal" id="wphrmSalarySlipInfo_form">   
                        <div class="form-group">
                            <label class="col-md-4 control-label"><?php _e('Header Logo Align', 'wphrm'); ?></label>
                            <div class="col-md-8">
                                <select class="bs-select form-control" data-show-subtext="true" name="wphrm_logo_align">
                                    <option value="">--<?php _e('Select', 'wphrm'); ?>--</option>
                                    <option <?php
                                    if (isset($wphrmSalarySlipInfo['wphrm_logo_align']) && $wphrmSalarySlipInfo['wphrm_logo_align'] == 'left') : echo esc_attr('selected = "selected"');
                                    endif;
                                    ?> value="left"><?php _e('Left', 'wphmr'); ?></option>
                                    <option <?php
                                    if (isset($wphrmSalarySlipInfo['wphrm_logo_align']) && $wphrmSalarySlipInfo['wphrm_logo_align'] == 'center') : echo esc_attr('selected = "selected"');
                                    endif;
                                    ?> value="center"><?php _e('Center', 'wphrm'); ?></option>
                                    <option <?php
                                    if (isset($wphrmSalarySlipInfo['wphrm_logo_align']) && $wphrmSalarySlipInfo['wphrm_logo_align'] == 'right') : echo esc_attr('selected = "selected"');
                                    endif;
                                    ?> value="right"><?php _e('Right', 'wphmr'); ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label"><?php _e('Content', 'wphrm'); ?></label>
                            <div class="col-md-8">
                                <textarea class="form-control" placeholder="<?php _e('Content', 'wphrm'); ?>" name="wphrm_slip_content" rows="2" id="wphrm_slip_content"><?php
                                    if (isset($wphrmSalarySlipInfo['wphrm_slip_content'])) : echo esc_textarea($wphrmSalarySlipInfo['wphrm_slip_content']);
                                    endif;
                                    ?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label"><?php _e('Footer Content Align', 'wphrm'); ?></label>
                            <div class="col-md-8">
                                <select class="bs-select form-control" data-show-subtext="true" name="wphrm_footer_content_align">
                                    <option value="">--<?php _e('Select', 'wphrm'); ?>--</option>
                                    <option <?php
                                    if (isset($wphrmSalarySlipInfo['wphrm_footer_content_align']) && $wphrmSalarySlipInfo['wphrm_footer_content_align'] == 'left') : echo esc_attr('selected = "selected"');
                                    endif;
                                    ?> value="left"><?php _e('Left', 'wphrm'); ?></option>
                                    <option <?php
                                    if (isset($wphrmSalarySlipInfo['wphrm_footer_content_align']) && $wphrmSalarySlipInfo['wphrm_footer_content_align'] == 'right') : echo esc_attr('selected = "selected"');
                                    endif;
                                    ?> value="right"><?php _e('Right', 'wphrm'); ?></option>
                                </select>
                            </div>
                        </div>
                        <!--   Default border color #CFD8DC-->
                        <div class="form-group">
                            <label class="col-md-4 control-label"><?php _e('Border Color', 'wphrm'); ?></label>
                            <div class="col-md-8">
                                <input class="form-control jscolor"    name="wphrm_border_color" type="text" id="wphrm_border_color" value="<?php
                                if (isset($wphrmSalarySlipInfo['wphrm_border_color'])) : echo esc_attr($wphrmSalarySlipInfo['wphrm_border_color']);
                                endif;
                                ?>" autocapitalize="none"  />
                            </div>
                        </div>
                        <!--  Default h1 color #ECEFF1-->
                        <div class="form-group">
                            <label class="col-md-4 control-label"><?php _e('H1 Background Color', 'wphrm'); ?></label>
                            <div class="col-md-8">
                                <input class="form-control jscolor"  name="wphrm_background_color" type="text" id="wphrm_background_color" value="<?php
                                if (isset($wphrmSalarySlipInfo['wphrm_background_color'])) : echo esc_attr($wphrmSalarySlipInfo['wphrm_background_color']);
                                endif;
                                ?>" autocapitalize="none"  />
                            </div>
                        </div>
                        <!--   Default font color #546E7A-->
                        <div class="form-group">
                            <label class="col-md-4 control-label"><?php _e('Font Color', 'wphrm'); ?></label>
                            <div class="col-md-8">
                                <input class="form-control jscolor"  name="wphrm_font_color" type="text" id="wphrm_font_color" value="<?php
                                if (isset($wphrmSalarySlipInfo['wphrm_font_color'])) : echo esc_attr($wphrmSalarySlipInfo['wphrm_font_color']);
                                endif;
                                ?>" autocapitalize="none"  />
                            </div>
                        </div>
                    </form>
                    <form id="salary_slip_settings_reset" class="search-form" method="post">
                        <input name="font_color" type="hidden"  id="font_color" value="546e7a">
                        <input name="logo_align" type="hidden"  id="logo_align" value="left">
                        <input name="footer_align" type="hidden"  id="footer_align" value="right">
                        <input name="border_color" type="hidden"  id="border_color" value="cfd8dc">
                        <input name="h1_color"  type="hidden"  id="h1_color" value="ECEFF1">
                        <div class="form-group " style=" text-align: center;">
                            <label class="col-md control-label"></label>
                            <div class="col-md">
                                <div class="customTooltip-wrap">
                     <span class="demo-loading-btn btn btn-sm yellow"><i class="fa fa-download" ></i><?php _e('Reset', 'wphrm'); ?></span>
                    <div class="customTooltip customTooltip1 nt-left-top nt-small-left">
                        <?php _e(' You can avail this feature in WPHRM <br><a href="?page=wphrm-pro-version">Pro version.</a>', 'wphrm'); ?>
                    </div>
                </div>
                               
                            </div>
                        </div>
                    </form> 
                </div>
            </div>
        </div>
        <div class="portlet box yellow">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-user"></i><?php _e('Users Permission Settings', 'wphrm'); ?>
                </div>
                <div class="actions">
                    <div class="customTooltip-wrap">
                    <span class="demo-loading-btn btn btn-sm btn-default" disabled><i class="fa fa-download" ></i><?php _e('Save', 'wphrm'); ?></span>
                    <div class="customTooltip customTooltip1 nt-left-top nt-small-left">
                        <?php _e(' You can avail this feature in WPHRM <br><a href="?page=wphrm-pro-version">Pro version.</a>', 'wphrm'); ?>
                    </div>
                </div>
                </div>
            </div>
            <div class="portlet-body">
                <div class="alert alert-success display-hide" id="wphrm_user_permission_success"><i class='fa fa-check-square' aria-hidden='true'></i> <?php echo esc_html($wphrm_messages_user_permissions_settings); ?>
                    <button class="close" data-close="alert"></button>
                </div>
                <div class="alert alert-danger display-hide" id="wphrm_user_permission_error">
                    <button class="close" data-close="alert"></button>
                </div>
                <form method="POST"  accept-charset="UTF-8" class="form-horizontal" id="wphrmUserPermissionInfo_frm">   
                    <div class="form-body">
                        <div class="form-group">
                            <label class="col-md-4 control-label"><?php _e('Employee', 'wphrm'); ?></label>
                            <div class="col-md-8">
                                <select class="bs-select form-control" data-show-subtext="true" name="wphrm_user_permission">
                                    <option value="">--<?php _e('Select', 'wphrm'); ?>--</option>
                                    <option <?php
                                    if (isset($wphrmUserPermissionInfo['wphrm_user_permission']) && $wphrmUserPermissionInfo['wphrm_user_permission'] == 'subscriber') : echo esc_attr('selected = "selected"');
                                    endif;
                                    ?> value="subscriber"><?php _e('Subscriber', 'wphrm'); ?></option>
                                    <option <?php
                                    if (isset($wphrmUserPermissionInfo['wphrm_user_permission']) && $wphrmUserPermissionInfo['wphrm_user_permission'] == 'editor') : echo esc_attr('selected = "selected"');
                                    endif;
                                    ?> value="editor"><?php _e('Editor', 'wphrm'); ?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="portlet box yellow">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-bar-chart"></i><?php _e('Set Expense Report Amount', 'wphrm'); ?>
                </div>
                <div class="actions">
                    <a href="javascript:;" onclick="jQuery('#wphrmExpenseReportInfo_frm').submit();" data-loading-text="Updating..."  class="demo-loading-btn btn btn-sm btn-default ">
                        <i class="fa fa-save" ></i><?php _e('Save', 'wphrm'); ?> </a>
                </div>
            </div>
            <div class="portlet-body">
                <div class="alert alert-success display-hide" id="wphrm_expense_report_success"><i class='fa fa-check-square' aria-hidden='true'></i> <?php echo esc_html($wphrmExpenseReportAmount); ?>
                    <button class="close" data-close="alert"></button>
                </div>
                <div class="alert alert-danger display-hide" id="wphrm_expense_report_error">
                    <button class="close" data-close="alert"></button>
                </div>
                <form method="POST"  accept-charset="UTF-8" class="form-horizontal" id="wphrmExpenseReportInfo_frm">   
                    <div class="form-body">
                        <div class="form-group">
                            <label class="col-md-4 control-label"><?php _e('Expense Amount', 'wphrm'); ?></label>
                            <div class="col-md-8">
                                <input class="form-control"  name="wphrm_expense_amount" type="text" id="wphrm_expense_amount" value="<?php
                                if (isset($wphrmExpenseReportInfo['wphrm_expense_amount'])) : echo esc_attr($wphrmExpenseReportInfo['wphrm_expense_amount']);
                                endif;
                                ?>" autocapitalize="none"  />
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="portlet box yellow">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-cog fa-fw"></i><?php _e('Bank Details Fields Settings', 'wphrm'); ?>                    
                </div>
                <div class="actions">
                    <div class="customTooltip-wrap">
                    <span class="demo-loading-btn btn btn-sm btn-default" disabled><i class="fa fa-download" ></i><?php _e('Save', 'wphrm'); ?></span>
                    <div class="customTooltip customTooltip1 nt-left-top nt-small-left">
                        <?php _e(' You can avail this feature in WPHRM <br><a href="?page=wphrm-pro-version">Pro version.</a>', 'wphrm'); ?>
                    </div>
                </div>
                </div>
            </div>
            <div class="portlet-body">
                <div class="alert alert-success display-hide" id="wphrmBankfield_success"><i class='fa fa-check-square' aria-hidden='true'></i> <?php echo esc_html($wphrmMessagesUpdateEarnings); ?>
                    <button class="close" data-close="alert"></button>
                </div>
                <div class="alert alert-danger display-hide" id="wphrmBankfield_error">
                    <button class="close" data-close="alert"></button>
                </div>
                <form method="POST"  accept-charset="UTF-8" class="form-horizontal" id="wphrmBankDetailsFieldsInfoForm" enctype="multipart/form-data">
                    <div class="form-body">
                        <h3 class="page-title" style="text-align: center;">  <?php _e('Bank Fields', 'wphrm'); ?> </h3>
                        <?php
                        $wphrmBankFieldsInfo = $this->WPHRMGetSettings('Bankfieldskey');
                        if (!empty($wphrmBankFieldsInfo)) {
                            $i = 1;
                            foreach ($wphrmBankFieldsInfo['Bankfieldslebal'] as $wphrmBankFieldsSettings) {
                                ?>                       
                                <div class="form-group <?php echo 'removefiled' . esc_attr($i) . 'Bankfieldslebal'; ?>">
                                    <div class="col-md-8">
                                        <input class="form-control form-control-inline" name="bank-fields-lebal[]" id="bank-fields-lebal" value="<?php
                                        if (isset($wphrmBankFieldsSettings)): echo trim(esc_attr($wphrmBankFieldsSettings));
                                        endif;
                                        ?>"  placeholder="<?php _e('Bank Field Label', 'wphrm'); ?>"/>
                                    </div>
                                    <div class="col-md-2">
                                        <a   onclick="deleteEarningAndDedutions('<?php echo esc_js($i) . 'Bankfieldslebal'; ?>');" data-loading-text="Updating..."  class="btn red">
                                            <i class='fa fa-trash' aria-hidden='true'></i></a>                                        
                                    </div>
                                </div>                                
                                <?php
                                $i++;
                            }
                        }
                        ?>
                        <div id="bank-fields-lebal-Before"></div>                             
                        <button type="button" class="btn btn-sm yellow form-control-inline" id="add-bank-fields-lebal" style="text-align: center;">
                            <i class="fa fa-plus"></i><?php _e('Add More Bank Fields', 'wphrm'); ?>
                        </button>
                    </div>                 
                </form>
            </div>
        </div>

        <div class="portlet box yellow">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-cog fa-fw"></i><?php _e('Other Details Fields Settings', 'wphrm'); ?>                    
                </div>
                <div class="actions">
                   <div class="customTooltip-wrap">
                    <span class="demo-loading-btn btn btn-sm btn-default" disabled><i class="fa fa-download" ></i><?php _e('Save', 'wphrm'); ?></span>
                    <div class="customTooltip customTooltip1 nt-left-top nt-small-left">
                        <?php _e(' You can avail this feature in WPHRM <br><a href="?page=wphrm-pro-version">Pro version.</a>', 'wphrm'); ?>
                    </div>
                </div>
                </div>
            </div>
            <div class="portlet-body">
                <div class="alert alert-success display-hide" id="wphrmotherfield_success"><i class='fa fa-check-square' aria-hidden='true'></i> <?php echo esc_html($wphrmMessagesUpdateEarnings); ?>
                    <button class="close" data-close="alert"></button>
                </div>
                <div class="alert alert-danger display-hide" id="wphrmotherfield_error">
                    <button class="close" data-close="alert"></button>
                </div>
                <form method="POST"  accept-charset="UTF-8" class="form-horizontal" id="wphrmotherDetailsFieldsInfoForm" enctype="multipart/form-data">
                    <div class="form-body">
                        <h3 class="page-title" style="text-align: center;">  <?php _e('Other Details Fields', 'wphrm'); ?> </h3>
                        <?php
                        $otherfieldskeyInfo = $this->WPHRMGetSettings('Otherfieldskey');
                        if (!empty($otherfieldskeyInfo)) {
                            $i = 1;
                            foreach ($otherfieldskeyInfo['Otherfieldslebal'] as $otherfieldsSettings) {
                                ?>                       
                                <div class="form-group <?php echo 'removefiled' . esc_attr($i) . 'otherfieldslebal'; ?>">
                                    <div class="col-md-8">
                                        <input class="form-control form-control-inline" name="other-fields-lebal[]" id="other-fields-lebal" value="<?php
                                        if (isset($otherfieldsSettings)): echo trim(esc_attr($otherfieldsSettings));
                                        endif;
                                        ?>"  placeholder="<?php _e('Other Field Label', 'wphrm'); ?>"/>
                                    </div>
                                    <div class="col-md-2">
                                        <a   onclick="deleteEarningAndDedutions('<?php echo esc_js($i) . 'otherfieldslebal'; ?>');" data-loading-text="Updating..."  class="btn red">
                                            <i class='fa fa-trash' aria-hidden='true'></i></a>                                        
                                    </div>
                                </div>

                                <?php
                                $i++;
                            }
                        }
                        ?>
                        <div id="other-fields-lebal-Before"></div>                             
                        <button type="button" class="btn btn-sm yellow form-control-inline" id="add-other-fields-lebal" style="text-align: center;">
                            <i class="fa fa-plus"></i><?php _e('Add More Other Fields', 'wphrm'); ?>
                        </button>
                    </div>

                </form>
            </div>
        </div>
        
          <div class="portlet box yellow">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-clock-o"></i><?php _e('Salary By Day/Hourly Settings', 'wphrm'); ?>                    
                </div>
                <div class="actions">
                     <div class="customTooltip-wrap">
                    <span class="demo-loading-btn btn btn-sm btn-default" disabled><i class="fa fa-download" ></i><?php _e('Save', 'wphrm'); ?></span>
                    <div class="customTooltip customTooltip1 nt-left-top nt-small-left">
                        <?php _e(' You can avail this feature in WPHRM <br><a href="?page=wphrm-pro-version">Pro version.</a>', 'wphrm'); ?>
                    </div>
                </div>
                </div>
            </div>
               <div class="portlet-body">
                <div class="alert alert-success display-hide" id="wphrsalaryDayOrHourlySuccess"><i class='fa fa-check-square' aria-hidden='true'></i> <?php echo esc_html($wphrm_messages_salary_slip_setting); ?> 
                    <button class="close" data-close="alert"></button>
                </div>
                <div class="alert alert-danger display-hide" id="wphrsalaryDayOrHourlyError">
                    <button class="close" data-close="alert"></button>
                </div>
                <form method="POST"  accept-charset="UTF-8" class="form-horizontal" id="wphrsalaryDayOrHourlyForm">   
                    <div class="form-body">
                        <div class="form-group">
                            <label class="col-md-4 control-label"><?php _e('Based on', 'wphrm'); ?></label>
                            <div class="col-md-8">
                                <div class="radio-list" data-error-container="#form_2_membership_error">
                                    <input  style="margin: 9px 4px 10px;" name="wphrm-according" type="radio" <?php
                                                    if (isset($wphrmSalaryAccording['wphrm-according']) && $wphrmSalaryAccording['wphrm-according'] == 'Day') : echo esc_attr('checked');
                                                    endif;
                                                    ?> id="wphrm-according" value="Day" checked>&nbsp;<?php _e('Days', 'wphrm'); ?> &nbsp;&nbsp;&nbsp;&nbsp;
                                                    <input style="margin: 9px 4px 10px;" name="wphrm-according" type="radio" id="wphrm-according" <?php
                                                    if (isset($wphrmSalaryAccording['wphrm-according']) && $wphrmSalaryAccording['wphrm-according'] == 'Hourly') : echo esc_attr('checked');
                                                    endif;
                                                    ?> value="Hourly" >&nbsp;<?php _e('Hours', 'wphrm'); ?>
                            </div></div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="row ">
    <div class="col-md-12 col-sm-12">
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-envelope"></i><?php _e('Messages Settings', 'wphrm'); ?>
                </div>
            </div>
            <div class="portlet-body">
                <div class="form-body">
                    <table class="table table-striped table-bordered table-hover" id="wphrmDataTable">
                        <thead>
                            <tr> <th><?php _e('S.No', 'wphrm'); ?></th>
                                <th><?php _e('Message titles', 'wphrm'); ?></th>
                                <th><?php _e('Message Descriptions', 'wphrm'); ?></th>
                                <th><?php _e('Action', 'wphrm'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            $wphrm_all_messages = $wpdb->get_results("SELECT * FROM $this->WphrmMessagesTable ORDER BY id ASC");
                            foreach ($wphrm_all_messages as $key => $wphrm_all_message) {
                                ?>
                                <tr>
                                    <td><?php echo esc_html($i); ?></td>
                                    <td> <?php
                                        if (isset($wphrm_all_message->messagesTitle)) : echo esc_html($wphrm_all_message->messagesTitle);
                                        endif;
                                        ?> </td>
                                    <td> <?php
                                        if (isset($wphrm_all_message->messagesDesc)) : echo esc_html($wphrm_all_message->messagesDesc);
                                        endif;
                                        ?> </td>
                                    <td>
                                        <a class="btn purple" data-toggle="modal" href="#edit_message" onclick="edit_messages(<?php
                                        if (isset($wphrm_all_message->id)) : echo esc_js($wphrm_all_message->id);
                                        endif;
                                        ?>, '<?php
                                        if (isset($wphrm_all_message->messagesTitle)) : echo trim(esc_js($wphrm_all_message->messagesTitle));
                                        endif;
                                        ?>', '<?php
                                        if (isset($wphrm_all_message->messagesDesc)) : echo trim(esc_js($wphrm_all_message->messagesDesc));
                                        endif;
                                        ?>')"> <i class="fa fa-edit"></i><?php _e('View', 'wphrm'); ?>/<?php _e('Edit', 'wphrm'); ?> </a>
                                    </td>
                                </tr>
                                <?php
                                $i++;
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div></div></div>
<script>
    jQuery(function (argument) {
        jQuery('[type="checkbox"]').bootstrapSwitch();
    });
    
(function() {
                jQuery('.customTooltip-wrap').customTooltip();
            })();
</script>