<?php
if ( ! defined( 'ABSPATH' ) ) exit;
wp_enqueue_style('wphrm-fullcalendar-css');
wp_enqueue_style('wphrm-bootstrap-select-css');
wp_enqueue_script('wphrm-graph-js');
global $current_user, $wpdb, $wp_query;
$wphrmCurrentuserId = $current_user->ID;
$wphrmUserRole = implode(',', $current_user->roles);
$usercounter = '';
$userBirthday = '';
$leavecounter = '';
$wphrmUsers = $this->WPHRMGetAllEmployees();
$currentDate = esc_sql(date('Y-m-d')); // esc
$currentDateChange = esc_sql(date('d-m-Y')); // esc
$lastDateChanges = esc_sql(date('m-Y')); // esc
$last_date_change = esc_sql('31-' . $lastDateChanges); // esc
$usercounter = count($wphrmUsers); // esc
$absent = esc_sql('absent'); // esc

$pending = esc_sql('pending'); // esc
$wphrmExpenseReportInformation = esc_sql('wphrmExpenseReportInfo'); // esc
$wphrmLeaveapplication = $wpdb->get_results("SELECT * FROM $this->WphrmAttendanceTable WHERE `status` = '$absent' AND applicationStatus = '$pending' ");
foreach ($wphrmLeaveapplication as $key => $wphrmLeaveapplications) {
    $leavecounter = $leavecounter + count($wphrmLeaveapplications);
}
$wphrmExpenseReportInfo = array();
$wphrmExpenseReportInfos = $wpdb->get_row("SELECT * FROM $this->WphrmSettingsTable WHERE `settingKey` = '$wphrmExpenseReportInformation'");
if (!empty($wphrmExpenseReportInfos)) {
    $wphrmExpenseReportInfo = unserialize(base64_decode($wphrmExpenseReportInfos->settingValue));
}

$wphrmLetestNotice = $wpdb->get_results("SELECT * FROM $this->WphrmNoticeTable ORDER BY id DESC LIMIT 3");
$wphrmTotalCountNotices = $wpdb->get_row("SELECT COUNT(*) as totalNotice FROM $this->WphrmNoticeTable ORDER BY id");

