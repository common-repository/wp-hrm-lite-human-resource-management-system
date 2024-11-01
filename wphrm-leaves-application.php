<?php
if ( ! defined( 'ABSPATH' ) ) exit;
global $current_user, $wpdb, $wp_query;
$wphrmCurrentuserId = $current_user->ID;
$wphrmUserRole = implode(',', $current_user->roles);
$wphrmLeaveUpdateMessages = $this->WPHRMGetMessage(32);
$wphrmLeaveDoneMessages = $this->WPHRMGetMessage(33);
?>
<!-- BEGIN PAGE HEADER-->
<div class="preloader">
<span class="preloader-custom-gif"></span>
</div>
<div id="add_static" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-close"></i></button>
                <h4 class="modal-title"><strong><i class="fa fa-edit"></i> <?php _e('Leave Application', 'wphrm'); ?></strong></h4>
            </div>	
            <div class="modal-body">
                <div class="portlet-body form">
                     <div class="alert alert-success display-hide" id="wphrm_add_leave_applications_success"><i class='fa fa-check-square' aria-hidden='true'></i> <?php echo esc_html($wphrmLeaveDoneMessages); ?>
                        <button class="close" data-close="alert"></button>
                    </div>
                    <div class="alert alert-danger display-hide" id="wphrm_add_leave_applications_error">
                        <button class="close" data-close="alert"></button>
                    </div>
                    <!-- BEGIN FORM-->
                    <form method="POST"  accept-charset="UTF-8" class="form-horizontal" id="wphrm_user_leave_applications_frm">
                        <div class="form-body">
                            <input  type="hidden" id="wphrm_employeeID" name="wphrm_employeeID" value="<?php if(isset($wphrmCurrentuserId)): echo esc_attr($wphrmCurrentuserId); endif; ?>"/>
                            <input  type="hidden" id="wphrm_status" name="wphrm_status" value="absent"/>
                            <input  type="hidden" id="wphrm_application_status" name="wphrm_application_status" value="pending"/>
                             <input  type="hidden" id="wphrm_attendanceID" name="wphrm_attendanceID" />
                            
                            <div class="form-group">
                                <label class="control-label col-md-3"><?php _e('Leave Type', 'wphrm'); ?>  </label>
                                <div class="col-md-8">
                                    <select class="form-control" name="wphrm_leavetype" id="wphrm_leavetype">
                                        <option value=""><?php _e('Select leave type', 'wphrm'); ?></option>
                                        <?php
                                        $selected ='';
                                        $wphrm_leavetypes = $wpdb->get_results("SELECT * FROM  $this->WphrmLeaveTypeTable"); 
                                        foreach ($wphrm_leavetypes as $key => $wphrm_leavetype) { ?>
                                        <option value="<?php if(isset($wphrm_leavetype->leaveType)) : echo esc_attr($wphrm_leavetype->leaveType) ;  endif; ?>"><?php if(isset($wphrm_leavetype->leaveType)) : echo esc_attr($wphrm_leavetype->leaveType); endif; ?></option>
                                        <?php } ?>
                                     </select>
                                  
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3"><?php _e('Leave Date', 'wphrm'); ?>  </label>
                                <div class="col-md-8">
                                    <input class="form-control form-control-inline input-medium after-current-date" data-date-format="dd-mm-yyyy"  type="text" name="wphrm_leavedate" id="wphrm_leavedate" placeholder="Leave Date" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3"><?php _e('Reason', 'wphrm'); ?></label>
                                <div class="col-md-8">
                                    <textarea class="form-control form-control-inline " rows="2"  name="wphrm_reason" id="wphrm_reason" placeholder="Reason"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-offset-3 col-md-9">
                                    <button type="submit"  class="demo-loading-btn btn blue"><i class="fa fa-edit"></i><?php _e('Apply Leave', 'wphrm'); ?></button>
                                    <button type="button" data-dismiss="modal" aria-hidden="true" class="btn red"><i class="fa fa-times"></i><?php _e('Cancel', 'wphrm'); ?></button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!-- END FORM-->
                </div>
            </div>
            <!-- END EXAMPLE TABLE PORTLET-->
        </div>
    </div>
