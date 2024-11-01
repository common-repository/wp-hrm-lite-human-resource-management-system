<?php
if ( ! defined( 'ABSPATH' ) ) exit;
global $current_user, $wpdb;
$wphrmUserRole = implode(',', $current_user->roles);
$readonly_class = '';
$readonly = 'readonly';
$edit_mode = false;

    if (isset($_REQUEST['employee_id']) && $_REQUEST['employee_id'] != '') {
    $wphrm_employee_edit_id = $_REQUEST['employee_id'];
} else {
    if (isset($wphrmUserRole) && $wphrmUserRole != 'administrator') {
        $wphrm_employee_edit_id = $current_user->ID;
    }
}
    
    $wphrmEmployeeBasicInfo = $this->WPHRMGetUserDatas($wphrm_employee_edit_id, 'wphrmEmployeeInfo');
    $wphrmEmployeeDocumentsInfo = $this->WPHRMGetUserDatas($wphrm_employee_edit_id, 'wphrmEmployeeDocumentInfo');
    
    $resumeDir = '';
    if (isset($wphrmEmployeeDocumentsInfo['resume'] ) && $wphrmEmployeeDocumentsInfo['resume'] != '') {
        $rdirs = explode('/', $wphrmEmployeeDocumentsInfo['resume']);
        $resumeDir = $rdirs[count($rdirs) - 1];
    }
    $offerDir = '';
    if (isset($wphrmEmployeeDocumentsInfo['offerLetter'] ) && $wphrmEmployeeDocumentsInfo['offerLetter'] != '') {
        $rdirs = explode('/', $wphrmEmployeeDocumentsInfo['offerLetter']);
        $offerDir = $rdirs[count($rdirs) - 1];
    }
    $joiningDir = '';
    if (isset($wphrmEmployeeDocumentsInfo['joiningLetter'] ) && $wphrmEmployeeDocumentsInfo['joiningLetter'] != '') {
        $rdirs = explode('/', $wphrmEmployeeDocumentsInfo['joiningLetter']);
        $joiningDir = $rdirs[count($rdirs) - 1];
    }
    $contractDir = '';
    if (isset($wphrmEmployeeDocumentsInfo['contract'] ) && $wphrmEmployeeDocumentsInfo['contract'] != '') {
        $rdirs = explode('/', $wphrmEmployeeDocumentsInfo['contract']);
        $contractDir = $rdirs[count($rdirs) - 1];
    }
    $idProofDir = '';
    if (isset($wphrmEmployeeDocumentsInfo['IDProof'] ) && $wphrmEmployeeDocumentsInfo['IDProof'] != '') {
        $rdirs = explode('/', $wphrmEmployeeDocumentsInfo['IDProof']);
        $idProofDir = $rdirs[count($rdirs) - 1];
    }

    $wphrmEmployeeSalaryInfo = $this->WPHRMGetUserDatas($wphrm_employee_edit_id, 'wphrmEmployeeSalaryInfo');
    $wphrmEmployeeBankInfo = $this->WPHRMGetUserDatas($wphrm_employee_edit_id, 'wphrmEmployeeBankInfo');
    $wphrmEmployeeOtherInfo = $this->WPHRMGetUserDatas($wphrm_employee_edit_id, 'wphrmEmployeeOtherInfo');
    $wphrmEmployeeFirstName = get_user_meta($wphrm_employee_edit_id, 'first_name', true);
    $wphrmEmployeeLastName = get_user_meta($wphrm_employee_edit_id, 'last_name', true);

