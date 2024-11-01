<?php
if ( ! defined( 'ABSPATH' ) ) exit;
global $current_user, $wpdb, $wp_query;
$wphrmCurrentuserId = $current_user->ID;
$wphrmUserRole = implode(',', $current_user->roles);
$wphrmUsers =  $this->WPHRMGetEmployees();
$wphrmUserData =  count($wphrmUsers);
?>
<!-- BEGIN PAGE CONTENT-->
<div class="preloader">
<span class="preloader-custom-gif"></span>
</div>
<div style="padding-left: 0px; padding-right:20px; padding-top:0px;" class="col-md-12">
    <!-- BEGIN PAGE HEADER-->
    <h3 class="page-title"><?php _e('Employees', 'wphrm'); ?></h3>
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li><i class="fa fa-home"></i><?php _e('Home', 'wphrm'); ?><i class="fa fa-angle-right"></i></li>
            <li><?php _e('Employees', 'wphrm'); ?></li>        
        </ul>
    </div>
    <!-- END PAGE HEADER-->
    <div class="row">
            <div class="col-md-12">
            <?php
            $employeePro = '';
            $employeeIcon = '';
           if (isset($wphrmUserRole) && $wphrmUserRole == 'administrator') {
               if($wphrmUserData <= 5){
               ?>
              <a class="btn green " href="?page=wphrm-employee-info" data-toggle="modal"><i class="fa fa-plus"></i><?php _e('Add New Employee', 'wphrm'); ?> </a>
              <?php }else{  ?>
              <div class="customTooltip-wrap">
                    <span class="btn green "><i class="fa fa-plus"></i><?php _e('Add New Employee', 'wphrm'); ?></span>
                    <div class="customTooltip nt-left-top nt-small">
                        <?php _e('WP HRM LITE is for upto 5 employees only. For more employees, BUY WPHRM <br><a href="?page=wphrm-pro-version">Pro version.</a>', 'wphrm'); ?>
                    </div>
                </div>
              <?php }
             } else {
                $table = '';
                $employeePro = __('My Profile', 'wphrm');
                $employeeIcon = 'fa fa-edit';
                } ?> 
          
            <div class="portlet box blue calendar">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="<?php if (isset($employeeIcon) && $employeeIcon == '') : echo esc_attr('fa fa-list'); else : echo esc_attr($employeeIcon); endif; ?>"></i>
                        <?php if (isset($employeePro) && $employeePro == '') : _e('Employees', 'wphrm');
                        else : echo esc_html($employeePro); endif; ?>
                    </div>
                </div>
                <div class="portlet-body">
                    <table class="table table-striped table-bordered table-hover" id="wphrmDataTable">
                        <thead>
                            <tr> <th><?php _e('S.No', 'wphrm'); ?></th>
                                <th class="text-center"><?php _e('Name', 'wphrm'); ?>  </th>
                                <th><?php _e('Department', 'wphrm'); ?>  </th>
                                <th><?php _e('Email', 'wphrm'); ?>  </th>
                                <th><?php _e('Phone No.', 'wphrm'); ?> </th>
                                <th> <?php _e('Status', 'wphrm'); ?></th>
                                <th><?php _e('Action', 'wphrm'); ?>  </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i=1;
                            $employeeDepartmentsLoads = '';
                            $wphrmDepartments = '';
                            $wphrmDepartmentInfo = '';
                            if(!empty($wphrmUsers)) :
                                foreach ($wphrmUsers as $key => $userdata) {
                                 if($key <= 4){
                                    $wphrmEmployeeInfo = $this->WPHRMGetUserDatas($userdata->data->ID, 'wphrmEmployeeInfo');
                                    $wphrmEmployeeFirstName = get_user_meta($userdata->data->ID, 'first_name', true);
                                    $wphrmEmployeeLastName = get_user_meta($userdata->data->ID, 'last_name', true);
                                    if (isset($wphrmEmployeeInfo['wphrm_employee_department']) && $wphrmEmployeeInfo['wphrm_employee_department'] != '') {
                                         $employeeDepartmentsLoads = esc_sql($wphrmEmployeeInfo['wphrm_employee_department']); // esc
                                        $wphrmDepartments = $wpdb->get_row("SELECT * FROM  $this->WphrmDepartmentTable  where `departmentID` = '$employeeDepartmentsLoads'");
                                        if ($wphrmDepartments != '') {
                                            $wphrmDepartmentInfo = unserialize(base64_decode($wphrmDepartments->departmentName));
                                        }
                                    }
                                    ?>
                                    <tr id="row">
                                         <td><?php echo esc_html($i); ?></td>
                                        <td class="text-center">
                                            <a href='?page=wphrm-employee-view-details&employee_id=<?php echo esc_attr($userdata->ID); ?>'>
                                            <?php
                                            if (isset($wphrmEmployeeFirstName)) : echo esc_html($wphrmEmployeeFirstName); endif; if (isset($wphrmEmployeeLastName)) : echo ' ' .esc_html( $wphrmEmployeeLastName); endif; ?>
                                            </a>
                                            </td>
                                        <td><?php
                                            if (isset($wphrmDepartmentInfo['departmentName'])) : echo esc_html($wphrmDepartmentInfo['departmentName']);
                                            endif; ?>
                                        </td>
                                        <td> <?php
                                            if (isset($wphrmEmployeeInfo['wphrm_employee_email'])) : echo esc_html($wphrmEmployeeInfo['wphrm_employee_email']);
                                            endif; ?>
                                        </td>
                                        <td><?php
                                            if (isset($wphrmEmployeeInfo['wphrm_employee_phone'])) : echo esc_html($wphrmEmployeeInfo['wphrm_employee_phone']);
                                            endif; ?>
                                        </td>
                                        <td><?php if (isset($wphrmEmployeeInfo['wphrm_employee_status']) && $wphrmEmployeeInfo['wphrm_employee_status'] == 'Active') { ?>  
                                                <span class="label label-sm label-success"><?php _e('Active', 'wphrm'); ?></span>
                                            <?php } else { ?>
                                                <span class="label label-sm label-danger"><?php _e('Inactive', 'wphrm'); ?></span>
                                            <?php } ?>
                                        </td>
                                        <td class="">
                                            <a class="btn purple" href='?page=wphrm-employee-view-details&employee_id=<?php echo esc_attr($userdata->ID); ?>'>
                                                <i class="fa fa-eye"></i><?php _e('View', 'wphrm'); ?>
                                            </a>
                                            <a class="btn blue" href='?page=wphrm-employee-info&employee_id=<?php echo esc_attr($userdata->ID); ?>'>
                                                <i class="fa fa-edit"></i><?php _e('Edit', 'wphrm'); ?>
                                            </a>
                                        </td>
                                    </tr>
                                <?php $i++; } }
                            else : ?>
                                <tr>
                                    <td colspan="7"><?php _e('No employees found in database.', 'wphrm'); ?>
                                    </td><td class="collapse"></td><td class="collapse"></td><td class="collapse"></td><td class="collapse"></td><td class="collapse"></td><td class="collapse"></td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>  
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END PAGE CONTENT-->
 <script>
(function() {
                jQuery('.customTooltip-wrap').customTooltip();
            })();</script>