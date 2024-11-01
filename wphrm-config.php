<?php
if ( ! defined( 'ABSPATH' ) ) exit;
class WPHRMConfig {    
    public $WphrmSalaryTable = 'wphrm_salary';
    public $WphrmMessagesTable = 'wphrm_messages';
    public $WphrmSettingsTable = 'wphrm_settings';
    public $WphrmHolidaysTable = 'wphrm_holidays';
    public $WphrmFinancialsTable = 'wphrm_financials';
    public $WphrmDesignationTable = 'wphrm_designation';
    public $WphrmDepartmentTable = 'wphrm_department';
    public $WphrmAttendanceTable = 'wphrm_attendance';
    public $WphrmLeaveTypeTable = 'wphrm_leavetypes';
    public $WphrmNotificationsTable = 'wphrm_notifications';
    public $WphrmNoticeTable = 'wphrm_notice';
    public $wphrmGetAdminId;
    protected $WPHRMREPORTS, $WPHRMAJAXDATAS;
   
    public $currency_symbols = array(
        'INR' => '&#8377;',
        'USD' => '&#36;',
        'GBP' => '&#163;',
        'JPY' => '&#165;',
        'YEN' => '&#165;',
        'EUR' => '&#8364;',
        'WON' => '&#8361;',
        'TRY' => '&#8356;', 
        'RUB' => '&#1088;',
        'RMB' => '&#165;',
        'KRW' => '&#8361;',
        'BTC' => '&#8361;',
        'THB' => '&#3647;',
        'BDT' => '&#2547;',
        'CRC' => '&#8353;',
        'GEL' => '&#4314;',
    );
    public $wphrm_month = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
 
   
}