?>
<div class="preloader">
<span class="preloader-custom-gif"></span>
</div>
<div style="padding-left: 0px; padding-right:20px; padding-top:0px;" class="col-md-12">
    <!-- BEGIN PAGE HEADER-->
    <h3 class="page-title"><?php _e('View Employee Informations', 'wphrm'); ?></h3>
    <!-- END PAGE HEADER-->
    <div class="row">
        <div class="col-md-12">
            <div class="page-bar">
                <ul class="page-breadcrumb">
                    <li> <i class="fa fa-home"></i> <?php _e('Home', 'wphrm'); ?> <i class="fa fa-angle-right"></i> </li>
                    <li> <?php _e('Employee', 'wphrm'); ?> </li>
                    <li> <i class="fa fa-angle-double-right"></i><strong><?php echo esc_html($wphrmEmployeeFirstName).' '.esc_html($wphrmEmployeeLastName); ?></strong></li>
                </ul>
            </div>
            <?php if (isset($wphrmUserRole) && $wphrmUserRole == 'administrator') { ?>
                <a class="btn green " href="?page=wphrm-employees"><i class="fa fa-arrow-left"></i><?php _e('Back', 'wphrm'); ?> </a>
               <?php if (isset($_REQUEST['page']) && $_REQUEST['page'] != 'wphrm-employee-info') { ?>
                <a class="btn green " href="?page=wphrm-employee-info&employee_id=<?php  echo esc_html($wphrm_employee_edit_id); ?>"><i class="fa fa-edit"></i><?php _e('Edit', 'wphrm'); ?> </a>
               <?php } else { ?>
                <a class="btn green " href="?page=wphrm-employee-view-details&employee_id=<?php echo esc_html($wphrm_employee_edit_id); ?>"><i class="fa fa-eye"></i><?php _e('View', 'wphrm'); ?> </a>
            <?php } } ?>
            <div class="row ">
                <div class="col-md-6 col-sm-6">
                    <div class="portlet box purple-wisteria">
                        <div class="portlet-title">
                            <div class="caption"> <i class="fa fa-edit"></i><?php _e('Personal Details ', 'wphrm'); ?></div>
                        </div>
                        <div class="portlet-body">
                            <div class="alert alert-success display-hide" id="personal_details_success">
                                <button class="close" data-close="alert"></button>
                            </div>
                            <div class="alert alert-danger display-hide" id="error">
                                <button class="close" data-close="alert"></button>
                            </div>
                            <form method="POST"  accept-charset="UTF-8" class="form-horizontal" id="wphrm_employee_basic_info_form" enctype="multipart/form-data">
                                <input type="hidden" name="wphrm_employee_id" id="wphrm_employee_id"  value="<?php
                                if (isset($wphrm_employee_edit_id)) : echo esc_attr($wphrm_employee_edit_id);
                                endif;
                                ?> "/>
                                <div class="form-body">
                                    <div class="form-group ">
                                        <label class="control-label col-md-4"><?php _e('Photo', 'wphrm'); ?></label>
                                        <div class="col-md-8">
                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                <div class="fileinput-new thumbnail" style="width: 200px; height: auto;">
                                                    <?php if (isset($wphrmEmployeeBasicInfo['employee_profile']) && $wphrmEmployeeBasicInfo['employee_profile'] != '') { ?>
                                                        <img src="<?php if (isset($wphrmEmployeeBasicInfo['employee_profile'])) : echo esc_attr($wphrmEmployeeBasicInfo['employee_profile']);
                                                    endif; ?>" width="200"><br>
                                                        <?php
                                                    }else {
                                                        if (isset($wphrmEmployeeBasicInfo['wphrm_employee_gender']) && $wphrmEmployeeBasicInfo['wphrm_employee_gender'] == 'Male') {
                                                            ?>
                                                            <img src="<?php echo esc_attr(plugins_url('assets/images/default-male.jpeg', __FILE__)); ?>" width="200">
                                                        <?php } else {
                                                            ?>
                                                            <img src="<?php echo esc_attr(plugins_url('assets/images/default-female.jpeg', __FILE__)); ?>" width="200">  
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label"><?php _e('First Name', 'wphrm'); ?><span class="required"></span></label>
                                        <div class="col-md-8">
                                            <input readonly class="form-control"  name="wphrm_employee_fname" type="text" id="wphrm_employee_fname" value="<?php
                                                   if (isset($wphrmEmployeeFirstName)) : echo esc_attr($wphrmEmployeeFirstName); endif; ?>" autocapitalize="none"  />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label"><?php _e('Last Name', 'wphrm'); ?></label>
                                        <div class="col-md-8">
                                            <input readonly class="form-control" name="wphrm_employee_lname" type="text" id="wphrm_employee_lname" value="<?php
                                                   if (isset($wphrmEmployeeLastName)) : echo esc_attr($wphrmEmployeeLastName);
                                                   endif;
                                                   ?>" autocapitalize="none"  />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label"><?php _e('Father Name', 'wphrm'); ?></label>
                                        <div class="col-md-8">
                                            <input readonly class="form-control" name="wphrm_employee_fathername" type="text" id="wphrm_employee_fathername" value="<?php
                                                   if (isset($wphrmEmployeeBasicInfo['wphrm_employee_fathername'])) : echo esc_attr($wphrmEmployeeBasicInfo['wphrm_employee_fathername']);
                                                   endif;
                                                   ?>" autocapitalize="none"  />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label"><?php _e('Email', 'wphrm'); ?></label>
                                        <div class="col-md-8">
                                            <input readonly class="form-control" name="wphrm_employee_email" type="text" id="wphrm_employee_email" value="<?php
                                                   if (isset($wphrmEmployeeBasicInfo['wphrm_employee_email'])) : echo esc_attr($wphrmEmployeeBasicInfo['wphrm_employee_email']);
                                                   endif;
                                                   ?>" autocapitalize="none"  />   
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label"><?php _e('Phone Number', 'wphrm'); ?></label>
                                        <div class="col-md-8">
                                            <input  class="form-control" readonly name="wphrm_employee_phone" type="text" id="wphrm_employee_phone" value="<?php
                                                    if (isset($wphrmEmployeeBasicInfo['wphrm_employee_phone'])) : echo esc_attr($wphrmEmployeeBasicInfo['wphrm_employee_phone']);
                                                    endif;
                                                    ?>" autocapitalize="none" autocorrect="off" maxlength="10" />     
                                        </div>
                                    </div>
                                     <div class="form-group">
                                            <label class="control-label col-md-4"><?php _e('Date of Birth', 'wphrm'); ?></label>
                                            <div class="col-md-8">
                                                <div class="input-group input-medium date"  data-date-format="dd-mm-yyyy" data-date-viewmode="years">
                                                    <input readonly="" class="form-control date-pickers"   type="text"  value="<?php
                                                    if (isset($wphrmEmployeeBasicInfo['wphrm_employee_bod'])) : echo  esc_attr($wphrmEmployeeBasicInfo['wphrm_employee_bod']);
                                                    endif;
                                                    ?>" autocapitalize="none"  />
                                                    <span class="input-group-btn">
                                                        <button class="btn default-date" type="button"><i class="fa fa-calendar" style="line-height: 1.9;"></i></button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label"><?php _e('Local Address', 'wphrm'); ?></label>
                                        <div class="col-md-8">
                                            <textarea readonly rows="3" class="form-control"   name="wphrm_employee_local_address" type="text" id="wphrm_employee_local_address" value="" autocapitalize="none" autocorrect="off"><?php
                                                if (isset($wphrmEmployeeBasicInfo['wphrm_employee_local_address'])) : echo esc_textarea($wphrmEmployeeBasicInfo['wphrm_employee_local_address']);
                                                endif;
                                                ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label"><?php _e('Permanent Address', 'wphrm'); ?></label>
                                        <div class="col-md-8">
                                            <textarea readonly rows="3" class="form-control"  name="wphrm_employee_permanant_address" type="text" id="wphrm_employee_permanant_address" value="" autocapitalize="none" autocorrect="off" ><?php
                                                if (isset($wphrmEmployeeBasicInfo['wphrm_employee_permanant_address'])) : echo esc_textarea($wphrmEmployeeBasicInfo['wphrm_employee_permanant_address']);
                                                endif;
                                                ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="portlet box purple-wisteria">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="fa fa-file-image-o"></i><?php _e('Documents', 'wphrm'); ?>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <div class="portlet-body">
                                <div class="alert alert-success display-hide" id="employee_document">
                                    <button class="close" data-close="alert"></button>
                                </div>
                                <div class="alert alert-danger display-hide" id="employee_document_error">
                                    <button class="close" data-close="alert"></button>
                                </div>
                                <form method="POST"  accept-charset="UTF-8" class="form-horizontal" id="wphrmEmployeeDocumentInfo_form" enctype="multipart/form-data"><input name="_method" type="hidden" value="PATCH"><input name="_token" type="hidden" value="CKw97QC4WEEKjxHdCpA3oZBiucWKYo0778rEpuPz">
                                    <input type="hidden" name="wphrm_employee_id" id="wphrm_employee_id"  value="<?php
                                           if (isset($wphrm_employee_edit_id)) : echo esc_attr($wphrm_employee_edit_id);
                                           endif;
                                           ?> "/>
                                    <div class="form-body">
                                       <div class="form-group">
                                                <label class="control-label col-md-3"><?php _e('Resume', 'wphrm'); ?></label>
                                                <div class="col-md-6">
                                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                                        <div class="input-group input-large" >
                                                            <div readonly  class="form-control uneditable-input" data-trigger="fileinput">
                                                                <i class="fa fa-file fileinput-exists"></i>&nbsp; <span class="fileinput-filename">
                                                                    <?php
                                                                    if (isset($resumeDir) && $resumeDir !='') : $resumeExt = pathinfo($resumeDir, PATHINFO_EXTENSION);
                                                                       echo esc_html(mb_strimwidth($resumeDir , 0, 10).'....'.$resumeExt);
                                                                    endif;
                                                                    ?>
                                                                </span>
                                                            </div>
                                                           
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                               <div class="col-md-3">
                                                    <?php  if (isset($wphrmEmployeeDocumentsInfo['resume']) && $wphrmEmployeeDocumentsInfo['resume'] != ''){ ?>
                                                         <a class="btn blue" target="blank" href="<?php  if (isset($wphrmEmployeeDocumentsInfo['resume'])) : echo esc_html($wphrmEmployeeDocumentsInfo['resume']); endif; ?>"><i class="fa fa-eye"></i><?php _e('View', 'wphrm'); ?></a>
                                                    <?php  }  ?>     
                                               </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3"><?php _e('Offer Letter', 'wphrm'); ?></label>
                                                <div class="col-md-6">
                                                    <div  class="fileinput fileinput-new" data-provides="fileinput">
                                                        <div  class="input-group input-large">
                                                            <div readonly class="form-control uneditable-input" data-trigger="fileinput">
                                                                <i class="fa fa-file fileinput-exists"></i>&nbsp; <span class="fileinput-filename">
                                                                    <?php
                                                                   if (isset($offerDir) && $offerDir !='') : 
                                                                     $offerExt = pathinfo($offerDir, PATHINFO_EXTENSION);
                                                                echo esc_html(mb_strimwidth($offerDir , 0, 10).'....'.$offerExt);
                                                                    endif;
                                                                    ?>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                               <div class="col-md-3">
                                                    <?php  if (isset($wphrmEmployeeDocumentsInfo['offerLetter']) && $wphrmEmployeeDocumentsInfo['offerLetter'] != ''){ ?>
                                                         <a class="btn blue" target="blank" href="<?php  if (isset($wphrmEmployeeDocumentsInfo['offerLetter'])) : echo esc_html($wphrmEmployeeDocumentsInfo['offerLetter']); endif; ?>"><i class="fa fa-eye"></i><?php _e('View', 'wphrm'); ?></a>
                                                         <?php  }  ?>   
                                                 </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3"><?php _e('Joining Letter', 'wphrm'); ?></label>
                                                <div class="col-md-6">
                                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                                        <div class="input-group input-large">
                                                            <div readonly class="form-control uneditable-input" data-trigger="fileinput">
                                                                <i class="fa fa-file fileinput-exists"></i>&nbsp; <span class="fileinput-filename">
                                                                    <?php
                                                                     if (isset($joiningDir) && $joiningDir !='') : 
                                                                         $joiningDirExt = pathinfo($joiningDir, PATHINFO_EXTENSION);
                                                                echo esc_html(mb_strimwidth($joiningDir , 0, 10).'....'.$joiningDirExt);
                                                                    endif;
                                                                    ?>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                               <div class="col-md-3">
                                                    <?php  if (isset($wphrmEmployeeDocumentsInfo['joiningLetter']) && $wphrmEmployeeDocumentsInfo['joiningLetter'] != ''){ ?>
                                                         <a class="btn blue" target="blank" href="<?php  if (isset($wphrmEmployeeDocumentsInfo['joiningLetter'])) : echo esc_html($wphrmEmployeeDocumentsInfo['joiningLetter']); endif; ?>"><i class="fa fa-eye"></i><?php _e('View', 'wphrm'); ?></a>
                                                       <?php  }  ?>    
                                               </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3"><?php _e('Contract and Agreement', 'wphrm'); ?></label>
                                                <div class="col-md-6">
                                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                                        <div class="input-group input-large">
                                                            <div readonly class="form-control uneditable-input" data-trigger="fileinput">
                                                                <i class="fa fa-file fileinput-exists"></i>&nbsp; <span class="fileinput-filename"><?php
                                                                   if (isset($contractDir) && $contractDir !='') :
                                                                         $contractDirExt = pathinfo($contractDir, PATHINFO_EXTENSION);
                                                                echo esc_html(mb_strimwidth($contractDir , 0, 10).'....'.$contractDirExt);
                                                                    endif;
                                                                    ?>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                     <?php  if (isset($wphrmEmployeeDocumentsInfo['contract']) && $wphrmEmployeeDocumentsInfo['contract'] != ''){ ?>
                                                         <a class="btn blue" target="blank" href="<?php  if (isset($wphrmEmployeeDocumentsInfo['contract'])) : echo esc_html($wphrmEmployeeDocumentsInfo['contract']); endif; ?>"><i class="fa fa-eye"></i><?php _e('View', 'wphrm'); ?></a>
                                                     <?php  }  ?>  
                                                  </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3"><?php _e('ID Proof', 'wphrm'); ?></label>
                                                <div class="col-md-6">
                                                    <div disabled class="fileinput fileinput-new" data-provides="fileinput">
                                                        <div class="input-group input-large">
                                                            <div  readonly class="form-control uneditable-input" data-trigger="fileinput">
                                                                <i class="fa fa-file fileinput-exists"></i>&nbsp; <span class="fileinput-filename"><?php
                                                                    if (isset($idProofDir) && $idProofDir !='') :
                                                                         $idProofDirExt = pathinfo($idProofDir, PATHINFO_EXTENSION);
                                                                echo esc_html(mb_strimwidth($idProofDir , 0, 10).'....'.$idProofDirExt);
                                                                    endif;
                                                                    ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <?php  if (isset($wphrmEmployeeDocumentsInfo['IDProof']) && $wphrmEmployeeDocumentsInfo['IDProof'] != ''){ ?>
                                                    <a class="btn blue" target="blank" href="<?php  if (isset($wphrmEmployeeDocumentsInfo['IDProof'])) : echo esc_html($wphrmEmployeeDocumentsInfo['IDProof']); endif; ?>"><i class="fa fa-eye"></i><?php _e('View', 'wphrm'); ?></a>
                                                    <?php } ?></div>
                                           
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6">
                    <div class="portlet box red-sunglo">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="fa fa-bank"></i><?php _e('Bank Account Details', 'wphrm'); ?>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <div class="alert alert-success display-hide" id="wphrm_bank_details">
                                <button class="close" data-close="alert"></button>
                            </div>
                            <div class="alert alert-danger display-hide" id="wphrm_bank_details_error">
                                <button class="close" data-close="alert"></button>
                            </div>
                            <form method="POST"  accept-charset="UTF-8" class="form-horizontal" id="wphrmEmployeeBankInfo_form">   
                                <input type="hidden" name="wphrm_employee_id" id="wphrm_employee_id"  value="<?php
                                       if (isset($wphrm_employee_edit_id)) : echo esc_attr($wphrm_employee_edit_id);
                                       endif;
                                       ?> "/>
                                <div id="alert_bank"></div>
                                <div class="form-body">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label"><?php _e('Account Holder Name', 'wphrm'); ?></label>
                                        <div class="col-md-8">
                                            <input readonly class="form-control"  name="wphrm_employee_bank_account_name" type="text" id="wphrm_employee_bank_account_name" value="<?php
                                                   if (isset($wphrmEmployeeBankInfo['wphrm_employee_bank_account_name'])) : echo esc_attr($wphrmEmployeeBankInfo['wphrm_employee_bank_account_name']);
                                                   endif; ?>" autocapitalize="none"  />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label"><?php _e('Account Number', 'wphrm'); ?></label>
                                        <div class="col-md-8">
                                            <input readonly class="form-control"  name="wphrm_employee_bank_account_no" type="text" id="wphrm_employee_bank_account_no" value="<?php
                                                   if (isset($wphrmEmployeeBankInfo['wphrm_employee_bank_account_no'])) : echo esc_attr($wphrmEmployeeBankInfo['wphrm_employee_bank_account_no']);
                                                   endif; ?>" autocapitalize="none"  />
                                        </div>
                                    </div>
                                    <?php
                                        if (isset($wphrmEmployeeBankInfo['wphrmbankfieldslebal']) && $wphrmEmployeeBankInfo['wphrmbankfieldslebal'] != '' && isset($wphrmEmployeeBankInfo['wphrmbankfieldsvalue']) && $wphrmEmployeeBankInfo['wphrmbankfieldsvalue'] != '') {
                                            foreach ($wphrmEmployeeBankInfo['wphrmbankfieldslebal'] as $lebalkey => $wphrmEmployeeSettingsBank) {
                                                foreach ($wphrmEmployeeBankInfo['wphrmbankfieldsvalue'] as $valuekey => $wphrmEmployeeSettingsvalue) {
                                                    if ($lebalkey == $valuekey) { ?>
                                                        <div class="form-group">
                                                            <label class="col-md-4 control-label"><?php _e($wphrmEmployeeSettingsBank, 'wphrm'); ?></label>
                                                            <input readonly name="bank-fields-lebal[]" type="hidden" id="bank-fields-lebal" value="<?php
                                                            if (isset($wphrmEmployeeSettingsBank)) : echo esc_attr($wphrmEmployeeSettingsBank); endif; ?>"/>
                                                            <div class="col-md-8">
                                                                <input readonly class="form-control" name="bank-fields-value[]" type="text" id="bank-fields-lebal" value="<?php
                                                                if (isset($wphrmEmployeeSettingsvalue)) : echo esc_attr($wphrmEmployeeSettingsvalue); endif; ?>" autocapitalize="none"  />
                                                            </div>
                                                        </div> 
                                                    <?php }
                                                }
                                            }
                                            $wphrmBankFieldsInfo = $this->WPHRMGetSettings('Bankfieldskey');
                                            if (!empty($wphrmBankFieldsInfo)) {
                                                foreach ($wphrmBankFieldsInfo['Bankfieldslebal'] as $wphrmBankFieldsSettings) {
                                                    if (!in_array($wphrmBankFieldsSettings, $wphrmEmployeeBankInfo['wphrmbankfieldslebal'])) { ?>
                                                        <div class="form-group">
                                                            <label class="col-md-4 control-label"><?php _e($wphrmBankFieldsSettings, 'wphrm'); ?></label>
                                                            <input readonly name="bank-fields-lebal[]" type="hidden" id="bank-fields-lebal" value="<?php
                                                            if (isset($wphrmBankFieldsSettings)) : echo esc_attr($wphrmBankFieldsSettings); endif; ?>"/>
                                                            <div readonly class="col-md-8">
                                                                <input readonly class="form-control" name="bank-fields-value[]" type="text" id="bank-fields-lebal" value="" autocapitalize="none"  />
                                                            </div>
                                                        </div> 
                                                    <?php }
                                                }
                                            }
                                        }else {
                                            $wphrmBankfieldskeyInfo = $this->WPHRMGetSettings('Bankfieldskey');
                                            if (!empty($wphrmBankfieldskeyInfo)) {
                                                foreach ($wphrmBankfieldskeyInfo['Bankfieldslebal'] as $wphrmBanksettingInfo) { ?>
                                                    <div class="form-group">
                                                        <label class="col-md-4 control-label"><?php _e($wphrmBanksettingInfo, 'wphrm'); ?></label>
                                                        <input readonly name="bank-fields-lebal[]" type="hidden" id="bank-fields-lebal" value="<?php
                                                        if (isset($wphrmBanksettingInfo)) : echo esc_attr($wphrmBanksettingInfo); endif; ?>"/>
                                                        <div class="col-md-8">
                                                            <input readonly class="form-control" name="bank-fields-value[]" type="text" id="bank-fields-value" value="" autocapitalize="none"  />
                                                        </div>
                                                    </div> 
                                                <?php }
                                            }
                                        } ?>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="portlet box red-sunglo">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="fa fa-comment-o"></i><?php _e('Other Details', 'wphrm'); ?>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <div class="alert alert-success display-hide" id="other_details_success">
                                <button class="close" data-close="alert"></button>
                            </div>
                            <div class="alert alert-danger display-hide" id="other_details_success_error">
                                <button class="close" data-close="alert"></button>
                            </div>
                            <form method="POST"  accept-charset="UTF-8" class="form-horizontal" id="wphrmEmployeeOtherInfo_form">   
                                <input type="hidden" name="wphrm_employee_id" id="wphrm_employee_id"  value="<?php
                                       if (isset($wphrm_employee_edit_id)) : echo esc_attr($wphrm_employee_edit_id);
                                       endif;
                                       ?> "/>
                                <div id="alert_bank"></div>
                                <div class="form-body">
                                    <?php
                                    if (isset($wphrmUserRole) && $wphrmUserRole == 'administrator') {
                                        if (isset($wphrmEmployeeOtherInfo['wphrmotherfieldslebal']) && $wphrmEmployeeOtherInfo['wphrmotherfieldslebal'] != '' && isset($wphrmEmployeeOtherInfo['wphrmotherfieldsvalue']) && $wphrmEmployeeOtherInfo['wphrmotherfieldsvalue'] != '') {
                                            foreach ($wphrmEmployeeOtherInfo['wphrmotherfieldslebal'] as $lebalkey => $wphrmEmployeeSettingsOther) {
                                                foreach ($wphrmEmployeeOtherInfo['wphrmotherfieldsvalue'] as $valuekey => $wphrmOtherSettingsvalue) {
                                                    if ($lebalkey == $valuekey) {
                                                        ?>
                                                        <div class="form-group">
                                                            <label class="col-md-4 control-label"><?php _e($wphrmEmployeeSettingsOther, 'wphrm'); ?></label>
                                                            <input readonly name="other-fields-lebal[]" type="hidden" id="other-fields-lebal" value="<?php
                                                            if (isset($wphrmEmployeeSettingsOther)) : echo esc_attr($wphrmEmployeeSettingsOther);
                                                            endif;
                                                            ?>"/>
                                                            <div class="col-md-8">
                                                                <input readonly class="form-control" name="other-fields-value[]" type="text" id="other-fields-lebal" value="<?php
                                                                if (isset($wphrmOtherSettingsvalue)) : echo esc_attr($wphrmOtherSettingsvalue);
                                                                endif;
                                                                ?>" autocapitalize="none"  />
                                                            </div>
                                                        </div> 
                                                        <?php
                                                    }
                                                }
                                            }
                                            $wphrmOtherFieldsInfo = $this->WPHRMGetSettings('Otherfieldskey');
                                            if (!empty($wphrmOtherFieldsInfo)) {
                                                foreach ($wphrmOtherFieldsInfo['Otherfieldslebal'] as $wphrmOtherFieldsSettings) {
                                                    if (!in_array($wphrmOtherFieldsSettings, $wphrmEmployeeOtherInfo['wphrmotherfieldslebal'])) {
                                                        ?>
                                                        <div class="form-group">
                                                            <label class="col-md-4 control-label"><?php _e($wphrmOtherFieldsSettings, 'wphrm'); ?></label>
                                                            <input readonly name="other-fields-lebal[]" type="hidden" id="other-fields-lebal" value="<?php
                                                            if (isset($wphrmOtherFieldsSettings)) : echo esc_attr($wphrmOtherFieldsSettings);
                                                            endif;
                                                            ?>"/>
                                                            <div class="col-md-8">
                                                                <input readonly class="form-control" name="other-fields-value[]" type="text" id="other-fields-lebal" value="" autocapitalize="none"  />
                                                            </div>
                                                        </div> 
                                                        <?php
                                                    }
                                                }
                                            }
                                        }else {
                                            $wphrmOtherfieldskeyInfo = $this->WPHRMGetSettings('Otherfieldskey');
                                            if (!empty($wphrmOtherfieldskeyInfo)) {
                                                foreach ($wphrmOtherfieldskeyInfo['Otherfieldslebal'] as $wphrmOthersettingInfo) {
                                                    ?>
                                                    <div class="form-group">
                                                        <label class="col-md-4 control-label"><?php _e($wphrmOthersettingInfo, 'wphrm'); ?></label>
                                                        <input readonly name="other-fields-lebal[]" type="hidden" id="other-fields-lebal" value="<?php
                                                        if (isset($wphrmOthersettingInfo)) : echo esc_attr($wphrmOthersettingInfo);
                                                        endif;
                                                        ?>"/>
                                                        <div class="col-md-8">
                                                            <input readonly class="form-control" name="other-fields-value[]" type="text" id="other-fields-value" value="" autocapitalize="none"  />
                                                        </div>
                                                    </div> 
                                                    <?php
                                                }
                                            }
                                        }
                                        ?>
                                        <?php
                                    }else {
                                        if (isset($wphrmEmployeeOtherInfo['wphrmotherfieldslebal']) && $wphrmEmployeeOtherInfo['wphrmotherfieldslebal'] != '' && isset($wphrmEmployeeOtherInfo['wphrmotherfieldsvalue']) && $wphrmEmployeeOtherInfo['wphrmotherfieldsvalue'] != '') {
                                            foreach ($wphrmEmployeeOtherInfo['wphrmotherfieldslebal'] as $lebalkey => $wphrmEmployeeSettingsOther) {
                                                foreach ($wphrmEmployeeOtherInfo['wphrmotherfieldsvalue'] as $valuekey => $wphrmOtherSettingsvalue) {
                                                    if ($lebalkey == $valuekey) {
                                                        ?>
                                                        <div class="form-group">
                                                            <label class="col-md-4 control-label"><?php _e($wphrmEmployeeSettingsOther, 'wphrm'); ?></label>
                                                            <div class="col-md-8">
                                                                <input readonly class="form-control" readonly="" type="text" id="other-fields-lebal" value="<?php
                                                                if (isset($wphrmOtherSettingsvalue)) : echo esc_attr($wphrmOtherSettingsvalue);
                                                                endif; ?>" autocapitalize="none"  />
                                                            </div>
                                                        </div> 
                                                        <?php
                                                    }
                                                }
                                            }
                                            $wphrmOtherFieldsInfo = $this->WPHRMGetSettings('Otherfieldskey');
                                            if (!empty($wphrmOtherFieldsInfo)) {
                                                foreach ($wphrmOtherFieldsInfo['Otherfieldslebal'] as $wphrmOtherFieldsSettings) {
                                                    if (!in_array($wphrmOtherFieldsSettings, $wphrmEmployeeOtherInfo['wphrmotherfieldslebal'])) { ?>
                                                        <div class="form-group">
                                                            <label class="col-md-4 control-label"><?php _e($wphrmOtherFieldsSettings, 'wphrm'); ?></label>
                                                            <div class="col-md-8">
                                                                <input readonly class="form-control" readonly=""  type="text" id="other-fields-lebal" value="" autocapitalize="none"  />
                                                            </div>
                                                        </div> 
                                                        <?php
                                                    }
                                                }
                                            }
                                        } else {
                                            $wphrmOtherfieldskeyInfo = $this->WPHRMGetSettings('Otherfieldskey');
                                            if (!empty($wphrmOtherfieldskeyInfo)) {
                                                foreach ($wphrmOtherfieldskeyInfo['Otherfieldslebal'] as $wphrmOthersettingInfo) {
                                                    ?>
                                                    <div class="form-group">
                                                        <label class="col-md-4 control-label"><?php _e($wphrmOthersettingInfo, 'wphrm'); ?></label>
                                                        <div class="col-md-8">
                                                            <input readonly class="form-control" readonly=""  type="text" id="other-fields-value" value="" autocapitalize="none"  />
                                                        </div>
                                                    </div> 
                                                    <?php
                                                }
                                            }
                                        }
                                    }
                                    ?>
                                    <?php if (isset($wphrmEmployeeOtherInfo['wphrm_employee_vehicle']) && $wphrmEmployeeOtherInfo['wphrm_employee_vehicle'] != '') : ?>
                                    <div class="form-group">                                        
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="wphrm_vehicle_type" class="col-md-4 control-label"><?php _e('Vehicle Type', 'wphrm'); ?>:</label>
                                                <div class="col-md-8">
                                                    <input readonly class="form-control" type="text" name="wphrm_vehicle_type" id="wphrm_vehicle_type" value="<?php
                                                    if (isset($wphrmEmployeeOtherInfo['wphrm_vehicle_type'])) : echo esc_attr($wphrmEmployeeOtherInfo['wphrm_vehicle_type']); endif; ?>"/>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="wphrm_employee_vehicle_model" class="col-md-4 control-label"><?php _e('Make-Model', 'wphrm'); ?>:</label>
                                                <div class="col-md-8">
                                                    <input readonly class="form-control" type="text" name="wphrm_employee_vehicle_model" id="wphrm_employee_vehicle_model" value="<?php
                                                    if (isset($wphrmEmployeeOtherInfo['wphrm_employee_vehicle_model'])) : echo esc_attr($wphrmEmployeeOtherInfo['wphrm_employee_vehicle_model']); endif; ?>"/>
                                                </div>
                                            </div>

                                            <div class="form-group" style="margin-bottom:0px;">
                                                <label for="wphrm_employee_vehicle_registrationno" class="col-md-4 control-label"><?php _e('Registration No.', 'wphrm'); ?>:</label>
                                                <div class="col-md-8">
                                                    <input readonly class="form-control" type="text" name="wphrm_employee_vehicle_registrationno" id="wphrm_employee_vehicle_registrationno" value="<?php
                                                    if (isset($wphrmEmployeeOtherInfo['wphrm_employee_vehicle_registrationno'])) : echo esc_attr($wphrmEmployeeOtherInfo['wphrm_employee_vehicle_registrationno']); endif; ?>"/>
                                                </div>
                                            </div>
                                        </div>                                        
                                    </div>
                                    <?php endif; ?>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label"><?php _e('T-Shirt Size', 'wphrm'); ?></label>
                                        <div class="col-md-8">
                                            <?php $wphrmTShirtSizes = array('xxxs'=>'XXXS', 'xxs'=>'XXS', 'xs'=>'XS', 's'=>'S', 'm'=>'M', 'l'=>'L', 'xl'=>'XL', 'xxl'=>'XXL', 'xxxl'=>'XXXL'); ?>
                                            <input readonly class="form-control" type="text" name="wphrm_employee_vehicle_model" id="wphrm_employee_vehicle_model" value="<?php
                                           if (isset($wphrmEmployeeOtherInfo['wphrm_t_shirt_size']) && $wphrmEmployeeOtherInfo['wphrm_t_shirt_size']!='') : echo esc_attr($wphrmTShirtSizes[$wphrmEmployeeOtherInfo['wphrm_t_shirt_size']]);
                                           endif; ?>"/>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>