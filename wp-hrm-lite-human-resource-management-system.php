<?php

/**
 * Plugin Name: WP HRM LITE
 * Plugin URI: https://indigothemes.com/products/wp-hrm-wordpress-plugin/
 * Description: WPHRM LITE is a WordPress plugin for Human resource management of small and medium sized companies. Using WPHRM LITE, companies can easily manage Employee data, Notices, Holidays, Leaves, Expenses etc.
 * Version: 1.1
 * Author: IndigoThemes
 * Author URI: https://indigothemes.com
 * Text Domain: wphrm
 */
if ( ! defined( 'ABSPATH' ) ) exit;
require_once('wphrm-config.php');
require_once('wphrm-ajax-data.php');
Class WPHRM extends WPHRMConfig {

// Define Global Variables and Other Private and Protected Variables.
// Constructor
    function __construct() {
        define('WPHRMLIB', dirname(__FILE__) . '/library/wphrm/');
        register_activation_hook(__FILE__, array(&$this, 'WPHRMPluginActivation'));
        add_action('init', array(&$this, 'WPHRMTextDomain'));
        add_action('admin_init', array(&$this, 'WPHRMAssets'));
        add_action('admin_menu', array(&$this, 'WPHRMMenu'));
        add_action( 'wp_login', array(&$this, 'WPHRMLoginUser'), 10, 2 );
        add_filter( 'login_message', array(&$this, 'WPHRMUserLoginMessage'));
        register_deactivation_hook(__FILE__, array(&$this, 'WPHRMPluginDeactive'));
        $this->wphrmGetAdminId();
        $wphrmAjaxDatas = new WPHRMAjaxDatas($this);
        $this->WPHRMAJAXDATAS = $wphrmAjaxDatas;
    }

    public function WPHRMAssets() {
        wp_enqueue_media();
        wp_enqueue_style('notification-css', plugins_url('assets/css/notification.css', __FILE__), '');
        wp_enqueue_style('wphrm-font-css', plugins_url('assets/css/font-wphrm.css', __FILE__), ''); // for the wp-hrm icon
        
        wp_register_style('wphrm-font-awesome-css', plugins_url('assets/css/font-awesome.min.css', __FILE__), '');
        wp_register_style('wphrm-bootstrap-switch-css', plugins_url('assets/css/bootstrap-switch.css', __FILE__), '');
        wp_register_style('wphrm-bootstrap-min-css', plugins_url('assets/css/bootstrap.min.css', __FILE__), '');
        wp_register_style('wphrm-datepicker3-css', plugins_url('assets/css/datepicker3.css', __FILE__), '');
        wp_register_style('wphrm-fullcalendar-css', plugins_url('assets/css/fullcalendar.min.css', __FILE__), '');
        wp_register_style('wphrm-dataTables-bootstrap-css', plugins_url('assets/css/dataTables.bootstrap.css', __FILE__), '');
        wp_register_style('wphrm-css', plugins_url('assets/css/wphrm.css', __FILE__), array(), '');
        wp_register_script('wphrm-bootstrap-fileinput-js', plugins_url('assets/js/bootstrap-fileinput.js', __FILE__), '');
        wp_register_script('wphrm-bootstrap-datepicker-js', plugins_url('assets/js/bootstrap-datepicker.js', __FILE__), '');
        wp_register_script('wphrm-jscolor-js', plugins_url('assets/js/jscolor.js', __FILE__), '');
        wp_register_script('wphrm-jquery-validate-js', plugins_url('assets/js/jquery.validate.min.js', __FILE__), '');
        wp_register_script('wphrm-additional-methods-js', plugins_url('assets/js/additional-methods.min.js', __FILE__), '');
        wp_register_script('wphrm-bootstrap-min-js', plugins_url('assets/js/bootstrap.min.js', __FILE__), '');
        wp_register_script('wphrm-jquery-dataTables-min-js', plugins_url('assets/js/jquery.dataTables.min.js', __FILE__), '');
        wp_register_script('wphrm-dataTables-bootstrap-js', plugins_url('assets/js/dataTables.bootstrap.js', __FILE__), '');
        wp_register_script('wphrm-bootstrap-switch-js', plugins_url('assets/js/bootstrap-switch.js', __FILE__), '');
        wp_register_script('wphrm-custom-js', plugins_url('assets/js/wphrm.js', __FILE__), '');
        
        wp_register_script('wphrm-bic-calendar-js', plugins_url('assets/js/bic_calendar.js', __FILE__), '');
        wp_register_script('wphrm-graph-js', plugins_url('assets/js/wphrm-graph.js', __FILE__), '');
        wp_register_script('wphrm-fullcalender-js', plugins_url('assets/js/fullcalendar.min.js', __FILE__), '');
        wp_register_script('wphrm-attendance-calenader-js', plugins_url('assets/js/attendance_calendar.js', __FILE__), '');
        wp_register_script('wphrm-dashboard-calenader-js', plugins_url('assets/js/dashboard_calendar.js', __FILE__), '');
        wp_register_script('wphrm-holiday-year-js', plugins_url('assets/js/year-holiday.js', __FILE__), '');
    }

    public function WPHRMEnqueues() {
        wp_enqueue_style('wphrm-font-awesome-css');
        wp_enqueue_style('wphrm-bootstrap-switch-css');
        wp_enqueue_style('wphrm-bootstrap-min-css');
        wp_enqueue_style('wphrm-datepicker3-css');
        wp_enqueue_style('wphrm-css');
        wp_enqueue_script('wphrm-bootstrap-fileinput-js');
        wp_enqueue_script('wphrm-bootstrap-datepicker-js');
        wp_enqueue_script('wphrm-jscolor-js');
        wp_enqueue_script('wphrm-jquery-validate-js');
        wp_enqueue_script('wphrm-additional-methods-js');
        wp_enqueue_script('wphrm-bootstrap-min-js');
        wp_enqueue_script('wphrm-jquery-dataTables-min-js');
        wp_enqueue_script('wphrm-dataTables-bootstrap-js');
        wp_enqueue_script('wphrm-bootstrap-switch-js');
        wp_enqueue_script('wphrm-custom-js');
        wp_localize_script('wphrm-custom-js', 'WPHRMCustomJS', array('records' => __('Records :', 'wphrm'), 'Deletemsg' => __('Are you sure you want to delete?', 'wphrm'), 'occasion' => __('*Must enter the Occasion', 'wphrm'), 'designationName' => __('Designation Name', 'wphrm'), 'departmentName' => __('Department Name', 'wphrm')
            , 'bankfieldlabel' => __('Bank Field Label', 'wphrm'), 'otherfieldlabel' => __('Other Field Label', 'wphrm'), 'salaryfieldlabel' => __('Salary Field Label', 'wphrm'), 'earninglabel' => __('Earning Label', 'wphrm'), 'deductionlabel' => __('Deduction Label', 'wphrm')));
        wp_localize_script('wphrm-jquery-dataTables-min-js', 'WPHRMJS', array('sSearch' => __('Search :', 'wphrm'), 'sSortAscending' => __(': activate to sort column ascending', 'wphrm'), 'sSortDescending' => __(': activate to sort column descending', 'wphrm'), 'sFirst' => __('First', 'wphrm'), 'sLast' => __('Last', 'wphrm')
            , 'sNext' => __('Next :', 'wphrm'), 'sPrevious' => __('Previous', 'wphrm'), 'sEmptyTable' => __('No data available in table', 'wphrm'), 'sInfo' => __('Showing ', 'wphrm'), 'of' => __('of', 'wphrm'), 'to' => __('to', 'wphrm'), 'entries' => __('entries', 'wphrm'), 'sInfoEmpty' => __('Showing 0 to 0 of 0 entries', 'wphrm'), 'totalentries' => __('total entries', 'wphrm'), 'filteredfrom' => __('filtered from', 'wphrm'), 'sLoadingRecords' => __('Loading...', 'wphrm')
            , 'sProcessing' => __('Processing...', 'wphrm'), 'sZeroRecords' => __('No matching records found', 'wphrm')));

        wp_localize_script('wphrm-fullcalender-js', 'WPHRMDashboardJS', array('today' => __('Today', 'wphrm'),
            'monthtitle' => __('MMMM YYYY', 'wphrm'), 'monthday' => __('ddd', 'wphrm'),));
    }

    public function WPHRMTextDomain() {
        load_plugin_textdomain('wphrm', false, basename(dirname(__FILE__)) . '/languages');
    }

    public function WPHRMMenu() {
        global $current_user, $wpdb;
        $wphrmUserRole = implode(',', $current_user->roles);
        
        add_menu_page('WP HRM', __('WP HRM LITE', 'wphrm'), 'wphrm-dashboard', 'wphrm', '', '');
        /** Add sub menus * */
        $wphrmPage = add_submenu_page('wphrm', 'Dashboard', __('Dashboard', 'wphrm'), 'manageOptionsDashboard', 'wphrm-dashboard', array(&$this, 'WPHRMDashboardCallback'));
        add_action( "admin_print_styles-{$wphrmPage}", array(&$this, 'WPHRMEnqueues') );
        $wphrmPage = add_submenu_page('wphrm', 'Departments', __('Departments', 'wphrm'), 'manageOptionsDepartment', 'wphrm-departments', array(&$this, 'WPHRMDepartments'));
         add_action( "admin_print_styles-{$wphrmPage}", array(&$this, 'WPHRMEnqueues') );
        if (isset($wphrmUserRole) && $wphrmUserRole == 'administrator') {
            $wphrmPage = add_submenu_page('wphrm', 'Employees', __('Employees', 'wphrm'), 'manageOptionsEmployee', 'wphrm-employees', array(&$this, 'WPHRMEmployees'));
            add_action( "admin_print_styles-{$wphrmPage}", array(&$this, 'WPHRMEnqueues') );
        } else {
            $wphrmPage = add_submenu_page('wphrm', 'My Profile', __('My Profile', 'wphrm'), 'manageOptionsEmployeeView', 'wphrm-employee-view-details', array(&$this, 'WPHRMEmployeeViewDetails'));
            add_action( "admin_print_styles-{$wphrmPage}", array(&$this, 'WPHRMEnqueues') );
        }
        $wphrmPage = add_submenu_page('wphrm', 'Holidays', __('Holidays', 'wphrm'), 'manageOptionsHolidays', 'wphrm-holidays', array(&$this, 'WPHRMHolidaysCallback'));
        add_action( "admin_print_styles-{$wphrmPage}", array(&$this, 'WPHRMEnqueues') );
        if (isset($wphrmUserRole) && $wphrmUserRole == 'administrator') {
            $wphrmPage = add_submenu_page('wphrm', 'Attendance Management', __('Attendance Management', 'wphrm'), 'manageOptionsAttendances', 'wphrm-attendances', array(&$this, 'WPHRMAttendancesCallback'));
            add_action( "admin_print_styles-{$wphrmPage}", array(&$this, 'WPHRMEnqueues') );
        } else {
            $wphrmPage = add_submenu_page('wphrm', 'Attendance Management', __('Attendance Management', 'wphrm'), 'manageOptionsAttendances', 'wphrm-view-attendance', array(&$this, 'WPHRMViewAttendances'));
            add_action( "admin_print_styles-{$wphrmPage}", array(&$this, 'WPHRMEnqueues') );
            
        }
        $wphrmPage = add_submenu_page('wphrm', 'Leave Management', __('Leave Management', 'wphrm'), 'manageOptionsLeaveApplications', 'wphrm-leaves-application', array(&$this, 'WPHRMLeavesApplicationCallback'));
        add_action( "admin_print_styles-{$wphrmPage}", array(&$this, 'WPHRMEnqueues') );
        if (isset($wphrmUserRole) && $wphrmUserRole == 'administrator') {
            $wphrmPage = add_submenu_page('wphrm', 'Salary Management', __('Salary Management', 'wphrm'), 'manageOptionsSalary', 'wphrm-salary', array(&$this, 'WPHRMSalaryCallback'));
            add_action( "admin_print_styles-{$wphrmPage}", array(&$this, 'WPHRMEnqueues') );
        } else {
            $wphrmPage = add_submenu_page('wphrm', 'Salary Management', __('Salary Management', 'wphrm'), 'manageOptionsSalary', 'wphrm-select-financials-month', array(&$this, 'WPHRMSelectFinancialsMonth'));
            add_action( "admin_print_styles-{$wphrmPage}", array(&$this, 'WPHRMEnqueues') );
         }
        $wphrmPage = add_submenu_page('wphrm', 'Notices', __('Notices', 'wphrm'), 'manageOptionsNotice', 'wphrm-notice', array(&$this, 'WPHRMNoticeCallback'));
        add_action( "admin_print_styles-{$wphrmPage}", array(&$this, 'WPHRMEnqueues') );
        $wphrmPage = add_submenu_page('wphrm', 'Finance Management', __('Finance Management', 'wphrm'), 'manageOptionsFinancials', 'wphrm-financials', array(&$this, 'WPHRMFinancialsCallback'));
        add_action( "admin_print_styles-{$wphrmPage}", array(&$this, 'WPHRMEnqueues') );
        $wphrmPage = add_submenu_page('wphrm', 'Notifications', __('Notifications ', 'wphrm'), 'manageOptionsNotifications', 'wphrm-notifications', array(&$this, 'WPHRMNotificationsCallback'));
        add_action( "admin_print_styles-{$wphrmPage}", array(&$this, 'WPHRMEnqueues') );
        $wphrmPage = add_submenu_page('wphrm', 'Settings', __('Settings', 'wphrm'), 'manageOptionsSettings', 'wphrm-settings', array(&$this, 'WPHRMSettingsCallback'));
        add_action( "admin_print_styles-{$wphrmPage}", array(&$this, 'WPHRMEnqueues') );
        $wphrmPage = add_submenu_page('wphrm', 'WPHRM PRO Features', __('WPHRM PRO Features', 'wphrm'), 'manageOptionsFbGroup', 'wphrm-pro-version', array(&$this, 'WPHRMProversionCallback'));
        add_action( "admin_print_styles-{$wphrmPage}", array(&$this, 'WPHRMEnqueues') );
        
        $wphrmPage = add_submenu_page('wphrm-departments', '', '', 'manageOptionsDepartment', 'wphrm-add-designation', array(&$this, 'WPHRMAddDesignation'));
        add_action( "admin_print_styles-{$wphrmPage}", array(&$this, 'WPHRMEnqueues') );
        $wphrmPage = add_submenu_page('wphrm-employees', '', '', 'manageOptionsEmployeeAdd', 'wphrm-add-employee', array(&$this, 'WPHRMEmployeePage'));
        add_action( "admin_print_styles-{$wphrmPage}", array(&$this, 'WPHRMEnqueues') );
        $wphrmPage = add_submenu_page('wphrm-employees', '', '', 'manageOptionsEmployee', 'wphrm-employee-info', array(&$this, 'WPHRMEmployeeInfo'));
        add_action( "admin_print_styles-{$wphrmPage}", array(&$this, 'WPHRMEnqueues') );
        $wphrmPage = add_submenu_page('wphrm-employees', '', '', 'manageOptionsEmployee', 'wphrm-employee-view-details', array(&$this, 'WPHRMEmployeeViewDetails'));
        add_action( "admin_print_styles-{$wphrmPage}", array(&$this, 'WPHRMEnqueues') );
        $wphrmPage = add_submenu_page('wphrm-attendances', '', '', 'manageOptionsAttendances', 'wphrm-view-attendance', array(&$this, 'WPHRMViewAttendances'));
        add_action( "admin_print_styles-{$wphrmPage}", array(&$this, 'WPHRMEnqueues') );
        $wphrmPage = add_submenu_page('wphrm-attendances', '', '', 'manageOptionsLeaveApplications', 'wphrm-leave-type', array(&$this, 'WPHRMLeaveType'));
        add_action( "admin_print_styles-{$wphrmPage}", array(&$this, 'WPHRMEnqueues') );
        $wphrmPage = add_submenu_page('wphrm-attendances', '', '', 'manageOptionsMarkAttendance', 'wphrm-mark-attendance', array(&$this, 'WPHRMMarkAttendances'));
        add_action( "admin_print_styles-{$wphrmPage}", array(&$this, 'WPHRMEnqueues') );
        $wphrmPage = add_submenu_page('wphrm-salary', '', '', 'manageOptionsSalary', 'wphrm-select-financials-month', array(&$this, 'WPHRMSelectFinancialsMonth'));
        add_action( "admin_print_styles-{$wphrmPage}", array(&$this, 'WPHRMEnqueues') );

        $wphrmPage = add_submenu_page('wphrm-salary', '', '', 'manageOptionsSalary', 'wphrm-salary-slip-pdf', array(&$this, 'WPHRMSalarySlipPdf'));
        add_action( "admin_print_styles-{$wphrmPage}", array(&$this, 'WPHRMEnqueues') );
        if (isset($wphrmUserRole) && $wphrmUserRole == 'administrator') {
            $wphrmPage = add_submenu_page('wphrm-notice', '', '', 'manageOptionsNotice', 'wphrm-add-notice', array(&$this, 'WPHRMAddNotice'));
            add_action( "admin_print_styles-{$wphrmPage}", array(&$this, 'WPHRMEnqueues') );
            } else {
            $wphrmPage = add_submenu_page('wphrm-notice', '', '', 'manageOptionsNotice', 'wphrm-view-notice', array(&$this, 'WPHRMAddNotice'));
            add_action( "admin_print_styles-{$wphrmPage}", array(&$this, 'WPHRMEnqueues') );
            }
        $wphrmPage = add_submenu_page('Financials', '', '', 'manageOptionsFinancials', 'wphrm-profit-loss-reports', array(&$this, 'WPHRMProfitLossReports'));
        add_action( "admin_print_styles-{$wphrmPage}", array(&$this, 'WPHRMEnqueues') );
        $wphrmPage = add_submenu_page('Financials', '', '', 'manageOptionsSalary', 'wphrm-salary-reports', array(&$this, 'WPHRMSalaryReports'));
        add_action( "admin_print_styles-{$wphrmPage}", array(&$this, 'WPHRMEnqueues') );
        $wphrmPage = add_submenu_page('wphrm-attendances', '', '', 'manageOptionsAbsent', 'wphrm-employee-absent', array(&$this, 'WPHRMEmployeeAbsent'));
        add_action( "admin_print_styles-{$wphrmPage}", array(&$this, 'WPHRMEnqueues') );
    }

    /** WP-HRM Activation Plugin * */
    public function WPHRMPluginActivation() {
        global $wpdb;
        require_once('wphrm-import.php');
        $wphrm_import = new WphrmImport();
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        if ($wpdb->get_var("SHOW TABLES LIKE '$this->WphrmSalaryTable'") != $this->WphrmSalaryTable) :
            $charset_collate = $wpdb->get_charset_collate();
            $sql = "CREATE TABLE `$this->WphrmSalaryTable` (
                `id` bigint(20) NOT NULL AUTO_INCREMENT,
                `employeeID` bigint(50) NOT NULL,
                `employeeKey` varchar(255) NOT NULL,
                `employeeValue` longtext NOT NULL,
                `date` date NOT NULL,
                PRIMARY KEY (`id`)
            )";
            dbDelta($sql);
        endif;
        if ($wpdb->get_var("SHOW TABLES LIKE '$this->WphrmSettingsTable'") != $this->WphrmSettingsTable) :
            $wphrm_import->sqlImport(WPHRMLIB . 'wphrm-settings.sql');
        endif;
        if ($wpdb->get_var("SHOW TABLES LIKE '$this->WphrmHolidaysTable'") != $this->WphrmHolidaysTable) :
            $charset_collate = $wpdb->get_charset_collate();
            $sql = "CREATE TABLE IF NOT EXISTS $this->WphrmHolidaysTable(
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `wphrmDate` date NOT NULL,
                `wphrmOccassion` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
                `createdAt` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
                `updatedAt` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
                PRIMARY KEY (`id`),
                UNIQUE KEY `holidays_date_unique` (`wphrmDate`))";
            dbDelta($sql);
        endif;
        if ($wpdb->get_var("SHOW TABLES LIKE '$this->WphrmFinancialsTable'") != $this->WphrmFinancialsTable) :
            $charset_collate = $wpdb->get_charset_collate();
            $sql = " CREATE TABLE IF NOT EXISTS $this->WphrmFinancialsTable (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `wphrmItem` varchar(100) NOT NULL,
                `wphrmAmounts` varchar(100) NOT NULL,
                `wphrmStatus` varchar(100) NOT NULL,
                `wphrmDate` date NOT NULL,
                PRIMARY KEY (`id`))";
            dbDelta($sql);
        endif;
        if ($wpdb->get_var("SHOW TABLES LIKE '$this->WphrmNotificationsTable'") != $this->WphrmNotificationsTable) :
            $charset_collate = $wpdb->get_charset_collate();
            $sql = " CREATE TABLE IF NOT EXISTS `$this->WphrmNotificationsTable` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `wphrmUserID` int(11) NOT NULL,
                    `wphrmFromId` int(11) NOT NULL,
                    `wphrmDesc` varchar(255) NOT NULL,
                    `notificationType` varchar(200) NOT NULL,
                    `wphrmStatus` enum('unseen','seen') NOT NULL,
                    `wphrmDate` date NOT NULL,
                    PRIMARY KEY (`id`))";
            dbDelta($sql);
        endif;
        if ($wpdb->get_var("SHOW TABLES LIKE '$this->WphrmDesignationTable'") != $this->WphrmDesignationTable) :
            $charset_collate = $wpdb->get_charset_collate();
            $sql = "CREATE TABLE IF NOT EXISTS $this->WphrmDesignationTable (
                `designationID` bigint(20) NOT NULL AUTO_INCREMENT,
                `departmentID` bigint(20) NOT NULL,
                `designationName` varchar(200) DEFAULT NULL,
                PRIMARY KEY (`designationID`))";
            dbDelta($sql);
        endif;
        if ($wpdb->get_var("SHOW TABLES LIKE '$this->WphrmDepartmentTable'") != $this->WphrmDepartmentTable) :
            $charset_collate = $wpdb->get_charset_collate();
            $sql = "CREATE TABLE IF NOT EXISTS $this->WphrmDepartmentTable(
                `departmentID` bigint(20) NOT NULL AUTO_INCREMENT,
                `departmentName` varchar(200) DEFAULT NULL,
                PRIMARY KEY (`departmentID`))";
            dbDelta($sql);
        endif;
        if ($wpdb->get_var("SHOW TABLES LIKE '$this->WphrmAttendanceTable'") != $this->WphrmAttendanceTable) :
            $charset_collate = $wpdb->get_charset_collate();
            $sql = "CREATE TABLE IF NOT EXISTS $this->WphrmAttendanceTable (
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `employeeID` bigint(20) NOT NULL,
                `date` date NOT NULL,
                `status` enum('absent','present') COLLATE utf8_unicode_ci NOT NULL,
                `leaveType` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
                `halfDayType` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
                `reason` text COLLATE utf8_unicode_ci NOT NULL,
                `applicationStatus` enum('approved','rejected','pending') COLLATE utf8_unicode_ci DEFAULT NULL,
                `appliedOn` date DEFAULT NULL,
                `updatedBy` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
                `createdAt` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
                `updatedAt` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
                PRIMARY KEY (`id`),
                KEY `attendance_employeeid_index` (`employeeID`),
                KEY `attendance_leavetype_index` (`leaveType`),
                KEY `attendance_updated_by_index` (`updatedBy`),
                KEY `attendance_halfdaytype_index` (`halfDayType`))";
            dbDelta($sql);
        endif;
        if ($wpdb->get_var("SHOW TABLES LIKE '$this->WphrmLeaveTypeTable'") != $this->WphrmLeaveTypeTable) :
            $wphrm_import->sqlImport(WPHRMLIB . 'wphrm-leave-types.sql');
        endif;
        if ($wpdb->get_var("SHOW TABLES LIKE '$this->WphrmMessagesTable'") != $this->WphrmMessagesTable) :
            $wphrm_import->sqlImport(WPHRMLIB . 'wphrm-messages.sql');
        endif;
        if ($wpdb->get_var("SHOW TABLES LIKE '$this->WphrmNoticeTable'") != $this->WphrmNoticeTable) :
            $charset_collate = $wpdb->get_charset_collate();
            $sql = "CREATE TABLE IF NOT EXISTS $this->WphrmNoticeTable (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `wphrmtitle` varchar(250) NOT NULL,
                `wphrmdesc` longtext NOT NULL,
                `wphrmcreatedDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`))";
            dbDelta($sql);
        endif;
        $role_admin = get_role('administrator');
        $role_subscriber = get_role('subscriber');
        $role_editor = get_role('editor');
        $role_admin->add_cap('manageOptionsEmployee');
        $role_admin->add_cap('manageOptionsDepartment');
        $role_admin->add_cap('manageOptionsHolidays');
        $role_admin->add_cap('manageOptionsAttendances');
        $role_admin->add_cap('manageOptionsLeaveApplications');
        $role_admin->add_cap('manageOptionsSalary');
        $role_admin->add_cap('manageOptionsEmployeeAdd');
        $role_admin->add_cap('manageOptionsDashboard');
        $role_admin->add_cap('manageOptionsNotice');
        $role_admin->add_cap('manageOptionsSettings');
        $role_admin->add_cap('manageOptionsMarkAttendance');
        $role_admin->add_cap('manageOptionsSlipDetails');
        $role_admin->add_cap('manageOptionsFinancials');
        $role_admin->add_cap('manageOptionsAbsent');
        $role_admin->add_cap('manageOptionsNotifications');
        $role_admin->add_cap('manageOptionsFbGroup');
        $role_admin->add_cap('read');
        $role_subscriber->add_cap('manageOptionsEmployeeView');
        $role_subscriber->add_cap('manageOptionsHolidays');
        $role_subscriber->add_cap('manageOptionsAttendances');
        $role_subscriber->add_cap('manageOptionsLeaveApplications');
        $role_subscriber->add_cap('manageOptionsSalary');
        $role_subscriber->add_cap('manageOptionsDashboard');
        $role_subscriber->add_cap('manageOptionsNotifications');
        $role_subscriber->add_cap('manageOptionsNotice');
        $role_subscriber->add_cap('manageOptionsFbGroup');
        $role_subscriber->add_cap('read');
        $role_editor->add_cap('manageOptionsEmployeeView');
        $role_editor->add_cap('manageOptionsHolidays');
        $role_editor->add_cap('manageOptionsAttendances');
        $role_editor->add_cap('manageOptionsLeaveApplications');
        $role_editor->add_cap('manageOptionsSalary');
        $role_editor->add_cap('manageOptionsDashboard');
        $role_editor->add_cap('manageOptionsNotifications');
        $role_editor->add_cap('manageOptionsNotice');
        $role_editor->add_cap('manageOptionsFbGroup');
        $role_editor->add_cap('read');
    }

    /** WP-HRM Deactive Plugin * */
    public function WPHRMPluginDeactive() {
        $role_admin = get_role('administrator');
        $role_subscriber = get_role('subscriber');
        $role_editor = get_role('editor');
        $role_admin->remove_cap('manageOptionsEmployee');
        $role_admin->remove_cap('manageOptionsDepartment');
        $role_admin->remove_cap('manageOptionsHolidays');
        $role_admin->remove_cap('manageOptionsAttendances');
        $role_admin->remove_cap('manageOptionsLeaveApplications');
        $role_admin->remove_cap('manageOptionsSalary');
        $role_admin->remove_cap('manageOptionsEmployeeAdd');
        $role_admin->remove_cap('manageOptionsDashboard');
        $role_admin->remove_cap('manageOptionsNotice');
        $role_admin->remove_cap('manageOptionsSettings');
        $role_admin->remove_cap('manageOptionsMarkAttendance');
        $role_admin->remove_cap('manageOptionsSlipDetails');
        $role_admin->remove_cap('manageOptionsAbsent');
        $role_admin->remove_cap('manageOptionsNotifications');
        $role_admin->remove_cap('manageOptionsFinancials');
        $role_admin->remove_cap('manageOptionsFbGroup');
        $role_subscriber->remove_cap('manageOptionsEmployeeView');
        $role_subscriber->remove_cap('manageOptionsHolidays');
        $role_subscriber->remove_cap('manageOptionsAttendances');
        $role_subscriber->remove_cap('manageOptionsLeaveApplications');
        $role_subscriber->remove_cap('manageOptionsSalary');
        $role_subscriber->remove_cap('manageOptionsDashboard');
        $role_subscriber->remove_cap('manageOptionsNotice');
        $role_subscriber->remove_cap('manageOptionsFbGroup');
        $role_subscriber->remove_cap('manageOptionsNotifications');
        $role_editor->remove_cap('manageOptionsEmployeeView');
        $role_editor->remove_cap('manageOptionsHolidays');
        $role_editor->remove_cap('manageOptionsAttendances');
        $role_editor->remove_cap('manageOptionsLeaveApplications');
        $role_editor->remove_cap('manageOptionsSalary');
        $role_editor->remove_cap('manageOptionsDashboard');
        $role_editor->remove_cap('manageOptionsNotice');
        $role_editor->remove_cap('manageOptionsNotifications');
        $role_editor->remove_cap('manageOptionsFbGroup');
    }

    /** BEGIN WP-HRM PAGE ACTIONS * */

    /** WP-HRM Deshboard * */
    public function WPHRMDashboardCallback() {
        include_once('wphrm-dashboard.php');
    }

    /** WP-HRM Searching * */
    public function WPHRMEmployees() {
        include_once('wphrm-employee-list.php');
    }

    /** WP-HRM Department * */
    public function WPHRMDepartments() {
        include_once('wphrm-department.php');
    }

    /** WP-HRM Designation * */
    public function WPHRMAddDesignation() {
        include_once('wphrm-designation.php');
    }

    /** WP-HRM Attendance View * */
    public function WPHRMViewAttendances() {
        include_once('wphrm-view-attendance.php');
    }

    /** WP-HRM Attendance type * */
    public function WPHRMLeaveType() {
        include_once('wphrm-leave-type.php');
    }

    /** WP-HRM Mark Attendance * */
    public function WPHRMMarkAttendances() {
        include_once('wphrm-mark-attendance.php');
    }

    /** WP-HRM Employess * */
    public function WPHRMEmployeePage() {
        include_once('wphrm-employee-page.php');
    }

    /** WP-HRM Employess Information * */
    public function WPHRMEmployeeInfo() {
        include_once('wphrm-employee-info.php');
    }

    /** WP-HRM View Employess Information * */
    public function WPHRMEmployeeViewDetails() {
        include_once('wphrm-employee-view-info.php');
    }

    /** WP-HRM Holidays * */
    public function WPHRMHolidaysCallback() {
        include_once('wphrm-holidays.php');
    }

    /** WP-HRM Attendance * */
    public function WPHRMAttendancesCallback() {
        include_once('wphrm-attendances.php');
    }

    /** WP-HRM Leaves * */
    public function WPHRMLeavesApplicationCallback() {
        include_once('wphrm-leaves-application.php');
    }

    /** WP-HRM Salary * */
    public function WPHRMSalaryCallback() {
        include_once('wphrm-salary.php');
    }

    /** WP-HRM Notice List * */
    public function WPHRMNoticeCallback() {
        include_once ('wphrm-notice.php');
    }

    /** WP-HRM Add Add Update Notice * */
    public function WPHRMAddNotice() {
        include_once ('wphrm-add-notice.php');
    }

    /** WP-HRM Financials * */
    public function WPHRMFinancialsCallback() {
        include_once ('wphrm-financials-report.php');
    }

    /** WP-HRM Notifications * */
    public function WPHRMNotificationsCallback() {
        include_once ('wphrm-notifications.php');
    }

    /** WP-HRM Settings * */
    public function WPHRMSettingsCallback() {
        include("wphrm-settings.php"); 
    }

    /** WP-HRM Pro version * */
    public function WPHRMProversionCallback() {
        include("wphrm-pro-version.php"); 
    }


    /** WP-HRM Employee Absent View * */
    public function WPHRMEmployeeAbsent() {
        include_once('wphrm-employee-absent.php');
    }

    /** END WP-HRM PAGE ACTIONS * */
    /** BEGIN WP_HRM FUNCTIONAL ACTIONS * */

    /** WP-HRM Get Admin ID Function * */
    public function wphrmGetAdminId() {
        $wphrmUsersQuery = new WP_User_Query(array(
            'role' => 'administrator',
            'orderby' => 'display_name'
        ));
        $results = $wphrmUsersQuery->get_results();
        $this->wphrmGetAdminId = $results[0]->ID;
    }

    /* WP-HRM Get Months * */

    public function WPHRMGetMonths() {
        global $wpdb;
        $wphrmMonths = esc_sql('wphrmMonths'); // esc
        $month = $wpdb->get_row("SELECT * FROM $this->WphrmSettingsTable WHERE `authorID`=0 AND `settingKey`='$wphrmMonths'");
        return unserialize(base64_decode($month->settingValue));
    }

    

    function WPHRMDateForSpecificDayBetweenDates($startDate, $endDate, $weekdayNumber, $dayCounter) {
        $startDate = strtotime($startDate);
        $endDate = strtotime($endDate);
        $dateArr = array();
        do {
            if (date("w", $startDate) != $weekdayNumber) {
                $startDate += (24 * 3600); // add 1 day
            }
        } while (date("w", $startDate) != $weekdayNumber);
        while ($startDate <= $endDate) {
            $dateArr[] = date('Y-m-d', $startDate);
            $startDate += ($dayCounter * 24 * 3600); // add 7 days
        }
        return($dateArr);
    }

    /** WP-HRM Get All Employee Active User
     *   @no-argument
     *   @return if administrator all Employee Users & if employee only one datas return.
     * */
    public function WPHRMGetEmployees() {
        global $current_user;
        $wphrmEmployeeUsers = array();
        $wphrmUserRole = implode(',', $current_user->roles);
        if ($wphrmUserRole == 'administrator') {
            $employeeRole = array('role__in' => array('subscriber', 'editor','Inactive'));
            $wphrmEmployeeUsers = get_users($employeeRole);
        } else {
            $wphrmEmployeeUsers[] = get_userdata($current_user->ID);
        }
        return $wphrmEmployeeUsers;
    }

    /** WP-HRM Get All Employee Active User For The All User Access
     *   @no-argument
     *   @return all Employee Users datas return.
     * */
    public function WPHRMGetAllEmployees() {
        $employeeRole = array('role__in' => array('subscriber', 'editor'));
        return get_users($employeeRole);
    }
  
    /** WP-HRM Sanitize array value
     *   @ Array value
     *   @return return Sanitize value.
     * */
    public function WPHRMSanitize($input) {
        $new_input = array();
        foreach ($input as $key => $val) {
            $new_input[$key] = sanitize_text_field($val);
        }
        return $new_input;
    }

    
    /** Month array key wise function. * */
    public function wphrmCurrentMonth($databaseMonth, $currentMonth) {
        $wphrmReturns = array();
        foreach ($databaseMonth as $dbm => $dbMonth):
            foreach ($currentMonth as $cm => $cMonth):
                if ($dbMonth == $cMonth):
                    $wphrmReturns[$cm] = $cMonth;
                endif;
            endforeach;
        endforeach;
        return $wphrmReturns;
    }

    public function WPHRMCheckUserID($ID) {
        $usersCheck = get_userdata($ID);
        if ($usersCheck == false) {
            wp_redirect(admin_url('admin.php?page=wphrm-employees'), 301);
        }
    }

    /**
     *   WP-HRM get User Information
     *   Perameter ID is user id
     *   Return User Information
     * */
    public function WPHRMGetUserDatas($ID, $key) {
        $wphrmUserInfo = get_user_meta($ID, $key, true);
        $wphrmUserDatas = array();
        if ($wphrmUserInfo != '') :
            $wphrmUserDatas = unserialize(base64_decode($wphrmUserInfo));
        endif;
        return $wphrmUserDatas;
    }

    /**
     *   WP-HRM get Notification Message
     *   Perameter ID is Message ID
     *   Return Meaage Description
     * */
    public function WPHRMGetMessage($ID) {
        global $wpdb;
        $wphrmMessage = '';
        $ID = esc_sql($ID); // esc
        $messages = $wpdb->get_row("SELECT * FROM  $this->WphrmMessagesTable  where `id` = $ID");
        if (isset($messages->messagesDesc)) : $wphrmMessage = $messages->messagesDesc;
        else : $wphrmMessage = '';
        endif;
        return $wphrmMessage;
    }

    /** Date For Specific Two Day Off function. * */
    function WPHRMDateForSpecificTwoDayOff($y, $m, $weekdayNumber, $dayCounter) {
        $startDate = "$y-$m-01";
        $endDate1 = date('t', strtotime($startDate));
        $endDate = "$y-$m-$endDate1";
        $startDateTime = strtotime($startDate);
        $endDate = strtotime($endDate);
        $dateArr = array();
        do {
            if (date("w", $startDateTime) != $weekdayNumber) {
                $startDateTime += (24 * 3600); // add 1 day
            }
        } while (date("w", $startDateTime) != $weekdayNumber);
        while ($startDateTime <= $endDate) {
            $dateArr[] = date('Y-m-d', $startDateTime);
            $startDateTime += ($dayCounter * 24 * 3600); // add 7 days
        }
        return($dateArr);
    }

    /** WP-HRM Financial Reports * */
    public function WPHRMGetFinancialsReport($currentYear = null) {
        global $wpdb;
        if ($currentYear == null) : $currentYear = date('Y');
        endif;
        $wphrmProfit = array();
        $wphrmLoss = array();
        $wphrmExpenceReport = array();
        $wphrmMonths = $this->WPHRMGetMonths();
        $wphrmFinacials = $wpdb->get_results("SELECT * FROM $this->WphrmFinancialsTable");
        foreach ($wphrmFinacials as $F => $wphrmFinacial) {
            $str = $wphrmFinacial->wphrmDate;
            $dates = explode("-", $str);
            if (!empty($dates)) :
                $year = $dates[0];
                $month = $dates[1];
                foreach ($wphrmMonths as $month_key => $wphrm_month) {
                    if ($year == $currentYear) {
                        if ($month == $month_key) {
                            if ($wphrmFinacial->wphrmStatus == 'Profit') {
                                $wphrmProfits = intval($wphrmFinacial->wphrmAmounts) + intval(isset($wphrmProfit[$month_key]) ? $wphrmProfit[$month_key] : '');
                                $wphrmProfit[$month_key] = $wphrmProfits;
                            }
                            if ($wphrmFinacial->wphrmStatus == 'Loss') {
                                $wphrmLosses = intval($wphrmFinacial->wphrmAmounts) + intval(isset($wphrmLoss[$month_key]) ? $wphrmLoss[$month_key] : '');
                                $wphrmLoss[$month_key] = $wphrmLosses;
                            }
                        }
                        $wphrmExpenceReport[$month_key] = intval(isset($wphrmProfit[$month_key]) ? $wphrmProfit[$month_key] : '') - intval(isset($wphrmLoss[$month_key]) ? $wphrmLoss[$month_key] : '');
                    }
                }
            endif;
        }
        return $wphrmExpenceReport;
    }

    /**
     *   WP-HRM get Settings
     *   @argument key 
     *   -featch settings from settingsTable using setting key
     *   @returns settings datas
     * */
    public function WPHRMGetSettings($key) {
        global $wpdb;
        $wphrmSettingsResults = array();
        $key = esc_sql($key); // esc
        $wphrmSettings = $wpdb->get_row("SELECT `settingValue` FROM  $this->WphrmSettingsTable  where `settingKey` = '$key'");
        if (!empty($wphrmSettings)) {
            $wphrmSettingsResults = unserialize(base64_decode($wphrmSettings->settingValue));
        }
        return $wphrmSettingsResults;
    }

    /** END WP_HRM FUNCTIONAL ACTIONS * */
    /** BEGIN WP_HRM AJAX PAGE ACTIONS * */

    /** Load  month wise  holidays View. * */
    public function WPHRMHolidayMonthWise() {
        ob_clean();
        if (isset($_POST['wphrm_year']) && isset($_POST['holiday_month'])) :
            $this->WPHRMAJAXDATAS->WPHRMGetHolidayMonth($_POST['wphrm_year'], $_POST['holiday_month']);
            $datas['wphrmHolidayMonth'] = ob_get_contents();
            ob_clean();
        endif;
        echo json_encode($datas);
        exit();
    }

    /** Load  month wise  holidays View. * */
    public function WPHRMHolidayYearWise() {
        ob_clean();
        if (isset($_POST['wphrm_year'])) :
            $this->WPHRMAJAXDATAS->WPHRMGetHolidayYear($_POST['wphrm_year']);
            $datas['wphrmHolidayYear'] = ob_get_contents();
            ob_clean();
        endif;
        echo json_encode($datas);
        exit();
    }

    /** Load   Financial Graph View. * */
    public function WPHRMAjaxFinancialGraphLoad() {
        ob_clean();
        if (isset($_POST['wphrm_year'])) :
            $this->WPHRMAJAXDATAS->WPHRMGetFinancialGraph($_POST['wphrm_year']);
            $datas['wphrmFinancialGraph'] = ob_get_contents();
            ob_clean();
        endif;
        echo json_encode($datas);
        exit();
    }

    /** ajax calender load function. * */
    public function WPHRMAjaxCalenderLoad() {
       ob_clean();
        $datas = array();
        if (isset($_POST['wphrm_empId']) && isset($_POST['wphrm_year'])) :
            $this->WPHRMAJAXDATAS->WPHRMGetSalaryData($_POST['wphrm_empId'], $_POST['wphrm_year']);
            $datas['wphrmSalaryData'] = ob_get_contents();
           ob_clean();
        endif;
        echo json_encode($datas);
        exit();
    }


    /** WP-HRM Get Salary Report Excel Formate * */
    public function WPHRMSalaryReports() {
        if (isset($_POST)) {
            $this->WPHRMREPORTS->WPHRMGetSalaryReportExcel($_POST['from-date'], $_POST['to-date'], $_POST['wphrm-employee-id']);
        }
    }

   
    /** Add Employee Details function. * */
    public function WPHRMEmployeeBasicInfo() {
        global $current_user;
        $wphrmCurrentUserRole = implode(',', $current_user->roles);
        $result = array();
        $password = '';
        $movefile_employee_profile = '';
        $wphrm_employee_permanant_address = '';
        $wphrm_employee_local_address = '';
        $wphrm_employee_bod = '';
        $wphrm_employee_phone = '';
        $wphrm_employee_status = '';
        $wphrm_employee_gender = '';
        $wphrm_employee_joining_date = '';
        $wphrm_employee_designation = '';
        $wphrm_employee_department = '';
        $wphrm_employee_userid = '';
        $wphrm_employee_uniqueid = '';
        $wphrm_employee_email = '';
        $wphrm_employee_fathername = '';
        $wphrm_employee_lname = '';
        $wphrm_employee_fname = '';
        $Employeerole = '';
        if (isset($_POST['wphrm_employee_password']) && $_POST['wphrm_employee_password'] != '') {
            $password = sanitize_text_field($_POST['wphrm_employee_password']);
        }
        if (isset($_POST['wphrm_employee_fname']) && $_POST['wphrm_employee_fname'] != '') {
            $wphrm_employee_fname = sanitize_text_field($_POST['wphrm_employee_fname']);
        }
        if (isset($_POST['wphrm_employee_lname']) && $_POST['wphrm_employee_lname'] != '') {
            $wphrm_employee_lname = sanitize_text_field($_POST['wphrm_employee_lname']);
        }
        if (isset($_POST['wphrm_employee_fathername']) && $_POST['wphrm_employee_fathername'] != '') {
            $wphrm_employee_fathername = sanitize_text_field($_POST['wphrm_employee_fathername']);
        }
        if (isset($_POST['wphrm_employee_email']) && $_POST['wphrm_employee_email'] != '') {
            $wphrm_employee_email = sanitize_text_field($_POST['wphrm_employee_email']);
        }
        if (isset($_POST['wphrm_employee_uniqueid']) && $_POST['wphrm_employee_uniqueid'] != '') {
            $wphrm_employee_uniqueid = sanitize_text_field($_POST['wphrm_employee_uniqueid']);
        }
        if (isset($_POST['wphrm_employee_userid']) && $_POST['wphrm_employee_userid'] != '') {
            $wphrm_employee_userid = sanitize_text_field($_POST['wphrm_employee_userid']);
        }
        if (isset($_POST['wphrm_employee_department']) && $_POST['wphrm_employee_department'] != '') {
            $wphrm_employee_department = sanitize_text_field($_POST['wphrm_employee_department']);
        }
        if (isset($_POST['wphrm_employee_designation']) && $_POST['wphrm_employee_designation'] != '') {
            $wphrm_employee_designation = sanitize_text_field($_POST['wphrm_employee_designation']);
        }
        if (isset($_POST['wphrm_employee_joining_date']) && $_POST['wphrm_employee_joining_date'] != '') {
            $wphrm_employee_joining_date = sanitize_text_field($_POST['wphrm_employee_joining_date']);
        }
        if (isset($_POST['wphrm_employee_gender']) && $_POST['wphrm_employee_gender'] != '') {
            $wphrm_employee_gender = sanitize_text_field($_POST['wphrm_employee_gender']);
        }
        if (isset($_POST['wphrm_employee_status']) && $_POST['wphrm_employee_status'] != '') {
            $wphrm_employee_status = sanitize_text_field($_POST['wphrm_employee_status']);
        }
        if (isset($_POST['wphrm_employee_phone']) && $_POST['wphrm_employee_phone'] != '') {
            $wphrm_employee_phone = sanitize_text_field($_POST['wphrm_employee_phone']);
        }
        if (isset($_POST['wphrm_employee_bod']) && $_POST['wphrm_employee_bod'] != '') {
            $wphrm_employee_bod = sanitize_text_field($_POST['wphrm_employee_bod']);
        }
        if (isset($_POST['wphrm_employee_local_address']) && $_POST['wphrm_employee_local_address'] != '') {
            $wphrm_employee_local_address = sanitize_text_field($_POST['wphrm_employee_local_address']);
        }
        if (isset($_POST['wphrm_employee_permanant_address']) && $_POST['wphrm_employee_permanant_address'] != '') {
            $wphrm_employee_permanant_address = sanitize_text_field($_POST['wphrm_employee_permanant_address']);
        }
        if (isset($wphrmCurrentUserRole) && $wphrmCurrentUserRole == 'administrator') {
            if ($wphrm_employee_status == 'Active') {
                $Employeerole = sanitize_text_field($_POST['wphrm_employee_role']);
            }else{
                $Employeerole = sanitize_text_field('Inactive');
            }
        }

        $wphrmEmployeeId = intval($_POST['wphrm_employee_id']);

        if (!empty($_FILES['employee_profile']['name'])) {
            $employeeProfiles = wp_check_filetype($_FILES['employee_profile']['name']);
            $employeeProfile = $employeeProfiles['ext'];
            if ($employeeProfile == 'png' || $employeeProfile == 'jpg' || $employeeProfile == 'jpeg' ||
                    $employeeProfile == 'gif' || $employeeProfile == 'PNG' || $employeeProfile == 'JPG' || $employeeProfile == 'JPEG' || $employeeProfile == 'GIF') {
                $uploadedEmployeeProfileFile = $_FILES['employee_profile'];
                $uploadEmployeeProfileOverrides = array('test_form' => false);
                $movefile_employee_profile = wp_handle_upload($uploadedEmployeeProfileFile, $uploadEmployeeProfileOverrides);
            } else {
                $result['error'] = __('This is invalid file format.', 'wphrm');
                echo json_encode($result);
                exit;
            }
        }
        if (!empty($wphrmEmployeeId)) :
            $wphrmFormDetails = array();
            $wphrmFormDetails = $this->WPHRMGetUserDatas($wphrmEmployeeId, 'wphrmEmployeeInfo');
            if (!empty($_FILES['employee_profile']['tmp_name'])) {
                $wphrmFormDetails['employee_profile'] = $movefile_employee_profile['url'];
            } else {
                $wphrmFormDetails['employee_profile'] = $wphrmFormDetails['employee_profile'];
            }
            $wphrmFormDetails['wphrm_employee_fname'] = $wphrm_employee_fname;
            $wphrmFormDetails['wphrm_employee_lname'] = $wphrm_employee_lname;
            $wphrmFormDetails['wphrm_employee_fathername'] = $wphrm_employee_fathername;
            $wphrmFormDetails['wphrm_employee_email'] = $wphrm_employee_email;
            $wphrmFormDetails['wphrm_employee_uniqueid'] = $wphrm_employee_uniqueid;
            $wphrmFormDetails['wphrm_employee_userid'] = $wphrm_employee_userid;
            $wphrmFormDetails['wphrm_employee_password'] = $password;
            $wphrmFormDetails['wphrm_employee_department'] = $wphrm_employee_department;
            $wphrmFormDetails['wphrm_employee_designation'] = $wphrm_employee_designation;
            $wphrmFormDetails['wphrm_employee_joining_date'] = $wphrm_employee_joining_date;
            $wphrmFormDetails['wphrm_employee_gender'] = $wphrm_employee_gender;
            $wphrmFormDetails['wphrm_employee_role'] = $Employeerole;
            $wphrmFormDetails['wphrm_employee_status'] = $wphrm_employee_status;
            $wphrmFormDetails['wphrm_employee_phone'] = $wphrm_employee_phone;
            $wphrmFormDetails['wphrm_employee_bod'] = $wphrm_employee_bod;
            $wphrmFormDetails['wphrm_employee_local_address'] = $wphrm_employee_local_address;
            $wphrmFormDetails['wphrm_employee_permanant_address'] = $wphrm_employee_permanant_address;


            if (empty($wphrmErrors)) {
                $userdata = array(
                    'ID' => $wphrmEmployeeId,
                    'user_email' => $wphrm_employee_email,
                    'display_name' => $wphrm_employee_userid,
                    'user_login' => $wphrm_employee_userid,
                    'first_name' => $wphrm_employee_fname,
                    'last_name' => $wphrm_employee_lname,
                    'role' => $Employeerole,
                );
                if ($password != '') {
                    $userdata['user_pass'] = $password;
                }
                wp_update_user($userdata);
                $wphrmFormDetailsData = base64_encode(serialize($wphrmFormDetails));
                update_user_meta($wphrmEmployeeId, "wphrmEmployeeInfo", $wphrmFormDetailsData);
                $result['success'] = true;
                $result['currentrole'] = $wphrmCurrentUserRole;
            } else {
                $result['error'] = $wphrmErrors;
            }
        else :
            
            $wphrmUsers =  $this->WPHRMGetEmployees();
            $wphrmUserData =  count($wphrmUsers);
             if($wphrmUserData <= 5){
            $wphrmFormDetails = array();
            if (!empty($_FILES['employee_profile']['tmp_name'])) {
                $wphrmFormDetails['employee_profile'] = $movefile_employee_profile['url'];
            }
            $wphrmFormDetails['wphrm_employee_fname'] = $wphrm_employee_fname;
            $wphrmFormDetails['wphrm_employee_lname'] = $wphrm_employee_lname;
            $wphrmFormDetails['wphrm_employee_fathername'] = $wphrm_employee_fathername;
            $wphrmFormDetails['wphrm_employee_email'] = $wphrm_employee_email;
            $wphrmFormDetails['wphrm_employee_uniqueid'] = $wphrm_employee_uniqueid;
            $wphrmFormDetails['wphrm_employee_userid'] = $wphrm_employee_userid;
            $wphrmFormDetails['wphrm_employee_password'] = $password;
            $wphrmFormDetails['wphrm_employee_department'] = $wphrm_employee_department;
            $wphrmFormDetails['wphrm_employee_designation'] = $wphrm_employee_designation;
            $wphrmFormDetails['wphrm_employee_joining_date'] = $wphrm_employee_joining_date;
            $wphrmFormDetails['wphrm_employee_gender'] = $wphrm_employee_gender;
            $wphrmFormDetails['wphrm_employee_role'] = $Employeerole;
            $wphrmFormDetails['wphrm_employee_status'] = $wphrm_employee_status;
            $wphrmFormDetails['wphrm_employee_phone'] = $wphrm_employee_phone;
            $wphrmFormDetails['wphrm_employee_bod'] = $wphrm_employee_bod;
            $wphrmFormDetails['wphrm_employee_local_address'] = $wphrm_employee_local_address;
            $wphrmFormDetails['wphrm_employee_permanant_address'] = $wphrm_employee_permanant_address;
            if (email_exists($wphrm_employee_email)) {
                $wphrmErrors = __('This email id already exists.', 'wphrm'); // Email address already registered
            }
            if (username_exists($wphrm_employee_userid)) {
                $wphrmErrors = __('Userid already taken', 'wphrm'); // Username already registered
            }
            if (empty($wphrmErrors)) {
                $wphrmNewUserId = wp_insert_user(array(
                    'user_email' => $wphrm_employee_email,
                    'user_pass' => $password,
                    'display_name' => $wphrm_employee_userid,
                    'user_login' => $wphrm_employee_userid,
                    'first_name' => $wphrm_employee_fname,
                    'user_registered' => date('Y-m-d H:i:s'),
                    'last_name' => $wphrm_employee_lname,
                    'role' => $Employeerole,
                ));
                $wphrmFormDetailsData = base64_encode(serialize($wphrmFormDetails));
                update_user_meta($wphrmNewUserId, "wphrmEmployeeInfo", $wphrmFormDetailsData);
                $result['success'] = $wphrmNewUserId;
                $result['currentrole'] = $wphrmCurrentUserRole;
            } else {
                $result['error'] = $wphrmErrors;
    } } else {
        $result['error'] = __("You can add upto 5 employees.", 'wphrm');
    }
        endif;
        echo json_encode($result);
        exit;
    }

    /** Add Employee documents Details function. * */
    public function WPHRMEmployeeDocumentInfo() {
        global $current_user;
        $wphrmCurrentUserRole = implode(',', $current_user->roles);
        $result = array();
        $wphrmEmployeeId = intval($_POST['wphrm_employee_id']);
        $wphrmFormDetails['resume'] = '';
        $wphrmFormDetails['offerLetter'] = '';
        $wphrmFormDetails['joiningLetter'] = '';
        $wphrmFormDetails['contract'] = '';
        $wphrmFormDetails['IDProof'] = '';

        if (!empty($_FILES['resume']['name'])) {
            $employeeResumes = wp_check_filetype($_FILES['resume']['name']);
            $employeeResume = $employeeResumes['ext'];
            if ($employeeResume == 'png' || $employeeResume == 'jpg' || $employeeResume == 'jpeg' || $employeeResume == 'txt' || $employeeResume == 'docx' || $employeeResume == 'DOCX' || $employeeResume == 'pdf' || $employeeResume == 'TXT' || $employeeResume == 'doc' || $employeeResume == 'DOC'|| $employeeResume == 'PDF' || $employeeResume == 'PNG' || $employeeResume == 'JPG' || $employeeResume == 'JPEG') {
                $uploadedResumeFile = $_FILES['resume'];
                $uploadResumeOverrides = array('test_form' => false);
                $movefileResume = wp_handle_upload($uploadedResumeFile, $uploadResumeOverrides);
            } else {
                $result['error'] = __('This is invalid file format.', 'wphrm');
            }
        }
        if (!empty($_FILES['offerLetter']['name'])) {
            $employeeOfferLetters = wp_check_filetype($_FILES['offerLetter']['name']);
            $employeeOfferLetter = $employeeOfferLetters['ext'];
            if ($employeeOfferLetter == 'PNG' || $employeeOfferLetter == 'JPG' || $employeeOfferLetter == 'JPEG' || $employeeOfferLetter == 'TXT' || $employeeOfferLetter == 'DOCX' || $employeeOfferLetter == 'PDF' || $employeeOfferLetter == 'png' || $employeeOfferLetter == 'jpg' ||  $employeeOfferLetter == 'doc' || $employeeOfferLetter == 'DOC' || $employeeOfferLetter == 'jpeg' || $employeeOfferLetter == 'txt' || $employeeOfferLetter == 'docx' || $employeeOfferLetter == 'pdf') {
                $uploadedOfferLetterFile = $_FILES['offerLetter'];
                $uploadOfferLetterOverrides = array('test_form' => false);
                $movefileOfferLetter = wp_handle_upload($uploadedOfferLetterFile, $uploadOfferLetterOverrides);
            } else {
                $result['error'] = __('This is invalid file format.', 'wphrm');
            }
        }
        if (!empty($_FILES['joiningLetter']['name'])) {
            $employeeJoiningLetters = wp_check_filetype($_FILES['joiningLetter']['name']);
            $employeeJoiningLetter = $employeeJoiningLetters['ext'];
            if ($employeeJoiningLetter == 'PNG' || $employeeJoiningLetter == 'JPG' || $employeeJoiningLetter == 'doc' || $employeeJoiningLetter == 'DOC' || $employeeJoiningLetter == 'JPEG' || $employeeJoiningLetter == 'TXT' || $employeeJoiningLetter == 'DOCX' || $employeeJoiningLetter == 'PDF' || $employeeJoiningLetter == 'png' || $employeeJoiningLetter == 'jpg' || $employeeJoiningLetter == 'jpeg' || $employeeJoiningLetter == 'txt' || $employeeJoiningLetter == 'docx' || $employeeJoiningLetter == 'pdf') {
                $uploadedJoiningLetterFile = $_FILES['joiningLetter'];
                $uploadJoiningLetterOverrides = array('test_form' => false);
                $movefileJoiningLetter = wp_handle_upload($uploadedJoiningLetterFile, $uploadJoiningLetterOverrides);
            } else {
                $result['error'] = __('This is invalid file format.', 'wphrm');
            }
        }
        if (!empty($_FILES['contract']['name'])) {
            $employeeContracts = wp_check_filetype($_FILES['contract']['name']);
            $employeeContract = $employeeContracts['ext'];
            if ($employeeContract == 'PNG' || $employeeContract == 'JPG' || $employeeContract == 'JPEG' || $employeeContract == 'doc' || $employeeContract == 'DOC' || $employeeContract == 'TXT' || $employeeContract == 'DOCX' || $employeeContract == 'PDF' || $employeeContract == 'png' || $employeeContract == 'jpg' || $employeeContract == 'jpeg' || $employeeContract == 'txt' || $employeeContract == 'docx' || $employeeContract == 'pdf') {
                $uploadedContractFile = $_FILES['contract'];
                $uploadContractOverrides = array('test_form' => false);
                $movefileContract = wp_handle_upload($uploadedContractFile, $uploadContractOverrides);
            } else {
                $result['error'] = __('This is invalid file format.', 'wphrm');
            }
        }
        if (!empty($_FILES['IDProof']['name'])) {
            $employeeIDProofs = wp_check_filetype($_FILES['IDProof']['name']);
            $employeeIDProof = $employeeIDProofs['ext'];
            if ($employeeIDProof == 'PNG' || $employeeIDProof == 'JPG' || $employeeIDProof == 'JPEG' || $employeeIDProof == 'TXT' || $employeeIDProof == 'doc' || $employeeIDProof == 'DOC'  || $employeeIDProof == 'DOCX' || $employeeIDProof == 'PDF' || $employeeIDProof == 'png' || $employeeIDProof == 'jpg' || $employeeIDProof == 'jpeg' || $employeeIDProof == 'txt' || $employeeIDProof == 'docx' || $employeeIDProof == 'pdf') {
                $uploadedIDProofFile = $_FILES['IDProof'];
                $uploadIDProofOverrides = array('test_form' => false);
                $movefileIDProof = wp_handle_upload($uploadedIDProofFile, $uploadIDProofOverrides);
            } else {
                $result['error'] = __('This is invalid file format.', 'wphrm');
            }
        }

        if (!empty($wphrmEmployeeId) && ($_FILES['resume']['name'] || $_FILES['offerLetter']['name'] || $_FILES['joiningLetter']['name'] || $_FILES['contract']['name'] || $_FILES['IDProof']['name'])) {
            $wphrmFormDetails = array();
            $wphrmFormDetails = $this->WPHRMGetUserDatas($wphrmEmployeeId, 'wphrmEmployeeDocumentInfo');
            if (!empty($_FILES['resume']['tmp_name'])) {
                $wphrmFormDetails['resume'] = sanitize_url($movefileResume['url']);
            } else if (isset($wphrmFormDetails['resume']) && !empty($wphrmFormDetails['resume'])) {
                $wphrmFormDetails['resume'] = $wphrmFormDetails['resume'];
            }
            if (!empty($_FILES['offerLetter']['tmp_name'])) {
                $wphrmFormDetails['offerLetter'] = sanitize_url($movefileOfferLetter['url']);
            } else if (isset($wphrmFormDetails['offerLetter']) && !empty($wphrmFormDetails['offerLetter'])) {
                $wphrmFormDetails['offerLetter'] = $wphrmFormDetails['offerLetter'];
            }
            if (!empty($_FILES['joiningLetter']['tmp_name'])) {
                $wphrmFormDetails['joiningLetter'] = sanitize_url($movefileJoiningLetter['url']);
            } else if (isset($wphrmFormDetails['joiningLetter']) && $wphrmFormDetails['joiningLetter']) {
                $wphrmFormDetails['joiningLetter'] = $wphrmFormDetails['joiningLetter'];
            }
            if (!empty($_FILES['contract']['tmp_name'])) {
                $wphrmFormDetails['contract'] = sanitize_url($movefileContract['url']);
            } else if (isset($wphrmFormDetails['contract']) && $wphrmFormDetails['contract']) {
                $wphrmFormDetails['contract'] = $wphrmFormDetails['contract'];
            }
            if (!empty($_FILES['IDProof']['tmp_name'])) {
                $wphrmFormDetails['IDProof'] = sanitize_url($movefileIDProof['url']);
            } else if (isset($wphrmFormDetails['IDProof']) && $wphrmFormDetails['IDProof']) {
                $wphrmFormDetails['IDProof'] = $wphrmFormDetails['IDProof'];
            }
            $wphrmFormDetailsData = base64_encode(serialize($wphrmFormDetails));
            update_user_meta($wphrmEmployeeId, "wphrmEmployeeDocumentInfo", $wphrmFormDetailsData);
            $result['success'] = true;
        } else {
            $wphrmFormDetails = array();
            if ($_FILES['resume']['name'] || $_FILES['offerLetter']['name'] || $_FILES['joiningLetter']['name'] || $_FILES['contract']['name'] || $_FILES['IDProof']['name']) {
                $wphrmFormDetails['resume'] = sanitize_url($movefileResume['url']);
                $wphrmFormDetails['offerLetter'] = sanitize_url($movefileOfferLetter['url']);
                $wphrmFormDetails['joiningLetter'] = sanitize_url($movefileJoiningLetter['url']);
                $wphrmFormDetails['contract'] = sanitize_url($movefileContract['url']);
                $wphrmFormDetails['IDProof'] = sanitize_url($movefileIDProof['url']);
                $wphrmFormDetailsData = base64_encode(serialize($wphrmFormDetails));
                $wphrmFormDetailsData = sanitize_text_field($wphrmFormDetailsData);
                update_user_meta($wphrmEmployeeId, "wphrmEmployeeDocumentInfo", $wphrmFormDetailsData);
                $result['success'] = true;
            } else {
                $result['error'] = __("Please select file.", 'wphrm');
            }
        }
        echo json_encode($result);
        exit;
    }

    /** Add Employee Salary Details function. * */
    public function WPHRMEmployeeSalaryInfo() {
        $wphrmEmployeeId = intval($_POST['wphrm_employee_id']);
        global $current_user;
        $wphrmCurrentUserRole = implode(',', $current_user->roles);
        $result = array();
        if (!empty($wphrmEmployeeId)) :
            $wphrmFormDetails = array();
            $wphrmFormDetails = $this->WPHRMGetUserDatas($wphrmEmployeeId, 'wphrmEmployeeSalaryInfo');
            $wphrmFormDetails['SalaryFieldsLebal'] = $this->WPHRMSanitize($_POST['salary-fields-lebal']);
            $wphrmFormDetails['SalaryFieldsvalue'] = $this->WPHRMSanitize($_POST['salary-fields-value']);
            $wphrmFormDetailsData = base64_encode(serialize($wphrmFormDetails));
            update_user_meta($wphrmEmployeeId, "wphrmEmployeeSalaryInfo", $wphrmFormDetailsData);
            $result['success'] = true;
        else :
            $wphrmFormDetails = array();
            $wphrmFormDetails['SalaryFieldsLebal'] = $this->WPHRMSanitize($_POST['salary-fields-lebal']);
            $wphrmFormDetails['SalaryFieldsvalue'] = $this->WPHRMSanitize($_POST['salary-fields-value']);
            $wphrmFormDetailsData = base64_encode(serialize($wphrmFormDetails));
            update_user_meta($wphrmEmployeeId, "wphrmEmployeeSalaryInfo", $wphrmFormDetailsData);
            $result['success'] = true;
        endif;
        echo json_encode($result);
        exit;
    }

    /** Add Employee bank Details function. * */
    public function WPHRMEmployeeBankInfo() {
        $wphrmEmployeeId = intval($_POST['wphrm_employee_id']);
        $result = array();
        if (!empty($wphrmEmployeeId)) :
            $wphrmFormDetails = array();
            $wphrmFormDetails = $this->WPHRMGetUserDatas($wphrmEmployeeId, 'wphrmEmployeeBankInfo');
            $wphrmFormDetails['wphrmbankfieldslebal'] = $this->WPHRMSanitize($_POST['bank-fields-lebal']);
            $wphrmFormDetails['wphrmbankfieldsvalue'] = $this->WPHRMSanitize($_POST['bank-fields-value']);
            $wphrmFormDetails['wphrm_employee_bank_account_name'] = $_POST['wphrm_employee_bank_account_name'];
            $wphrmFormDetails['wphrm_employee_bank_account_no'] = $_POST['wphrm_employee_bank_account_no'];
            $wphrmFormDetailsData = base64_encode(serialize($wphrmFormDetails));
            update_user_meta($wphrmEmployeeId, "wphrmEmployeeBankInfo", $wphrmFormDetailsData);
            $result['success'] = true;
        else :
            $wphrmFormDetails = array();
            $wphrmFormDetails = $this->WPHRMGetUserDatas($wphrmEmployeeId, 'wphrmEmployeeBankInfo');
            $wphrmFormDetails['wphrmbankfieldslebal'] = $this->WPHRMSanitize($_POST['bank-fields-lebal']);
            $wphrmFormDetails['wphrmbankfieldsvalue'] = $this->WPHRMSanitize($_POST['bank-fields-value']);
            $wphrmFormDetails['wphrm_employee_bank_account_name'] = $_POST['wphrm_employee_bank_account_name'];
            $wphrmFormDetails['wphrm_employee_bank_account_no'] = $_POST['wphrm_employee_bank_account_no'];
            $wphrmFormDetailsData = base64_encode(serialize($wphrmFormDetails));
            update_user_meta($wphrmEmployeeId, "wphrmEmployeeBankInfo", $wphrmFormDetailsData);
            $result['success'] = true;
        endif;
        echo json_encode($result);
        exit;
    }

    /** Add Employee other Details function. * */
    public function WPHRMEmployeeOtherInfo() {
        $wphrmEmployeeId = intval($_POST['wphrm_employee_id']);
        if (isset($_POST['wphrm_vehicle_type']) && $_POST['wphrm_vehicle_type'] != '') :
            $wphrmVehicleType = $_POST['wphrm_vehicle_type'];
        else:
            $wphrmVehicleType = '';
        endif;
        if (isset($_POST['wphrm_employee_vehicle']) && $_POST['wphrm_employee_vehicle'] != '') :
            $wphrmEmployeeVehicle = $_POST['wphrm_employee_vehicle'];
        else:
            $wphrmEmployeeVehicle = '';
        endif;
        $wphrmFormDetails['wphrm_vehicle_type'] = '';
        $wphrmFormDetails['wphrm_employee_vehicle'] = '';
        global $current_user;
        $wphrmCurrentUserRole = implode(',', $current_user->roles);
        $result = array();
        if (!empty($wphrmEmployeeId)) :
            $wphrmFormDetails = array();
            $wphrmFormDetails = $this->WPHRMGetUserDatas($wphrmEmployeeId, 'wphrmEmployeeOtherInfo');
            $wphrmFormDetails['wphrmotherfieldslebal'] = $this->WPHRMSanitize($_POST['other-fields-lebal']);
            $wphrmFormDetails['wphrmotherfieldsvalue'] = $this->WPHRMSanitize($_POST['other-fields-value']);
            $wphrmFormDetails['wphrm_employee_vehicle'] = sanitize_text_field($wphrmEmployeeVehicle);
            $wphrmFormDetails['wphrm_vehicle_type'] = sanitize_text_field($wphrmVehicleType);
            $wphrmFormDetails['wphrm_employee_vehicle_model'] = sanitize_text_field($_POST['wphrm_employee_vehicle_model']);
            $wphrmFormDetails['wphrm_employee_vehicle_registrationno'] = sanitize_text_field($_POST['wphrm_employee_vehicle_registrationno']);
            $wphrmFormDetails['wphrm_t_shirt_size'] = sanitize_text_field($_POST['wphrm_t_shirt_size']);
            $wphrmFormDetailsData = base64_encode(serialize($wphrmFormDetails));
            update_user_meta($wphrmEmployeeId, "wphrmEmployeeOtherInfo", $wphrmFormDetailsData);
            $result['success'] = true;
        else :
            $wphrmFormDetails = array();
            $wphrmFormDetails = $this->WPHRMGetUserDatas($wphrmEmployeeId, 'wphrmEmployeeBankInfo');
            $wphrmFormDetails['wphrmotherfieldslebal'] = $this->WPHRMSanitize($_POST['other-fields-lebal']);
            $wphrmFormDetails['wphrmotherfieldsvalue'] = $this->WPHRMSanitize($_POST['other-fields-value']);
            $wphrmFormDetails['wphrm_employee_vehicle'] = sanitize_text_field($wphrmEmployeeVehicle);
            $wphrmFormDetails['wphrm_vehicle_type'] = sanitize_text_field($wphrmVehicleType);
            $wphrmFormDetails['wphrm_employee_vehicle_model'] = sanitize_text_field($_POST['wphrm_employee_vehicle_model']);
            $wphrmFormDetails['wphrm_employee_vehicle_registrationno'] = sanitize_text_field($_POST['wphrm_employee_vehicle_registrationno']);
            $wphrmFormDetails['wphrm_t_shirt_size'] = sanitize_text_field($_POST['wphrm_t_shirt_size']);
            $wphrmFormDetailsData = base64_encode(serialize($wphrmFormDetails));
            update_user_meta($wphrmEmployeeId, "wphrmEmployeeOtherInfo", $wphrmFormDetailsData);
            $result['success'] = true;
        endif;
        echo json_encode($result);
        exit;
    }

      /** Add Employee Department function. * */
    public function WPHRMDepartmentInfo() {
        global $current_user, $wpdb;
        $wphrmDepartmentId = '';
        if (isset($_POST['wphrm_department_id']) && $_POST['wphrm_department_id'] != '') :
            $wphrmDepartmentId = esc_sql($_POST['wphrm_department_id']);  // esc
        endif;
        $wphrmCurrentUserRole = implode(',', $current_user->roles);
        $result = array();
        if (!empty($wphrmDepartmentId)) :
            $wphrmFormDetails = array();
            $wphrm_department = $wpdb->get_row("SELECT * FROM $this->WphrmDepartmentTable WHERE `departmentID` = $wphrmDepartmentId");
            $wphrmFormDetails = unserialize(base64_decode($wphrm_department->departmentName));
            $wphrmFormDetails['departmentName'] = sanitize_text_field($_POST['editdepartment_name']);
            $wphrmFormDetailsData = base64_encode(serialize($wphrmFormDetails));
            $id = $wpdb->query("UPDATE $this->WphrmDepartmentTable SET `departmentName`='$wphrmFormDetailsData' WHERE `departmentID`= $wphrmDepartmentId");
            $result['success'] = true;
        else :
            $wphrmFormDetails = array();
            if ($_POST['departmentName'] != '') {
                foreach ($_POST['departmentName'] as $wphrmDepartmentName) {
                    $wphrmFormDetails['departmentName'] = sanitize_text_field($wphrmDepartmentName);
                    $wphrmFormDetailsData = base64_encode(serialize($wphrmFormDetails));
                    $wpdb->query("INSERT INTO $this->WphrmDepartmentTable (`departmentName`) VALUES('$wphrmFormDetailsData')");
                }
            }
            $result['success'] = true;

        endif;
        echo json_encode($result);
        exit;
    }

    /** Add Employee Department function. * */
    public function WPHRMDesignationInfo() {
        global $current_user, $wpdb;
        $wphrmDesignationId = '';
        if (isset($_POST['wphrm_designation_id']) && $_POST['wphrm_designation_id'] != '') :
            $wphrmDesignationId = esc_sql($_POST['wphrm_designation_id']);  // esc
        endif;
        $wphrmCurrentUserRole = implode(',', $current_user->roles);
        $result = array();
        if (!empty($wphrmDesignationId)) :
            $wphrmFormDetails = array();
            $wphrm_designation = $wpdb->get_row("SELECT * FROM $this->WphrmDesignationTable WHERE `designationID` = $wphrmDesignationId");
            $wphrmFormDetails = unserialize(base64_decode($wphrm_designation->designationName));
            $wphrmFormDetails['designationName'] = sanitize_text_field($_POST['editdesignation']);
            $wphrmFormDetailsData = base64_encode(serialize($wphrmFormDetails));
            $id = $wpdb->query("UPDATE $this->WphrmDesignationTable SET  `designationName`='$wphrmFormDetailsData'  WHERE `designationID`= $wphrmDesignationId");
            $result['success'] = true;
        else :

            $wphrmFormDetails = array();
            if ($_POST['designation_name'] != '') {
                foreach ($_POST['designation_name'] as $wphrmDesignation) {
                    $wphrmFormDetails['designationName'] = sanitize_text_field($wphrmDesignation);
                    $wphrmFormDetailsData = base64_encode(serialize($wphrmFormDetails));
                    $wpdb->query("INSERT INTO $this->WphrmDesignationTable (`designationName`, `departmentID`) VALUES('$wphrmFormDetailsData','" . $_POST['departmentID'] . "')");
                }
            }
            $result['success'] = true;
        endif;
        echo json_encode($result);
        exit;
    }

    /** Leave type function. * */
    public function WPHRMLeavetypeInfo() {
        global $current_user, $wpdb;
        $wphrmLeaveTypeId = '';
        $leaveType = '';
        $numOfLeave = '';
        $leaveType = sanitize_text_field($_POST['leaveType']);
        $wphrmPeriod = sanitize_text_field($_POST['wphrm_period']);
        $numOfLeave = sanitize_text_field($_POST['numberOfLeave']);
        if (isset($_POST['wphrm_leavetype_id']) && $_POST['wphrm_leavetype_id'] != '') :
            $wphrmLeaveTypeId = esc_sql($_POST['wphrm_leavetype_id']);  // esc
        endif;
        $wphrmCurrentUserRole = implode(',', $current_user->roles);
        $result = array();
        if (!empty($wphrmLeaveTypeId)) :
            $wphrmFormDetails = array();
            $wphrm_leavetype = $wpdb->get_row("SELECT * FROM $this->WphrmLeaveTypeTable WHERE `id` = $wphrmLeaveTypeId");
            $id = $wpdb->query("UPDATE $this->WphrmLeaveTypeTable SET `leaveType`='$leaveType',`period`='$wphrmPeriod', `numberOfLeave` ='$numOfLeave' WHERE `id` = $wphrmLeaveTypeId");
            $result['success'] = true;
        else :
            $wphrmFormDetails = array();
            $id = $wpdb->query("INSERT INTO $this->WphrmLeaveTypeTable (`leaveType`, `period`, `numberOfLeave`) VALUES('$leaveType', '$wphrmPeriod', '$numOfLeave')");
            $result['success'] = true;
        endif;
        echo json_encode($result);
        exit;
    }

    /** Leave Application function. * */
    public function WPHRMLeaveApplicationsInfo() {
        global $current_user, $wpdb;
        $wphrmLeaveApplicationId = '';
        $wphrmNotificationSettings = '';
        $wphrmNotificationSetting = '';
        $wphrmEmployeeID = '';
        if (isset($_POST['wphrm_leave_application_id']) && $_POST['wphrm_leave_application_id'] != '') :
            $wphrmLeaveApplicationId = sanitize_text_field($_POST['wphrm_leave_application_id']);
        endif;
        if (isset($_POST['wphrm_employeeID']) && $_POST['wphrm_employeeID'] != '') :
            $wphrmEmployeeID = sanitize_text_field($_POST['wphrm_employeeID']);
        endif;
        $wphrmCurrentUserRole = implode(',', $current_user->roles);
        $result = array();
        if (!empty($wphrmLeaveApplicationId)) :

            $id = $wpdb->query("UPDATE `$this->WphrmAttendanceTable` SET `applicationStatus` ='" . sanitize_text_field($_POST['applicationStatus']) . "' WHERE `id` = '" . $wphrmLeaveApplicationId . "'");
            $wphrmNotificationSettings = $wpdb->get_row("SELECT * FROM  $this->WphrmSettingsTable WHERE `settingKey` = 'wphrmNotificationsSettingsInfo'");
            if (!empty($wphrmNotificationSettings)) {
                $wphrmNotificationSetting = unserialize(base64_decode($wphrmNotificationSettings->settingValue));
                if ($wphrmNotificationSetting['wphrm_leave_notification'] == 1) {
                    $notification = array('wphrmUserID' => sanitize_text_field($wphrmEmployeeID),
                        'wphrmDesc' => __('Your leave application has been ', 'wphrm') . sanitize_text_field($_POST['applicationStatus']),
                        'notificationType' => 'Leave ' . sanitize_text_field($_POST['applicationStatus']),
                        'wphrmStatus' => sanitize_text_field('unseen'),
                        'wphrmDate' => sanitize_text_field(date('Y-m-d')),
                    );
                    $wpdb->insert($this->WphrmNotificationsTable, $notification);
                    $result['success'] = true;
                }
            }

        endif;
        echo json_encode($result);
        exit;
    }

   

    /** Add Employee Delete Department function. * */
    public function WPHRMCustomDelete() {
        global $current_user, $wpdb;
        $WPHRMCustomDelete_id = esc_sql($_POST['WPHRMCustomDelete_id']);  // esc
        $wphrmTableName = $_POST['table_name'];
        $filedName = $_POST['filed_name'];
        $wphrmCurrentUserRole = implode(',', $current_user->roles);
        $result = array();
        if (!empty($WPHRMCustomDelete_id)) :
            $id = $wpdb->query("DELETE FROM `$wphrmTableName` WHERE `$filedName` = $WPHRMCustomDelete_id");
            $result['success'] = true;
        else :
            $result['error'] = false;
        endif;
        echo json_encode($result);
        exit;
    }

    /** Add Employee Delete designation ajax function. * */
    public function WPHRMDesignationAjax() {
        global $current_user, $wpdb;
        $list = '';
        $result = array();
        if (isset($_POST['id'])) {
            $wphrmDepartmentId = esc_sql($_POST['id']);  // esc
        }
        $wphrmCurrentUserRole = implode(',', $current_user->roles);
        if (!empty($wphrmDepartmentId)) :
            $wphrm_designation = $wpdb->get_results("SELECT * FROM $this->WphrmDesignationTable WHERE `departmentID` = $wphrmDepartmentId");
            foreach ($wphrm_designation as $wphrm_designation_result):
                $wphrmFormDetails1 = unserialize(base64_decode($wphrm_designation_result->designationName));
                $somthing['name'] = $wphrmFormDetails1['designationName'];
                $somthing['id'] = $wphrm_designation_result->designationID;
                $list[] = $somthing;
            endforeach;
        endif;
        $response['success'] = '1';
        $response['message'] = __('Designation Successfully Done', 'wphrm');
        $response['details'] = $list;
        echo json_encode($response);
        exit;
    }

    
    /** Add multiple Weekends function. * */
    public function WPHRMwphrmAddyearInWeekendInfo() {
        global $current_user, $wpdb;
        $wphrmYear = '';
        if (isset($_POST['wphrmyear']) && $_POST['wphrmyear'] != '') {
            $wphrmYear = $_POST['wphrmyear'];
        }
        $wphrmWeekend = '';
        if (isset($_POST['wphrmWeekend']) && $_POST['wphrmWeekend'] != '') {
            $wphrmWeekend = $_POST['wphrmWeekend'];
            if ($wphrmWeekend == 'Sunday') {
                $wphrmWeekCounter = 0;
            } else if ($wphrmWeekend == 'Saturday') {
                $wphrmWeekCounter = 6;
            } else if ($wphrmWeekend == 'Friday') {
                $wphrmWeekCounter = 5;
            } else if ($wphrmWeekend == 'Thursday') {
                $wphrmWeekCounter = 4;
            } else if ($wphrmWeekend == 'Wednesday') {
                $wphrmWeekCounter = 3;
            } else if ($wphrmWeekend == 'Tuesday') {
                $wphrmWeekCounter = 2;
            } else if ($wphrmWeekend == 'Monday') {
                $wphrmWeekCounter = 1;
            }
        }

        $wphrmWeekend = esc_sql(sanitize_text_field($wphrmWeekend));
        $wphrmTypeWeekend = '';
        if (isset($_POST['wphrmTypeWeekend']) && $_POST['wphrmTypeWeekend'] != '') {
            $wphrmTypeWeekend = $_POST['wphrmTypeWeekend'];
        }
        $wphrmType = '';
        if (isset($_POST['wphrmType']) && $_POST['wphrmType'] != '') {
            $wphrmType = $_POST['wphrmType'];
        }
        $monthS = $this->WPHRMGetMonths();
        $result = array();
        $holiday_dates = esc_sql(sanitize_text_field(date('Y-m-d H:i:s')));
        foreach ($monthS as $monthSkey => $monthSs) {
            if ($monthSkey <= 12) {
                $days = $this->WPHRMDateForSpecificTwoDayOff($wphrmYear, $monthSkey, $wphrmWeekCounter, 7);
                foreach ($days as $daykey => $dayData) {
                    foreach ($wphrmType as $wphrmTypes) {
                        if ($daykey == $wphrmTypes) {
                            $holidayinserts = esc_sql($dayData); // esc
                            $id = $wpdb->query("SELECT * FROM $this->WphrmHolidaysTable WHERE `wphrmDate` = '" . $holidayinserts . "'");
                            if (!empty($id)) {
                                
                            } else {
                                $wpdb->query("INSERT INTO $this->WphrmHolidaysTable (`wphrmDate`,`wphrmOccassion`,`createdAt`,`updatedAt`) VALUES('" . sanitize_text_field($holidayinserts) . "','" . sanitize_text_field($wphrmWeekend) . "','" . sanitize_text_field($holiday_dates) . "' ,'" . sanitize_text_field($holiday_dates) . "')");
                            }
                        }
                    }
                }
            }
        }
        $result['success'] = __('Weekends have been successfully added.', 'wphrm');
        echo json_encode($result);
        exit;
    }

    /** holidays function. * */
    public function WPHRMAddHolidays() {
        global $current_user, $wpdb;
        $wphrmCurrentUserRole = implode(',', $current_user->roles);
        $holidayDate = $_POST['holiday_date'];
        $occasion = $_POST['occasion'];
        $result = array();
        $holiday_dates = date('Y-m-d H:i:s');
        foreach ($holidayDate as $holi => $holidayinsert):
            foreach ($occasion as $occ => $occasioninsert):
                if ($holi == $occ) {
                    $holidayinserts = date('Y-m-d', strtotime($holidayinsert)); // esc
                    $id = $wpdb->query("SELECT * FROM $this->WphrmHolidaysTable WHERE `wphrmDate` = '" . $holidayinserts . "'");
                    if (!empty($id)) {
                        $result['error'] = __('Holiday already exists.', 'wphrm');
                    } else {
                        if (!empty($occasioninsert)) {
                            $id = $wpdb->query("INSERT INTO $this->WphrmHolidaysTable (`wphrmDate`,`wphrmOccassion`,`createdAt`,`updatedAt`) VALUES('" . sanitize_text_field($holidayinserts) . "','" . sanitize_text_field($occasioninsert) . "','" . sanitize_text_field($holiday_dates) . "' ,'" . sanitize_text_field($holiday_dates) . "')");
                        }
                    }
                }
            endforeach;
        endforeach;
        $result['success'] = true;
        echo json_encode($result);
        exit;
    }

    /** attendance celender view. * */
    public function WPHRMEmployeeAttendanceData() {
        global $wpdb;
        $employee_id = '';
        $flag = true;
        $present = array();
        if (isset($_POST['employee_id']) && !empty($_POST['employee_id'])) {
            $employee_id = $_POST['employee_id']; // esc
        }
        $employeeHolidayResult = $employeeAttendanceResult = array();
        $holiday_dates = esc_sql(date('Y-m-d')); // esc
        if (isset($employee_id) && !empty($employee_id)) {
            $employee_holidays = $wpdb->get_results("select * from $this->WphrmHolidaysTable");
            $employee_attendance = $wpdb->get_results("select * from $this->WphrmAttendanceTable where `employeeID` = $employee_id and `date` <= '$holiday_dates'");
           
            foreach ($employee_attendance as $employee_attendancekey => $employee_attendances) {
                if ($employee_attendances->status == 'absent') {
                    $background = 'rgb(198, 61, 15)';
                } else {
                    $background = 'rgb(31, 137, 127)';
                }
                $employeeAttendanceResult[] = array('title' => '' . $employee_attendances->status . '',
                    'start' => '' . $employee_attendances->date . '',
                    'backgroundColor' => '' . $background . '');
            }
             foreach ($employee_holidays as $employee_holidaykey => $employee_holiday) {
                $employeeHolidayResult[] = array('title' => '' . $employee_holiday->wphrmOccassion . '',
                    'start' => '' . $employee_holiday->wphrmDate . '',
                    'backgroundColor' => 'grey');
            }
        } else {
            $employee_holidays = $wpdb->get_results("select * from $this->WphrmHolidaysTable");
            $employee_attendance = $wpdb->get_results("select `status` ,`date` from $this->WphrmAttendanceTable where  `date` <= '$holiday_dates' group by date");

            foreach ($employee_holidays as $employee_holidaykey => $employee_holiday) {
                $employeeHolidayResult[] = array('title' => '' . $employee_holiday->wphrmOccassion . '',
                    'start' => '' . $employee_holiday->wphrmDate . '',
                    'backgroundColor' => 'grey');
            }
            foreach ($employee_attendance as $employee_attendances) {
                $present = '';
                $employee_attendanceCount = $wpdb->get_results("select `status` ,`date` from $this->WphrmAttendanceTable where  `date` = '$employee_attendances->date'");
                foreach ($employee_attendanceCount as $employee_attendanceCounts) {
                    $present[] = $employee_attendanceCounts->status;
                }
                if (in_array('absent', $present)) {
                    $employeeAttendanceResult[] = array('title' => 'absents',
                        'start' => '' . $employee_attendances->date . '',
                        'backgroundColor' => 'rgb(198, 61, 15)',
                        'url' => '?page=wphrm-employee-absent&date=' . $employee_attendances->date);
                } else {
                    $employeeAttendanceResult[] = array('title' => 'All present',
                        'start' => '' . $employee_attendances->date . '',
                        'backgroundColor' => 'rgb(31, 137, 127)',
                    );
                }
            }
            
        }
        $my_attendance = array_merge($employeeHolidayResult, $employeeAttendanceResult);
        echo json_encode($my_attendance);
        exit;
    }

    /** employee attendance reports view. * */
    public function WPHRMEmployeeAttendanceReports() {
        global $wpdb;
        $wphrmEmployeeId = esc_sql($_POST['employee_id']); // esc
        $month = esc_sql($_POST['month']); // esc
        $year = esc_sql($_POST['year']); // esc
        $from = esc_sql($year . '-' . $month . '-' . '01'); // esc
        $to = esc_sql($year . '-' . $month . '-' . '31'); // esc
        $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $result = array();
        $present = array();
        $employeeHolidays = $wpdb->get_results("select * from $this->WphrmHolidaysTable where `wphrmDate` between '$from' AND '$to'");
        $employeeAttendance = $wpdb->get_results("select * from $this->WphrmAttendanceTable where `employeeID` = $wphrmEmployeeId and `date` between '$from' AND '$to'");
        if (!empty($employeeHolidays)) {
            $holidaysReports = count($employeeHolidays);
            $workingday = $days - $holidaysReports;
        } else {
            $workingday = $days;
        }
        foreach ($employeeAttendance as $employee_attendancekey => $employeeAttendances) {
            if ($employeeAttendances->status == 'present') {
                $present[] = $employeeAttendances->status;
            }
        }
        if (!empty($present)) {
            $presents = count($present);
        } else {
            $presents = 0;
        }
        $PerReport = ($presents * 100 ) / $workingday;
        $result['success'] = 'success';
        $result['Working'] = '' . $workingday . '/' . $presents . '';
        $result['PerReport'] = bcdiv($PerReport, 1, 2) . ' %';
        echo json_encode($result);
        exit;
    }

    /** Mark Attendance function. * */
    public function WPHRMEmployeeAttendanceMark() {
        global $current_user, $wpdb;
        $result = array();
        $attendanceCreateArray = '';
        $attendanceUpdateArray = '';
        $getAttendanceById = '';
        $wphrmCurrentUserRole = implode(',', $current_user->roles);
        $CreatedDates = esc_sql(date('Y-m-d H:i:s')); // esc
        $employee_ID = '';
        $leaveType = '';
        $reason = '';
        $attendance_mark = '';
        if (isset($_POST['checkbox'])) {
            $attendance_mark = esc_sql($_POST['checkbox']); // esc
        }
        if (isset($_POST['attendancedate']) && $_POST['attendancedate'] != '') {
            $attendanceDate = esc_sql($_POST['attendancedate']); // esc
        } else {
            $attendanceDate = esc_sql(date('Y-m-d')); // esc
        }
        if (isset($_POST['employees'])) {
            $employee_ID = esc_sql($_POST['employees']); // esc
        }
        if (isset($_POST['leaveType'])) {
            $leaveType = esc_sql($_POST['leaveType']); // esc
        }
        if (isset($_POST['reason'])) {
            $reason = esc_sql($_POST['reason']); // esc
        }
        $notification = array();
        $mark_manual = array();
        foreach ($employee_ID as $id) :
            $mark_manual[$id] = array();
            if (isset($attendance_mark[$id])) :
                $mark_manual[$id]['status'] = 'on';
            else :
                $mark_manual[$id]['status'] = 'off';
            endif;
            if (isset($leaveType[$id])) :
                $mark_manual[$id]['leaveType'] = $leaveType[$id];
            else :
                $mark_manual[$id]['leaveType'] = $leaveType[$id];
            endif;
            if (isset($reason[$id])) :
                $mark_manual[$id]['leaveReason'] = $reason[$id];
            else :
                $mark_manual[$id]['leaveReason'] = $reason[$id];
            endif;
        endforeach;

        foreach ($mark_manual as $key => $attendance_marks):
            if ($attendance_marks['status'] == 'on') {
                $getAttendanceById = $wpdb->get_row("select * from $this->WphrmAttendanceTable where `date` = '" . $attendanceDate . "' and `employeeID` ='" . $key . "'");
                if (empty($getAttendanceById)) {
                    $attendanceCreateArray = array('employeeID' => sanitize_text_field($key),
                        'date' => sanitize_text_field($attendanceDate),
                        'leaveType' => '',
                        'reason' => '',
                        'status' => sanitize_text_field('present'),
                        'updatedBy' => sanitize_text_field($wphrmCurrentUserRole),
                        'createdAt' => sanitize_text_field($CreatedDates),
                        'updatedAt' => sanitize_text_field($CreatedDates),
                    );

                    $wpdb->insert($this->WphrmAttendanceTable, $attendanceCreateArray);
                } else {
                    $attendanceUpdateArray = array('employeeID' => sanitize_text_field($key),
                        'date' => sanitize_text_field($attendanceDate),
                        'leaveType' => '',
                        'reason' => '',
                        'status' => sanitize_text_field('present'),
                        'updatedBy' => sanitize_text_field($wphrmCurrentUserRole),
                        'updatedAt' => sanitize_text_field($CreatedDates),
                    );
                    $where_array = array('employeeID' => $key
                        , 'date' => $attendanceDate);

                    $wpdb->update($this->WphrmAttendanceTable, $attendanceUpdateArray, $where_array);
                }
            } else {
                $getAttendanceById = $wpdb->get_row("select * from $this->WphrmAttendanceTable where `date` = '" . $attendanceDate . "' and `employeeID` ='" . $key . "'");
                if (empty($getAttendanceById)) {
                    $attendanceCreateArray = array('employeeID' => sanitize_text_field($key),
                        'date' => sanitize_text_field($attendanceDate),
                        'status' => sanitize_text_field('absent'),
                        'leaveType' => sanitize_text_field($attendance_marks['leaveType']),
                        'reason' => sanitize_text_field($attendance_marks['leaveReason']),
                        'updatedBy' => sanitize_text_field($wphrmCurrentUserRole),
                        'createdAt' => sanitize_text_field($CreatedDates),
                        'updatedAt' => sanitize_text_field($CreatedDates),
                    );

                    $wpdb->insert($this->WphrmAttendanceTable, $attendanceCreateArray);
                } else {
                    $attendanceUpdateArray = array('employeeID' => sanitize_text_field($key),
                        'date' => sanitize_text_field($attendanceDate),
                        'status' => sanitize_text_field('absent'),
                        'leaveType' => sanitize_text_field($attendance_marks['leaveType']),
                        'reason' => sanitize_text_field($attendance_marks['leaveReason']),
                        'updatedBy' => sanitize_text_field($wphrmCurrentUserRole),
                        'createdAt' => sanitize_text_field($CreatedDates),
                        'updatedAt' => sanitize_text_field($CreatedDates),
                    );
                    $where_array = array('employeeID' => $key
                        , 'date' => $attendanceDate);

                    $wpdb->update($this->WphrmAttendanceTable, $attendanceUpdateArray, $where_array);
                }
            }
        endforeach;
        $result['success'] = true;
        echo json_encode($result);
        exit;
    }

    
    /** Messages details functions. * */
    public function WPHRMAllMessagesInfo() {
        global $wpdb;
        $result = array();
        $wphrmMessagesTitle = esc_sql($_POST['wphrm_messages_title']); // esc
        $wphrmMessagesDesc = esc_sql($_POST['wphrm_messages_desc']); // esc
        $wphrmMessagesId = esc_sql($_POST['wphrm_messages_id']); // esc

        $wphrmAllMessagesInfo = $wpdb->get_row("SELECT * FROM $this->WphrmMessagesTable WHERE `id` = '$wphrmMessagesId' ");
        if (!empty($wphrmAllMessagesInfo)) {
            $wphrmMessagesTitle = sanitize_text_field($wphrmMessagesTitle); // sanitize_text_field
            $wphrmMessagesDesc = sanitize_text_field($wphrmMessagesDesc); // sanitize_text_field
            $id = $wpdb->query("UPDATE $this->WphrmMessagesTable SET `messagesTitle` = '$wphrmMessagesTitle', `messagesDesc` ='$wphrmMessagesDesc' WHERE `id`= '$wphrmMessagesId'");
            $result['success'] = true;
        } else {
            $result['error'] = __('Something went wrong.', 'wphrm');
        }
        echo json_encode($result);
        exit;
    }

    /** Setting Function for Add Currency . * */
    public function WPHRMExpenseReportInfo() {
        global $wpdb;
        $message = array();
        $wphrmExpenseAmount = '';
        if ($_POST['wphrm_expense_amount'] != '') {
            $wphrmExpenseAmount = $_POST['wphrm_expense_amount'];
        }
        $wphrmExpenseReportSettings = $this->WPHRMGetSettings('wphrmExpenseReportInfo');
        if (!empty($wphrmExpenseReportSettings)) :
            $wphrmFormDetails = $wphrmExpenseReportSettings;
            $wphrmFormDetails['wphrm_expense_amount'] = sanitize_text_field($wphrmExpenseAmount);
            $wphrmFormDetailsData = base64_encode(serialize($wphrmFormDetails));
            $id = $wpdb->query("UPDATE $this->WphrmSettingsTable SET `settingValue`= '$wphrmFormDetailsData' WHERE `settingKey`='wphrmExpenseReportInfo'");
            $message['success'] = true;
        else :
            $wphrmFormDetails['wphrm_expense_amount'] = sanitize_text_field($wphrmExpenseAmount);
            $wphrmFormDetailsData = base64_encode(serialize($wphrmFormDetails));
            $id = $wpdb->query("INSERT INTO $this->WphrmSettingsTable (`settingKey`, `settingValue`) 
                                               VALUES('wphrmExpenseReportInfo','$wphrmFormDetailsData')");
            if ($id) {
                $message['success'] = true;
            } else {
                $message['error'] = __('Something went wrong.', 'wphrm');
            }
        endif;
        echo json_encode($message);
        exit;
    }

   

    /** Setting Function for Add Currency . * */
    public function WPHRMGeneralSettingsInfo() {
        global $wpdb;
        $message = array();
        $wphrmCompanyFullName = '';
        $wphrmCompanyEmail = '';
        $wphrmCompanyPhone = '';
        $wphrmCompanyAddress = '';
        $wphrmCurrency = '';
        if (!empty($_FILES['wphrm_company_logo']['name'])) {
            $wphrmCompanyLogo = wp_check_filetype($_FILES['wphrm_company_logo']['name']);
            $wphrmCompanyLogos = $wphrmCompanyLogo['ext'];
            if ($wphrmCompanyLogos == 'PNG' || $wphrmCompanyLogos == 'JPG' || $wphrmCompanyLogos == 'JPEG' || $wphrmCompanyLogos == 'GIF' ||
                    $wphrmCompanyLogos == 'png' || $wphrmCompanyLogos == 'jpg' || $wphrmCompanyLogos == 'jpeg' || $wphrmCompanyLogos == 'gif') {
                $wphrmCompanyLogosFile = $_FILES['wphrm_company_logo'];
                $wphrmCompanyLogosOverrides = array('test_form' => false);
                $wphrmCompanyLogosProfile = wp_handle_upload($wphrmCompanyLogosFile, $wphrmCompanyLogosOverrides);
            } else {
                $result['error'] = __('This is invalid file format.', 'wphrm');
                echo json_encode($result);
                exit;
            }
        }
        if ($_POST['wphrm_company_full_name'] != '') {
            $wphrmCompanyFullName = $_POST['wphrm_company_full_name'];
        }
        if ($_POST['wphrm_company_email'] != '') {
            $wphrmCompanyEmail = $_POST['wphrm_company_email'];
        }
        if ($_POST['wphrm_company_phone'] != '') {
            $wphrmCompanyPhone = $_POST['wphrm_company_phone'];
        }
        if ($_POST['wphrm_company_address'] != '') {
            $wphrmCompanyAddress = $_POST['wphrm_company_address'];
        }
        if ($_POST['wphrm_currency'] != '') {
            $wphrmCurrency = $_POST['wphrm_currency'];
        }
        $wphrm_general_settings = $this->WPHRMGetSettings('wphrmGeneralSettingsInfo');
        if (!empty($wphrm_general_settings)) :
            $wphrmFormDetails = $wphrm_general_settings;
            if (!empty($_FILES['wphrm_company_logo']['tmp_name'])) {
                $wphrmFormDetails['wphrm_company_logo'] = sanitize_text_field($wphrmCompanyLogosProfile['url']);
            } else {
                $wphrmFormDetails['wphrm_company_logo'] = sanitize_text_field($wphrmFormDetails['wphrm_company_logo']);
            }
            $wphrmFormDetails['wphrm_company_full_name'] = sanitize_text_field($wphrmCompanyFullName);
            $wphrmFormDetails['wphrm_company_email'] = sanitize_text_field($wphrmCompanyEmail);
            $wphrmFormDetails['wphrm_company_phone'] = sanitize_text_field($wphrmCompanyPhone);
            $wphrmFormDetails['wphrm_company_address'] = sanitize_text_field($wphrmCompanyAddress);
            $wphrmFormDetails['wphrm_currency'] = sanitize_text_field($wphrmCurrency);
            $wphrmFormDetailsData = base64_encode(serialize($wphrmFormDetails));
            $id = $wpdb->query("UPDATE $this->WphrmSettingsTable SET `settingValue`= '$wphrmFormDetailsData' WHERE `settingKey`='wphrmGeneralSettingsInfo'");
            $message['success'] = true;
        else :
            $wphrmFormDetails['wphrm_company_logo'] = sanitize_text_field($wphrmCompanyLogosProfile['url']);
            $wphrmFormDetails['wphrm_company_full_name'] = sanitize_text_field($wphrmCompanyFullName);
            $wphrmFormDetails['wphrm_company_email'] = sanitize_text_field($wphrmCompanyEmail);
            $wphrmFormDetails['wphrm_company_phone'] = sanitize_text_field($wphrmCompanyPhone);
            $wphrmFormDetails['wphrm_company_address'] = sanitize_text_field($wphrmCompanyAddress);
            $wphrmFormDetails['wphrm_currency'] = sanitize_text_field($wphrmCurrency);
            $wphrmFormDetailsData = base64_encode(serialize($wphrmFormDetails));
            $id = $wpdb->query("INSERT INTO $this->WphrmSettingsTable (`settingKey`, `settingValue`) VALUES('wphrmGeneralSettingsInfo','$wphrmFormDetailsData')");
            if ($id) {
               $message['success'] = true;
            } else {
                $message['error'] = __('Something went wrong.', 'wphrm');
            }
        endif;
        echo json_encode($message);
        exit;
    }

    /** Add Fuction for Leave Application * */
    public function WPHRMUserLeaveApplicationsInfo() {
        global $wpdb;
        $message = array();
        $wphrmEmployeeID = '';
        $wphrmStatus = '';
        $wphrmApplicationStatus = '';
        $wphrmLeavetype = '';
        $wphrmLeavedate = '';
        $wphrmReason = '';
        $attendanceID = '';
        $appliedOn = date('Y-m-d');
        $notification = '';
        $appliedDate = array();
        $createUpdatedate = date('Y-m-d H:i:s');

        if ($_POST['wphrm_attendanceID'] != '') {
            $attendanceID = esc_sql($_POST['wphrm_attendanceID']); // esc
        }
        if ($_POST['wphrm_employeeID'] != '') {
            $wphrmEmployeeID = esc_sql($_POST['wphrm_employeeID']); // esc
        }
        if ($_POST['wphrm_status'] != '') {
            $wphrmStatus = esc_sql($_POST['wphrm_status']); // esc
        }
        if ($_POST['wphrm_application_status'] != '') {
            $wphrmApplicationStatus = esc_sql($_POST['wphrm_application_status']); // esc
        }
        if ($_POST['wphrm_leavetype'] != '') {
            $wphrmLeavetype = esc_sql($_POST['wphrm_leavetype']); // esc
        }
        if ($_POST['wphrm_leavedate'] != '') {
            $wphrmLeavedate = esc_sql(date('Y-m-d', strtotime($_POST['wphrm_leavedate']))); // esc
        }
        if ($_POST['wphrm_reason'] != '') {
            $wphrmReason = esc_sql($_POST['wphrm_reason']); // esc
        }
        $wphrmEmployeeInfo = $this->WPHRMGetUserDatas($wphrmEmployeeID, 'wphrmEmployeeInfo');
        $userLeaveApplications = $wpdb->get_row("SELECT * FROM $this->WphrmAttendanceTable WHERE `id` = '$attendanceID'");
        if (!empty($userLeaveApplications)) :
            $id = $wpdb->query("UPDATE $this->WphrmAttendanceTable SET   `date`= '".sanitize_text_field($wphrmLeavedate)."', `leaveType`= '".sanitize_text_field($wphrmLeavetype)."', `reason`= '".sanitize_text_field($wphrmReason)."' ,`updatedAt`='".sanitize_text_field($createUpdatedate)."'  WHERE `id`= '$attendanceID'");
            $message['success'] = true;
        else :

            $userGetApplications = $wpdb->get_results("SELECT * FROM $this->WphrmAttendanceTable WHERE `employeeID` = $wphrmEmployeeID");
            foreach ($userGetApplications as $userGetApplication) {
                if ($wphrmLeavedate == $userGetApplication->date) {
                    $appliedDate[] = $userGetApplication->date;
                }
            }

            if (empty($appliedDate)) {
                $id = $wpdb->query("INSERT INTO $this->WphrmAttendanceTable (`employeeID`, `date`, `status`, `leaveType`, `reason`, `appliedOn`, `applicationStatus`, `createdAt`, `updatedAt`) 
                                               VALUES('".sanitize_text_field($wphrmEmployeeID)."', '".sanitize_text_field($wphrmLeavedate)."', '".sanitize_text_field($wphrmStatus)."', '".sanitize_text_field($wphrmLeavetype)."', '".sanitize_text_field($wphrmReason)."', '".sanitize_text_field($appliedOn)."', '".sanitize_text_field($wphrmApplicationStatus)."', '".sanitize_text_field($createUpdatedate)."', '".sanitize_text_field($createUpdatedate)."')");
                $wphrmNotificationSetting = $this->WPHRMGetSettings('wphrmNotificationsSettingsInfo');
                if ($wphrmNotificationSetting['wphrm_leave_notification'] == 1) {
                    $wphrmLeaveMonth = date('d F Y', strtotime($wphrmLeavedate));
                    $notification = array('wphrmUserID' => sanitize_text_field($this->wphrmGetAdminId),
                        'wphrmDesc' => sanitize_text_field($wphrmEmployeeInfo['wphrm_employee_fname'] . ' ' . $wphrmEmployeeInfo['wphrm_employee_lname'] . ' has requested a leave for ' . $wphrmLeaveMonth . '.'),
                        'notificationType' => sanitize_text_field('Leave Request'),
                        'wphrmStatus' => sanitize_text_field('unseen'),
                        'wphrmDate' => sanitize_text_field(date('Y-m-d')),
                    );
                    $wpdb->insert($this->WphrmNotificationsTable, $notification);
                }
                if ($id) {
                   $message['success'] = true;
                } else {
                    $message['error'] = __('Something went wrong.', 'wphrm');
                }
            } else {
                $message['error'] = __(' Leave has already been applied for this date.', 'wphrm');
            }
        endif;
        echo json_encode($message);
        exit;
    }

   

   

    /** Setting Function for all notifications . * */
    public function WPHRMFinancialsInfo() {
        global $wpdb;
        $message = array();
        $wphrmTableName = $this->WphrmFinancialsTable;
        $wphrmFinancialsId = '';
        $wphrmItem = '';
        $wphrmAmount = '';
        $wphrmStatus = '';
        $wphrmFinancialsDate = '';
        if (isset($_POST['finacials_id']) && $_POST['finacials_id'] != '') {
            $wphrmFinancialsId = esc_sql($_POST['finacials_id']); // esc
        }
        if (isset($_POST['wphrm-item']) && $_POST['wphrm-item'] != '') {
            $wphrmItem = esc_sql($_POST['wphrm-item']); // esc
        }
        if (isset($_POST['wphrm-amount']) && $_POST['wphrm-amount'] != '') {
            $wphrmAmount = esc_sql($_POST['wphrm-amount']); // esc
        }
        if (isset($_POST['wphrm-status']) && $_POST['wphrm-status'] != '') {
            $wphrmStatus = esc_sql($_POST['wphrm-status']); // esc
        }
        if (isset($_POST['wphrm-financials-date']) && $_POST['wphrm-financials-date'] != '') {
            $wphrmFinancialsDate = esc_sql($_POST['wphrm-financials-date']); // esc
        }
        $dateFinal = esc_sql(date('Y-m-d', strtotime($wphrmFinancialsDate))); // esc
        $wphrmFinancials = $wpdb->get_row("SELECT * FROM $this->WphrmFinancialsTable WHERE `id` = '$wphrmFinancialsId'");
        if (!empty($wphrmFinancials)) :
            $whereArray = array('id' => $wphrmFinancialsId);
            $updateFinancialsArray = array('wphrmItem' => sanitize_text_field($wphrmItem),
                'wphrmAmounts' => sanitize_text_field($wphrmAmount),
                'wphrmStatus' => sanitize_text_field($wphrmStatus),
                'wphrmDate' => sanitize_text_field($dateFinal)
            );
            $id = $wpdb->update($this->WphrmFinancialsTable, $updateFinancialsArray, $whereArray);
            $message['success'] = true;
        else :
            $addFinancialsArray = array('wphrmItem' => sanitize_text_field($wphrmItem),
                'wphrmAmounts' => sanitize_text_field($wphrmAmount),
                'wphrmStatus' => sanitize_text_field($wphrmStatus),
                'wphrmDate' => sanitize_text_field($dateFinal)
            );
            $id = $wpdb->insert($this->WphrmFinancialsTable, $addFinancialsArray);
            if ($id) {
                $message['success'] = true;
            } else {
                $message['error'] = __('Something went wrong.', 'wphrm');
            }
        endif;
        echo json_encode($message);
        exit();
    }
    
    /** Setting Function for all notifications . * */
    public function WPHRMNoticeInfo() {
        global $wpdb, $current_user;
        $message = array();
        $wphrmNoticeTitle = '';
        $wphrmNoticeDesc = '';
        $wphrmNoticeId = '';
        $wphrmNotificationSetting = $this->WPHRMGetSettings('wphrmNotificationsSettingsInfo');
        $notification = '';
        if (isset($_POST['wphrm_notice_title']) && $_POST['wphrm_notice_title'] != '') {
            $wphrmNoticeTitle = esc_sql($_POST['wphrm_notice_title']); // esc
        }
        if (isset($_POST['wphrm_notice_desc']) && $_POST['wphrm_notice_desc'] != '') {
            $wphrmNoticeDesc = esc_sql($_POST['wphrm_notice_desc']); // esc
        }
        if (isset($_POST['wphrm_notice_id']) && $_POST['wphrm_notice_id'] != '') {
            $wphrmNoticeId = esc_sql($_POST['wphrm_notice_id']); // esc
        }
        $wphrmcreatedDate = esc_sql(date('Y-m-d H:i:s')); // esc
        $wphrmNotice = $wpdb->get_row("SELECT * FROM  $this->WphrmNoticeTable where id = '$wphrmNoticeId'");
        if (!empty($wphrmNotice)) :

            $wphrmNoticeTitle = sanitize_text_field($wphrmNoticeTitle); // sanitize_text_field
            $wphrmNoticeDesc = sanitize_post($wphrmNoticeDesc); // sanitize_text_field

            $id = $wpdb->query("UPDATE $this->WphrmNoticeTable SET `wphrmtitle`= '$wphrmNoticeTitle', `wphrmdesc`='$wphrmNoticeDesc' WHERE `id`='$wphrmNoticeId'");
            $message['success'] = true;
            $message['success'] = true;
        else :
           
            $wphrmNoticeTitle = sanitize_text_field($wphrmNoticeTitle); // sanitize_text_field
            $wphrmNoticeDesc = sanitize_post($wphrmNoticeDesc); // sanitize_text_field
            $id = $wpdb->query("INSERT INTO $this->WphrmNoticeTable (`wphrmtitle`, `wphrmdesc`, `wphrmcreatedDate`) 
                                               VALUES('$wphrmNoticeTitle', '$wphrmNoticeDesc', '$wphrmcreatedDate')");

            if ($id) {
                $message['success'] = true;
            } else {
                $message['error'] = __('Something went wrong.', 'wphrm');
            }
            if ($wphrmNotificationSetting['wphrm_notice_notification'] == 1) {
                $wphrmUsers = $this->WPHRMGetEmployees();
                foreach ($wphrmUsers as $key => $userdata) {
                    $notification = array('wphrmUserID' => sanitize_text_field($userdata->ID),
                        'wphrmDesc' => __('A new item has been added to the notice board.', 'wphrm'),
                        'notificationType' => sanitize_text_field('Notice Board'),
                        'wphrmStatus' => sanitize_text_field('unseen'),
                        'wphrmDate' => sanitize_text_field(date('Y-m-d')),
                    );
                    $wpdb->insert($this->WphrmNotificationsTable, $notification);
                }
            }

        endif;
        echo json_encode($message);
        exit;
    }

    /** Get notifications . * */
    public function WPHRMNotificationInfo() {
        global $current_user, $wpdb;
        $wphrmResults = array();
        $result = array();
        $wphrmUserId = esc_sql($current_user->ID); // esc
        $wphrmGeneralSettingsInfo = esc_sql('wphrmGeneralSettingsInfo'); // esc
        $message = array();
        $getNotifications = $wpdb->get_results("SELECT * FROM $this->WphrmNotificationsTable WHERE `wphrmUserID`='$wphrmUserId' AND `wphrmStatus`='unseen'");
        if (!empty($getNotifications)) {
            $getlogo = $wpdb->get_row("SELECT * FROM $this->WphrmSettingsTable WHERE `settingKey`='$wphrmGeneralSettingsInfo'");
            $wphrmFormDetails = unserialize(base64_decode($getlogo->settingValue));
            foreach ($getNotifications as $getNotification) {
                $dateformat = esc_sql(date('d-m-Y', strtotime($getNotification->wphrmDate))); // esc
                $result[] = array('id' => $getNotification->id, 'title' => $getNotification->notificationType . ' ' . $dateformat, 'desc' => $getNotification->wphrmDesc, 'logo' => $wphrmFormDetails['wphrm_company_logo']);
            }
        }
        echo json_encode($result);
        exit;
    }


    
    /** User Login **/
    public function WPHRMLoginUser($user_login, $user = null) {
        
        if ( !$user ) {
            $user = get_user_by('login', $user_login);
        }
        
        if ( !$user ) {
            return;
        }
        $wphrmEmployeeBasicInfo = $this->WPHRMGetUserDatas($user->ID, 'wphrmEmployeeInfo');
        if (isset($wphrmEmployeeBasicInfo['wphrm_employee_status']) && $wphrmEmployeeBasicInfo['wphrm_employee_status'] == 'Inactive') :
           
            wp_clear_auth_cookie();
            $login_url = site_url( 'wp-login.php', 'login' );
            $login_url = add_query_arg( 'disabled', '1', $login_url );
            wp_redirect( $login_url );
            exit;
        endif;
    }    
    
    /** User Account Disabled Message **/
    public function WPHRMUserLoginMessage( $message ) { 
        // Show the error message if it seems to be a disabled user
        if ( isset( $_GET['disabled'] ) && $_GET['disabled'] == 1 ) 
            $message =  '<div id="login_error">' . apply_filters( 'ja_disable_users_notice', __( 'Account has been disabled.', 'wphrm' ) ) . '</div>';
        return $message;
    }
}

/** Create Object For WPHRM function. * */
$wphrm = new WPHRM();

/** Ajax Calling function. * */

add_action('wp_ajax_WPHRMEmployeeBasicInfo', array(&$wphrm, 'WPHRMEmployeeBasicInfo'));
add_action('wp_ajax_nopriv_WPHRMEmployeeBasicInfo', array(&$wphrm, 'WPHRMEmployeeBasicInfo'));
add_action('wp_ajax_WPHRMEmployeeDocumentInfo', array(&$wphrm, 'WPHRMEmployeeDocumentInfo'));
add_action('wp_ajax_nopriv_WPHRMEmployeeDocumentInfo', array(&$wphrm, 'WPHRMEmployeeDocumentInfo'));
add_action('wp_ajax_WPHRMEmployeeBankInfo', array(&$wphrm, 'WPHRMEmployeeBankInfo'));
add_action('wp_ajax_nopriv_WPHRMEmployeeBankInfo', array(&$wphrm, 'WPHRMEmployeeBankInfo'));
add_action('wp_ajax_WPHRMEmployeeOtherInfo', array(&$wphrm, 'WPHRMEmployeeOtherInfo'));
add_action('wp_ajax_nopriv_WPHRMEmployeeOtherInfo', array(&$wphrm, 'WPHRMEmployeeOtherInfo'));
add_action('wp_ajax_WPHRMDepartmentInfo', array(&$wphrm, 'WPHRMDepartmentInfo'));
add_action('wp_ajax_nopriv_WPHRMDepartmentInfo', array(&$wphrm, 'WPHRMDepartmentInfo'));
add_action('wp_ajax_WPHRMDesignationInfo', array(&$wphrm, 'WPHRMDesignationInfo'));
add_action('wp_ajax_nopriv_WPHRMDesignationInfo', array(&$wphrm, 'WPHRMDesignationInfo'));
add_action('wp_ajax_WPHRMHolidayMonthWise', array(&$wphrm, 'WPHRMHolidayMonthWise'));
add_action('wp_ajax_nopriv_WPHRMHolidayMonthWise', array(&$wphrm, 'WPHRMHolidayMonthWise'));
add_action('wp_ajax_WPHRMHolidayYearWise', array(&$wphrm, 'WPHRMHolidayYearWise'));
add_action('wp_ajax_nopriv_WPHRMHolidayYearWise', array(&$wphrm, 'WPHRMHolidayYearWise'));
add_action('wp_ajax_WPHRMCustomDelete', array(&$wphrm, 'WPHRMCustomDelete'));
add_action('wp_ajax_nopriv_WPHRMCustomDelete', array(&$wphrm, 'WPHRMCustomDelete'));
add_action('wp_ajax_WPHRMDesignationAjax', array(&$wphrm, 'WPHRMDesignationAjax'));
add_action('wp_ajax_nopriv_WPHRMDesignationAjax', array(&$wphrm, 'WPHRMDesignationAjax'));
add_action('wp_ajax_WPHRMAddHolidays', array(&$wphrm, 'WPHRMAddHolidays'));
add_action('wp_ajax_nopriv_WPHRMAddHolidays', array(&$wphrm, 'WPHRMAddHolidays'));
add_action('wp_ajax_WPHRMEmployeeAttendanceMark', array(&$wphrm, 'WPHRMEmployeeAttendanceMark'));
add_action('wp_ajax_nopriv_WPHRMEmployeeAttendanceMark', array(&$wphrm, 'WPHRMEmployeeAttendanceMark'));
add_action('wp_ajax_WPHRMEmployeeAttendanceData', array(&$wphrm, 'WPHRMEmployeeAttendanceData'));
add_action('wp_ajax_nopriv_WPHRMEmployeeAttendanceData', array(&$wphrm, 'WPHRMEmployeeAttendanceData'));
add_action('wp_ajax_WPHRMEmployeeAttendanceReports', array(&$wphrm, 'WPHRMEmployeeAttendanceReports'));
add_action('wp_ajax_nopriv_WPHRMEmployeeAttendanceReports', array(&$wphrm, 'WPHRMEmployeeAttendanceReports'));
add_action('wp_ajax_WPHRMLeavetypeInfo', array(&$wphrm, 'WPHRMLeavetypeInfo'));
add_action('wp_ajax_nopriv_WPHRMLeavetypeInfo', array(&$wphrm, 'WPHRMLeavetypeInfo'));
add_action('wp_ajax_WPHRMLeaveApplicationsInfo', array(&$wphrm, 'WPHRMLeaveApplicationsInfo'));
add_action('wp_ajax_nopriv_WPHRMLeaveApplicationsInfo', array(&$wphrm, 'WPHRMLeaveApplicationsInfo'));
add_action('wp_ajax_WPHRMAjaxCalenderLoad', array(&$wphrm, 'WPHRMAjaxCalenderLoad'));
add_action('wp_ajax_nopriv_WPHRMAjaxCalenderLoad', array(&$wphrm, 'WPHRMAjaxCalenderLoad'));
add_action('wp_ajax_WPHRMGeneralSettingsInfo', array(&$wphrm, 'WPHRMGeneralSettingsInfo'));
add_action('wp_ajax_nopriv_WPHRMGeneralSettingsInfo', array(&$wphrm, 'WPHRMGeneralSettingsInfo'));
add_action('wp_ajax_WPHRMNoticeInfo', array(&$wphrm, 'WPHRMNoticeInfo'));
add_action('wp_ajax_nopriv_WPHRMNoticeInfo', array(&$wphrm, 'WPHRMNoticeInfo'));
add_action('wp_ajax_WPHRMUserLeaveApplicationsInfo', array(&$wphrm, 'WPHRMUserLeaveApplicationsInfo'));
add_action('wp_ajax_nopriv_WPHRMUserLeaveApplicationsInfo', array(&$wphrm, 'WPHRMUserLeaveApplicationsInfo'));
add_action('wp_ajax_WPHRMAllMessagesInfo', array(&$wphrm, 'WPHRMAllMessagesInfo'));
add_action('wp_ajax_nopriv_WPHRMAllMessagesInfo', array(&$wphrm, 'WPHRMAllMessagesInfo'));
add_action('wp_ajax_WPHRMFinancialsInfo', array(&$wphrm, 'WPHRMFinancialsInfo'));
add_action('wp_ajax_nopriv_WPHRMFinancialsInfo', array(&$wphrm, 'WPHRMFinancialsInfo'));
add_action('wp_ajax_WPHRMAjaxFinancialGraphLoad', array(&$wphrm, 'WPHRMAjaxFinancialGraphLoad'));
add_action('wp_ajax_nopriv_WPHRMAjaxFinancialGraphLoad', array(&$wphrm, 'WPHRMAjaxFinancialGraphLoad'));
add_action('wp_ajax_WPHRMExpenseReportInfo', array(&$wphrm, 'WPHRMExpenseReportInfo'));
add_action('wp_ajax_nopriv_WPHRMExpenseReportInfo', array(&$wphrm, 'WPHRMExpenseReportInfo'));
add_action('wp_ajax_WPHRMwphrmAddyearInWeekendInfo', array(&$wphrm, 'WPHRMwphrmAddyearInWeekendInfo'));
add_action('wp_ajax_nopriv_WPHRMwphrmAddyearInWeekendInfo', array(&$wphrm, 'WPHRMwphrmAddyearInWeekendInfo'));
add_action('wp_ajax_WPHRMSalaryMonthAjax', array(&$wphrm, 'WPHRMSalaryMonthAjax'));
add_action('wp_ajax_nopriv_WPHRMSalaryMonthAjax', array(&$wphrm, 'WPHRMSalaryMonthAjax'));