?>
<style>
    .fc-title {
    cursor: pointer!important;
}
</style> 
<div class="preloader">
<span class="preloader-custom-gif"></span>
</div>
<div style="padding-left: 0px; padding-right:20px; padding-top:0px;" class="col-md-12">
    <!-- BEGIN PAGE HEADER-->
    <h3 class="page-title"><?php _e('Dashboard', 'wphrm'); ?></h3>
    <div class="page-bar" style="margin-bottom: 23px;">
        <ul class="page-breadcrumb">
            <li><i class="fa fa-home"></i><?php _e('Home', 'wphrm'); ?><i class="fa fa-angle-right"></i></li>
            <li><?php _e('Dashboard', 'wphrm'); ?></li>
        </ul>
    </div>
    <!-- END PAGE HEADER-->
    <!-- BEGIN DASHBOARD MODULES STATS -->
    <input type="hidden" id="employee_id" value="<?php echo esc_attr($current_user->ID); ?>">

    <!-- WP-HRM Attendances Module -->
    <?php if (isset($wphrmUserRole) && $wphrmUserRole == 'administrator') { ?>
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
            <a class="dashboard-stat dashboard-stat-light red-soft" href="?page=wphrm-mark-attendance">
                <div class="visual"><i class="fa fa-book"></i></div>
                <div class="details">
                    <div class="number"><?php echo esc_html(date('d-F-Y')); ?></div>
                    <div class="desc"><?php _e("Today's Attendance Record", 'wphrm'); ?></div>
                </div>
            </a>
        </div>
        <!-- WP-HRM Employees Module -->
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
            <a class="dashboard-stat dashboard-stat-light green" href="?page=wphrm-employees">
                <div class="visual"><i class="fa fa-user"></i></div>
                <div class="details">
                    <div class="number"><?php _e('5', 'wphrm'); ?></div>
                    <div class="desc"><?php _e('Employees', 'wphrm'); ?></div>
                </div>
            </a>
        </div>
        <!-- WP-HRM Leave Module -->
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
            <a class="dashboard-stat dashboard-stat-light blue-soft" href="?page=wphrm-leaves-application">
                <div class="visual"><i class="fa fa-envelope"></i></div>
                <div class="details">
                    <div class="number"><?php
                        if ($leavecounter == '') {
                            _e('No', 'wphrm');
                        } else {
                           echo esc_html($leavecounter);
                        }
                        ?></div>
                    <div class="desc"><?php _e('Leave Applications', 'wphrm'); ?></div>
                </div>
            </a>
        </div>
        <div class="clearfix"> </div>
        <div class="row">
            <!-- DASHBOARD ATTENDANCE MODULE -->
            <div class="col-md-12 col-sm-12">
                <div class="portlet light ">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon-share font-blue-steel hide"></i>
                            <span class="caption-subject font-blue-steel bold uppercase"><?php _e("Attendance Record", 'wphrm'); ?></span>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div id="Dashboard_Calendar" class="has-toolbar"></div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
    <!-- DASHBOARD BIRTHDAYS MODULE -->
    <div class="col-md-6 col-sm-6">
        <div class="portlet light notice-board">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-share font-blue-steel hide"></i>
                    <span class="caption-subject font-blue-steel bold uppercase" ><?php _e('Notice Board', 'wphrm'); ?></span>
                </div>
            </div>
            <div class="portlet-body">
                <div class="scroller" style="height: 300px;" data-always-visible="1" data-rail-visible="0">
                    <div class="cont-col2">
                        <div class="col-lg-12">
                            <?php
                            $countNotice = 1;
                            foreach ($wphrmLetestNotice as $wphrmLetestNotices) {
                                if (isset($wphrmLetestNotices->wphrmtitle) && $wphrmLetestNotices->wphrmtitle != '') :
                                    ?>
                                    <div class="notices">
                                        <span class="notice-title"><?php  echo substr(esc_html($countNotice). ') ' . esc_html($wphrmLetestNotices->wphrmtitle), 0, 45) . '.....'; ?></span>
                                        <?php
                                        $wphrmAction = 'wphrm-add-notice';
                                        if (isset($wphrmUserRole) && $wphrmUserRole == 'administrator') : $wphrmAction = 'wphrm-add-notice';
                                        else : $wphrmAction = 'wphrm-view-notice';
                                        endif;
                                        ?>
                                        <span class="notice-readmore"><a style="margin-left: 18px;" href="?page=<?php echo $wphrmAction; ?>&notice_id=<?php
                                            if (isset($wphrmLetestNotices->id)) : echo esc_html($wphrmLetestNotices->id);
                                            endif;
                                            ?>" data-toggle="modal"  class="btn btn-xs blue">
                                                                         <?php _e('Read More', 'wphrm'); ?>
                                            </a></span><div style="clear:both"></div>
                                    </div>
                                <?php endif; ?>          
                                <?php
                                $countNotice++;
                            }
                            ?>
                            <br>
                            <?php if ($wphrmTotalCountNotices->totalNotice > 3) { ?>
                                <a style="float: left; margin-left: 18px;" href="?page=wphrm-notice" data-toggle="modal"  class="btn btn-xs blue">
                                <?php _e('More Notices', 'wphrm'); ?>
                            </a>
                            <?php
                        } else {
                            if ($wphrmTotalCountNotices->totalNotice == 0) {
                                ?>
                                <div class="col-sm-12" style="text-align:center"><strong><?php _e('No Notices Found.', 'wphrm'); ?></strong></div>
                                <?php
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-md-6 col-sm-6">
    <div class="portlet light ">
        <div class="portlet-title">
            <div class="caption">
                <i class="icon-share font-blue-steel hide"></i>
                <span class="caption-subject font-blue-steel bold uppercase" ><?php _e("Birthdays", 'wphrm'); ?></span>
            </div>
        </div>
        <div class="portlet-body">
            <div class="scroller" style="height: 300px;" data-always-visible="1" data-rail-visible="0">
                <div class="cont-col2">
                    <div class="col-sm-5">
                        <h2 style="font-size: 12px;"><?php _e("Today's Birthdays", 'wphrm'); ?></h2>
                        <p><?php _e('Date  : ', 'wphrm'); ?><?php echo esc_html(date('d-F-Y', strtotime($currentDateChange))); ?></p>
                        <div class="overflowbirthday">
                            <?php
                            $countTotalEmployeeBirthDays = 0;
                            foreach ($wphrmUsers as $key => $userdata) {
                                if($key <= 4){
                                $userRole = implode(',', $userdata->roles);
                                $currentMonthDay = date('m-d');
                                $wphrmUserinfo = $this->WPHRMGetUserDatas($userdata->ID, 'wphrmEmployeeInfo');
                                if (isset($wphrmUserinfo['wphrm_employee_bod'])) {
                                    $employeebirthMonthDay = date('m-d', strtotime($wphrmUserinfo['wphrm_employee_bod']));
                                    if ($currentMonthDay == $employeebirthMonthDay) {
                                        $countTotalEmployeeBirthDays++;
                                        ?>
                                        <div>                                    
                                            <div class="desc-img"> 
                                                <div class="desc">
                                                    <div style="margin-bottom: 10px"><i class="fa fa-birthday-cake"></i>&nbsp; <?php
                                                        if (isset($wphrmUserinfo['wphrm_employee_fname'])) : echo esc_html($wphrmUserinfo['wphrm_employee_fname']);
                                                        endif;
                                                        ?>
                                                    </div>
                                                </div> 
                                                <div class="birthday">
                                                    <?php if (isset($wphrmUserinfo['employee_profile']) && $wphrmUserinfo['employee_profile'] != '') { ?>
                                                        <img src="<?php
                                                        if (isset($wphrmUserinfo['employee_profile'])) : echo esc_html($wphrmUserinfo['employee_profile']);
                                                        endif;
                                                        ?>" width="80" style="margin-bottom: 11px;" />
                                                             <?php
                                                         } else {
                                                             if ($wphrmUserinfo['wphrm_employee_gender'] == 'Male') {
                                                                 ?>
                                                            <img style="margin-bottom: 11px;" src="<?php echo esc_attr(plugins_url('assets/images/default-male.jpeg', __FILE__)); ?>" width="80" />
                                                        <?php } else { ?>
                                                            <img style="margin-bottom: 11px;" src="<?php echo esc_attr(plugins_url('assets/images/default-female.jpeg', __FILE__)); ?>" width="80" />
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </div>
                                            </div>                                    
                                        </div>

                                        <?php
                                    }
                                }
                            } }
                            if ($countTotalEmployeeBirthDays == 0) {
                                ?>
                                <div class="col-sm-12 float-left"><strong><?php _e('No Birthdays Found.', 'wphrm'); ?></strong></div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="col-sm-7 overflowbirthday" >
                        <h2 class="upcomin-h2"><?php _e("Upcoming Birthdays", 'wphrm'); ?></h2>
                        <table class="table table-striped table-bordered table-birthday table-hover">
                            <thead>
                                <tr>
                                    <th><?php _e('Pic', 'wphrm'); ?></th>
                                    <th><?php _e('Name', 'wphrm'); ?></th>
                                    <th> <i class="fa fa-birthday-cake"></i><?php _e('Date', 'wphrm'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                 $countTotalEmployeeupcomingBirthDays = 0;
                                foreach ($wphrmUsers as $key => $userdata) {
                                    if($key <= 4){
                                    $userRole = implode(',', $userdata->roles);
                                    $currentyear = date('Y');
                                    $currentMonthDay = date('d-m-'.$currentyear.'');
                                    $endOFMonthDay = date('Y-m-d', strtotime("+30 days"));
                                    $wphrmUserinfo = $this->WPHRMGetUserDatas($userdata->ID, 'wphrmEmployeeInfo');
                                    if (isset($wphrmUserinfo['wphrm_employee_bod']) && $wphrmUserinfo['wphrm_employee_bod'] !='') {
                                        $employeebirthMonthDay = date('d-m', strtotime($wphrmUserinfo['wphrm_employee_bod']));
                                         $employeebirthMonthDay = $employeebirthMonthDay.'-'.$currentyear;
                                      
                                        if (strtotime($currentMonthDay) < strtotime($employeebirthMonthDay) &&  strtotime($endOFMonthDay) >= strtotime($employeebirthMonthDay)) {
                                            $countTotalEmployeeupcomingBirthDays++;
                                            ?>
                                            <tr>
                                                <td>
                                                    <?php if ($wphrmUserinfo['employee_profile'] != '') { ?>
                                                        <img src="<?php
                                                        if (isset($wphrmUserinfo['employee_profile'])) : echo esc_attr($wphrmUserinfo['employee_profile']);
                                                        endif;
                                                        ?>" width="60"><br>
                                                             <?php
                                                         } else {
                                                             if ($wphrmUserinfo['wphrm_employee_gender'] == 'Male') {
                                                                 ?>
                                                            <img src="<?php echo esc_attr(plugins_url('assets/images/default-male.jpeg', __FILE__)); ?>" width="60">
                                                        <?php } else { ?>
                                                            <img src="<?php echo esc_attr(plugins_url('assets/images/default-female.jpeg', __FILE__)); ?>" width="60">  
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </td>
                                                <td><?php
                                                    if (isset($wphrmUserinfo['wphrm_employee_fname'])) : echo esc_html($wphrmUserinfo['wphrm_employee_fname']);
                                                    endif;
                                                    ?></td><td>
                                                    <?php
                                                    if (isset($wphrmUserinfo['wphrm_employee_bod'])) : echo esc_html($wphrmUserinfo['wphrm_employee_bod']);
                                                    endif;
                                                    ?> </td>
                                            </tr>   

                                            <?php
                                        }
                                    }
                                } }
                                  if ($countTotalEmployeeupcomingBirthDays == 0) {
                                ?>
                                <tr><td colspan="4">   
                                <div class="col-sm-12" style="text-align:left;"><strong><?php _e('No upcoming birthdays found in next 30 days.', 'wphrm'); ?></strong></div>
                                                 </td></tr>
                                    <?php } ?>
                                
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<div class="clearfix"> </div>
<!-- END DASHBOARD MODULES STATS -->

<?php if (isset($wphrmUserRole) && $wphrmUserRole == 'administrator') { ?>
    <!-- BEGIN EXPENSES MODULES STATS -->
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 xs-chart-hidden">
        <div class="portlet box blue">
            <div class="portlet-title">
                <div class="caption"><?php _e('Profit & Loss Report', 'wphrm'); ?>

                </div>
            </div>
            <div class="portlet-body">
                <div id="financial_graph"></div>
                <input class="yeardata " type="hidden">
                <input type="hidden" class="image_url" value="<?php echo esc_html(plugins_url('assets/images/', __FILE__)); ?>">
                <div style="text-align: Center;">
                    <form name="wphrm-profit-loss" id="wphrm-profit-loss" action="?page=wphrm-profit-loss-reports" method="post">
                        <div style="margin-left:50px;">
                            <table>
                                <tr>
                                    <td><div class="groph-icons-green"></div></td>
                                    <td>&nbsp;&nbsp;&nbsp;<?php _e('Profit', 'wphrm'); ?></td></tr>
                                <tr><td><div class="groph-icons-red"></div></td>
                                    <td>&nbsp;&nbsp;&nbsp;<?php _e('Loss', 'wphrm'); ?></td>
                                </tr>
                            </table>
                        </div>
                        <label>
                            <?php _e('From Date', 'wphrm'); ?> : <input placeholder="<?php _e('From Date', 'wphrm'); ?>" data-date-format="dd-mm-yyyy" name="from-date" id="from-date" class="date-picker form-control input-small input-inline">
                        </label>
                        <label> &nbsp;&nbsp;<?php _e('To Date', 'wphrm'); ?> : <input placeholder="<?php _e('To Date', 'wphrm'); ?>" data-date-format="dd-mm-yyyy" name="to-date" id="to-date" class="date-picker form-control input-small input-inline">
                        </label>
                        <input type="hidden" name="wphrm-report-type" value="" id="wphrm-report-type" class="wphrm-report-type" />
                        <input type="hidden" name="wphrm-report-action" value="" id="wphrm-report-action" class="wphrm-report-action" />
                        <input type="hidden" name="mainsearch" id="mainsearch" value="">&nbsp;&nbsp;&nbsp;&nbsp;<label>
                            
                             <div class="customTooltip-wrap">
                    <span class="btn green "><i class="fa fa-download" ></i><?php _e('Download Excel', 'wphrm'); ?></span>
                    <div class="customTooltip nt-left-top nt-small">
                        <?php _e(' You can avail this feature in WPHRM <br><a href="?page=wphrm-pro-version">Pro version.</a>', 'wphrm'); ?>
                    </div>
                </div>
                           </label>
                    </form>
                </div>
                <div id="chart">
                    <ul id="numbers">
                        <?php
                        $wphrmAmmountValue = isset($wphrmExpenseReportInfo['wphrm_expense_amount']) ? $wphrmExpenseReportInfo['wphrm_expense_amount'] : '';
                        $wphrmAmmounts = array();
                        $wphrmTmp = 0;
                        for ($i = 1; $i <= 10; $i++) {
                            $wphrmAmmounts[] = $wphrmTmp + $wphrmAmmountValue;
                            $wphrmTmp = $wphrmTmp + $wphrmAmmountValue;
                        }
                        rsort($wphrmAmmounts);
                     
                        ?>
                        <input class="wphrm_level" type="hidden" value="<?php echo esc_html($wphrmTmp); ?>">
                        <?php foreach ($wphrmAmmounts as $wphrmAmmount) { ?>
                            <li><span><?php echo esc_html($wphrmAmmount); ?></span></li>
                        <?php } ?>
                    </ul>
                    <ul id="bars" class="ajax_financial_graph_load">
                        <?php
                        $wphrmMonths = $this->WPHRMGetMonths();
                        $wphrmProfilossReport = $this->WPHRMGetFinancialsReport();
                        if (!empty($wphrmProfilossReport)) :
                            foreach ($wphrmProfilossReport as $monthkey => $mothReport) :
                                if ($mothReport >= 0):
                                    ?>
                                    <li><div data-amount="<?php echo esc_html($mothReport); ?>" class="bar"></div><span><?php esc_html_e($wphrmMonths[$monthkey], 'wphrm' ); ?></span></li>
                                <?php else : ?>
                                    <li><div data-amount="<?php echo esc_html(abs($mothReport)); ?>" class="bar_lose"></div><span><?php esc_html_e($wphrmMonths[$monthkey], 'wphrm' ); ?></span></li>
                                <?php
                                endif;
                            endforeach;
                        else :
                            foreach ($wphrmMonths as $wphrmMonth) :
                                ?>
                                <li><div data-amount="" class="bar"></div><span><?php echo esc_html($wphrmMonth); ?></span></li>
                                <?php
                            endforeach;
                        endif;
                        ?>                            
                        <div class="wphrm-chart-loader"><div class="wphrm-chart-loader-img"></div></div>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div id="financialModal" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="alert alert-success display-hide" id="WPHRMCustomDelete_success"><i class='fa fa-check-square' aria-hidden='true'></i> <?php echo esc_html($wphrmMessagesUpdateDeparment); ?>
                <button class="close" data-close="alert"></button>
            </div>
            <div class="alert alert-danger display-hide" id="WPHRMCustomDelete_error">
                <button class="close" data-close="alert"></button>
            </div>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-close"></i></button>
                <h4 class="modal-title"><?php _e('Financials', 'wphrm'); ?></h4>
            </div>
            <div class="modal-body" id="info"><?php _e('Please select date range.', 'wphrm'); ?></div>
            <div class="modal-footer">
               
                <button type="button" data-dismiss="modal" aria-hidden="true" class="btn default"><i class="fa fa-times"></i><?php _e('Cancel', 'wphrm'); ?></button>
            </div>
        </div>
    </div>
</div>
    <!-- END EXPENSES MODULES STATS -->
<?php } ?>
<?php
wp_enqueue_script('wphrm-fullcalender-js');
wp_enqueue_script('wphrm-dashboard-calenader-js');

?>
<script>
  
    jQuery(document).ready(function () {
        Dashboard_Calendar.init();
        jQuery('#financial_graph').bic_calendar({});
    });
    
(function() {
                jQuery('.customTooltip-wrap').customTooltip();
            })();
</script>