<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/** WP_HRM ajax data featch with HTML
 *   
 */
class WPHRMAjaxDatas {
    // global protected variable for the using database tables
    protected $wphrmMainClass, $departmentTable, $salaryTable, $designationTable, $settingsTable, $holidayTable;

    public function __construct($wphrmMainClass) {
        // user wphrm main object only & set protected global in table name config file in wphrm mail class
        $this->wphrmMainClass = $wphrmMainClass;
        $this->departmentTable = $this->wphrmMainClass->WphrmDepartmentTable;
        $this->salaryTable = $this->wphrmMainClass->WphrmSalaryTable;
        $this->designationTable = $this->wphrmMainClass->WphrmDesignationTable;
        $this->settingsTable = $this->wphrmMainClass->WphrmSettingsTable;
        $this->financialsTable = $this->wphrmMainClass->WphrmFinancialsTable;
        $this->holidayTable = $this->wphrmMainClass->WphrmHolidaysTable;
    }

    public function WPHRMGetFinancialGraph($wphrmYear) {
        ob_end_clean();
        if (ob_get_level() == 0){ ob_start(); }
        global $wpdb;
        $wphrmMonths = $this->wphrmMainClass->WPHRMGetMonths();
        $wphrmProfilossReport = $this->wphrmMainClass->WPHRMGetFinancialsReport($wphrmYear);
        if (!empty($wphrmProfilossReport)) :
            foreach ($wphrmProfilossReport as $monthkey => $mothReport) :
                if ($mothReport >= 0) :
                    ?>
                    <li><div data-amount="<?php echo esc_attr($mothReport); ?>" class="bar"></div><span><?php echo esc_html($wphrmMonths[$monthkey]); ?></span></li>
                <?php else : ?>
                    <li><div data-amount="<?php echo abs($mothReport); ?>" class="bar_lose"></div><span><?php echo esc_html($wphrmMonths[$monthkey]); ?></span></li>
                <?php
                endif;
            endforeach;
            ?>
            <script>
                /**  chart js  **/
                jQuery("#bars li .bar").each(function (key, bar) {
                    var percentage = jQuery(this).data('amount');
                    var amounts = jQuery('.wphrm_level').val();
                    var final = (percentage * 100) / amounts;
                    jQuery(this).animate({
                        'height': final + '%'
                    }, 1000);
                })
                jQuery("#bars li .bar_lose").each(function (key, bar_lose) {
                    var percentage = jQuery(this).data('amount');
                    var amounts = jQuery('.wphrm_level').val();
                    var final = (percentage * 100) / amounts;
                    jQuery(this).animate({
                        'height': final + '%'
                    }, 1000);
                })
            </script>
        <?php else : ?>
            <?php foreach ($wphrmMonths as $wphrmMonth) : ?>
                <li><div data-amount="" class="bar"></div><span><?php echo esc_html($wphrmMonth); ?></span></li>
            <?php endforeach; ?>
        <?php
        endif;
    }

    /** WP-HRM Ajax Data For the Holiday List * */
    public function WPHRMGetHolidayMonth($wphrmYear, $wphrmMonth) {
        ob_end_clean();
        if (ob_get_level() == 0){ ob_start(); }
        global $current_user, $wpdb;
        $wphrmUserRole = implode(',', $current_user->roles);
        ?>
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-calendar"></i><?php esc_html_e($wphrmMonth, 'wphrm'); ?>
            </div>
        </div>
        <div class="portlet-body">
            <div class="table-scrollable">
                <table class="table table-hover">
                    <thead>
                        <tr> 
                            <th><?php _e('Date', 'wphrm'); ?></th>
                            <th><?php _e('Occasion', 'wphrm'); ?> </th>
                            <th><?php _e('Day', 'wphrm'); ?> </th>
                            <?php if (isset($wphrmUserRole) && $wphrmUserRole == 'administrator') { ?>
                                <th><?php _e('Actions', 'wphrm'); ?> </th>
                            <?php } ?>                
                        </tr>
                    </thead>
                    <tbody>
                       
                        <?php
                        $current_year = esc_sql($wphrmYear); // esc
                        $holiday_month = esc_sql(date("m", strtotime($wphrmMonth))); // esc
                        $wphrm_holidays = $wpdb->get_results("SELECT * FROM  $this->holidayTable where  wphrmDate BETWEEN '$current_year-$holiday_month-01' AND '$current_year-$holiday_month-31'");
                        if (!empty($wphrm_holidays)) :
                            foreach ($wphrm_holidays as $key => $wphrm_holidays_between) {
                                ?>
                                <tr id="row102">
                                    <td> <?php echo esc_html(date('d F Y', strtotime($wphrm_holidays_between->wphrmDate))); ?> </td>
                                    <td> <?php echo esc_html($wphrm_holidays_between->wphrmOccassion); ?> </td>
                                    <td><?php
                                        $originalDate = $wphrm_holidays_between->wphrmDate;
                                        echo esc_html($newDate = date("D", strtotime($originalDate)));
                                        ?> </td>
                                    <td>
                                        <?php if (isset($wphrmUserRole) && $wphrmUserRole == 'administrator') { ?>
                                            <button type="button" onclick="WPHRMCustomDelete(<?php echo esc_js($wphrm_holidays_between->id); ?>, '<?php echo esc_js($this->holidayTable); ?>', 'id')" href="#" class="btn btn-xs red">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        <?php } ?>
                                    </td> 
                                </tr>
                            <?php } ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="4" style="text-align:center"><?php _e('No holidays found.', 'wphrm'); ?></td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div><?php
    }

