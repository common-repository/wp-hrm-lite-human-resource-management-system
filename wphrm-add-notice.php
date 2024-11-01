<?php
if ( ! defined( 'ABSPATH' ) ) exit;
global $current_user, $wpdb;
$wphrmMessages = $this->WPHRMGetMessage(26);
$wphrmUserRole = implode(',', $current_user->roles);
$readonly_class = '';
$readonly = '';
$edit_mode = false;
$wphrm_notice_desc = '';
$wphrmNotices ='';
if (isset($_REQUEST['notice_id']) && !empty($_REQUEST['notice_id'])) :
    $noticeId = esc_sql($_REQUEST['notice_id']); // esc
    $wphrmNotices = $wpdb->get_row("SELECT * FROM $this->WphrmNoticeTable WHERE id = '$noticeId'");
    endif;
?>
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div class="preloader">
<span class="preloader-custom-gif"></span>
</div>
<?php if (isset($wphrmUserRole) && $wphrmUserRole == 'subscriber' || $wphrmUserRole == 'editor') { ?>
<div style="padding-left: 0px; padding-right:20px; padding-top:0px;" class="col-md-12">  
    <h3 class="page-title"><?php _e('Notice', 'wphrm'); ?></h3>
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li><i class="fa fa-home"></i><?php _e('Home', 'wphrm'); ?><i class="fa fa-angle-right"></i></li>
            <li><?php _e('Notice Board', 'wphrm'); ?></li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
          <a class="btn green " href="?page=wphrm-notice"><i class="fa fa-arrow-left"></i><?php _e('Back', 'wphrm'); ?></a>
            <div class="portlet box blue calendar">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-exclamation-triangle"></i><?php _e('Notice Board', 'wphrm'); ?>
                    </div>
                </div>
                <div class="portlet-body form" style="background: #FFFDE7;">
                    <?php if (isset($wphrmNotices->wphrmdesc)) : echo stripslashes($wphrmNotices->wphrmdesc); endif; ?> 
                </div>
            </div>
        </div>
    </div>
</div>
  <?php } else { ?> 
<!-- END PAGE CONTENT-->
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div style="padding-left: 0px; padding-right:20px; padding-top:0px;" class="col-md-12">    
     <h3 class="page-title">
        <?php if (isset($noticeId) && $noticeId != '') { _e('Edit Notice', 'wphrm'); } else { _e('Add Notice', 'wphrm'); } ?>
    </h3>
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li><i class="fa fa-home"></i><?php _e('Home', 'wphrm'); ?><i class="fa fa-angle-right"></i></li>
            <li>
                <?php if (isset($noticeId) && $noticeId != '') { _e('Edit Notice', 'wphrm'); } else { ?>
                <?php _e('Add Notice', 'wphrm'); ?>
            <?php } ?>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
<a class="btn green " href="?page=wphrm-notice"><i class="fa fa-arrow-left"></i><?php _e('Back', 'wphrm'); ?></a>
            <div class="portlet box blue calendar">
                <div class="portlet-title">
                    <div class="caption">
                       <?php if (isset($noticeId) && $noticeId != '') { _e('Edit Notice', 'wphrm'); } else { _e('Add Notice', 'wphrm'); } ?>
                    </div>
                </div>
                <div class="portlet-body form">
                    <!-- BEGIN FORM-->
                    <form method="POST"  accept-charset="UTF-8" class="form-horizontal form-bordered">
                        <div class="form-body">
                            <div class="alert alert-success display-hide" id="wphrmNoticeInfo_success"><i class='fa fa-check-square' aria-hidden='true'></i> <?php echo esc_html($wphrmMessages); ?>
                                <button class="close" data-close="alert"></button>
                            </div>
                            <div class="alert alert-danger display-hide" id="wphrmNoticeInfo_error">
                                <button class="close" data-close="alert"></button>
                            </div>
                            
                            <input type="hidden"  id="wphrm_notice_id" name="wphrm_notice_id" value="<?php if (isset($noticeId)) : echo esc_attr($noticeId); endif; ?>">
                            <div class="form-group">
                                <label class="col-md-3 control-label"><?php _e('Title', 'wphrm'); ?> :<?php echo isset($wphrmNotice->wphrmtitle) ?> <span class="required">*</span>
                                </label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" id="wphrm_notice_title" name="wphrm_notice_title" placeholder="<?php _e('Title', 'wphrm'); ?>" value="<?php
                                    if (isset($wphrmNotices->wphrmtitle)) : echo  esc_attr($wphrmNotices->wphrmtitle); endif; ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label"><?php _e('Description', 'wphrm'); ?> :<span class="required">*</span>
                                </label>
                                <div class="col-md-8">
                                    <?php $desc ='';
                                    if (isset($wphrmNotices->wphrmdesc)) : $desc = $wphrmNotices->wphrmdesc; endif;
                                    wp_editor(stripslashes($desc), 'wphrm_notice_desc', array('media_buttons' => false, 'editor_height' => 200, 'editor_width' => 100)); ?>
                                </div>
                            </div>
                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-offset-3 col-md-9">
                                        <?php if (isset($noticeId) && $noticeId != ''){ ?>
                                            <button type="button" id="wphrmNoticeInfo_frm" class="btn green"><i class="fa fa-edit"></i><?php _e('Edit Notice', 'wphrm'); ?></button>
                                        <?php } else { ?>
                                            <button type="button" id="wphrmNoticeInfo_frm"  class="btn green"><i class="fa fa-plus"></i><?php _e('Add Notice', 'wphrm'); ?></button>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <!-- END FORM-->
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END PAGE CONTENT-->
<?php } ?>