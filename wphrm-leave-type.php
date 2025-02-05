<?php
if ( ! defined( 'ABSPATH' ) ) exit;
global $current_user, $wpdb;
$wphrmUserRole = implode(',', $current_user->roles);
$readonly_class = '';
$readonly = '';
$edit_mode = false;
$wphrm_messages_leave_type_add = $this->WPHRMGetMessage(19);
$wphrm_messages_leave_type_update = $this->WPHRMGetMessage(18);
$wphrm_messages_leave_type_delete = $this->WPHRMGetMessage(20);
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
                <h4 class="modal-title"><strong><i class="fa fa-edit"></i> <?php _e('Add Leave Type', 'wphrm'); ?></strong></h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-success display-hide" id="wphrm_add_leavetype_success"><i class='fa fa-check-square' aria-hidden='true'></i> <?php echo esc_html($wphrm_messages_leave_type_add); ?>
                    <button class="close" data-close="alert"></button>
                </div>
                <div class="alert alert-danger display-hide" id="wphrm_add_leavetype_error">
                    <button class="close" data-close="alert"></button>
                </div>
                <div class="portlet-body form">
                    <!-- BEGIN FORM-->
                    <form method="POST" accept-charset="UTF-8" class="form-horizontal" id="wphrm_add_leavetype_form">
                        <div class="form-body">
                            <div class="form-group">
                                <label class="control-label col-md-3"><?php _e('Leave Type', 'wphrm'); ?>  </label>
                                <div class="col-md-8">
                                    <input class="form-control form-control-inline " name="leaveType" id="add_leaveType" type="text" value="" placeholder="<?php _e('Leave Type', 'wphrm'); ?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3"><?php _e('Time Period', 'wphrm'); ?></label>
                                <div class="col-md-8">
                                    <select class="form-control" name="wphrm_period" id="wphrm_period">
                                        <option value=""><?php _e('Select Time Period', 'wphrm'); ?></option>
                                        <option value="<?php _e('Monthly', 'wphrm'); ?>"><?php _e('Monthly', 'wphrm'); ?></option>
                                        <option value="<?php _e('Quarterly', 'wphrm'); ?>"><?php _e('Quarterly', 'wphrm'); ?></option>
                                        <option value="<?php _e('Yearly', 'wphrm'); ?>"><?php _e('Yearly', 'wphrm'); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3"><?php _e('No of Leave', 'wphrm'); ?>  </label>
                                <div class="col-md-8">
                                    <input class="form-control form-control-inline " name="numberOfLeave" id="add_num_of_leave" type="text" value="" placeholder="<?php _e('Number Of Leave', 'wphrm'); ?>" />
                                </div></div>
                        </div>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-offset-3 col-md-9">
                                    <button type="submit"  class="btn blue"><i class="fa fa-plus"></i><?php _e('Add', 'wphrm'); ?> </button>
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
                <h4 class="modal-title"><strong><i class="fa fa-edit"></i><?php _e('Edit LeaveTypes', 'wphrm'); ?></strong></h4>
            </div>
            <div class="modal-body">
                <div class="portlet-body form">
                    <div class="alert alert-success display-hide" id="wphrm_edit_leavetype_success"><i class='fa fa-check-square' aria-hidden='true'></i> <?php echo esc_html($wphrm_messages_leave_type_update); ?>
                        <button class="close" data-close="alert"></button>
                    </div>
                    <div class="alert alert-danger display-hide" id="wphrm_edit_leavetype_error">
                        <button class="close" data-close="alert"></button>
                    </div>
                    <!-- BEGIN FORM-->
                    <form method="POST"  accept-charset="UTF-8" class="form-horizontal" id="wphrm_edit_leavetype_form">
                        <div class="form-body">
                            
                            <div class="form-group">
                                <label class="control-label col-md-3"><?php _e('Leave Type', 'wphrm'); ?>  </label>
                                <div class="col-md-8">
                                    <input class="form-control form-control-inline " name="leaveType" id="edit_leaveType" type="text" value="" placeholder="LeaveType" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3"><?php _e('Time Period', 'wphrm'); ?></label>
                                <div class="col-md-8">
                                    <select class="form-control" name="wphrm_period" id="edit_wphrm_period">
                                        <option value=""><?php _e('SelectTime Period', 'wphrm'); ?></option>
                                        <option value="<?php _e('Monthly', 'wphrm'); ?>"><?php _e('Monthly', 'wphrm'); ?></option>
                                        <option value="<?php _e('Quarterly', 'wphrm'); ?>"><?php _e('Quarterly', 'wphrm'); ?></option>
                                        <option value="<?php _e('Yearly', 'wphrm'); ?>"><?php _e('Yearly', 'wphrm'); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3"><?php _e('No of Leave', 'wphrm'); ?>  </label>
                                <div class="col-md-8">
                                    <input class="form-control form-control-inline " name="numberOfLeave" id="edit_num_of_leave" type="text" value="" placeholder="Number Of Leave" />
                                </div></div>
                        </div>
                       <div class="form-actions">
                            <div class="row">
                                <div class="col-md-offset-3 col-md-9">
                                    <button type="submit"  class="btn blue"><i class="fa fa-edit"></i><?php _e('Update', 'wphrm'); ?> </button>
                                    <button type="button" data-dismiss="modal" aria-hidden="true" class="btn red"><i class="fa fa-times"></i> Close</button>
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
            <div class="alert alert-success display-hide" id="WPHRMCustomDelete_success"><i class='fa fa-check-square' aria-hidden='true'></i> <?php echo esc_html($wphrm_messages_leave_type_delete); ?>
                <button class="close" data-close="alert"></button>
            </div>
            <div class="alert alert-danger display-hide" id="WPHRMCustomDelete_success">
                <button class="close" data-close="alert"></button>
            </div>
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
    <?php _e('Leave Types', 'wphrm'); ?>
