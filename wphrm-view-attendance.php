<?php
if ( ! defined( 'ABSPATH' ) ) exit;
wp_enqueue_style('wphrm-fullcalendar-css');
wp_enqueue_style('wphrm-bootstrap-select-css');
global $current_user, $wpdb;
$wphrmUserRole = implode(',', $current_user->roles);
$readonly_class = '';
$readonly = '';
$edit_mode = false;
$page = 'employees';
$employee_id = '';
if (isset($_REQUEST['employee_id']) && !empty($_REQUEST['employee_id'])) {
    $employee_id = $_REQUEST['employee_id'];
     } else{
      $employee_id = $current_user->ID; 
     }
    $wphrmEmployeeBasicInfo = get_user_meta($employee_id, 'wphrmEmployeeInfo', true);
    $wphrmEmployeeBasicInfo = unserialize(base64_decode($wphrmEmployeeBasicInfo));

?>
<style>
    .fc-title {
   cursor: auto !important;
}
</style>
<div class="preloader">
<span class="preloader-custom-gif"></span>
</div>
<h3 class="page-title"><?php _e('Attendance', 'wphrm'); ?></h3>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li><i class="fa fa-home"></i><?php _e('Home', 'wphrm'); ?><i class="fa fa-angle-right"></i></li>
        <li><?php _e('Attendance of', 'wphrm'); ?></li>
        <li>
            <?php
            if (isset($wphrmEmployeeBasicInfo['wphrm_employee_fname'])) : echo esc_html($wphrmEmployeeBasicInfo['wphrm_employee_fname']); endif;
            if (isset($wphrmEmployeeBasicInfo['wphrm_employee_lname'])) : echo ' ' . esc_html($wphrmEmployeeBasicInfo['wphrm_employee_lname']); endif;
            ?>
        </li>
    </ul>