    /** WP-HRM Ajax Data For the Holiday List 
     *   @argument Year & Month
     *   @return Moth list for the Selected Year
     * */
    public function WPHRMGetHolidayYear($wphrmYear) {
        ob_end_clean();
        if (ob_get_level() == 0){ ob_start(); }
        global $current_user, $wpdb;
        $wphrmUserRole = implode(',', $current_user->roles);
        $holiday_month = esc_sql(date("m", strtotime('January'))); // esc
        $current_year = esc_sql($wphrmYear); // esc
        $wphrm_holidays = $wpdb->get_results("SELECT * FROM  $this->holidayTable where  wphrmDate BETWEEN '$current_year-$holiday_month-01' AND '$current_year-$holiday_month-31'");
        ?>
        <div class="col-md-3">
            <ul class="ver-inline-menu tabbable margin-bottom-10" >
                <?php
                for ($m = 1; $m <= 12; $m++) {
                    $month[] = date('F', mktime(0, 0, 0, $m, 1, date('Y')));
                } $months = $month;
                $activeMonth = 'January';
                foreach ($months as $key => $month) {
                    ?>
                    <li   <?php if ($month == $activeMonth) { ?> class="active" <?php } ?> >
                        <a  data-toggle="tab" class="datasa" onclick="wphrm_month('<?php echo esc_js($month); ?>',<?php echo esc_js($key); ?>);" href="#<?php echo esc_attr($month); ?>"><i class="fa fa-calendar"></i> <?php esc_html_e($month, 'wphrm'); ?></a>
                        <span class="after"></span>
                    </li>
                <?php } ?>
            </ul>
        </div>
        <div class="col-md-9">
            <div class="tab-content">
                <?php foreach ($months as $month) { ?>
                    <div id="<?php esc_html_e($month, 'wphrm'); ?>" class="tab-pane <?php
                    if ($month == $activeMonth) {
                        echo 'active';
                    }
                    ?>">
                        <div class="portlet box blue month_holidays" >
                            <div class="portlet-title">
                                <div class="caption"><i class="fa fa-calendar"></i><?php esc_html_e($month, 'wphrm'); ?></div>
                            </div>
                            <div class="portlet-body">
                                <div class="table-scrollable">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th> <?php _e('Date', 'wphrm'); ?> </th>
                                                <th> <?php _e('Occasion', 'wphrm'); ?> </th>
                                                <th> <?php _e('Day', 'wphrm'); ?> </th>
                                                <?php if (isset($wphrmUserRole) && $wphrmUserRole == 'administrator') { ?>
                                                    <th><?php _e('Actions', 'wphrm'); ?></th>
                                                <?php } ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                           
                                            <?php
                                            if (!empty($wphrm_holidays)) :
                                                foreach ($wphrm_holidays as $key => $wphrm_holidays_between) {
                                                    ?>
                                                    <tr id="row102">
                                                        <td><?php
                                                            $whrmholidate = date('d F Y', strtotime($wphrm_holidays_between->wphrmDate));
                                                            echo esc_html($whrmholidate);
                                                            ?> </td>
                                                        <td><?php echo esc_html($wphrm_holidays_between->wphrmOccassion); ?> </td>
                                                        <td><?php
                                                            $originalDate = $wphrm_holidays_between->wphrmDate;
                                                            echo esc_html($newDate = date("D", strtotime($originalDate)));
                                                            ?></td>
                                                        <td>
                                                            <?php if (isset($wphrmUserRole) && $wphrmUserRole == 'administrator') { ?>  <button type="button" onclick="WPHRMCustomDelete(<?php echo esc_js($wphrm_holidays_between->id); ?>, '<?php echo esc_js($this->holidayTable); ?>', 'id')" href="#" class="btn btn-xs red">
                                                                    <i class="fa fa-trash"></i>
                                                                </button> <?php } ?>
                                                        </td> 
                                                    </tr>
                                                    <?php
                                                }
                                            else :
                                                ?>
                                                <tr>
                                                    <td colspan="4" style="text-align:center"><?php _e('No holiday here.', 'wphrm'); ?></td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div><?php
    }
}
?>