<?php
    if ( ! defined( 'ABSPATH' ) ) exit;
    global $current_user, $wpdb, $wp_query;
    $wphrmUserRole = implode(',', $current_user->roles);
    $wphrmUsers = $this->WPHRMGetEmployees();
?>
<!-- BEGIN PAGE HEADER-->
<div class="preloader">
<span class="preloader-custom-gif"></span>
</div>
<h3 class="page-title"><?php _e('Attendance Management', 'wphrm'); ?></h3>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li><i class="fa fa-home"></i><?php _e('Home', 'wphrm'); ?><i class="fa fa-angle-right"></i></li>
        <li><?php _e('Attendance Management', 'wphrm'); ?></li>
    </ul>
</div>
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div style="padding-left: 0px; padding-right:20px; padding-top:0px;" class="col-md-12">
    <div class="row">
        <div class="col-md-12">
            <?php if($wphrmUserRole=='administrator') { ?>
                <a class="btn green " href="?page=wphrm-mark-attendance" data-toggle="modal"><i class="fa fa-plus"></i><?php _e('Mark Attendance ', 'wphrm'); ?> </a>
                 <a class="btn green " href="?page=wphrm-mark-attendance&status='edit'" data-toggle="modal"><i class="fa fa-edit"></i><?php _e('Edit Attendance ', 'wphrm'); ?> </a>
            <?php } ?>
            <div class="portlet box blue calendar">
                <div class="portlet-title">
                     <?php if($wphrmUserRole=='administrator') { ?>
                    <div class="caption"><i class="fa fa-list"></i><?php _e('List of Attendance', 'wphrm'); ?></div>
                     <?php } else{ ?> 
                   <div class="caption"><i class="fa fa-edit"></i><?php _e('Attendance Details', 'wphrm'); ?></div>      
                         <?php } ?>
                </div>
                <div class="portlet-body">
                       <?php if($wphrmUserRole=='administrator') { ?>
                     <table class="wphrmtable table table-striped table-bordered table-hover" id="wphrmDataTable" >
                    <?php } else { ?>
                     <table class="wphrmtable table table-striped table-bordered table-hover"><?php } ?>
                         <thead>
                            <tr>
                                <th><?php _e('EmployeeID', 'wphrm'); ?></th>
                                <th class="text-center"><?php _e('Image', 'wphrm'); ?></th>
                                <th><?php _e('Name', 'wphrm'); ?></th>
                                <th><?php _e('Last Absent', 'wphrm'); ?></th>
                                <th><?php _e('Leaves', 'wphrm'); ?></th>
                                <th><?php _e('Status', 'wphrm'); ?></th>
                                <th><?php _e('Actions', 'wphrm'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $attendance_date = esc_sql(date('Y-m-d')); // esc
                            $todaydate = esc_sql('0'); // esc
                            $employeeAttendanceCount = esc_sql('0'); // esc
                            $currentMonth = date('m'); // esc
                            foreach ($wphrmUsers as $key => $userdata) {
                                if($key <= 4){
                                $wphrmEmployeeInfo = $this->WPHRMGetUserDatas($userdata->ID, 'wphrmEmployeeInfo');
                                if (isset($wphrmEmployeeInfo['wphrm_employee_status']) && $wphrmEmployeeInfo['wphrm_employee_status'] == 'Active') {
                                    $getAttendancebyId = $wpdb->get_row("select * from $this->WphrmAttendanceTable where `date` = '" . $attendance_date . "' and `employeeID` ='" . $userdata->ID . "'");
                                    $lastAbsent = $wpdb->get_results("select * from $this->WphrmAttendanceTable where  `employeeID` ='" . $userdata->ID . "' and `status` = 'absent' and `date` <= '$attendance_date' order by id desc");
                                    if (!empty($lastAbsent)) {
                                       
                                        if ($lastAbsent[0]->date == $attendance_date) {
                                            $todaydate = 'today';
                                        } else {
                                            $now = time(); // or your date as well
                                            $yourDate = date('d-F-Y',strtotime($lastAbsent[0]->date));
//                                            $datediff = $now - $yourDate;
//                                            $beforeday = floor($datediff / (60 * 60 * 24));
                                            $todaydate = $yourDate;
                                        }
                                    } else { $todaydate = '0'; } ?>
                                    <tr id="row">
                                        <td>
                                            <?php if (isset($wphrmEmployeeInfo['wphrm_employee_uniqueid'])) : 
                                                echo esc_html($wphrmEmployeeInfo['wphrm_employee_uniqueid']); 
                                            endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if (isset($wphrmEmployeeInfo['employee_profile']) && $wphrmEmployeeInfo['employee_profile'] != '') { ?>
                                                <img src="<?php if (isset($wphrmEmployeeInfo['employee_profile'])) : echo esc_attr($wphrmEmployeeInfo['employee_profile']); endif; ?>" width="100"><br>
                                                    <?php } else {
                                                    if (isset($wphrmEmployeeInfo['wphrm_employee_gender']) && $wphrmEmployeeInfo['wphrm_employee_gender'] == 'Male') { ?>
                                                        <img src="<?php echo esc_attr(plugins_url('assets/images/default-male.jpeg', __FILE__)); ?>" width="100">
                                                <?php } else { ?>
                                                    <img src="<?php echo esc_attr(plugins_url('assets/images/default-female.jpeg', __FILE__)); ?>" width="100">  
                                                <?php }
                                            } ?>
                                        </td>
                                        <td> <a  href="?page=wphrm-view-attendance&employee_id=<?php echo esc_attr($userdata->ID); ?>">
                                            <?php
                                            if (isset($wphrmEmployeeInfo['wphrm_employee_fname'])) : echo esc_html($wphrmEmployeeInfo['wphrm_employee_fname']);
                                            endif;
                                            ?><?php
                                            if (isset($wphrmEmployeeInfo['wphrm_employee_lname'])) : echo ' ' . esc_html($wphrmEmployeeInfo['wphrm_employee_lname']);
                                            endif;
                                            ?></a></td>
                                        <td><?php echo esc_html($todaydate); ?> </td>
                                        <td > 
                                            <table class="leaves">
                                            <?php
                                            if(isset($wphrmEmployeeInfo['wphrm_employee_joining_date'])){
                                                $wphrmEmployeeJoiningDate = $wphrmEmployeeInfo['wphrm_employee_joining_date'];
                                            }
                                            $wphrmEmployeeJoiningDate = new DateTime($wphrmEmployeeJoiningDate);
                                            $today = new DateTime();
                                            $interval = $today->diff($wphrmEmployeeJoiningDate);
                                            $wphrmEmployeeJoiningToCurrentTotalYear = ((int)$interval->format('%y years')+1);
                                            $curQuarter = ceil($currentMonth/3);
                                            $leavesTypes = $wpdb->get_results("SELECT * FROM $this->WphrmLeaveTypeTable");
                                            foreach ($leavesTypes as $leavesType) {
                                                $totalNoOfLeave = 0;
                                                if($leavesType->period=='Monthly') {
                                                    $totalNoOfLeave = intval($leavesType->numberOfLeave * $currentMonth);
                                                } else if($leavesType->period=='Quarterly') {
                                                    $totalNoOfLeave = intval($leavesType->numberOfLeave * $curQuarter);
                                                } else if($leavesType->period=='Yearly') {
                                                    $totalNoOfLeave = intval($leavesType->numberOfLeave * $wphrmEmployeeJoiningToCurrentTotalYear);
                                                } 
                                                $employeeLeaves = $wpdb->get_row("SELECT COUNT(id) AS leaveCounter FROM $this->WphrmAttendanceTable WHERE `status`='absent' AND `employeeID` ='" . $userdata->ID . "' AND `leaveType`='$leavesType->leaveType' AND `date` <= '$attendance_date' AND `applicationStatus`='approved'");
                                                ?><tr><td style="text-align: right;"> <?php echo esc_html($leavesType->leaveType) . ' : '; ?></td> <?php
                                                if ($totalNoOfLeave >= $employeeLeaves->leaveCounter) { ?>
                                                    <td>&nbsp;<span class="label label-sm label-success"><?php echo esc_html($employeeLeaves->leaveCounter) . '/' .$totalNoOfLeave. '<br>'; ?></span></td>
                                                <?php } else { ?>
                                                    <td>&nbsp;<span class="label label-sm label-danger"><?php echo  esc_html($employeeLeaves->leaveCounter) . '/' . $totalNoOfLeave . '<br>'; ?></span></td>
                                                <?php } ?>
                                                </tr>
                                         <?php   } ?>
                                                </table>
                                        </td>
                                        <td>
                                            <?php if (isset($wphrmEmployeeInfo['wphrm_employee_status']) && $wphrmEmployeeInfo['wphrm_employee_status'] == 'Active') { ?>  
                                                <span class="label label-sm label-success"><?php _e('Active', 'wphrm'); ?> </span>
                                            <?php } else { ?>
                                                <span class="label label-sm label-danger"><?php _e('Inactive', 'wphrm'); ?></span>
                                            <?php } ?>
                                        </td>
                                        <td class="">
                                            <a class="btn purple" href="?page=wphrm-view-attendance&employee_id=<?php echo esc_attr($userdata->ID); ?>">
                                                <i class="fa fa-eye"></i><?php _e('View', 'wphrm'); ?> 
                                            </a>
                                        </td>
                                    </tr>
                                <?php $employeeAttendanceCount++;
                                }
                            } }
                            if($employeeAttendanceCount==0) { ?>
                                <tr>
                                    <td colspan="8"><?php _e('No attendance data found in database.', 'wphrm'); ?>
                                    </td><td class="collapse"></td><td class="collapse"></td><td class="collapse"></td><td class="collapse"></td><td class="collapse"></td><td class="collapse"></td><td class="collapse"></td>
                                </tr>
                            <?php } ?> 
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END PAGE CONTENT-->