</div>
<div id="edit_static" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-close"></i></button>
                <h4 class="modal-title"><strong><i class="fa fa-edit"></i><?php _e('Leave Application', 'wphrm'); ?> </strong></h4>
            </div>
            <div class="modal-body">
                <div class="portlet-body form">
                     <div class="alert alert-success display-hide" id="wphrm_edit_application_success"><i class='fa fa-check-square' aria-hidden='true'></i> <?php echo esc_html($wphrmLeaveUpdateMessages); ?>
                                <button class="close" data-close="alert"></button>
                            </div>
                            <div class="alert alert-danger display-hide" id="wphrm_edit_application_error">
                                <button class="close" data-close="alert"></button>
                            </div>
                    <!-- BEGIN FORM-->
                    <form method="POST"  accept-charset="UTF-8" class="form-horizontal" id="wphrm_leave_applications_frm">
                        <div class="form-body">
                            <div class="form-group">
                                <label class="control-label col-md-3"><?php _e('Name', 'wphrm'); ?>  </label>
                                <div class="col-md-8">
                                    <input class="form-control form-control-inline " readonly type="text" value="" id="application_name" placeholder="Name" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3"><?php _e('Leave Type', 'wphrm'); ?>  </label>
                                <div class="col-md-8">
                                    <input class="form-control form-control-inline " readonly type="text" value="" id="application_leavetype" placeholder="Leave Type" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3"><?php _e('Leave Date', 'wphrm'); ?>  </label>
                                <div class="col-md-8">
                                    <input class="form-control form-control-inline " readonly type="text" value="" id="application_leavedate" placeholder="Leave Date" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3"><?php _e('Reason', 'wphrm'); ?></label>
                                <div class="col-md-8">
                                    <textarea class="form-control form-control-inline " rows="2" readonly type="text" id="application_reason" placeholder="Reason"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3"><?php _e('Applied On', 'wphrm'); ?></label>
                                <div class="col-md-8">
                                    <input class="form-control form-control-inline " readonly type="text" value="" id="application_appliedon" placeholder="Applied On " />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3"><?php _e('Application Status', 'wphrm'); ?></label>
                                <div class="col-md-8">
                                    <select id="applicationStatus" name="applicationStatus" class="form-control form-control-inline ">
                                      <option  value="approved"><?php _e('Approved', 'wphrm'); ?></option>
                                        <option value="rejected"><?php _e('Rejected', 'wphrm'); ?></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-offset-3 col-md-9">
                                    <button type="submit" data-loading-text="Updating..." class="demo-loading-btn btn blue"><i class="fa fa-edit"></i><?php _e('Update', 'wphrm'); ?> </button>
                                     <button type="button" data-dismiss="modal" aria-hidden="true" class="btn red"><i class="fa fa-times"></i><?php _e('Cancel', 'wphrm'); ?></button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!-- END FORM-->
                </div>
            </div>
            <!-- END EXAMPLE TABLE PORTLET-->
        </div>
    </div>
</div>
<div id="deleteModal" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-close"></i></button>
                <h4 class="modal-title"><?php _e('Confirmation', 'wphrm'); ?></h4>
            </div>
            <div class="modal-body" id="info"><p><?php _e('Are you sure you want to delete', 'wphrm'); ?>?</p></div>
            <div class="modal-footer">
               
                <button type="button" data-dismiss="modal" class="btn red" id="delete"><i class="fa fa-trash"></i><?php _e('Delete', 'wphrm'); ?> </button>
                  <button type="button" data-dismiss="modal" aria-hidden="true" class="btn default"><i class="fa fa-times"></i><?php _e('Cancel', 'wphrm'); ?></button>
            </div>
        </div>
    </div>
</div>
<h3 class="page-title">
   <?php _e('Leave Management', 'wphrm'); ?>
