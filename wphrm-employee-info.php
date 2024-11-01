<?php
if ( ! defined( 'ABSPATH' ) ) exit;
global $current_user, $wpdb;
$wphrmUserRole = implode(',', $current_user->roles);
$readonly_class = '';
$readonly = 'readonly';
$edit_mode = false;
$wphrmEmployeeEditId = '';


if (isset($_REQUEST['employee_id']) && $_REQUEST['employee_id'] != '') {
    $wphrmEmployeeEditId = $_REQUEST['employee_id'];
} else {
    if (isset($wphrmUserRole) && $wphrmUserRole != 'administrator') {
        $wphrmEmployeeEditId = $current_user->ID;
    }
}
 

$wphrmEmployeeBasicInfo = $this->WPHRMGetUserDatas($wphrmEmployeeEditId, 'wphrmEmployeeInfo');
$wphrmEmployeeDocumentsInfo = $this->WPHRMGetUserDatas($wphrmEmployeeEditId, 'wphrmEmployeeDocumentInfo');
$wphrmMessagesPersonal = $this->WPHRMGetMessage(3);
$wphrmMessagesBank = $this->WPHRMGetMessage(4);
$wphrmMessagesDocuments = $this->WPHRMGetMessage(5);
$wphrmMessagesSalary = $this->WPHRMGetMessage(7);
$wphrmMessagesOther = $this->WPHRMGetMessage(6);

$wphrmUserPermissionInfoformation = esc_sql('wphrmUserPermissionInfo'); // esc
$wphrmUserPermissionInfos = $wpdb->get_row("SELECT * FROM $this->WphrmSettingsTable WHERE `settingKey` = '$wphrmUserPermissionInfoformation'");
if (!empty($wphrmUserPermissionInfos)) {
    $wphrmUserPermissionInfo = unserialize(base64_decode($wphrmUserPermissionInfos->settingValue));
}

$resumeDir = '';
if (isset($wphrmEmployeeDocumentsInfo['resume']) && $wphrmEmployeeDocumentsInfo['resume'] != '') {
    $rdirs = explode('/', $wphrmEmployeeDocumentsInfo['resume']);
    $resumeDir = $rdirs[count($rdirs) - 1];
}
$offerDir = '';
if (isset($wphrmEmployeeDocumentsInfo['offerLetter']) && $wphrmEmployeeDocumentsInfo['offerLetter'] != '') {
    $rdirs = explode('/', $wphrmEmployeeDocumentsInfo['offerLetter']);
    $offerDir = $rdirs[count($rdirs) - 1];
}
$joiningDir = '';
if (isset($wphrmEmployeeDocumentsInfo['joiningLetter']) && $wphrmEmployeeDocumentsInfo['joiningLetter'] != '') {
    $rdirs = explode('/', $wphrmEmployeeDocumentsInfo['joiningLetter']);
    $joiningDir = $rdirs[count($rdirs) - 1];
}
$contractDir = '';
if (isset($wphrmEmployeeDocumentsInfo['contract']) && $wphrmEmployeeDocumentsInfo['contract'] != '') {
    $rdirs = explode('/', $wphrmEmployeeDocumentsInfo['contract']);
    $contractDir = $rdirs[count($rdirs) - 1];
}
$idProofDir = '';
if (isset($wphrmEmployeeDocumentsInfo['IDProof']) && $wphrmEmployeeDocumentsInfo['IDProof'] != '') {
    $rdirs = explode('/', $wphrmEmployeeDocumentsInfo['IDProof']);
    $idProofDir = $rdirs[count($rdirs) - 1];
}
$wphrmEmployeeSalaryInfo = $this->WPHRMGetUserDatas($wphrmEmployeeEditId, 'wphrmEmployeeSalaryInfo');
$wphrmEmployeeFirstName = get_user_meta($wphrmEmployeeEditId, 'first_name', true);
$wphrmEmployeeLastName = get_user_meta($wphrmEmployeeEditId, 'last_name', true);

$wphrmEmployeeBankInfo = $this->WPHRMGetUserDatas($wphrmEmployeeEditId, 'wphrmEmployeeBankInfo');
$wphrmEmployeeOtherInfo = $this->WPHRMGetUserDatas($wphrmEmployeeEditId, 'wphrmEmployeeOtherInfo');

$wphrmDesignations = $wpdb->get_results("SELECT * FROM  $this->WphrmDesignationTable");
foreach ($wphrmDesignations as $key => $wphrmDesignation) {
    $wphrmDesignationarr[] = $wphrmDesignation->departmentID;
}
?>
<!-- BEGIN PAGE HEADER-->
<div class="preloader">
    <span class="preloader-custom-gif"></span>