</div>
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div style="padding-left: 0px; padding-right:20px; padding-top:0px;" class="col-md-12">
    <div class="row">
        <input type="hidden" id="employee_id" value="<?php echo esc_attr($employee_id); ?>">
        <div class="col-md-12">
             <?php if (isset($wphrmUserRole) && $wphrmUserRole == 'administrator') { ?>
            <a class="btn green " href="?page=wphrm-attendances"><i class="fa fa-arrow-left"></i><?php _e('Back', 'wphrm'); ?></a>
             <?php } ?>
            <div class="portlet box blue calendar">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-calendar"></i><?php
                        if (isset($wphrmEmployeeBasicInfo['wphrm_employee_fname'])) : echo esc_html($wphrmEmployeeBasicInfo['wphrm_employee_fname']); endif;
                        if (isset($wphrmEmployeeBasicInfo['wphrm_employee_lname'])) : echo ' ' . esc_html($wphrmEmployeeBasicInfo['wphrm_employee_lname']); endif; ?>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="portlet-body">
                        <div class="row">
                            <div class="col-md-4 col-sm-12">
                                <form action="#" class="form-horizontal form-row-sepe">
                                    <div class="form-body">
                                        <h3><?php _e('Search Attendance', 'wphrm'); ?></h3>
                                        <?php if (isset($wphrmUserRole) && $wphrmUserRole == 'administrator') { ?>
                                            <div class="form-group">
                                                <div class="col-md-10">
                                                    <h4><?php _e('Select Employee', 'wphrm'); ?></h4>
                                                    <select class="form-control input-large select2me" data-placeholder="<?php _e('Select Employee', 'wphrm'); ?>..." onchange="redirect_to()" id="changeEmployee" name="employeeID">
                                                        <?php
                                                        $wphrmUserRole = implode(',', $current_user->roles);
                                                        $wphrmUsers = get_users($wphrmUserRole);
                                                        foreach ($wphrmUsers as $key => $userdata) {
                                                            if($key <= 5){
                                                            foreach ($userdata->roles as $role => $roles) {
                                                                if ($roles != 'administrator') { ?>
                                                                    <option value="<?php echo esc_attr($userdata->ID); ?>" <?php
                                                                    if ($employee_id == $userdata->ID) { echo esc_attr('selected ="selected"'); } ?>><?php echo esc_html($userdata->nickname); ?></option>
                                                                <?php }
                                                            }
                                        }  } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <div class="form-group">
                                            <div class="col-md-10">
                                                <h4><?php _e('Month', 'wphrm'); ?></h4>
                                                <select class ="form-control select2me"  data-live-search="true" id="monthSelect"  name="forMonth" onclick="changeMonthYear();return false;">
                                                    <?php
                                                    $month_array = $this->WPHRMGetMonths();
                                                    $current_month = date("m");
                                                    foreach ($month_array as $monthkey => $month_arrays) {
                                                        ?>
                                                        <option value="<?php echo esc_attr($monthkey); ?>" <?php
                                                        if ($current_month == $monthkey) {
                                                            echo esc_attr('selected ="selected"');
                                                        }
                                                        ?>><?php echo esc_html($month_arrays); ?></option>
                                                            <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-10">
                                                <h4>Year</h4>
                                                <select class ="form-control select2me"  data-live-search="true" id="yearSelect" name="forMonth" onclick="changeMonthYear();return false;">
                                                    <?php
                                                    if (isset($wphrmEmployeeBasicInfo['wphrm_employee_joining_date'])) :
                                                        $joining_year = date("Y", strtotime($wphrmEmployeeBasicInfo['wphrm_employee_joining_date']));
                                                        $current_year = date("Y");
                                                        $years_array = range($current_year, $joining_year);
                                                    else :
                                                        $current_year = date("Y");
                                                        $years_array = range($current_year, 1960);
                                                    endif;
                                                    foreach ($years_array as $years_key => $years_arrays) {
                                                        ?>
                                                        <option value="<?php echo esc_attr($years_arrays); ?>" <?php
                                                        if ($current_year == $years_arrays) {
                                                            echo esc_attr('selected ="selected"');
                                                        }
                                                        ?>><?php echo esc_html($years_arrays); ?></option>
                                                            <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-10">
                                                <div class="alert alert-danger text-center">
                                                    <strong><?php _e('Attendance', 'wphrm'); ?> </strong>
                                                    <div id="attendanceReport"><?php _e('NA', 'wphrm'); ?> </div>
                                                </div>
                                            </div>
                                        </div>                <!--/span-->
                                        <div class="row">
                                            <div class="col-md-10">
                                                <div class="alert alert-danger text-center">
                                                    <strong><?php _e('Attendance', 'wphrm'); ?> %</strong>
                                                    <div id="attendancePerReport"><?php _e('NA', 'wphrm'); ?> </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                
                               <?php
                            $attendance_date = esc_sql(date('Y-m-d')); // esc
                            $todaydate = esc_sql('0'); // esc
                            $employeeAttendanceCount = esc_sql('0'); // esc
                            $currentMonth = date('m'); // esc
                            
                                $wphrmEmployeeInfo = $this->WPHRMGetUserDatas($employee_id, 'wphrmEmployeeInfo');
                                
                                if (isset($wphrmEmployeeInfo['wphrm_employee_status']) && $wphrmEmployeeInfo['wphrm_employee_status'] == 'Active') {
                                    $getAttendancebyId = $wpdb->get_row("select * from $this->WphrmAttendanceTable where `date` = '" . $attendance_date . "' and `employeeID` ='" . $employee_id. "'");
                                    $lastAbsent = $wpdb->get_results("select * from $this->WphrmAttendanceTable where  `employeeID` ='" .$employee_id . "' and `status` = 'absent' and `date` <= '$attendance_date' order by id desc");
                                    if (!empty($lastAbsent)) {
                                        if ($lastAbsent[0]->date == $attendance_date) {
                                            $todaydate = 'today';
                                        } else {
                                            $now = time(); // or your date as well
                                            $yourDate = strtotime($lastAbsent[0]->date);
                                            $datediff = $now - $yourDate;
                                            $beforeday = floor($datediff / (60 * 60 * 24));
                                            $todaydate = 'Before : ' . $beforeday . ' Day';
                                        }
                                    } else { $todaydate = '0'; } ?>
                                    
                                    <div class="row">
                                            <div class="col-md-10">
                                                 <div class="alert alert-danger text-center">
                                                  
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
                                                $employeeLeaves = $wpdb->get_row("SELECT COUNT(id) AS leaveCounter FROM $this->WphrmAttendanceTable WHERE `status`='absent' AND `employeeID` ='" . $employee_id. "' AND `leaveType`='$leavesType->leaveType' AND `date` <= '$attendance_date' AND `applicationStatus`='approved'");
                                                echo esc_html($leavesType->leaveType) . ' :';
                                               if ($totalNoOfLeave >= $employeeLeaves->leaveCounter) { ?>
                                                    <strong><?php echo esc_html($employeeLeaves->leaveCounter) . '/' .$totalNoOfLeave. '<br>'; ?></strong>
                                                <?php } else { ?>
                                                    <?php echo  esc_html($employeeLeaves->leaveCounter) . '/' . $totalNoOfLeave . '<br>'; ?>
                                                <?php }
                                            } ?>
                                                 </div> </div></div>
                                <?php 
                            } 
                                ?>
                            </div>
                            <div class="col-md-8 col-sm-12">
                                <div id="Attendance_Calendar" class="has-toolbar">
                                </div>
                            </div>
                        </div>
                        <!-- END CALENDAR PORTLET-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END PAGE CONTENT-->
<?php
wp_enqueue_script('wphrm-bootstrap-select-js');
wp_enqueue_script('wphrm-fullcalender-js');
wp_enqueue_script('wphrm-attendance-calenader-js');
?>
<script>
jQuery(document).ready(function () { 
    Attendance_Calendar.init();
    showReport();
    });
</script>