</h3>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li><i class="fa fa-home"></i><?php _e('Home', 'wphrm'); ?><i class="fa fa-angle-right"></i></li>
        <li><?php _e('Leave Management', 'wphrm'); ?></li>
    </ul>
</div>
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div style="padding-left: 0px; padding-right:20px; padding-top:0px;" class="col-md-12">
    <div class="row">
        <div class="col-md-12">
            <?php if (isset($wphrmUserRole) && $wphrmUserRole == 'administrator') { ?>
                    <a class="btn green" href="?page=wphrm-leave-type" data-toggle="modal"><i class="fa fa-repeat"></i><?php _e('Leave Types', 'wphrm'); ?></a>
            <?php } else { ?>
                    <a class="btn green" data-toggle="modal" href="#add_static"><i class="fa fa-plus"></i><?php _e('Create Leave Application', 'wphrm'); ?></a>
            <?php } ?>           
            <div class="portlet box blue calendar">
                <div class="portlet-title">
                    <div class="caption"> <i class="fa fa-list"></i><?php _e('List of Leave Applications ', 'wphrm'); ?></div>
                </div>
                <div class="portlet-body">
                  <?php if (isset($wphrmUserRole) && $wphrmUserRole == 'administrator') { ?>
                <table class="table table-striped table-bordered table-hover" id="wphrmDataTable">
                        <thead>
                            <tr>  
                                <th><?php _e('S.No', 'wphrm'); ?></th>
                                <th><?php _e('Name', 'wphrm'); ?></th>
                                <th><?php _e('Date', 'wphrm'); ?></th>
                                <th><?php _e('Leave Type', 'wphrm'); ?></th>
                                <th><?php _e('Reason', 'wphrm'); ?></th>
                                <th><?php _e('Applied on', 'wphrm'); ?></th>
                                <th><?php _e('Status', 'wphrm'); ?></th>
                                <th><?php _e('Actions', 'wphrm'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i=1;
                            $wphrm_leaveapplication = $wpdb->get_results("SELECT * FROM $this->WphrmAttendanceTable WHERE `applicationStatus`!=''");
                            if(!empty($wphrm_leaveapplication)) :
                            foreach ($wphrm_leaveapplication as $key => $wphrm_leaveapplications) {
                                $wphrmEmployeeInfo_load = get_user_meta($wphrm_leaveapplications->employeeID, 'wphrmEmployeeInfo', true);
                                $wphrmEmployeeInfo = unserialize(base64_decode($wphrmEmployeeInfo_load)); ?>
                                <tr>  
                                     <td><?php echo esc_html($i); ?></td>
                                    <td>
                                        <?php if (isset($wphrmEmployeeInfo['wphrm_employee_fname'])) : echo esc_html($wphrmEmployeeInfo['wphrm_employee_fname']); endif;
                                        if (isset($wphrmEmployeeInfo['wphrm_employee_lname'])) : echo ' ' . esc_html($wphrmEmployeeInfo['wphrm_employee_lname']); endif; ?>
                                    </td>
                                    <td>
                                        <?php if (isset($wphrm_leaveapplications->date)) : echo esc_html(date('d-m-Y', strtotime($wphrm_leaveapplications->date))); endif; ?>
                                    </td>
                                    <td>
                                        <?php if (isset($wphrm_leaveapplications->leaveType)) : echo esc_html($wphrm_leaveapplications->leaveType); endif; ?>
                                    </td>
                                    <td>
                                        <?php if (isset($wphrm_leaveapplications->reason)) : echo esc_html(esc_html($wphrm_leaveapplications->reason)); endif; ?>
                                    </td>
                                    <td>
                                        <?php if (isset($wphrm_leaveapplications->appliedOn)) : echo esc_html(date('d-m-Y', strtotime($wphrm_leaveapplications->appliedOn))); endif; ?>
                                    </td>
                                    <td>
                                        <?php if (isset($wphrm_leaveapplications->applicationStatus) && $wphrm_leaveapplications->applicationStatus == 'pending') { ?> <span class="label label-warning"><?php _e('pending', 'wphrm'); ?></span> <?php } else if (isset($wphrm_leaveapplications->applicationStatus) && $wphrm_leaveapplications->applicationStatus == 'approved') { ?>
                                            <span class="label label-success"><?php _e('Approved', 'wphrm'); ?></span>
                                        <?php } else if (isset($wphrm_leaveapplications->applicationStatus) && $wphrm_leaveapplications->applicationStatus == 'rejected') { ?>
                                            <span class="label label-danger"><?php _e('Rejected', 'wphrm'); ?></span>
                                        <?php } ?>
                                    </td>
                                    <td> 
                                        <a class="btn purple" data-toggle="modal" href="#edit_static" onclick="applicationEdit(<?php ?><?php 
                                        if (isset($wphrm_leaveapplications->id)) :  echo esc_js($wphrm_leaveapplications->id); endif; ?>,'<?php 
                                        if (isset($wphrm_leaveapplications->employeeID)) :  echo esc_js($wphrm_leaveapplications->employeeID); endif; ?>', '<?php
                                        if (isset($wphrmEmployeeInfo['wphrm_employee_fname'])) : echo esc_js($wphrmEmployeeInfo['wphrm_employee_fname']);
                                        endif; if (isset($wphrmEmployeeInfo['wphrm_employee_lname'])) : echo ' ' . esc_js($wphrmEmployeeInfo['wphrm_employee_lname']);
                                        endif; ?>','<?php if (isset($wphrm_leaveapplications->date)) : echo esc_js(date('d-m-Y', strtotime($wphrm_leaveapplications->date)));
                                        endif; ?>','<?php if (isset($wphrm_leaveapplications->leaveType)) : echo esc_js($wphrm_leaveapplications->leaveType);
                                        endif; ?>','<?php if (isset($wphrm_leaveapplications->reason)) : echo $wphrm_leaveapplications->reason;
                                        endif; ?>','<?php if (isset($wphrm_leaveapplications->appliedOn)) : echo esc_js(date('d-m-Y', strtotime($wphrm_leaveapplications->appliedOn)));
                                        endif; ?>','<?php if (isset($wphrm_leaveapplications->applicationStatus)) : echo esc_js($wphrm_leaveapplications->applicationStatus);
                                        endif; ?>')"><i class="fa fa-edit"></i><?php _e('View/Edit', 'wphrm'); ?></a>
                                        <a class="btn red" href="javascript:;" onclick="WPHRMCustomDelete(<?php if (isset($wphrm_leaveapplications->id)) :  echo esc_js($wphrm_leaveapplications->id); endif; ?>, '<?php echo esc_js($this->WphrmAttendanceTable) ?>', 'id')"><i class="fa fa-trash"></i><?php _e('Delete', 'wphrm'); ?> </a>
                                    </td>
                                </tr>
                            <?php $i++; }
                            else : ?>
                                <tr>
                                    <td colspan="8"><?php _e('No leave applications data found in database.', 'wphrm'); ?>
                                    </td><td class="collapse"></td><td class="collapse"></td><td class="collapse"></td><td class="collapse"></td><td class="collapse"></td><td class="collapse"></td><td class="collapse"></td>
                                </tr>
                        <?php endif; ?>
                        </tbody>
                </table>
        <?php } else { ?>
                      <table class="table table-striped table-bordered table-hover" id="wphrmDataTable">
                        <thead>
                            <tr>                          
                                <th><?php _e('Name', 'wphrm'); ?></th>
                                <th><?php _e('Date', 'wphrm'); ?></th>
                                <th><?php _e('Leave Type', 'wphrm'); ?></th>
                                <th><?php _e('Reason', 'wphrm'); ?></th>
                                <th><?php _e('Applied on', 'wphrm'); ?></th>
                                <th><?php _e('Status', 'wphrm'); ?></th>
                                <th><?php _e('Actions', 'wphrm'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $wphrm_leaveapplication = $wpdb->get_results("SELECT * FROM $this->WphrmAttendanceTable WHERE `applicationStatus`!= '' AND `employeeID` = '$wphrmCurrentuserId'");
                            if(!empty($wphrm_leaveapplication)) :
                                foreach ($wphrm_leaveapplication as $key => $wphrm_leaveapplications) { 
                                    $wphrmEmployeeInfo_load = get_user_meta($wphrmCurrentuserId, 'wphrmEmployeeInfo', true);
                                    $wphrmEmployeeInfo = unserialize(base64_decode($wphrmEmployeeInfo_load)); ?>
                                    <tr>                                   
                                    <td><?php
                                        if (isset($wphrmEmployeeInfo['wphrm_employee_fname'])) : echo esc_html($wphrmEmployeeInfo['wphrm_employee_fname']);
                                        endif;
                                        if (isset($wphrmEmployeeInfo['wphrm_employee_lname'])) : echo ' ' . esc_html($wphrmEmployeeInfo['wphrm_employee_lname']);
                                        endif; ?></td>
                                    <td><?php
                                        if (isset($wphrm_leaveapplications->date)) : echo esc_html(date('d-m-Y', strtotime($wphrm_leaveapplications->date)));
                                        endif; ?></td>
                                    <td><?php
                                        if (isset($wphrm_leaveapplications->leaveType)) : echo esc_html($wphrm_leaveapplications->leaveType);
                                        endif; ?></td>
                                    <td><?php
                                        if (isset($wphrm_leaveapplications->reason)) : echo esc_html($wphrm_leaveapplications->reason);
                                        endif; ?></td>
                                    <td><?php
                                        if (isset($wphrm_leaveapplications->appliedOn)) : echo esc_html(date('d-m-Y', strtotime($wphrm_leaveapplications->appliedOn)));
                                        endif; ?>
                                    </td>
                                    <td><?php if (isset($wphrm_leaveapplications->applicationStatus) && $wphrm_leaveapplications->applicationStatus == 'pending') { ?>
                                        <span class="label label-warning"><?php _e('pending', 'wphrm'); ?></span> <?php } else if (isset($wphrm_leaveapplications->applicationStatus) && $wphrm_leaveapplications->applicationStatus == 'approved') { ?>
                                        <span class="label label-success"><?php _e('Approved', 'wphrm'); ?></span>
                                    <?php } else if (isset($wphrm_leaveapplications->applicationStatus) && $wphrm_leaveapplications->applicationStatus == 'rejected') { ?>
                                        <span class="label label-danger"><?php _e('Rejected', 'wphrm'); ?></span>
                                    <?php } ?>
                                    </td>
                                    <td>                                        
                                        <?php if (isset($wphrm_leaveapplications->applicationStatus) && $wphrm_leaveapplications->applicationStatus =='approved'){}else{ ?>
                                        <a class="btn purple" data-toggle="modal" href="#add_static" onclick="user_staticEdit(<?php if (isset($wphrm_leaveapplications->id)) :  echo esc_js($wphrm_leaveapplications->id); endif; ?>,'<?php
                                        if (isset($wphrm_leaveapplications->date)) : echo  esc_js(date('d-m-Y', strtotime($wphrm_leaveapplications->date)));
                                        endif; ?>','<?php if (isset($wphrm_leaveapplications->leaveType)) : echo esc_js($wphrm_leaveapplications->leaveType);
                                        endif; ?>','<?php if (isset($wphrm_leaveapplications->reason)) : echo esc_js($wphrm_leaveapplications->reason);
                                        endif; ?>')">
                                            <i class="fa fa-edit"></i><?php _e('View/Edit', 'wphrm'); ?> 
                                        </a>
                                        <?php } ?>
                                    </td>
                                    </tr>
                                <?php  }
                            else : ?>
                                <tr>
                                    <td colspan="7"><?php _e('No leave applications data found in database.', 'wphrm'); ?>
                                    </td><td class="collapse"></td><td class="collapse"></td><td class="collapse"></td><td class="collapse"></td><td class="collapse"></td><td class="collapse"></td>
                                </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
            <?php } ?>                  
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END PAGE CONTENT-->