</div>
<div style="padding-left: 0px; padding-right:20px; padding-top:0px;" class="col-md-12">
    <!-- BEGIN PAGE HEADER-->
    <h3 class="page-title"><?php _e('Employee', 'wphrm'); ?></h3>
    <!-- END PAGE HEADER-->
    <div class="row">
        <div class="col-md-12">
            <div class="page-bar">
                <ul class="page-breadcrumb">
                    <li><i class="fa fa-home"></i><?php _e('Home', 'wphrm'); ?><i class="fa fa-angle-right"></i></li>
                    <li><?php _e('Employee', 'wphrm'); ?><i class="fa fa-angle-right"></i></li>
                    <li> <?php if (isset($wphrmEmployeeEditId) && $wphrmEmployeeEditId != '') { ?>
                            <?php _e('Edit Employee', 'wphrm'); ?>
                        <li> <i class="fa fa-angle-double-right"></i><strong><?php echo esc_html($wphrmEmployeeFirstName) . ' ' . esc_html($wphrmEmployeeLastName); ?></strong></li>
                    <?php } else { ?>
                        <?php _e('Add Employee', 'wphrm'); ?>     
                            <?php } ?> </li>

                </ul>
            </div>
            <?php if (isset($wphrmUserRole) && $wphrmUserRole == 'administrator') { ?>
                <a class="btn green " href="?page=wphrm-employees"><i class="fa fa-arrow-left"></i><?php _e('Back', 'wphrm'); ?> </a>
                <?php if (isset($_REQUEST['page']) && $_REQUEST['page'] != 'wphrm-employee-info' && isset($wphrmEmployeeEditId) && $wphrmEmployeeEditId != '') { ?>
                    <a class="btn green " href="?page=wphrm-employee-info&employee_id=<?php echo esc_html($wphrmEmployeeEditId); ?>"><i class="fa fa-edit"></i><?php _e('Edit', 'wphrm'); ?> </a>
                <?php } else if (isset($_REQUEST['page']) && $_REQUEST['page'] != 'wphrm-employee-view-details' && isset($wphrmEmployeeEditId) && $wphrmEmployeeEditId != ''){ ?>
                    <a class="btn green " href="?page=wphrm-employee-view-details&employee_id=<?php echo esc_html($wphrmEmployeeEditId); ?>"><i class="fa fa-eye"></i><?php _e('View', 'wphrm'); ?> </a>
                    <?php
                }
            }
            ?>
            <div class="row ">
                <div class="col-md-6 col-sm-6">
                    <div class="portlet box purple-wisteria">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="fa fa-edit"></i><?php _e('Personal Details', 'wphrm'); ?>
                            </div>

                            <div class="actions">
                                <a href="javascript:;"  onclick="jQuery('#wphrm_employee_basic_info_form').submit();" data-loading-text="Updating..."  class="demo-loading-btn btn btn-sm btn-default ">
                                    <i class="fa fa-save" ></i><?php _e('Save', 'wphrm'); ?>  </a>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <div class="alert alert-success display-hide" id="personal_details_success"><i class='fa fa-check-square' aria-hidden='true'></i> <?php echo esc_html($wphrmMessagesPersonal); ?>
                                <button class="close" data-close="alert"></button>
                            </div>
                            <div class="alert alert-danger display-hide" id="personal_details_error">
                                <button class="close" data-close="alert"></button>
                            </div>
                            <form method="POST" accept-charset="UTF-8" class="form-horizontal" id="wphrm_employee_basic_info_form" enctype="multipart/form-data">
                                <input name="wphrm_employee_role" type="hidden" id="wphrm_employee_role" value="<?php
                                if (isset($wphrmUserPermissionInfo['wphrm_user_permission']) && $wphrmUserPermissionInfo['wphrm_user_permission'] != ''): echo esc_attr($wphrmUserPermissionInfo['wphrm_user_permission']);
                                else : echo esc_attr('subscriber');
                                endif;
                                ?>" />
                                <input type="hidden" name="wphrm_employee_id" id="wphrm_employee_id"  value="<?php
                                if (isset($wphrmEmployeeEditId)) : echo esc_attr($wphrmEmployeeEditId);
                                endif;
                                ?> "/>
                                <div class="form-body">
                                    <div class="form-group ">
                                        <label class="control-label col-md-4"><?php _e('Photo', 'wphrm'); ?> </label>
                                        <div class="col-md-8">
                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                <div class="fileinput-new thumbnail" style="width: 200px; height: auto;">
                                                    <?php if (isset($wphrmEmployeeBasicInfo['employee_profile']) != '') { ?>
                                                        <img src="<?php
                                                        if (isset($wphrmEmployeeBasicInfo['employee_profile'])) : echo esc_attr($wphrmEmployeeBasicInfo['employee_profile']);
                                                        endif;
                                                        ?>" width="200">
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
                                                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;">
                                                </div>
                                                <div>

                                                    <span class="btn default btn-file">
                                                        <span class="fileinput-new">
                                                            <?php _e('Select image', 'wphrm'); ?></span>
                                                        <span class="fileinput-exists">
                                                            <?php _e('Change', 'wphrm'); ?></span>
                                                        <input type="file" name="employee_profile" id="employee_profile">
                                                    </span>
                                                    <a href="#" class="btn red fileinput-exists" data-dismiss="fileinput">
                                                        <?php _e('Remove', 'wphrm'); ?></a>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <div class="col-md-4">
                                        </div>
                                        <div class="col-md-1"><span class="label label-danger span-padding"><?php _e('NOTE', 'wphrm'); ?> !</span></div> <div class="col-md-6 notice-info"><?php _e("Only 'jpeg', 'jpg', 'png' filetypes are allowed.", 'wphrm'); ?></div>
                                    </div>

                                    <?php if (isset($wphrmUserRole) && $wphrmUserRole == 'administrator') { ?>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label"><?php _e('First Name', 'wphrm'); ?><span class="required"></span></label>
                                            <div class="col-md-8">
                                                <input class="form-control"  name="wphrm_employee_fname" type="text" id="wphrm_employee_fname" value="<?php
                                                if (isset($wphrmEmployeeFirstName)) : echo esc_attr($wphrmEmployeeFirstName);
                                                endif;
                                                ?>" autocapitalize="none"  />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label"><?php _e('Last Name', 'wphrm'); ?></label>
                                            <div class="col-md-8">
                                                <input class="form-control" name="wphrm_employee_lname" type="text" id="wphrm_employee_lname" value="<?php
                                                if (isset($wphrmEmployeeLastName)) : echo esc_attr($wphrmEmployeeLastName);
                                                endif;
                                                ?>" autocapitalize="none"  />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label"><?php _e('Father Name', 'wphrm'); ?></label>
                                            <div class="col-md-8">
                                                <input  class="form-control" name="wphrm_employee_fathername" type="text" id="wphrm_employee_fathername" value="<?php
                                                if (isset($wphrmEmployeeBasicInfo['wphrm_employee_fathername'])) : echo esc_attr($wphrmEmployeeBasicInfo['wphrm_employee_fathername']);
                                                endif;
                                                ?>" autocapitalize="none"  />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label"><?php _e('Email', 'wphrm'); ?></label>
                                            <div class="col-md-8">
                                                <input class="form-control" name="wphrm_employee_email" type="text" id="wphrm_employee_email" value="<?php
                                                if (isset($wphrmEmployeeBasicInfo['wphrm_employee_email'])) : echo esc_attr($wphrmEmployeeBasicInfo['wphrm_employee_email']);
                                                endif;
                                                ?>" autocapitalize="none"  />   
                                            </div>
                                        </div>


                                        <div class="form-group">
                                            <label class="control-label col-md-4"><?php _e('Employee Id', 'wphrm'); ?>
                                            </label>
                                            <div class="col-md-8">
                                                <input  name="wphrm_employee_uniqueid" type="text" id="wphrm_employee_uniqueid" value="<?php
                                                if (isset($wphrmEmployeeBasicInfo['wphrm_employee_uniqueid'])) : echo esc_attr($wphrmEmployeeBasicInfo['wphrm_employee_uniqueid']);
                                                endif;
                                                ?>" class="form-control" />
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-4"><?php _e('User ID', 'wphrm'); ?>
                                            </label>
                                            <div class="col-md-8">
                                                <input  name="wphrm_employee_userid" type="text" id="wphrm_employee_userid" value="<?php
                                                if (isset($wphrmEmployeeBasicInfo['wphrm_employee_userid'])) : echo esc_attr($wphrmEmployeeBasicInfo['wphrm_employee_userid']);
                                                endif;
                                                ?>" class="form-control" />
                                            </div>
                                        </div>
                                        <?php if ($wphrmEmployeeEditId == '') { ?>
                                            <div class="form-group">
                                                <label class="control-label col-md-4"><?php _e('Password', 'wphrm'); ?>  </label>
                                                <div class="col-md-8">
                                                    <input name="wphrm_employee_password" id="wphrm_employee_password" type="password" value="" class="form-control" >
                                                    <input id="methods" type="checkbox" class="form-control" /> Show password</label>&nbsp;&nbsp;&nbsp;&nbsp;<button type="button" name="generate" id="generatePassword"  class="btn default"><i class="fa fa-cogs"></i>&nbsp;&nbsp;Generate Password</button>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <div class="form-group">
                                            <label class="control-label col-md-4"><?php _e('Department', 'wphrm'); ?>  </label>
                                            <div class="col-md-8">
                                                <select class="form-control select2me" name="wphrm_employee_department" id="wphrm_employee_department">
                                                    <option value=""> <?php _e('Select Department', 'wphrm'); ?></option>  
                                                    <?php
                                                    $selected = '';
                                                    $wphrmDepartments = $wpdb->get_results("SELECT * FROM  $this->WphrmDepartmentTable");


                                                    foreach ($wphrmDepartments as $key => $wphrmDepartment) {

                                                        if (in_array($wphrmDepartment->departmentID, $wphrmDesignationarr)) {
                                                            $wphrmDepartmentInfo = unserialize(base64_decode($wphrmDepartment->departmentName));
                                                            if (intval($wphrmEmployeeBasicInfo['wphrm_employee_department']) == intval($wphrmDepartment->departmentID)) {
                                                                $selected = 'selected';
                                                            } else {
                                                                $selected = '';
                                                            }
                                                            ?>
                                                            <?php echo esc_attr($wphrmDepartment->departmentID); ?>
                                                            <option value="<?php
                                                            if (isset($wphrmDepartment->departmentID)) : echo esc_attr($wphrmDepartment->departmentID);
                                                            endif;
                                                            ?> "<?php echo esc_attr($selected); ?>><?php
                                                                        if (isset($wphrmDepartmentInfo['departmentName'])) : echo esc_html($wphrmDepartmentInfo['departmentName']);
                                                                        endif;
                                                                        ?></option>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <input type="hidden" id="wphrm_ajax_employee_designation" value="<?php
                                            if (isset($wphrmEmployeeBasicInfo['wphrm_employee_designation'])) : echo esc_attr($wphrmEmployeeBasicInfo['wphrm_employee_designation']);
                                            endif;
                                            ?>">
                                            <label class="control-label col-md-4"><?php _e('Designation', 'wphrm'); ?>
                                            </label>
                                            <div class="col-md-8">
                                                <select class="form-control select2me" name="wphrm_employee_designation" id="wphrm_employee_designation">
                                                    <option value=""> <?php _e('Select Designation', 'wphrm'); ?></option>                                        
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4"><?php _e('Joining Date', 'wphrm'); ?></label>
                                            <div class="col-md-8">
                                                <div class="input-group input-medium date before-current-date"  data-date-format="dd-mm-yyyy" data-date-viewmode="years">
                                                    <input class="form-control date-pickers"  name="wphrm_employee_joining_date" type="text" id="wphrm_employee_joining_date" value="<?php
                                                    if (isset($wphrmEmployeeBasicInfo['wphrm_employee_joining_date'])) : echo esc_attr($wphrmEmployeeBasicInfo['wphrm_employee_joining_date']);
                                                    endif;
                                                    ?>" autocapitalize="none"  />
                                                    <span class="input-group-btn">
                                                        <button class="btn default-date" type="button"><i class="fa fa-calendar" style="line-height: 1.9;"></i></button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4"><?php _e('Gender', 'wphrm'); ?>
                                            </label>
                                            <div class="col-md-8">
                                                <div class="radio-list" data-error-container="#form_2_membership_error">
                                                    <input  name="wphrm_employee_gender" type="radio" <?php
                                                    if (isset($wphrmEmployeeBasicInfo['wphrm_employee_gender']) && $wphrmEmployeeBasicInfo['wphrm_employee_gender'] == 'Male') : echo esc_attr('checked');
                                                    endif;
                                                    ?> id="wphrm_employee_gender" value="Male" checked>&nbsp;<?php _e('Male', 'wphrm'); ?> &nbsp;&nbsp;&nbsp;&nbsp;
                                                    <input  name="wphrm_employee_gender" type="radio" id="wphrm_employee_gender" <?php
                                                    if (isset($wphrmEmployeeBasicInfo['wphrm_employee_gender']) && $wphrmEmployeeBasicInfo['wphrm_employee_gender'] == 'Female') : echo esc_attr('checked');
                                                    endif;
                                                    ?> value="Female" >&nbsp;<?php _e('Female', 'wphrm'); ?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-4"><?php _e('Employee Status', 'wphrm'); ?>
                                            </label>
                                            <div class="col-md-8">
                                                <div class="radio-list" data-error-container="#form_2_membership_error">
                                                    <input  name="wphrm_employee_status" type="radio" <?php
                                                    if (isset($wphrmEmployeeBasicInfo['wphrm_employee_status']) && $wphrmEmployeeBasicInfo['wphrm_employee_status'] == 'Active') : echo esc_attr('checked');
                                                    endif;
                                                    ?> id="wphrm_employee_status" value="Active" checked>&nbsp;Active &nbsp;&nbsp;

                                                    <input  name="wphrm_employee_status" type="radio" id="wphrm_employee_status" <?php
                                                    if (isset($wphrmEmployeeBasicInfo['wphrm_employee_status']) && $wphrmEmployeeBasicInfo['wphrm_employee_status'] == 'Inactive') : echo esc_attr('checked');
                                                    endif;
                                                    ?> value="Inactive" >&nbsp;<?php _e('Inactive', 'wphrm'); ?>
                                                </div>
                                            </div>
                                        </div>


                                    <?php } else { ?>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label"><?php _e('First Name', 'wphrm'); ?><span class="required"></span></label>
                                            <div class="col-md-8">
                                                <input readonly class="form-control"  name="wphrm_employee_fname" type="text" id="wphrm_employee_fname" value="<?php
                                                if (isset($wphrmEmployeeFirstName)) : echo esc_attr($wphrmEmployeeFirstName);
                                                endif;
                                                ?>" autocapitalize="none"  />
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
                                    <?php } ?>
                                    <?php if (isset($wphrmUserRole) && $wphrmUserRole == 'administrator') { ?>
                                        <div class="form-group">
                                            <label class="control-label col-md-4"><?php _e('Date of Birth', 'wphrm'); ?></label>
                                            <div class="col-md-8">
                                                <div class="input-group input-medium date before-current-date" data-date-format="dd-mm-yyyy" data-date-viewmode="years">
                                                    <input class="form-control"  name="wphrm_employee_bod" type="text" id="wphrm_employee_bod" value="<?php
                                                    if (isset($wphrmEmployeeBasicInfo['wphrm_employee_bod'])) : echo esc_attr($wphrmEmployeeBasicInfo['wphrm_employee_bod']);
                                                    endif;
                                                    ?>" autocapitalize="none"  />
                                                    <span class="input-group-btn">
                                                        <button class="btn default-date" type="button"><i class="fa fa-calendar" style="line-height: 1.9;"></i></button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } else { ?>
                                        <div class="form-group">
                                            <label class="control-label col-md-4"><?php _e('Date of Birth', 'wphrm'); ?></label>
                                            <div class="col-md-8">
                                                <div class="input-group input-medium"  data-date-format="dd-mm-yyyy" data-date-viewmode="years">
                                                    <input readonly="" class="form-control"  name="wphrm_employee_bod" type="text" id="wphrm_employee_bod" value="<?php
                                                    if (isset($wphrmEmployeeBasicInfo['wphrm_employee_bod'])) : echo esc_attr($wphrmEmployeeBasicInfo['wphrm_employee_bod']);
                                                    endif;
                                                    ?>" autocapitalize="none"  />
                                                    <span class="input-group-btn">
                                                        <button class="btn default-date" type="button"><i class="fa fa-calendar" style="line-height: 1.9;"></i></button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label"><?php _e('Phone Number', 'wphrm'); ?></label>
                                        <div class="col-md-8">
                                            <input  class="form-control"  name="wphrm_employee_phone" type="text" id="wphrm_employee_phone" value="<?php
                                            if (isset($wphrmEmployeeBasicInfo['wphrm_employee_phone'])) : echo esc_attr($wphrmEmployeeBasicInfo['wphrm_employee_phone']);
                                            endif;
                                            ?>" autocapitalize="none" autocorrect="off"  />     
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label"><?php _e('Local Address', 'wphrm'); ?></label>
                                        <div class="col-md-8">
                                            <textarea  rows="3" class="form-control" name="wphrm_employee_local_address" type="text" id="wphrm_employee_local_address" value="" autocapitalize="none" autocorrect="off"><?php
                                                if (isset($wphrmEmployeeBasicInfo['wphrm_employee_local_address'])) : echo esc_textarea($wphrmEmployeeBasicInfo['wphrm_employee_local_address']);
                                                endif;
                                                ?></textarea>
                                        </div>
                                    </div>
                                    <?php if (isset($wphrmUserRole) && $wphrmUserRole == 'administrator') { ?>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label"><?php _e('Permanent Address', 'wphrm'); ?></label>
                                            <div class="col-md-8">
                                                <textarea  rows="3" class="form-control" name="wphrm_employee_permanant_address" style="margin-bottom: 3px;" type="text" id="wphrm_employee_permanant_address" value="" autocapitalize="none" autocorrect="off"><?php
                                                    if (isset($wphrmEmployeeBasicInfo['wphrm_employee_permanant_address'])) : echo esc_textarea($wphrmEmployeeBasicInfo['wphrm_employee_permanant_address']);
                                                    endif;
                                                    ?></textarea>
                                                <button type="button"  onclick="copyLocalAddresss()"  class="btn default"><i class="fa fa-copy"></i>&nbsp;Copy Local Address</button>
                                            </div>
                                        </div>
                                    <?php } else { ?> 
                                        <div class="form-group">
                                            <label class="col-md-4 control-label"><?php _e('Permanent Address', 'wphrm'); ?></label>
                                            <div class="col-md-8">
                                                <textarea  rows="3" class="form-control" readonly><?php
                                                    if (isset($wphrmEmployeeBasicInfo['wphrm_employee_permanant_address'])) : echo esc_textarea($wphrmEmployeeBasicInfo['wphrm_employee_permanant_address']);
                                                    endif;
                                                    ?></textarea>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </form>
                        </div>
                    </div>
                    <?php if (isset($wphrmUserRole) && $wphrmUserRole == 'administrator') { ?>
                        <div class="portlet box purple-wisteria">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-file-image-o"></i><?php _e('Documents', 'wphrm'); ?>
                                </div>
                                <?php if (isset($wphrmEmployeeEditId) && $wphrmEmployeeEditId != '') { ?>
                                    <div class="actions">
                                        <a href="javascript:;"  onclick="jQuery('#wphrmEmployeeDocumentInfo_form').submit();" data-loading-text="Updating..."  class="demo-loading-btn btn btn-sm btn-default ">
                                            <i class="fa fa-save" ></i><?php _e('Save', 'wphrm'); ?></a>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="portlet-body">
                                <div class="portlet-body">
                                    <div class="clearfix margin-top-10">
                                        <p class="p-center"><span class="label label-danger"><?php _e('NOTE', 'wphrm'); ?> !</span>
                                            &nbsp;&nbsp;<?php _e("Only 'jpeg', 'jpg', 'png', 'txt', 'pdf', 'doc' filetypes are allowed.", 'wphrm'); ?></p>
                                    </div>
                                    <button class="close" data-close="alert"></button>
                                    <div class="alert alert-success display-hide" id="Documents_success"><i class='fa fa-check-square' aria-hidden='true'></i> <?php echo esc_html($wphrmMessagesDocuments); ?>
                                    </div>
                                    <div class="alert alert-danger display-hide" id="Documents_error">
                                        <button class="close" data-close="alert"></button>
                                    </div>
                                    <form method="POST"  accept-charset="UTF-8" class="form-horizontal" id="wphrmEmployeeDocumentInfo_form" enctype="multipart/form-data"><input name="_method" type="hidden" value="PATCH"><input name="_token" type="hidden" value="CKw97QC4WEEKjxHdCpA3oZBiucWKYo0778rEpuPz">
                                        <input type="hidden" name="wphrm_employee_id" id="wphrm_employee_id"  value="<?php
                                        if (isset($wphrmEmployeeEditId)) : echo esc_attr($wphrmEmployeeEditId);
                                        endif;
                                        ?> "/>
                                        <div class="form-body">
                                            <div class="form-group">
                                                <label class="control-label col-md-4"><?php _e('Resume', 'wphrm'); ?></label>
                                                <div class="col-md-8">
                                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                                        <div class="input-group input-large">
                                                            <div class="form-control uneditable-input" data-trigger="fileinput">
                                                                <i class="fa fa-file fileinput-exists"></i>&nbsp; <span class="fileinput-filename"><?php
                                                                    if (isset($resumeDir) && $resumeDir !='') : $resumeExt = pathinfo($resumeDir, PATHINFO_EXTENSION);
                                                                                          echo esc_html(mb_strimwidth($resumeDir , 0, 10).'....'.$resumeExt);
                                                                    endif;
                                                                    ?></span>
                                                            </div>
                                                            <span class="input-group-addon btn default btn-file">
                                                                <span class="fileinput-new">
                                                                    <?php _e('Select file', 'wphrm'); ?> </span>
                                                                <span class="fileinput-exists">
                                                                    <?php _e('Change', 'wphrm'); ?> </span>
                                                                <input type="file" name="resume" class="documents-Upload">
                                                            </span>
                                                            <a href="#" class="input-group-addon btn red fileinput-exists" data-dismiss="fileinput">
                                                                <?php _e('Remove', 'wphrm'); ?> </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-4"><?php _e('Offer Letter', 'wphrm'); ?></label>
                                                <div class="col-md-8">
                                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                                        <div class="input-group input-large" >
                                                            <div readonly class="form-control uneditable-input" data-trigger="fileinput">
                                                                <i class="fa fa-file fileinput-exists"></i>&nbsp; <span class="fileinput-filename"></span>
                                                            </div>
                                                           
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-4"><?php _e('Joining Letter', 'wphrm'); ?></label>
                                                <div class="col-md-8">
                                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                                        <div class="input-group input-large" >
                                                            <div readonly class="form-control uneditable-input" data-trigger="fileinput">
                                                                <i class="fa fa-file fileinput-exists"></i>&nbsp; <span class="fileinput-filename"></span>
                                                            </div>
                                                           
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-4"><?php _e('Contract and Agreement', 'wphrm'); ?></label>
                                                <div class="col-md-8">
                                               <div class="fileinput fileinput-new" data-provides="fileinput">
                                                        <div class="input-group input-large" >
                                                            <div readonly class="form-control uneditable-input" data-trigger="fileinput">
                                                                <i class="fa fa-file fileinput-exists"></i>&nbsp; <span class="fileinput-filename"></span>
                                                            </div>
                                                           
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-4"><?php _e('ID Proof', 'wphrm'); ?></label>
                                                <div class="col-md-8">
                                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                                        <div class="input-group input-large" >
                                                            <div readonly class="form-control uneditable-input" data-trigger="fileinput">
                                                                <i class="fa fa-file fileinput-exists"></i>&nbsp; <span class="fileinput-filename"></span>
                                                            </div>
                                                           
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>


                    <?php }else { ?>

                        <div class="portlet box purple-wisteria">
                            <div class="portlet-title">
                                <div class="caption"><i class="fa fa-file-image-o"></i><?php _e('Documents', 'wphrm'); ?></div>
                            </div>
                            <div class="portlet-body">
                                <div class="portlet-body">
                                    <div class="alert alert-success display-hide" id="employee_document">
                                        <button class="close" data-close="alert"></button>
                                    </div>
                                    <div class="alert alert-danger display-hide" id="employee_document_error">
                                        <button class="close" data-close="alert"></button>
                                    </div>
                                    <form method="POST"  accept-charset="UTF-8" class="form-horizontal" id="wphrmEmployeeDocumentInfo_form" enctype="multipart/form-data">
                                        <input type="hidden" name="wphrm_employee_id" id="wphrm_employee_id"  value="<?php
                                        if (isset($wphrmEmployeeEditId)) : echo esc_attr($wphrmEmployeeEditId);
                                        endif;
                                        ?> "/>
                                        <div class="form-body">
                                            <div class="form-group">
                                                <label class="control-label col-md-4"><?php _e('Resume', 'wphrm'); ?></label>
                                                <div class="col-md-8">
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
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-4"><?php _e('Offer Letter', 'wphrm'); ?></label>
                                                <div class="col-md-8">
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
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-4"><?php _e('Joining Letter', 'wphrm'); ?></label>
                                                <div class="col-md-8">
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
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-4"><?php _e('Contract and Agreement', 'wphrm'); ?></label>
                                                <div class="col-md-8">
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
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-4"><?php _e('ID Proof', 'wphrm'); ?></label>
                                                <div class="col-md-8">
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
                                                <div class="col-md-3"> </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <div class="col-md-6 col-sm-6">
                    <?php if (isset($wphrmUserRole) && $wphrmUserRole == 'administrator') { ?>
                        <div class="portlet box red-sunglo">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-bank"></i><?php _e('Bank Account Details', 'wphrm'); ?>
                                </div>
                                <?php if (isset($wphrmEmployeeEditId) && $wphrmEmployeeEditId != '') { ?>
                                    <div class="actions">
                                        <a href="javascript:;"  onclick="jQuery('#wphrmEmployeeBankInfo_form').submit();" data-loading-text="Updating..."  class="demo-loading-btn btn btn-sm btn-default ">
                                            <i class="fa fa-save" ></i><?php _e('Save', 'wphrm'); ?>  </a>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="portlet-body">
                                <div class="alert alert-success display-hide" id="wphrm_bank_details"><i class='fa fa-check-square' aria-hidden='true'></i> <?php echo esc_html($wphrmMessagesBank); ?>
                                    <button class="close" data-close="alert"></button>
                                </div>
                                <div class="alert alert-danger display-hide" id="wphrm_bank_details_error">
                                    <button class="close" data-close="alert"></button>
                                </div>
                                <form method="POST"  accept-charset="UTF-8" class="form-horizontal" id="wphrmEmployeeBankInfo_form">   
                                    <input type="hidden" name="wphrm_employee_id" id="wphrm_employee_id"  value="<?php
                                    if (isset($wphrmEmployeeEditId)) : echo esc_attr($wphrmEmployeeEditId);
                                    endif;
                                    ?> "/>
                                    <div id="alert_bank"></div>
                                    <div class="form-body">
                                        <div class="form-group">
                                            <label class="col-md-4 control-label"><?php _e('Account Holder Name', 'wphrm'); ?></label>
                                            <div class="col-md-8">
                                                <input class="form-control"  name="wphrm_employee_bank_account_name" type="text" id="wphrm_employee_bank_account_name" value="<?php
                                                if (isset($wphrmEmployeeBankInfo['wphrm_employee_bank_account_name'])) : echo esc_attr($wphrmEmployeeBankInfo['wphrm_employee_bank_account_name']);
                                                endif;
                                                ?>" autocapitalize="none"  />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label"><?php _e('Account Number', 'wphrm'); ?></label>
                                            <div class="col-md-8">
                                                <input class="form-control"  name="wphrm_employee_bank_account_no" type="password" id="wphrm_employee_bank_account_no" value="<?php
                                                if (isset($wphrmEmployeeBankInfo['wphrm_employee_bank_account_no'])) : echo esc_attr($wphrmEmployeeBankInfo['wphrm_employee_bank_account_no']);
                                                endif;
                                                ?>" autocapitalize="none"  />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label"><?php _e('Confirm Account Number', 'wphrm'); ?></label>
                                            <div class="col-md-8">
                                                <input class="form-control"  name="wphrm_Confirm_mployee_bank_account_no" type="text" id="wphrm_Confirm_mployee_bank_account_no"
                                                       autocapitalize="none"  value="<?php
                                                       if (isset($wphrmEmployeeBankInfo['wphrm_employee_bank_account_no'])) : echo esc_attr($wphrmEmployeeBankInfo['wphrm_employee_bank_account_no']);
                                                       endif;
                                                       ?>" />
                                            </div>
                                        </div>
                                        <?php
                                        if (isset($wphrmEmployeeBankInfo['wphrmbankfieldslebal']) && $wphrmEmployeeBankInfo['wphrmbankfieldslebal'] != '' && isset($wphrmEmployeeBankInfo['wphrmbankfieldsvalue']) && $wphrmEmployeeBankInfo['wphrmbankfieldsvalue'] != '') {
                                            foreach ($wphrmEmployeeBankInfo['wphrmbankfieldslebal'] as $lebalkey => $wphrmEmployeeSettingsBank) {
                                                foreach ($wphrmEmployeeBankInfo['wphrmbankfieldsvalue'] as $valuekey => $wphrmEmployeeSettingsvalue) {
                                                    if ($lebalkey == $valuekey) {
                                                        ?>
                                                        <div class="form-group">
                                                            <label class="col-md-4 control-label"><?php _e($wphrmEmployeeSettingsBank, 'wphrm'); ?></label>
                                                            <input name="bank-fields-lebal[]" type="hidden" id="bank-fields-lebal" value="<?php
                                                            if (isset($wphrmEmployeeSettingsBank)) : echo esc_attr($wphrmEmployeeSettingsBank);
                                                            endif;
                                                            ?>"/>
                                                            <div class="col-md-8">
                                                                <input class="form-control" name="bank-fields-value[]" type="text" id="bank-fields-lebal" value="<?php
                                                                if (isset($wphrmEmployeeSettingsvalue)) : echo esc_attr($wphrmEmployeeSettingsvalue);
                                                                endif;
                                                                ?>" autocapitalize="none"  />
                                                            </div>
                                                        </div> 
                                                        <?php
                                                    }
                                                }
                                            }
                                            $wphrmBankFieldsInfo = $this->WPHRMGetSettings('Bankfieldskey');
                                            if (!empty($wphrmBankFieldsInfo)) {
                                                foreach ($wphrmBankFieldsInfo['Bankfieldslebal'] as $wphrmBankFieldsSettings) {
                                                    if (!in_array($wphrmBankFieldsSettings, $wphrmEmployeeBankInfo['wphrmbankfieldslebal'])) {
                                                        ?>
                                                        <div class="form-group">
                                                            <label class="col-md-4 control-label"><?php _e($wphrmBankFieldsSettings, 'wphrm'); ?></label>
                                                            <input name="bank-fields-lebal[]" type="hidden" id="bank-fields-lebal" value="<?php
                                                            if (isset($wphrmBankFieldsSettings)) : echo esc_attr($wphrmBankFieldsSettings);
                                                            endif;
                                                            ?>"/>
                                                            <div class="col-md-8">
                                                                <input class="form-control" name="bank-fields-value[]" type="text" id="bank-fields-lebal" value="" autocapitalize="none"  />
                                                            </div>
                                                        </div> 
                                                        <?php
                                                    }
                                                }
                                            }
                                        }else {
                                            $wphrmBankfieldskeyInfo = $this->WPHRMGetSettings('Bankfieldskey');
                                            if (!empty($wphrmBankfieldskeyInfo)) {
                                                foreach ($wphrmBankfieldskeyInfo['Bankfieldslebal'] as $wphrmBanksettingInfo) {
                                                    ?>
                                                    <div class="form-group">
                                                        <label class="col-md-4 control-label"><?php _e($wphrmBanksettingInfo, 'wphrm'); ?></label>
                                                        <input name="bank-fields-lebal[]" type="hidden" id="bank-fields-lebal" value="<?php
                                                        if (isset($wphrmBanksettingInfo)) : echo esc_attr($wphrmBanksettingInfo);
                                                        endif;
                                                        ?>"/>
                                                        <div class="col-md-8">
                                                            <input class="form-control" name="bank-fields-value[]" type="text" id="bank-fields-value" value="" autocapitalize="none"  />
                                                        </div>
                                                    </div> 
                                                    <?php
                                                }
                                            }
                                        }
                                        ?>
                                    </div>
                                </form>
                            </div>
                        </div>
                    <?php } else { ?>
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
                                    if (isset($wphrmEmployeeEditId)) : echo esc_attr($wphrmEmployeeEditId);
                                    endif;
                                    ?> "/>
                                    <div id="alert_bank"></div>
                                    <div class="form-body">
                                        <div class="form-group">
                                            <label class="col-md-4 control-label"><?php _e('Account Holder Name', 'wphrm'); ?></label>
                                            <div class="col-md-8">
                                                <input readonly class="form-control"  name="wphrm_employee_bank_account_name" type="text" id="wphrm_employee_bank_account_name" value="<?php
                                                if (isset($wphrmEmployeeBankInfo['wphrm_employee_bank_account_name'])) : echo esc_attr($wphrmEmployeeBankInfo['wphrm_employee_bank_account_name']);
                                                endif;
                                                ?>" autocapitalize="none"  />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label"><?php _e('Account Number', 'wphrm'); ?></label>
                                            <div class="col-md-8">
                                                <input readonly class="form-control"  name="wphrm_employee_bank_account_no" type="text" id="wphrm_employee_bank_account_no" value="<?php
                                                if (isset($wphrmEmployeeBankInfo['wphrm_employee_bank_account_no'])) : echo esc_attr($wphrmEmployeeBankInfo['wphrm_employee_bank_account_no']);
                                                endif;
                                                ?>" autocapitalize="none"  />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label"><?php _e('Bank Name', 'wphrm'); ?></label>
                                            <div class="col-md-8">
                                                <input readonly class="form-control" name="wphrm_employee_bank_name" type="text" id="wphrm_employee_bank_name" value="<?php
                                                if (isset($wphrmEmployeeBankInfo['wphrm_employee_bank_name'])) : echo esc_attr($wphrmEmployeeBankInfo['wphrm_employee_bank_name']);
                                                endif;
                                                ?>" autocapitalize="none"  />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label"><?php _e('Branch', 'wphrm'); ?></label>
                                            <div class="col-md-8">
                                                <input readonly class="form-control"  name="wphrm_employee_bank_branch" type="text" id="wphrm_employee_bank_branch" value="<?php
                                                if (isset($wphrmEmployeeBankInfo['wphrm_employee_bank_branch'])) : echo esc_attr($wphrmEmployeeBankInfo['wphrm_employee_bank_branch']);
                                                endif;
                                                ?>" autocapitalize="none"  />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label"><?php _e('IFSC Code', 'wphrm'); ?></label>
                                            <div class="col-md-8">
                                                <input readonly class="form-control"  name="wphrm_employee_bank_ifsc" type="text" id="wphrm_employee_bank_ifsc" value="<?php
                                                if (isset($wphrmEmployeeBankInfo['wphrm_employee_bank_ifsc'])) : echo esc_attr($wphrmEmployeeBankInfo['wphrm_employee_bank_ifsc']);
                                                endif;
                                                ?>" autocapitalize="none"  />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label"><?php _e('PAN Number', 'wphrm'); ?></label>
                                            <div class="col-md-8">
                                                <input readonly  class="form-control" name="wphrm_employee_pannumber" type="text" id="wphrm_employee_pannumber" value="<?php
                                                if (isset($wphrmEmployeeBankInfo['wphrm_employee_pannumber'])) : echo esc_attr($wphrmEmployeeBankInfo['wphrm_employee_pannumber']);
                                                endif;
                                                ?>" autocapitalize="none"  />
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="portlet box red-sunglo">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="fa fa-comment-o"></i><?php _e('Other Details', 'wphrm'); ?>
                            </div>
                            <?php if (isset($wphrmEmployeeEditId) && $wphrmEmployeeEditId != '') { ?>
                                <div class="actions">
                                    <a href="javascript:;"  onclick="jQuery('#wphrmEmployeeOtherInfo_form').submit();" data-loading-text="Updating..."  class="demo-loading-btn btn btn-sm btn-default ">
                                        <i class="fa fa-save" ></i> <?php _e('Save', 'wphrm'); ?> </a>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="portlet-body">
                            <div class="alert alert-success display-hide" id="other_details_success"><i class='fa fa-check-square' aria-hidden='true'></i> <?php echo esc_html($wphrmMessagesOther); ?>
                                <button class="close" data-close="alert"></button>
                            </div>
                            <div class="alert alert-danger display-hide" id="other_details_success_error">
                                <button class="close" data-close="alert"></button>
                            </div>
                            <form method="POST"  accept-charset="UTF-8" class="form-horizontal" id="wphrmEmployeeOtherInfo_form">   
                                <input type="hidden" name="wphrm_employee_id" id="wphrm_employee_id"  value="<?php
                                if (isset($wphrmEmployeeEditId)) : echo esc_attr($wphrmEmployeeEditId);
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
                                                            <input name="other-fields-lebal[]" type="hidden" id="other-fields-lebal" value="<?php
                                                            if (isset($wphrmEmployeeSettingsOther)) : echo esc_attr($wphrmEmployeeSettingsOther);
                                                            endif;
                                                            ?>"/>
                                                            <div class="col-md-8">
                                                                <input class="form-control" name="other-fields-value[]" type="text" id="other-fields-lebal" value="<?php
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
                                                            <input name="other-fields-lebal[]" type="hidden" id="other-fields-lebal" value="<?php
                                                            if (isset($wphrmOtherFieldsSettings)) : echo esc_attr($wphrmOtherFieldsSettings);
                                                            endif;
                                                            ?>"/>
                                                            <div class="col-md-8">
                                                                <input class="form-control" name="other-fields-value[]" type="text" id="other-fields-lebal" value="" autocapitalize="none"  />
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
                                                        <input name="other-fields-lebal[]" type="hidden" id="other-fields-lebal" value="<?php
                                                        if (isset($wphrmOthersettingInfo)) : echo esc_attr($wphrmOthersettingInfo);
                                                        endif;
                                                        ?>"/>
                                                        <div class="col-md-8">
                                                            <input class="form-control" name="other-fields-value[]" type="text" id="other-fields-value" value="" autocapitalize="none"  />
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
                                                                <input class="form-control" readonly="" type="text" id="other-fields-lebal" value="<?php
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
                                                            <div class="col-md-8">
                                                                <input class="form-control" readonly=""  type="text" id="other-fields-lebal" value="" autocapitalize="none"  />
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
                                                            <input class="form-control" readonly=""  type="text" id="other-fields-value" value="" autocapitalize="none"  />
                                                        </div>
                                                    </div> 
                                                    <?php
                                                }
                                            }
                                        }
                                    }
                                    ?>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label"><?php _e('Vehicle', 'wphrm'); ?></label>
                                        <div class="col-md-8">
                                            <label for="wphrm_employee_vehicle" class="control-label"><?php _e('Do you come by vehicle', 'wphrm'); ?></label>
                                            <input class="form-control" style="margin-top:7px;" name="wphrm_employee_vehicle" type="checkbox" id="wphrm_employee_vehicle" <?php
                                            if (isset($wphrmEmployeeOtherInfo['wphrm_employee_vehicle']) != '') : echo esc_attr('checked');
                                            endif;
                                            ?> value="checked" autocapitalize="none"  />                                            
                                        </div>
                                        <div class="col-md-12 wphrm_vehicle_details" style="margin-top:15px;" id="wphrm_vehicle_details">
                                            <div class="form-group">
                                                <label class="col-md-4 control-label" scope="row"><?php _e('Vehicle Type', 'wphrm'); ?>:</label>
                                                <div class="col-md-8">
                                                    <select class="form-control" name="wphrm_vehicle_type" id="wphrm_vehicle_type" >
                                                        <option <?php
                                                        if (isset($wphrmEmployeeOtherInfo['wphrm_vehicle_type']) && $wphrmEmployeeOtherInfo['wphrm_vehicle_type'] == 'Bike') {
                                                            echo esc_attr('selected');
                                                        }
                                                        ?> value="Bike"><?php _e('Bike', 'wphrm'); ?></option>
                                                        <option <?php
                                                        if (isset($wphrmEmployeeOtherInfo['wphrm_vehicle_type']) && $wphrmEmployeeOtherInfo['wphrm_vehicle_type'] == 'Car') {
                                                            echo esc_attr('selected');
                                                        }
                                                        ?> value="Car"><?php _e('Car', 'wphrm'); ?></option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-4 control-label" scope="row"><?php _e('Make-Model', 'wphrm'); ?>:</label>
                                                <div class="col-md-8">
                                                    <input class="form-control" type="text" name="wphrm_employee_vehicle_model" id="wphrm_employee_vehicle_model" value="<?php
                                                    if (isset($wphrmEmployeeOtherInfo['wphrm_employee_vehicle_model'])) : echo esc_attr($wphrmEmployeeOtherInfo['wphrm_employee_vehicle_model']);
                                                    endif;
                                                    ?>"/>
                                                </div>
                                            </div>

                                            <div class="form-group" style="margin-bottom:0px;">
                                                <label class="col-md-4 control-label" scope="row"><?php _e('Registration No.', 'wphrm'); ?>:</label>
                                                <div class="col-md-8">
                                                    <input class="form-control" type="text" name="wphrm_employee_vehicle_registrationno" id="wphrm_employee_vehicle_registrationno" value="<?php
                                                    if (isset($wphrmEmployeeOtherInfo['wphrm_employee_vehicle_registrationno'])) : echo esc_attr($wphrmEmployeeOtherInfo['wphrm_employee_vehicle_registrationno']);
                                                    endif;
                                                    ?>"/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label"><?php _e('T-Shirt Size', 'wphrm'); ?></label>
                                        <div class="col-md-8">
                                            <?php $wphrmTShirtSizes = array('xxxs' => 'XXXS', 'xxs' => 'XXS', 'xs' => 'XS', 's' => 'S', 'm' => 'M', 'l' => 'L', 'xl' => 'XL', 'xxl' => 'XXL', 'xxxl' => 'XXXL'); ?>
                                            <select class="form-control" name="wphrm_t_shirt_size" id="wphrm_t_shirt_size">
                                                <option value=""><?php _e('Select your size', 'wphrm'); ?></option>
                                                <?php foreach ($wphrmTShirtSizes as $key => $size) : ?>
                                                    <option <?php
                                                    if (isset($wphrmEmployeeOtherInfo['wphrm_t_shirt_size']) &&
                                                            $wphrmEmployeeOtherInfo['wphrm_t_shirt_size'] == $key) {
                                                        echo esc_attr('selected');
                                                    }
                                                    ?> 
                                                        value="<?php echo esc_attr($key); ?>"><?php echo esc_html($size); ?></option>
                                                    <?php endforeach; ?>
                                            </select>
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