<?php
if ( ! defined( 'ABSPATH' ) ) exit;
global $current_user, $wpdb;
$wphrmUserRole = implode(',', $current_user->roles);
$readonly_class = '';
$readonly = '';
$edit_mode = false;
$wphrmMessagesNoticeBordDelete = $this->WPHRMGetMessage(13);
?>
<!-- BEGIN PAGE HEADER-->
<div class="preloader">
<span class="preloader-custom-gif"></span>
</div>
<div id="deleteModal" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="alert alert-success display-hide" id="WPHRMCustomDelete_success"><i class='fa fa-check-square' aria-hidden='true'></i> <?php echo esc_html($wphrmMessagesUpdateDeparment->messagesDesc); ?>
                <button class="close" data-close="alert"></button>
            </div>
            <div class="alert alert-danger display-hide" id="WPHRMCustomDelete_error">
                <button class="close" data-close="alert"></button>
            </div>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-close"></i></button>
                <h4 class="modal-title"><?php _e('Confirmation', 'wphrm'); ?></h4>
            </div>
            <div class="modal-body" id="info"><p><?php _e('Are you sure you want to delete', 'wphrm'); ?>?</p></div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn red" id="delete"><i class="fa fa-trash"></i><?php _e('Delete', 'wphrm'); ?></button>
                <button type="button" data-dismiss="modal" aria-hidden="true" class="btn default"><i class="fa fa-times"></i><?php _e('Cancel', 'wphrm'); ?></button>
            </div>
        </div>
    </div>
</div>
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div style="padding-left: 0px; padding-right:20px; padding-top:0px;" class="col-md-12">
    
    <!-- BEGIN PAGE HEADER-->
    <h3 class="page-title"><?php _e('Notices', 'wphrm'); ?></h3>
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li><i class="fa fa-home"></i><?php _e('Home', 'wphrm'); ?><i class="fa fa-angle-right"></i></li>
            <li><?php _e('Notices', 'wphrm'); ?></li>
        </ul>
    </div>
    <!-- END PAGE HEADER-->
    <div class="row">
        <div class="col-md-12">
            <?php if (isset($wphrmUserRole) && $wphrmUserRole == 'administrator') { ?> 
                <a class="btn green " href="?page=wphrm-add-notice" data-toggle="modal"><i class="fa fa-plus"></i><?php _e('Add New Notice', 'wphrm'); ?></a>
            <?php } ?>
            
            <div class="portlet box blue calendar">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-list"></i><?php _e('List of Notices', 'wphrm'); ?>
                    </div>
                </div>
                <div class="portlet-body">
                    <table class="table table-striped table-bordered table-hover" id="wphrmDataTable">
                        <thead>
                            <tr> <th><?php _e('S.No', 'wphrm'); ?></th>
                                <th><?php _e('Notice Title', 'wphrm'); ?></th>
                                <th><?php _e('Actions', 'wphrm'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i=1;
                            $wphrmNotices = $wpdb->get_results("SELECT * FROM  $this->WphrmNoticeTable");
                            if(!empty($wphrmNotices)) :
                                foreach ($wphrmNotices as $key => $wphrmNotice) { ?>
                                    <tr>
                                         <td><?php echo esc_html($i); ?></td>
                                        <td>
                                            <?php if (isset($wphrmNotice->wphrmtitle)) : echo esc_html($wphrmNotice->wphrmtitle); endif; ?>
                                        </td>                                    
                                       
                                        <td>
                                            <?php if (isset($wphrmUserRole) && $wphrmUserRole == 'subscriber' || $wphrmUserRole == 'editor') { ?>
                                            <a class="btn purple"  href="?page=wphrm-view-notice&notice_id=<?php if (isset($wphrmNotice->id)) : echo esc_attr($wphrmNotice->id);
                                                endif; ?>"> <i class="fa fa-edit"></i><?php _e('View', 'wphrm'); ?> </a>
                                            <?php } else { ?>
                                                <a class="btn purple"  href="?page=wphrm-add-notice&notice_id=<?php if (isset($wphrmNotice->id)) : echo esc_attr($wphrmNotice->id); endif; ?>">
                                                    <i class="fa fa-edit"></i><?php _e('Edit', 'wphrm'); ?>
                                                </a>
                                                <a class="btn red" href="javascript:;" onclick="WPHRMCustomDelete(<?php if (isset($wphrmNotice->id)) : echo esc_js($wphrmNotice->id); endif; ?>, '<?php echo trim(esc_js($this->WphrmNoticeTable)); ?>', 'id')">
                                                    <i class="fa fa-trash"></i><?php _e('Delete', 'wphrm'); ?>
                                                </a>
                                            <?php } ?>                                        
                                        </td>
                                    </tr>
                                <?php $i++; }
                            else : ?>
                                <tr>
                                    <td colspan="4"><?php _e('No notices found in the database.', 'wphrm'); ?>
                                    </td><td class="collapse"></td><td class="collapse"></td>
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