</h3>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li><i class="fa fa-home"></i><?php _e('Home', 'wphrm'); ?><i class="fa fa-angle-right"></i></li>
        <li><?php _e('Leave Types', 'wphrm'); ?> </li>
    </ul>
</div>
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div style="padding-left: 0px; padding-right:20px; padding-top:0px;" class="col-md-12">
    <div class="row">
        <div class="col-md-12">
            <a class="btn green " href="#add_static" data-toggle="modal"><i class="fa fa-plus"></i><?php _e('Add New Leave Type', 'wphrm'); ?></a>
            <a class="btn green " href="?page=wphrm-leaves-application"><i class="fa fa-arrow-left"></i><?php _e('Back', 'wphrm'); ?> </a>
            <div class="portlet box blue calendar">
                <div class="portlet-title">
                    <div class="caption"> <i class="fa fa-list"></i><?php _e('List of Leave Types', 'wphrm'); ?>  </div>
                </div>
                <div class="portlet-body">
                    <table class="table table-striped table-bordered table-hover" id="wphrmDataTable">
                        <thead>
                            <tr> <th><?php _e('S.No', 'wphrm'); ?></th>
                                <th><?php _e('Leave Type', 'wphrm'); ?></th>
                                <th><?php _e('Time Period', 'wphrm'); ?></th>
                                <th><?php _e('No. of leave', 'wphrm'); ?></th>
                                <th><?php _e('Actions', 'wphrm'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i=1;
                            $wphrm_leavetype = $wpdb->get_results("SELECT * FROM   $this->WphrmLeaveTypeTable");
                            foreach ($wphrm_leavetype as $key => $wphrm_leavetypes) { ?>
                                <tr>
                                     <td><?php echo esc_html($i); ?></td>
                                    <td> <?php if (isset($wphrm_leavetypes->leaveType)): echo esc_html($wphrm_leavetypes->leaveType); endif; ?> </td>
                                    <td> <?php if (isset($wphrm_leavetypes->period)): echo esc_html($wphrm_leavetypes->period); endif; ?> </td>
                                    <td> <?php if (isset($wphrm_leavetypes->numberOfLeave)): echo esc_html($wphrm_leavetypes->numberOfLeave); endif; ?> </td>
                                    <td>
                                        <a class="btn purple" data-toggle="modal" href="#edit_static" onclick="leavetypeEdit(<?php if (isset($wphrm_leavetypes->id)): echo esc_js($wphrm_leavetypes->id); endif; ?>, '<?php if (isset($wphrm_leavetypes->leaveType)): echo esc_js($wphrm_leavetypes->leaveType);
                                endif; ?>','<?php if (isset($wphrm_leavetypes->period)): echo esc_js($wphrm_leavetypes->period);
                                endif; ?>', '<?php if (isset($wphrm_leavetypes->numberOfLeave)): echo esc_js($wphrm_leavetypes->numberOfLeave);
                                endif; ?>')"> <i class="fa fa-edit"></i><?php _e('View/Edit', 'wphrm'); ?></a>
                                            <a class="btn red" href="javascript:;" onclick="WPHRMCustomDelete(<?php if (isset($wphrm_leavetypes->id)): echo esc_js($wphrm_leavetypes->id); endif; ?>, '<?php echo esc_js($this->WphrmLeaveTypeTable) ?>', 'id')">
                                           <i class="fa fa-trash"></i><?php _e('Delete', 'wphrm'); ?></a>
                                       </td>
                                </tr>
                            <?php $i++; } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END PAGE CONTENT-->