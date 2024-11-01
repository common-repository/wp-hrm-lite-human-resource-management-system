/*
 * WP-HRM
 * Copyright 2014-2016 IndigoThemes
 */
jQuery(window).load(function () {
    jQuery('.preloader-custom-gif').hide();
    jQuery('.preloader ').hide();
});
jQuery(document).ready(function () {
    var TableManaged = function () {
        var initTable2 = function () {
            // DataTable
            var table = jQuery('#wphrmDataTable');
            table.DataTable({
                // Internationalisation.
                "language": {
                    "aria": {
                        "sortAscending": ": activate to sort column ascending",
                        "sortDescending": ": activate to sort column descending"
                    },
                    "emptyTable": "No data available",
                    "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                    "infoEmpty": "No entries found",
                    "infoFiltered": "(filtered1 from _MAX_ total entries)",
                    "lengthMenu": "Show _MENU_ entries",
                    "search": "Search:",
                    "zeroRecords": "No matching records found"
                },
                "bStateSave": true, // save datatable state(pagination, sort, etc) in cookie.
                "lengthMenu": [
                    [5, 15, 20, -1],
                    [5, 15, 20, "All"] // change per page values here
                ],
                // set the initial value
                "pageLength": 20,
                "language": {
                    "lengthMenu": WPHRMCustomJS.records + " _MENU_ ",
                    "paging": {
                        "previous": "Prev",
                        "next": "Next"
                    }
                },
                "columnDefs": [{// set default column settings
                        'orderable': true,
                        'targets': [0]
                    }, {
                        "searchable": true,
                        "targets": [0]
                    }],
                "order": []

            });
        }
        return {
            //main function to initiate the module
            init: function () {
                if (!jQuery().dataTable) {
                    return;
                }
                initTable2();
            }
        };
    }();
    var date = new Date();
    var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
    var yesterday = new Date(date.getFullYear(), date.getMonth(), date.getDate() - 1);
    jQuery('.before-current-date').datepicker({
        format: 'dd-mm-yyyy',
        startDate: '01-01-1901',
        setDate: today,
        endDate: today,
        autoclose: true
    });

    jQuery('.after-current-date').datepicker({
        startDate: '1d',
        autoclose: true
    });

    jQuery('#from-date').datepicker({
        format: 'dd-mm-yyyy',
        startDate: '01-01-1901',
        setDate: yesterday,
        endDate: yesterday,
        autoclose: true
    });
    jQuery('#to-date').datepicker({
        format: 'dd-mm-yyyy',
        startDate: '01-01-1901',
        setDate: today,
        endDate: today,
        autoclose: true
    });

    (function (jQuery) {
        jQuery.fn.strongPassword = function () {
            var password = [];
            var len = 32;
            var symbols = "";
            var digits = "";
            var similar = "";
            for (i = 0; i < len; i++) {
                var num = randomNumber();
                num = checkChar(num, symbols, digits);
                password.push(String.fromCharCode(num));
            }
            jQuery(this).val(password.join(''));
        }
        randomNumber = function () {
            return Math.floor(Math.random() * (127 - 33 + 1)) + 33; // 33 to 127
        }
        checkChar = function (num, symbols, digits, similar) {
            if (!symbols) {
                while (hasSymbols(num)) {
                    num = randomNumber();
                }
            }
            if (!digits) {
                while (hasDigits(num)) {
                    num = randomNumber();
                }
            }
            if (!similar) {
                while (hasSimilarChars(num)) {
                    num = randomNumber();
                }
            }
            return num;
        }
        hasDigits = function (num) {
            if (num >= 48 && num <= 57) {
                return true;
            }
            return false;
        }
        hasSymbols = function (num) {
            if ((num >= 33 && num <= 47) || (num >= 58 && num <= 64) || (num >= 91 && num <= 96) || (num >= 123 && num <= 126)) {
                return true;
            }
            return false;
        }
        hasSimilarChars = function (num) {
            if (num == 48 || num == 49 || num == 73 || num == 76 || num == 79 || num == 105 || num == 108 || num == 111) {
                return true;
            }
            return false;
        }
    })(jQuery);
    (function (jQuery) {
        jQuery.toggleShowPassword = function (options) {
            var settings = jQuery.extend({
                field: "#wphrm_employee_password",
                control: "#toggle_show_password",
            }, options);
            var control = jQuery(settings.control);
            var field = jQuery(settings.field)
            control.bind('click', function () {
                if (control.is(':checked')) {
                    field.attr('type', 'text');
                } else {
                    field.attr('type', 'password');
                }
            })
        };
    }(jQuery));
    jQuery.toggleShowPassword({
        field: '#wphrm_employee_password',
        control: '#methods'
    });
    jQuery("#generatePassword").on('click', function () {
        jQuery('#wphrm_employee_password').strongPassword();
    });
    /** Tabs **/
    jQuery('ul.tabs li').click(function () {
        var tab_id = jQuery(this).attr('data-tab');
        jQuery('ul.tabs li').removeClass('current');
        jQuery('.tab-content').removeClass('current');
        jQuery(this).addClass('current');
        jQuery("#" + tab_id).addClass('current');
    });
    // onready class used for attendance mark
    jQuery('.leaveType').hide();
    jQuery('.reason').hide();
    jQuery('.halfLeaveType').hide();
    jQuery('.checkbox').hide();
    /** chart js **/
    jQuery("#bars li .bar").each(function (key, bar) {
        var percentage = jQuery(this).data('amount');
        var amounts = jQuery('.wphrm_level').val();
        var final = (percentage * 100) / amounts;
        jQuery(this).animate({
            'height': final + '%'
        }, 1000);
    });


    /** Upload Profile images Validation **/
    jQuery("#employee_profile").change(function () {
        var fileExtension = ['jpeg', 'jpg', 'png', 'gif'];
        if (jQuery.inArray(jQuery(this).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
            jQuery('#personal_details_error').html("<i class='fa fa-close' aria-hidden='true'></i> Only \'" + fileExtension.join('\', \'') + "\' filetypes are allowed.");
            jQuery('#personal_details_error').removeClass('display-hide');
            return false;
        } else {
            jQuery('#personal_details_error').addClass('display-hide');
        }
    });
    /** Upload Documents Validation **/
    jQuery(".documents-Upload").change(function () {
        var fileExtension = ['gif','GIF','png','PNG','jpg','DOC','doc','DOCX','docx','txt','TXT','JPEG','JPG'];
        if (jQuery.inArray(jQuery(this).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
            jQuery('#Documents_error').html("<i class='fa fa-close' aria-hidden='true'></i> Only \'" + fileExtension.join('\', \'') + "\' filetypes are allowed.");
            jQuery('#Documents_error').removeClass('display-hide');
        } else {
            jQuery('#Documents_error').addClass('display-hide');
        }
    });
    jQuery("#bars li .bar_lose").each(function (key, bar_lose) {
        var percentage = jQuery(this).data('amount');
        var amounts = jQuery('.wphrm_level').val();
        var final = (percentage * 100) / amounts;
        jQuery(this).animate({
            'height': final + '%'
        }, 1000);
    })

    jQuery("#wphrmyearchoose").hide();
   
    // this is for employee Documents details store in database ajax
    var wphrm_employee_basic_info_form = jQuery("#wphrm_employee_basic_info_form");
    wphrm_employee_basic_info_form.validate({
        rules: {
            wphrm_employee_fname: "required",
            wphrm_employee_lname: "required",
            wphrm_employee_uniqueid: "required",
            wphrm_employee_email: {
                required: true,
                email: true
            },
            wphrm_employee_userid: "required",
            wphrm_employee_password: "required",
            wphrm_employee_department: "required",
            wphrm_employee_designation: "required",
            wphrm_employee_joining_date: "required",
        },
        submitHandler: function (wphrm_employee_basic_info_form) {
            jQuery('.preloader-custom-gif').show();
            jQuery('.preloader ').show();
            var formData = new FormData(jQuery(wphrm_employee_basic_info_form)['0']);
            formData.append('action', 'WPHRMEmployeeBasicInfo');
            var ajax_url = ajaxurl;
            jQuery.ajax({
                method: "POST",
                url: ajax_url,
                contentType: false,
                cache: false,
                processData: false,
                data: formData,
                success: function (output) {
                    var data = JSON.parse(output);
                    if (data.success) {
                        var employee_id = data.success;
                        var role = data.currentrole;
                        jQuery('.preloader-custom-gif').hide();
                        jQuery('.preloader ').hide();
                        jQuery('#personal_details_success').removeClass('display-hide');
                        jQuery("html, body").animate({scrollTop: 0}, "slow");
                        if (role == 'administrator') {
                            if (employee_id != true) {
                                window.setTimeout(function () {
                                    window.location.href = '?page=wphrm-employee-info&employee_id=' + employee_id;
                                }, 500);
                            }
                        }
                    } else {
                        jQuery('.preloader-custom-gif').hide();
                        jQuery('.preloader ').hide();
                        jQuery('#personal_details_error').html(data.error);
                        jQuery('#personal_details_error').removeClass('display-hide');
                        jQuery("#personal_details_error").css('color', 'red');
                        jQuery("html, body").animate({scrollTop: 0}, "slow");
                    }
                }
            });
        }
    });
    // this is for employee Documents details store in database ajax
    var wphrmEmployeeDocumentInfo_form = jQuery("#wphrmEmployeeDocumentInfo_form");
    wphrmEmployeeDocumentInfo_form.validate({
        rules: {
        },
        submitHandler: function (wphrmEmployeeDocumentInfo_form) {
            jQuery('.preloader-custom-gif').show();
            jQuery('.preloader ').show();
            var formData = new FormData(jQuery(wphrmEmployeeDocumentInfo_form)['0']);
            formData.append('action', 'WPHRMEmployeeDocumentInfo');
            var ajax_url = ajaxurl;
            jQuery.ajax({
                method: "POST",
                url: ajax_url,
                contentType: false,
                cache: false,
                processData: false,
                data: formData,
                success: function (output) {
                    var data = JSON.parse(output);
                    if (data.success) {
                        jQuery('.preloader-custom-gif').hide();
                        jQuery('.preloader ').hide();
                        jQuery('#Documents_success').removeClass('display-hide');
                    } else {
                        jQuery('.preloader-custom-gif').hide();
                        jQuery('.preloader ').hide();
                        jQuery('#Documents_error').html(data.error);
                        jQuery('#Documents_error').removeClass('display-hide');
                        jQuery("#Documents_error").css('color', 'red');
                    }
                }
            });
        }
    });
   

    // this is for employee bank details store in database ajax
    var wphrmEmployeeBankInfo_form = jQuery("#wphrmEmployeeBankInfo_form");
    wphrmEmployeeBankInfo_form.validate({
        rules: {
            wphrm_employee_bank_account_name: "required",
            wphrm_employee_bank_name: "required",
            wphrm_employee_bank_account_no: {
                required: true,
            },
            wphrm_Confirm_mployee_bank_account_no: {
                equalTo: "#wphrm_employee_bank_account_no"
            },
        },
        messages: {
            wphrm_Confirm_mployee_bank_account_no: {
                equalTo: "Account number don't match",
            },
        },
        submitHandler: function (wphrmEmployeeBankInfo_form) {
            var wphrmbankfieldslebal = [];
            jQuery("input[name='bank-fields-lebal[]']").each(function () {
                wphrmbankfieldslebal.push(jQuery(this).val());
            });
            var wphrmbankfieldsvalue = [];
            jQuery("input[name='bank-fields-value[]']").each(function () {
                wphrmbankfieldsvalue.push(jQuery(this).val());
            });
            jQuery('.preloader-custom-gif').show();
            jQuery('.preloader ').show();
            var formData = new FormData(jQuery(wphrmEmployeeBankInfo_form)['0']);
            formData.append('action', 'WPHRMEmployeeBankInfo');
            var ajax_url = ajaxurl;
            jQuery.ajax({
                method: "POST",
                url: ajax_url,
                contentType: false,
                cache: false,
                processData: false,
                data: formData,
                success: function (output) {
                    var data = JSON.parse(output);
                    if (data.success) {
                        jQuery('.preloader-custom-gif').hide();
                        jQuery('.preloader ').hide();
                        jQuery('#wphrm_bank_details').removeClass('display-hide');
                        jQuery("html, body").animate({scrollTop: 0}, "slow");
                    } else {
                        jQuery('.preloader-custom-gif').hide();
                        jQuery('.preloader ').hide();
                        jQuery('#wphrm_bank_details_error').html(data.errors);
                        jQuery('#wphrm_bank_details_error').removeClass('display-hide');
                        jQuery("#wphrm_bank_details_error").css('color', 'red');
                        jQuery("html, body").animate({scrollTop: 0}, "slow");
                    }
                }
            });
        }
    });

    // this is for employee other details store in database ajax
    var wphrmEmployeeOtherInfo_form = jQuery("#wphrmEmployeeOtherInfo_form");
    wphrmEmployeeOtherInfo_form.validate({
        rules: {
        },
        submitHandler: function (wphrmEmployeeOtherInfo_form) {
            var wphrmotherfieldslebal = [];
            jQuery("input[name='other-fields-lebal[]']").each(function () {
                wphrmotherfieldslebal.push(jQuery(this).val());
            });
            var wphrmotherfieldsvalue = [];
            jQuery("input[name='other-fields-value[]']").each(function () {
                wphrmotherfieldsvalue.push(jQuery(this).val());
            });

            var formData = new FormData(jQuery(wphrmEmployeeOtherInfo_form)['0']);
            formData.append('action', 'WPHRMEmployeeOtherInfo');
            jQuery('.preloader-custom-gif').show();
            jQuery('.preloader ').show();
            var ajax_url = ajaxurl;
            jQuery.ajax({
                method: "POST",
                url: ajax_url,
                contentType: false,
                cache: false,
                processData: false,
                data: formData,
                success: function (output) {
                    var data = JSON.parse(output);
                    if (data.success) {
                        jQuery('.preloader-custom-gif').hide();
                        jQuery('.preloader ').hide();
                        jQuery('#other_details_success').removeClass('display-hide');
                    } else {
                        jQuery('.preloader-custom-gif').hide();
                        jQuery('.preloader ').hide();
                        jQuery('#other_details_success_error').html(data.errors);
                        jQuery('#other_details_success_error').removeClass('display-hide');
                        jQuery("#other_details_success_error").css('color', 'red');
                    }
                }
            });
        }
    });

    // this is for Department store in database ajax
    var department_frm = jQuery(".department_frm");
    department_frm.validate({
        rules: {
            departmentName: "required",
        },
        submitHandler: function (department_frm) {
            jQuery('.preloader-custom-gif').show();
            jQuery('.preloader ').show();
            var departmentName = [];
            jQuery("input[name='departmentName[]']").each(function () {
                departmentName.push(jQuery(this).val());
            });
            var formData = new FormData(jQuery(department_frm)['0']);
            formData.append('action', 'WPHRMDepartmentInfo');
            var ajax_url = ajaxurl;
            jQuery.ajax({
                method: "POST",
                url: ajax_url,
                contentType: false,
                cache: false,
                processData: false,
                data: formData,
                success: function (output) {
                    var data = JSON.parse(output);
                    if (data.success) {
                        jQuery('.preloader-custom-gif').hide();
                        jQuery('.preloader ').hide();
                        jQuery('#wphrmDepartmentInfo_success').removeClass('display-hide');
                        window.setTimeout(function () {
                            window.location.reload();
                        }, 500);

                    } else {
                        jQuery('.preloader-custom-gif').hide();
                        jQuery('.preloader ').hide();
                        jQuery('#wphrmDepartmentInfo_error').html(data.errors);
                        jQuery('#wphrmDepartmentInfo_error').removeClass('display-hide');
                        jQuery("#wphrmDepartmentInfo_error").css('color', 'red');
                    }
                }
            });
        }
    });
// this is for employee  details store in database ajax
    var wphrmEmployeeAttendanceMark_frm = jQuery("#wphrmEmployeeAttendanceMark_frm");
    wphrmEmployeeAttendanceMark_frm.validate({
        rules: {
        },
        submitHandler: function (wphrmEmployeeAttendanceMark_frm) {
            var formData = new FormData(jQuery(wphrmEmployeeAttendanceMark_frm)['0']);
            formData.append('action', 'WPHRMEmployeeAttendanceMark');
            var ajax_url = ajaxurl;
            jQuery('.preloader-custom-gif').show();
            jQuery('.preloader ').show();
            jQuery.ajax({
                method: "POST",
                url: ajax_url,
                contentType: false,
                cache: false,
                processData: false,
                data: formData,
                success: function (output) {
                    var data = JSON.parse(output);
                    if (data.success) {
                        jQuery('.preloader-custom-gif').hide();
                        jQuery('.preloader ').hide();
                        jQuery("html, body").animate({scrollTop: 0}, "slow");
                        jQuery('#employee_attendance_mark_success').removeClass('display-hide');
                    }
                }
            });
        }
    });

    /* this is for Designation store in database ajax */
    var designation_frm = jQuery(".designation_frm");
    designation_frm.validate({
        rules: {
            designation_name: "required",
        },
        submitHandler: function (designation_frm) {
            jQuery('.preloader-custom-gif').show();
            jQuery('.preloader ').show();
            var designation_name = [];
            jQuery("input[name='designation_name[]']").each(function () {
                designation_name.push(jQuery(this).val());
            });
            var formData = new FormData(jQuery(designation_frm)['0']);
            formData.append('action', 'WPHRMDesignationInfo');
            var ajax_url = ajaxurl;
            jQuery.ajax({
                method: "POST",
                url: ajax_url,
                contentType: false,
                cache: false,
                processData: false,
                data: formData,
                success: function (output) {
                    var data = JSON.parse(output);
                    if (data.success) {
                        jQuery('.preloader-custom-gif').hide();
                        jQuery('.preloader ').hide();
                        jQuery('#wphrmDesignationInfo_success').removeClass('display-hide');
                        jQuery("#designation_name").val("");
                        window.location.reload();
                    } else {
                        jQuery('.preloader-custom-gif').hide();
                        jQuery('.preloader ').hide();
                        jQuery('#wphrmDesignationInfo_error').html(data.errors);
                        jQuery('#wphrmDesignationInfo_error').removeClass('display-hide');
                        jQuery("#wphrmDesignationInfo_error").css('color', 'red');
                    }
                }
            });
        }
    });

    /** Add multiple Weekends  */
    var wphrmAddyearInWeekendfrm = jQuery("#add-year-in-weekendfrm");
    wphrmAddyearInWeekendfrm.validate({
        rules: {
            wphrmyear: "required",
            wphrmWeekend: "required",
        },
        submitHandler: function (wphrmAddyearInWeekendfrm) {

            var formData = new FormData(jQuery(wphrmAddyearInWeekendfrm)['0']);
            formData.append('action', 'WPHRMwphrmAddyearInWeekendInfo');
            jQuery('.preloader-custom-gif').show();
            jQuery('.preloader ').show();
            var ajax_url = ajaxurl;
            jQuery.ajax({
                method: "POST",
                url: ajax_url,
                contentType: false,
                cache: false,
                processData: false,
                data: formData,
                success: function (output) {
                    var data = JSON.parse(output);
                    if (data.success) {
                        jQuery('.preloader-custom-gif').hide();
                        jQuery('.preloader ').hide();
                        jQuery('#weekend_success').removeClass('display-hide');
                        jQuery('#weekend_success').html("<i class='fa fa-check-square' aria-hidden='true'></i> " + data.success);
                        jQuery("#wphrmyear").val("");
                        jQuery("#wphrmWeekend").val("");
                        window.setTimeout(function () {
                            window.location.reload();
                        }, 500);
                    } else {
                        jQuery('.preloader-custom-gif').hide();
                        jQuery('.preloader ').hide();
                        jQuery('#weekend_error').html(data.error);
                        jQuery('#weekend_error').removeClass('display-hide');
                        jQuery("#weekend_error").css('color', 'red');
                    }
                }
            });
        }
    });
    
     // For notice
    jQuery("#wphrmNoticeInfo_frm").click(function (e) {
        e.preventDefault();
        var wphrm_notice_id = jQuery("#wphrm_notice_id").val();
        var wphrm_notice_title = jQuery("#wphrm_notice_title").val();
        var wphrm_notice_desc = tinymce.get('wphrm_notice_desc').getContent();
        var ajax_url = ajaxurl;
        wphrm_data = {
            'action': 'WPHRMNoticeInfo',
            'wphrm_notice_title': wphrm_notice_title,
            'wphrm_notice_desc': wphrm_notice_desc,
            'wphrm_notice_id': wphrm_notice_id,
        }
        jQuery('.preloader-custom-gif').show();
        jQuery('.preloader ').show();
        jQuery.post(ajax_url, wphrm_data, function (response) {
            var data = JSON.parse(response);
            if (data.success == true) {
                jQuery('.preloader-custom-gif').hide();
                jQuery('.preloader ').hide();
                jQuery('#wphrmNoticeInfo_success').removeClass('display-hide');
                jQuery("html, body").animate({scrollTop: 0}, "slow");
            } else {
                jQuery('.preloader-custom-gif').hide();
                jQuery('.preloader ').hide();
                jQuery("#wphrmNoticeInfo_success").hide();
                jQuery('#wphrmNoticeInfo_error').html(data.error);
                jQuery('#wphrmNoticeInfo_error').removeClass('display-hide');
                jQuery("#wphrmNoticeInfo_error").css('color', 'red');
                jQuery("html, body").animate({scrollTop: 0}, "slow");
            }
        });
    });

    
    /* this is for hodiday add in database  */
    jQuery(function () {
        var wphrmAddHolidays_frm = jQuery("#wphrmAddHolidays_frm");
        wphrmAddHolidays_frm.validate({
            submitHandler: function (wphrmAddHolidays_frm) {
                jQuery('.preloader-custom-gif').show();
                jQuery('.preloader ').show();
                var formData = new FormData(jQuery(wphrmAddHolidays_frm)['0']);
                formData.append('action', 'WPHRMAddHolidays');
                var ajax_url = ajaxurl;
                jQuery.ajax({
                    method: "POST",
                    url: ajax_url,
                    contentType: false,
                    cache: false,
                    processData: false,
                    data: formData,
                    success: function (output) {
                        var data = JSON.parse(output);
                        if (data.success == true) {
                            jQuery('.preloader-custom-gif').hide();
                            jQuery('.preloader ').hide();
                            jQuery('#holiday_success').removeClass('display-hide');
                            jQuery("#holiday_date").val("");
                            jQuery("#occasion").val("");
                            window.setTimeout(function () {
                                window.location.reload();
                            }, 500);

                        } else {
                            jQuery('.preloader-custom-gif').hide();
                            jQuery('.preloader ').hide();
                            jQuery('#holiday_error').html(data.error);
                            jQuery('#holiday_error').removeClass('display-hide');
                            jQuery("#holiday_error").css('color', 'red');
                        }
                    }
                });
            }
        });

    });

    /* Add leave type */
    var wphrm_add_leavetype_form = jQuery("#wphrm_add_leavetype_form");
    wphrm_add_leavetype_form.validate({
        rules: {
            leaveType: "required",
            numberOfLeave: "required",
            wphrm_period: "required",
        },
        submitHandler: function (wphrm_add_leavetype_form) {
            jQuery('.preloader-custom-gif').show();
            jQuery('.preloader ').show();
            var formData = new FormData(jQuery(wphrm_add_leavetype_form)['0']);
            formData.append('action', 'WPHRMLeavetypeInfo');
            var ajax_url = ajaxurl;
            jQuery.ajax({
                method: "POST",
                url: ajax_url,
                contentType: false,
                cache: false,
                processData: false,
                data: formData,
                success: function (output) {
                    var data = JSON.parse(output);
                    if (data.success) {
                        jQuery('.preloader-custom-gif').hide();
                        jQuery('.preloader ').hide();
                        jQuery('#wphrm_add_leavetype_success').removeClass('display-hide');
                        jQuery("#add_leaveType").val("");
                        jQuery("#wphrm_period").val("");
                        jQuery("#add_num_of_leave").val("");
                        window.setTimeout(function () {
                            window.location.reload();
                        }, 500);

                    } else {
                        jQuery('.preloader-custom-gif').hide();
                        jQuery('.preloader ').hide();
                        jQuery('#wphrm_add_leavetype_error').html(data.errors);
                        jQuery('#wphrm_add_leavetype_error').removeClass('display-hide');
                        jQuery("#wphrm_add_leavetype_error").css('color', 'red');
                    }
                }
            });
        }
    });

    /* Selected Department wise get designation */
    var id = jQuery('#wphrm_employee_department').val();
    if (id != '') {
        var designationid = jQuery('#wphrm_ajax_employee_designation').val();
        getDesignation(id, designationid);
    }

    /* add employee department  */
    jQuery('#wphrm_employee_department').change(function () {
        var deprtid = jQuery('#wphrm_employee_department').val();
        var desigID = jQuery('#wphrm_ajax_employee_designation').val();
        getDesignation(deprtid, desigID);
    });

    
  
    // General Settings
    var wphrmGeneralSettingsInfo_form = jQuery("#wphrmGeneralSettingsInfo_form");
    wphrmGeneralSettingsInfo_form.validate({
        rules: {
            wphrm_company_email: {
                email: true,
            },
        },
        submitHandler: function (wphrmGeneralSettingsInfo_form) {
            jQuery('.preloader-custom-gif').show();
            jQuery('.preloader ').show();
            var formData = new FormData(jQuery(wphrmGeneralSettingsInfo_form)['0']);
            formData.append('action', 'WPHRMGeneralSettingsInfo');
            var ajax_url = ajaxurl;
            jQuery.ajax({
                method: "POST",
                url: ajax_url,
                contentType: false,
                cache: false,
                processData: false,
                data: formData,
                success: function (output) {
                    var data = JSON.parse(output);
                    if (data.success == true) {
                        jQuery('.preloader-custom-gif').hide();
                        jQuery('.preloader ').hide();
                        jQuery('#general_settings_success').removeClass('display-hide');
                    } else {
                        jQuery('.preloader-custom-gif').hide();
                        jQuery('.preloader ').hide();
                        jQuery('#general_settings_error').html(data.error);
                        jQuery('#general_settings_error').removeClass('display-hide');
                        jQuery("#general_settings_error").css('color', 'red');
                    }
                }
            });
        }
    });

    // Change Password Settings
    var wphrmChangePasswordInfo_form = jQuery("#wphrmChangePasswordInfo_form");
    wphrmChangePasswordInfo_form.validate({
        rules: {
            wphrm_current_password: {
                required: true,
            },
            wphrm_new_password: {
                required: true,
            },
            wphrm_conform_password: {
                equalTo: "#wphrm_new_password"
            }
        },
        submitHandler: function (wphrmChangePasswordInfo_form) {
            jQuery('.preloader-custom-gif').show();
            jQuery('.preloader ').show();
            var formData = new FormData(jQuery(wphrmChangePasswordInfo_form)['0']);
            formData.append('action', 'WPHRMChangePasswordInfo');
            var ajax_url = ajaxurl;
            jQuery.ajax({
                method: "POST",
                url: ajax_url,
                contentType: false,
                cache: false,
                processData: false,
                data: formData,
                success: function (output) {
                    var data = JSON.parse(output);
                    if (data.success == true) {
                        jQuery('.preloader-custom-gif').hide();
                        jQuery('.preloader ').hide();
                        jQuery('#wphrmChangePasswordInfo_success').removeClass('display-hide');
                    } else {
                        jQuery('.preloader-custom-gif').hide();
                        jQuery('.preloader ').hide();
                        jQuery("#wphrmChangePasswordInfo_success").hide();
                        jQuery('#wphrmChangePasswordInfo_error').html(data.error);
                        jQuery('#wphrmChangePasswordInfo_error').removeClass('display-hide');
                        jQuery("#wphrmChangePasswordInfo_error").css('color', 'red');
                    }
                }
            });
        }
    });

    // salary-slip-settings
    var wphrmSalarySlipInfo_form = jQuery("#wphrmSalarySlipInfo_form");
    wphrmSalarySlipInfo_form.validate({
        rules: {
        },
        submitHandler: function (wphrmSalarySlipInfo_form) {
            jQuery('.preloader-custom-gif').show();
            jQuery('.preloader ').show();
            var formData = new FormData(jQuery(wphrmSalarySlipInfo_form)['0']);
            formData.append('action', 'WPHRMSalarySlipInfo');
            var ajax_url = ajaxurl;
            jQuery.ajax({
                method: "POST",
                url: ajax_url,
                contentType: false,
                cache: false,
                processData: false,
                data: formData,
                success: function (output) {
                    var data = JSON.parse(output);
                    if (data.success == true) {
                        jQuery('.preloader-custom-gif').hide();
                        jQuery('.preloader ').hide();
                        jQuery('#wphrmSalarySlipInfo_success').removeClass('display-hide');
                    } else {
                        jQuery('.preloader-custom-gif').hide();
                        jQuery('.preloader ').hide();
                        jQuery("#wphrmSalarySlipInfo_success").hide();
                        jQuery('#wphrmSalarySlipInfo_error').html(data.error);
                        jQuery('#wphrmSalarySlipInfo_error').removeClass('display-hide');
                        jQuery("#wphrmSalarySlipInfo_error").css('color', 'red');
                    }
                }
            });
        }
    });

    // For page permissions
    jQuery("#wphrmSalarySlipFieldsInfoForm").submit(function (e) {
        e.preventDefault();
        var wphrmearninglebal = [];
        jQuery("input[name='earninglebal[]']").each(function () {
            wphrmearninglebal.push(jQuery(this).val());
        });
        var wphrmdeductionlebal = [];
        jQuery("input[name='deductionlebal[]']").each(function () {
            wphrmdeductionlebal.push(jQuery(this).val());
        });
        var ajax_url = ajaxurl;
        wphrm_data = {
            'action': 'wphrmAddEarningLabelInfo',
            'wphrmearninglebal': wphrmearninglebal,
            'wphrmdeductionlebal': wphrmdeductionlebal,
        }
        jQuery('.preloader-custom-gif').show();
        jQuery('.preloader ').show();
        jQuery.post(ajax_url, wphrm_data, function (response) {
            var data = JSON.parse(response);
            if (data.success == true) {
                jQuery('.preloader-custom-gif').hide();
                jQuery('.preloader ').hide();
                jQuery('#wphrmsalaryslipfield_success').removeClass('display-hide');
            } else {
                jQuery('.preloader-custom-gif').hide();
                jQuery('.preloader ').hide();
                jQuery('#wphrmsalaryslipfield_error').html(data.error);
                jQuery('#wphrmsalaryslipfield_error').removeClass('display-hide');
                jQuery("#wphrmsalaryslipfield_error").css('color', 'red');
            }
        });
    });

    // Bank detail fields info settings
    jQuery("#wphrmBankDetailsFieldsInfoForm").submit(function (e) {
        e.preventDefault();
        var wphrmBankfieldsLebal = [];
        jQuery("input[name='bank-fields-lebal[]']").each(function () {
            wphrmBankfieldsLebal.push(jQuery(this).val());
        });
        var ajax_url = ajaxurl;
        wphrm_data = {
            'action': 'wphrmAddBnakDetailsLabelInfo',
            'wphrmBankfieldsLebal': wphrmBankfieldsLebal,
        }
        jQuery('.preloader-custom-gif').show();
        jQuery('.preloader ').show();
        jQuery.post(ajax_url, wphrm_data, function (response) {
            var data = JSON.parse(response);
            if (data.success == true) {
                jQuery('.preloader-custom-gif').hide();
                jQuery('.preloader ').hide();
                jQuery('#wphrmBankfield_success').removeClass('display-hide');
            } else {
                jQuery('.preloader-custom-gif').hide();
                jQuery('.preloader ').hide();
                jQuery('#wphrmBankfield_error').html(data.error);
                jQuery('#wphrmBankfield_error').removeClass('display-hide');
                jQuery("#wphrmBankfield_error").css('color', 'red');
            }
        });
    });

    // Other detail fields info settings
    jQuery("#wphrmotherDetailsFieldsInfoForm").submit(function (e) {
        e.preventDefault();
        var wphrmOtherfieldsLebal = [];
        jQuery("input[name='other-fields-lebal[]']").each(function () {
            wphrmOtherfieldsLebal.push(jQuery(this).val());
        });
        var ajax_url = ajaxurl;
        wphrm_data = {
            'action': 'wphrmAddOtherDetailsLabelInfo',
            'wphrmOtherfieldsLebal': wphrmOtherfieldsLebal,
        }
        jQuery('.preloader-custom-gif').show();
        jQuery('.preloader ').show();
        jQuery.post(ajax_url, wphrm_data, function (response) {
            var data = JSON.parse(response);
            if (data.success == true) {
                jQuery('.preloader-custom-gif').hide();
                jQuery('.preloader ').hide();
                jQuery('#wphrmotherfield_success').removeClass('display-hide');
            } else {
                jQuery('.preloader-custom-gif').hide();
                jQuery('.preloader ').hide();
                jQuery('#wphrmotherfield_error').html(data.error);
                jQuery('#wphrmotherfield_error').removeClass('display-hide');
                jQuery("#wphrmotherfield_error").css('color', 'red');
            }
        });
    });

    
    
    // For expense report amount
    var wphrmExpenseReportInfo_frm = jQuery("#wphrmExpenseReportInfo_frm");
    wphrmExpenseReportInfo_frm.validate({
        rules: {},
        submitHandler: function (wphrmExpenseReportInfo_frm) {
            jQuery('.preloader-custom-gif').show();
            jQuery('.preloader ').show();
            var formData = new FormData(jQuery(wphrmExpenseReportInfo_frm)['0']);
            formData.append('action', 'WPHRMExpenseReportInfo');
            var ajax_url = ajaxurl;
            jQuery.ajax({
                method: "POST",
                url: ajax_url,
                contentType: false,
                cache: false,
                processData: false,
                data: formData,
                success: function (output) {
                    var data = JSON.parse(output);
                    if (data.success == true) {
                        jQuery('.preloader-custom-gif').hide();
                        jQuery('.preloader ').hide();
                        jQuery('#wphrm_expense_report_success').removeClass('display-hide');

                    } else {
                        jQuery('.preloader-custom-gif').hide();
                        jQuery('.preloader ').hide();
                        jQuery("#wphrm_expense_report_error").hide();
                        jQuery('#wphrm_expense_report_error').html(data.error);
                        jQuery('#wphrm_expense_report_error').removeClass('display-hide');
                        jQuery("#wphrm_expense_report_error").css('color', 'red');
                    }
                }
            });
        }
    });

    

    /** Input box not copy and paste validation**/
    jQuery("#wphrm_employee_bank_account_no").bind('copy paste', function (e) {
        e.preventDefault();
        jQuery("#wphrm_bank_details_error").html("Copy paste doesn't work for security reasons.");
        jQuery('.alert').addClass('display-hide');
        jQuery('#wphrm_bank_details_error').removeClass('display-hide');
    });

    jQuery('#wphrm_employee_bank_account_no').bind('paste', function (e) {
        e.preventDefault();
        jQuery("#wphrm_bank_details_error").html("Copy paste doesn't work for security reasons.");
        jQuery('.alert').addClass('display-hide');
        jQuery('#wphrm_bank_details_error').removeClass('display-hide');
    });

    var month = new Date();
    var monthGet = month.getMonth();
    jQuery('.month-data').val(monthGet);


    jQuery("#myid li").click(function () {
        var dataId = jQuery(this).attr("data-id");
        jQuery('.month-data').val(dataId);
    });


    var setYear = jQuery('.yeardata').val();
    /* For add multiple input text in holiday  */
    var $insertBefore = jQuery('#insertBefore');
    var $i = 0;


    jQuery('#plusButton').click(function () {
        var monthselect = jQuery('.month-data').val();
        var setYear = jQuery('.yeardata').val();
        $i = $i + 1;
        jQuery(' <div class="form-group addHoliday'+$i+'"> ' +
                '<div class="col-md-5" ><input class="form-control form-control-inline input-medium date-picker-default' + $i + '" name="holiday_date[' + $i + ']" type="text" value="" placeholder="Date"/></div>' +
                '<div class="col-md-5"><input class="form-control form-control-inline occasioncl" name="occasion[' + $i + ']" type="text" value="" placeholder="' + WPHRMCustomJS.occasion + '"/></div>' +
                '<div class="col-md-2"><a id="remScnt" class="btn red" onclick="addHoliday(' + $i + ');"><i class="fa fa-trash" aria-hidden="true"></i></a></div>' +
                '</div>').insertBefore($insertBefore);
        jQuery.fn.datepicker.defaults.format = "dd-mm-yyyy";
        jQuery.fn.datepicker.defaults.autoclose = true;
        jQuery('.date-picker-default' + $i).datepicker("setDate", new Date(setYear, monthselect));
    });

    /* For add multiple input text in Earning  */
    var $EarninginsertBefore = jQuery('#earninginsertBefore');
    var $i = 0;
    jQuery('#add-more-earning').click(function () {
        $i = $i + 1;
        jQuery('<tr id="earning_scents' + $i + '"><td><input type="text"  class="earningDeduction"  name="wphrm-earning-lebal[]"></td>' +
                '<td  style="padding-right: 0px; float:right;">' +
                '<input type="text" class="earningcal earningDeduction validationonnumber" onkeyup="calculateEarningSum()" name="wphrm-earning-value[]" >' +
                '</td><td style="text-align: center;"><a id="remScnt" class="btn red" onclick="removeearning(' + $i + ');"><i class="fa fa-trash" aria-hidden="true"></i></a></td>' +
                '</tr>').insertBefore($EarninginsertBefore);
    });

    /* For add multiple input text in Deduction  */
    var $deductioninsertBefore = jQuery('#deductioninsertBefore');
    var $i = 0;
    jQuery('#add-more-Deduction').click(function () {
        $i = $i + 1;
        jQuery('<tr id="deduction_scents' + $i + '"><td><input type="text"  class="earningDeduction"   name="wphrm-deduction-lebal[]"></td>' +
                '<td  style="padding-right: 0px; float:right;">' +
                '<input type="text" class="earningDeduction deductioncal validationonnumber"  onkeyup="calculateDeductionSum()" name="wphrm-deduction-value[]" >' +
                '</td><td style="text-align: center;"><a  id="remScnt" class="btn red" onclick="removedeductions(' + $i + ');"><i class="fa fa-trash" aria-hidden="true"></i></a></td>' +
                '</tr>').insertBefore($deductioninsertBefore);
    });

    /* For add multiple input text in Deduction  */
    var $bankfieldslebalBefore = jQuery('#bank-fields-lebal-Before');
    var $i = 0;
    jQuery('#add-bank-fields-lebal').click(function () {
        $i = $i + 1;
        jQuery('<div class="form-group" id="Bankfieldlabel_scents' + $i + '"><div class="col-md-8">' +
                '<input class="form-control form-control-inline" name="bank-fields-lebal[]" placeholder="' + WPHRMCustomJS.bankfieldlabel + '"></div>' +
                '<div class="col-md-2"><a  id="remScnt" class="btn red" onclick="Bankfieldlabel(' + $i + ');"><i class="fa fa-trash" aria-hidden="true"></i></a></div>' +
                '</div></div>').insertBefore($bankfieldslebalBefore);
    });

    /* For add multiple input text in Deduction  */
    var $otherfieldslebalBefore = jQuery('#other-fields-lebal-Before');
    var $i = 0;
    jQuery('#add-other-fields-lebal').click(function () {
        $i = $i + 1;
        jQuery('<div class="form-group" id="Otherfieldlabel_scents' + $i + '"><div class="col-md-8">' +
                '<input class="form-control form-control-inline" name="other-fields-lebal[]" placeholder="' + WPHRMCustomJS.otherfieldlabel + '"></div>' +
                '<div class="col-md-2"><a id="remScnt" class="btn red" onclick="Otherfieldlabel(' + $i + ');"><i class="fa fa-trash" aria-hidden="true"></i></a></div>' +
                '</div></div>').insertBefore($otherfieldslebalBefore);
    });

    /* For add multiple input text in Deduction  */
    var $salaryfieldslebalBefore = jQuery('#salary-fields-lebal-Before');
    var $i = 0;
    jQuery('#add-salary-fields-lebal').click(function () {
        $i = $i + 1;
        jQuery('<div class="form-group" id="salaryfieldlabel_scents' + $i + '"><div class="col-md-8">' +
                '<input class="form-control form-control-inline" name="salary-fields-lebal[]" placeholder="' + WPHRMCustomJS.salaryfieldlabel + '"></div>' +
                '<div class="col-md-2"><a  id="remScnt" class="btn red" onclick="Salaryfieldlabel(' + $i + ');"><i class="fa fa-trash" aria-hidden="true"></i></a></div>' +
                '</div></div>').insertBefore($salaryfieldslebalBefore);
    });

    /* For add multiple input text in Deduction  */
    var $earninglebalinsertBefore = jQuery('#earninglebalinsertBefore');
    var $i = 0;
    jQuery('#addearninglebal').click(function () {
        $i = $i + 1;
        jQuery('<div class="form-group" id="earning_scents' + $i + '"><div class="col-md-8">' +
                '<input class="form-control form-control-inline" name="earninglebal[]" placeholder="' + WPHRMCustomJS.earninglabel + '"></div>' +
                '<div class="col-md-2"><a  id="remScnt" class="btn red" onclick="removeearning(' + $i + ');"><i class="fa fa-trash" aria-hidden="true"></i></a></div>' +
                '</div></div>').insertBefore($earninglebalinsertBefore);
    });
    var $deductionlebalinsertBefore = jQuery('#deductionlebalinsertBefore');
    var $i = 0;
    jQuery('#adddeductionlebal').click(function () {
        $i = $i + 1;
        jQuery('<div class="form-group" id="deduction_scents' + $i + '"><div class="col-md-8">' +
                '<input class="form-control form-control-inline" name="deductionlebal[]" placeholder="' + WPHRMCustomJS.deductionlabel + '"></div>' +
                '<div class="col-md-2"><a  id="remScnt" class="btn red" onclick="removedeductions(' + $i + ');"><i class="fa fa-trash" aria-hidden="true"></i></a></div>' +
                '</div></div>').insertBefore($deductionlebalinsertBefore);
    });

    /* For add multiple input text in departments  */
    var $insertBeforeDepartment = jQuery('#insertBeforeDepartment');
    var $i = 0;
    jQuery('#plusButtonDepartment').click(function () {
        $i = $i + 1;
        jQuery('<div class="form-group" id="departmentID' + $i + '"><div class="col-md-10">' +
                '<input class="form-control form-control-inline " name="departmentName[]" id="department_name" type="text"  value="" placeholder="' + WPHRMCustomJS.departmentName + '" /></div>' +
                '<div class="col-md-2"><a  id="remScnt" class="btn red" onclick="departmentAdd(' + $i + ');"><i class="fa fa-trash" aria-hidden="true"></i></a></div>' +
                '</div>').insertBefore($insertBeforeDepartment);
    });

    /* For add multiple input text in designation  */
    var $insertBeforeDesignation = jQuery('#insertBeforeDesignation');
    var $i = 0;
    jQuery('#plusButtonDesignation').click(function () {
        $i = $i + 1;
        jQuery('<div class="form-group" id="designationID' + $i + '"><div class="col-md-10">' +
                '<input class="form-control form-control-inline " name="designation_name[]" id="designation_name" type="text" value="" placeholder="' + WPHRMCustomJS.designationName + '" /></div>' +
                '<div class="col-md-2"><a  id="remScnt" class="btn red" onclick="designationAdd(' + $i + ');"><i class="fa fa-trash" aria-hidden="true"></i></a></div>' +
                '</div>').insertBefore($insertBeforeDesignation);
    });


    /*for Add leave Applications  modul*/
    var wphrm_user_leave_applications_frm = jQuery("#wphrm_user_leave_applications_frm");
    wphrm_user_leave_applications_frm.validate({
        rules: {
            wphrm_leavetype: {
                required: true,
            }
            , wphrm_leavedate: {
                required: true,
            },
            wphrm_reason: {
                required: true,
            }
        },
        submitHandler: function (wphrm_user_leave_applications_frm) {
            jQuery('.preloader-custom-gif').show();
            jQuery('.preloader ').show();
            var formData = new FormData(jQuery(wphrm_user_leave_applications_frm)['0']);
            var EnteredDate = jQuery("#wphrm_leavedate").val(); // For JQuery
            var dateAr = EnteredDate.split('-');
            var newDate = dateAr[2] + '-' + dateAr[1] + '-' + dateAr[0];
            var myDates = new Date(newDate);
            var myDate = myDates.setDate(myDates.getDate() + 2);

            var d = new Date();
            var curr_date = d.getDate();
            var curr_month = d.getMonth() + 1;
            curr_month = curr_month < 10 ? '0' + curr_month : curr_month;
            curr_date = curr_date < 10 ? '0' + curr_date : curr_date;
            var curr_year = d.getFullYear();
            var newtoday = curr_year + "-" + curr_month + "-" + curr_date;
            var todaydate = new Date(newtoday);
            var todaydates = todaydate.setDate(todaydate.getDate() + 2);
            if (myDate >= todaydates) { 
            
            formData.append('action', 'WPHRMUserLeaveApplicationsInfo');
            var ajax_url = ajaxurl;
            jQuery.ajax({
                method: "POST",
                url: ajax_url,
                contentType: false,
                cache: false,
                processData: false,
                data: formData,
                success: function (output) {
                    var data = JSON.parse(output);
                    if (data.success) {
                        jQuery('.preloader-custom-gif').hide();
                        jQuery('.preloader ').hide();
                        jQuery('#wphrm_add_leave_applications_success').removeClass('display-hide');
                        jQuery('#wphrm_leavetype').val("");
                        jQuery('#wphrm_leavedate').val("");
                        jQuery('#wphrm_reason').val("");
                        window.setTimeout(function () {
                            window.location.reload();
                        }, 500);
                    } else {
                        jQuery('.preloader-custom-gif').hide();
                        jQuery('.preloader ').hide();
                        jQuery('#wphrm_add_leave_applications_error').html(data.error);
                        jQuery('#wphrm_add_leave_applications_error').removeClass('display-hide');
                        jQuery("#wphrm_add_leave_applications_error").css('color', 'red');
                    }
                }
            });
            } else {
                jQuery('.preloader-custom-gif').hide();
                jQuery('.preloader ').hide();
                jQuery('#wphrm_add_leave_applications_error').html('Entered date is less than today date');
                jQuery('#wphrm_add_leave_applications_error').removeClass('display-hide');
                return false;
            }
        }
    });

    /*finacial add modul*/
    var wphrm_financials_frm = jQuery("#wphrm_financials_frm");
    wphrm_financials_frm.validate({
        rules: {
            'wphrm-item': {
                required: true,
            }
            , 'wphrm-amount': {
                required: true,
                number: true,
            },
            'wphrm-status': {
                required: true,
            },
            'wphrm-financials-date': {
                required: true,
            }
        }, messages: {
            'wphrm-amount': {
                required: "Please enter amount.",
                number: "Please enter a valid amount."
            },
        },
        submitHandler: function (wphrm_financials_frm) {
            jQuery('.preloader-custom-gif').show();
            jQuery('.preloader ').show();
            var formData = new FormData(jQuery(wphrm_financials_frm)['0']);
            formData.append('action', 'WPHRMFinancialsInfo');
            var ajax_url = ajaxurl;
            jQuery.ajax({
                method: "POST",
                url: ajax_url,
                contentType: false,
                cache: false,
                processData: false,
                data: formData,
                success: function (output) {
                    var data = JSON.parse(output);
                    if (data.success) {
                        jQuery('.preloader-custom-gif').hide();
                        jQuery('.preloader ').hide();
                        jQuery('#wphrm_financials_success').removeClass('display-hide');
                        jQuery("#wphrm_item").val("");
                        jQuery("#wphrm_amount").val("");
                        jQuery("#wphrm_status").val("");
                        jQuery("#wphrm_financials_date").val("");
                        window.setTimeout(function () {
                            window.location.reload();
                        }, 500);
                    } else {
                        jQuery('.preloader-custom-gif').hide();
                        jQuery('.preloader ').hide();
                        jQuery('#wphrm_financials_error').html(data.errors);
                        jQuery('#wphrm_financials_error').removeClass('display-hide');
                        jQuery("#wphrm_financials_error").css('color', 'red');
                    }
                }
            });
        }
    });

    

    /* Delete  Salary generated slips    */
    jQuery("#frm_salary_delete").submit(function (e) {
        e.preventDefault();
        var wphrm_employeeOther_id = jQuery("#employeedelete_id").val();
        var wphrm_generate_month = jQuery("#generate_month_delete").val();
        var wphrm_generate_year = jQuery("#generate_year_delete").val();
        var ajax_url = ajaxurl;
        wphrm_data = {
            'action': 'WPHRMRemoveSalarySlip',
            'wphrm_employeeOther_id': wphrm_employeeOther_id,
            'wphrm_generate_year': wphrm_generate_year,
            'wphrm_generate_month': wphrm_generate_month,
        }
        jQuery.post(ajax_url, wphrm_data, function (response) {
            var data = JSON.parse(response);
            if (data.success) {
                location.href = '?page=wphrm-select-financials-month&employee_id=' + wphrm_employeeOther_id;
            }
        });
    });

    

    /* For search  by datepicker */
    /* For date picker */
    jQuery('.date-picker').datepicker({
        dateFormat: 'yyyy-mm-dd',
        autoclose: true
    });


    /* For vehicle Checked */
    if (jQuery('#wphrm_employee_vehicle').is(":checked")) {
        jQuery("#wphrm_vehicle_details").toggle('open');
    }
    jQuery("#wphrm_employee_vehicle").click(function () {
        if (jQuery(this).is(":checked")) {
            jQuery("#wphrm_vehicle_details").toggle('open');
        } else {
            jQuery("#wphrm_vehicle_details").toggle('hide');
        }
    });

    /** T-Shirt Size **/
    jQuery("#tshirt_info_toggle").hover(function () {
        jQuery("#wphrm_tshirt_size_info_popup").fadeToggle();
    }, function () {
        jQuery("#wphrm_tshirt_size_info_popup").fadeToggle();
    });
    jQuery('#employee_pofile_img').on('click', function () {
        jQuery('#employee_profile').click();
    });
    
    jQuery("#mark-attendance-date").change(function () {
    var attendanceDate = jQuery('#mark-attendance-date').val();
    var ajax_url = ajaxurl + '?page=wphrm-mark-attendance&status="edit"&attendancedate=' + attendanceDate;
    var url = ajax_url.replace('/admin-ajax', '/admin');
    window.location.href = url;
});
    
    TableManaged.init();
});


// Get Designation 
function getDesignation(departmentID, designationID) {
    jQuery('#wphrm_employee_designation option:not(:first)').remove();
    if (departmentID != '') {
        jQuery.ajax({
            method: "POST",
            url: ajaxurl,
            data: {
                'id': departmentID, 'action': 'WPHRMDesignationAjax'
            },
            dataType: 'json',
            success: function (data) {
                jQuery(data.details).each(function (key, item) {
                    var selectval = '';
                    if (item.id == designationID) {
                        selectval = "selected='selected'";
                        jQuery('#wphrm_employee_designation').append(jQuery('<option>', {
                            value: item.id,
                            text: item.name,
                            selected: selectval
                        }));
                    } else {
                        jQuery('#wphrm_employee_designation').append(jQuery('<option>', {
                            value: item.id,
                            text: item.name,
                        }));
                    }
                });
            }
        });
    }
}

/*for edit department modul*/
function departmentEdit(id, department_name) {
    jQuery("#editdepartment_name").val(department_name);
    var wphrm_edit_department = jQuery(".wphrm_edit_department");
    wphrm_edit_department.validate({
        rules: {
            editdepartment_name: "required",
        },
        submitHandler: function (wphrm_edit_department) {
            jQuery('.preloader-custom-gif').show();
            jQuery('.preloader ').show();
            var formData = new FormData(jQuery(wphrm_edit_department)['0']);
            formData.append('action', 'WPHRMDepartmentInfo');
            formData.append('wphrm_department_id', id);
            var ajax_url = ajaxurl;
            jQuery.ajax({
                method: "POST",
                url: ajax_url,
                contentType: false,
                cache: false,
                processData: false,
                data: formData,
                success: function (output) {
                    var data = JSON.parse(output);
                    if (data.success) {
                        jQuery('.preloader-custom-gif').show();
                        jQuery('.preloader ').show();
                        jQuery('.preloader-custom-gif').hide();
                        jQuery('.preloader ').hide();
                        jQuery('#wphrm_Edepartment_info_success').removeClass('display-hide');
                        window.setTimeout(function () {
                            window.location.reload();
                        }, 500);
                    } else {
                        jQuery('.preloader-custom-gif').hide();
                        jQuery('.preloader ').hide();
                        jQuery('#wphrm_Edepartment_info_error').html(data.errors);
                        jQuery('#wphrm_Edepartment_info_error').removeClass('display-hide');
                        jQuery("#wphrm_Edepartment_info_error").css('color', 'red');
                    }
                }
            });
        }
    });
}


/*for edit designation modul*/
function designationEdit(id, designation_name) {
    jQuery("#editdesignation").val(designation_name);
    var wphrm_edit_designation = jQuery(".wphrm_edit_designation");
    wphrm_edit_designation.validate({
        rules: {
            editdepartment_name: "required",
        },
        submitHandler: function (wphrm_edit_designation) {
            jQuery('.preloader-custom-gif').show();
            jQuery('.preloader ').show();
            var formData = new FormData(jQuery(wphrm_edit_designation)['0']);
            formData.append('action', 'WPHRMDesignationInfo');
            formData.append('wphrm_designation_id', id);
            var ajax_url = ajaxurl;
            jQuery.ajax({
                method: "POST",
                url: ajax_url,
                contentType: false,
                cache: false,
                processData: false,
                data: formData,
                success: function (output) {
                    var data = JSON.parse(output);
                    if (data.success) {
                        jQuery('.preloader-custom-gif').hide();
                        jQuery('.preloader ').hide();
                        jQuery('#wphrm_Edesignation_info_success').removeClass('display-hide');
                        window.setTimeout(function () {
                            window.location.reload();
                        }, 500);
                    } else {
                        jQuery('.preloader-custom-gif').hide();
                        jQuery('.preloader ').hide();
                        jQuery('#wphrm_Edesignation_info_error').html(data.errors);
                        jQuery('#wphrm_Edesignation_info_error').removeClass('display-hide');
                        jQuery("#wphrm_Edesignation_info_error").css('color', 'red');
                    }
                }
            });
        }
    });
}

/* for edit leave type modul */
function leavetypeEdit(id, leavetype, period, no_of_leave) {
    jQuery("#edit_leaveType").val(leavetype);
    jQuery("#edit_num_of_leave").val(no_of_leave);
    jQuery("#edit_wphrm_period").val(period);
    var wphrm_edit_leavetype_form = jQuery("#wphrm_edit_leavetype_form");
    wphrm_edit_leavetype_form.validate({
        rules: {
            leaveType: "required",
            numberOfLeave: "required",
        },
        submitHandler: function (wphrm_edit_leavetype_form) {
            jQuery('.preloader-custom-gif').show();
            jQuery('.preloader ').show();
            var formData = new FormData(jQuery(wphrm_edit_leavetype_form)['0']);
            formData.append('action', 'WPHRMLeavetypeInfo');
            formData.append('wphrm_leavetype_id', id);
            var ajax_url = ajaxurl;
            jQuery.ajax({
                method: "POST",
                url: ajax_url,
                contentType: false,
                cache: false,
                processData: false,
                data: formData,
                success: function (output) {
                    var data = JSON.parse(output);
                    if (data.success) {
                        jQuery('.preloader-custom-gif').hide();
                        jQuery('.preloader ').hide();
                        jQuery('#wphrm_edit_leavetype_success').removeClass('display-hide');

                        window.setTimeout(function () {
                            window.location.reload();
                        }, 500);
                    } else {
                        jQuery('.preloader-custom-gif').hide();
                        jQuery('.preloader ').hide();
                        jQuery('#wphrm_edit_leavetype_error').html(data.errors);
                        jQuery('#wphrm_edit_leavetype_error').removeClass('display-hide');
                        jQuery("#wphrm_edit_leavetype_error").css('color', 'red');
                    }
                }
            });
        }
    });
}

/** for Finacials Edit modul **/
function finacialsEdit(id, item, amount, date, status) {
    jQuery("#finacials_id").val(id);
    jQuery("#wphrm_eitem").val(item);
    jQuery("#wphrm_eamount").val(amount);
    jQuery("#wphrm_efinancials_date").val(date);
    jQuery("#wphrm_estatus").val(status);
    var wphrm_edit_financials_frm = jQuery("#wphrm_edit_financials_frm");
    wphrm_edit_financials_frm.validate({
        rules: {
            'wphrm-item': {
                required: true,
            }
            , 'wphrm-amount': {
                required: true,
                number: true,
            },
            'wphrm-status': {
                required: true,
            },
            'wphrm-financials-date': {
                required: true,
            }
        }, messages: {
            'wphrm-amount': {
                required: "Please enter amount.",
                number: "Please enter a valid amount."
            },
        },
        submitHandler: function (wphrm_edit_financials_frm) {
            jQuery('.preloader-custom-gif').show();
            jQuery('.preloader ').show();
            var formData = new FormData(jQuery(wphrm_edit_financials_frm)['0']);
            formData.append('action', 'WPHRMFinancialsInfo');
            var ajax_url = ajaxurl;
            jQuery.ajax({
                method: "POST",
                url: ajax_url,
                contentType: false,
                cache: false,
                processData: false,
                data: formData,
                success: function (output) {
                    var data = JSON.parse(output);
                    if (data.success) {
                        jQuery('.preloader-custom-gif').hide();
                        jQuery('.preloader ').hide();
                        jQuery('#wphrm_edit_financials_success').removeClass('display-hide');
                        window.setTimeout(function () {
                            window.location.reload();
                        }, 500);
                    } else {
                        jQuery('.preloader-custom-gif').hide();
                        jQuery('.preloader ').hide();
                        jQuery('#wphrm_edit_financials_error').html(data.errors);
                        jQuery('#wphrm_edit_financials_error').removeClass('display-hide');
                        jQuery("#wphrm_edit_financials_error").css('color', 'red');
                    }
                }
            });
        }
    });
}

/** for edit leave Applications  modul **/
function applicationEdit(id, employeeID, name, date, leave_type, reason, appliedon, applicationStatus) {
    jQuery("#application_name").val(name);
    jQuery("#application_leavedate").val(date);
    jQuery("#application_leavetype").val(leave_type);
    jQuery("#application_reason").val(reason);
    jQuery("#application_appliedon").val(appliedon);
    jQuery("#applicationStatus").val(applicationStatus);
    var wphrm_leave_applications_frm = jQuery("#wphrm_leave_applications_frm");
    wphrm_leave_applications_frm.validate({
        rules: {},
        submitHandler: function (wphrm_leave_applications_frm) {
            jQuery('.preloader-custom-gif').show();
            jQuery('.preloader ').show();
            var formData = new FormData(jQuery(wphrm_leave_applications_frm)['0']);
            formData.append('action', 'WPHRMLeaveApplicationsInfo');
            formData.append('wphrm_leave_application_id', id);
            formData.append('wphrm_employeeID', employeeID);
            var ajax_url = ajaxurl;
            jQuery.ajax({
                method: "POST",
                url: ajax_url,
                contentType: false,
                cache: false,
                processData: false,
                data: formData,
                success: function (output) {
                    var data = JSON.parse(output);
                    if (data.success) {
                        jQuery('.preloader-custom-gif').hide();
                        jQuery('.preloader ').hide();
                        jQuery('#wphrm_edit_application_success').removeClass('display-hide');
                        window.setTimeout(function () {
                            window.location.reload();
                        }, 500);
                    } else {
                        jQuery('.preloader-custom-gif').hide();
                        jQuery('.preloader ').hide();
                        jQuery('#wphrm_edit_application_error').html(data.errors);
                        jQuery('#wphrm_edit_application_error').removeClass('display-hide');
                        jQuery("#wphrm_edit_application_error").css('color', 'red');
                    }
                }
            });
        }
    });
}

/** for edit leave Applications  modul **/
function user_staticEdit(id, date, leave_type, reason) {
    jQuery("#wphrm_leavedate").val(date);
    jQuery("#wphrm_leavetype").val(leave_type);
    jQuery("#wphrm_reason").val(reason);
    jQuery("#wphrm_attendanceID").val(id);
    var wphrm_user_leave_applications_frm = jQuery("#wphrm_user_leave_applications_frm");
    wphrm_user_leave_applications_frm.validate({
        wphrm_leavetype: {
            required: true,
        }
        , wphrm_leavedate: {
            required: true,
        },
        wphrm_reason: {
            required: true,
        },
        submitHandler: function (wphrm_user_leave_applications_frm) {
            jQuery('.preloader-custom-gif').show();
            jQuery('.preloader ').show();
            var formData = new FormData(jQuery(wphrm_user_leave_applications_frm)['0']);
            formData.append('action', 'WPHRMUserLeaveApplicationsInfo');
            var ajax_url = ajaxurl;
            jQuery.ajax({
                method: "POST",
                url: ajax_url,
                contentType: false,
                cache: false,
                processData: false,
                data: formData,
                success: function (output) {
                    var data = JSON.parse(output);
                    if (data.success) {
                        jQuery('.preloader-custom-gif').hide();
                        jQuery('.preloader ').hide();
                        jQuery('#wphrm_add_leave_applications_success').removeClass('display-hide');
                        window.setTimeout(function () {
                            window.location.reload();
                        }, 500);
                    } else {
                        jQuery('.preloader-custom-gif').hide();
                        jQuery('.preloader ').hide();
                        jQuery('#wphrm_add_leave_applications_error').html(data.errors);
                        jQuery('#wphrm_add_leave_applications_error').removeClass('display-hide');
                        jQuery("#wphrm_add_leave_applications_error").css('color', 'red');
                    }
                }
            });
        }
    });
}

/** for edit Massages  modul **/
function edit_messages(id, title, desc) {
    jQuery("#wphrm_messages_id").val(id);
    jQuery("#wphrm_messages_title").val(title);
    jQuery("#wphrm_messages_desc").val(desc);
    var wphrmAllMessagesInfo_form = jQuery("#wphrmAllMessagesInfo_form");
    wphrmAllMessagesInfo_form.validate({
        wphrm_messages_title: {
            required: true,
        }
        , wphrm_messages_desc: {
            required: true,
        },
        submitHandler: function (wphrmAllMessagesInfo_form) {
            jQuery('.preloader-custom-gif').show();
            jQuery('.preloader ').show();
            var formData = new FormData(jQuery(wphrmAllMessagesInfo_form)['0']);
            formData.append('action', 'WPHRMAllMessagesInfo');
            var ajax_url = ajaxurl;
            jQuery.ajax({
                method: "POST",
                url: ajax_url,
                contentType: false,
                cache: false,
                processData: false,
                data: formData,
                success: function (output) {
                    var data = JSON.parse(output);
                    if (data.success) {
                        jQuery('.preloader-custom-gif').hide();
                        jQuery('.preloader ').hide();
                        jQuery('#wphrmAllMessagesInfo_success').removeClass('display-hide');
                        window.setTimeout(function () {
                            window.location.reload();
                        }, 500);
                    } else {
                        jQuery('.preloader-custom-gif').hide();
                        jQuery('.preloader ').hide();
                        jQuery('#wphrmAllMessagesInfo_error').removeClass('display-hide');
                        jQuery("#wphrmAllMessagesInfo_error").css('color', 'red');
                    }
                }
            });
        }
    });
}

/** wphrm Months **/
function wphrm_month(val, key) {
    var dataId = key;
    jQuery('.month-data').val(dataId);
    jQuery(".month_holidays").empty();
    jQuery('.preloader-custom-gif').show();
    jQuery('.preloader ').show();
    var wphrm_year = jQuery(".yeardata").val();
    var ajax_url = ajaxurl;
    wphrm_holiday_data = {
        'action': 'WPHRMHolidayMonthWise',
        'holiday_month': val,
        'wphrm_year': wphrm_year,
    }
    jQuery.post(ajax_url, wphrm_holiday_data, function (response) {
        var data = JSON.parse(response);
        if (data != '') {
            jQuery('.preloader-custom-gif').hide();
            jQuery('.preloader ').hide();
            jQuery(".month_holidays").html(data.wphrmHolidayMonth);
        } else {
            jQuery('.preloader-custom-gif').hide();
            jQuery('.preloader ').hide();
            jQuery(".month_holidays").html("No data Found.");
        }
    });
}

/* for costom delete js */
function WPHRMCustomDelete(id, tablename, filed_name) {
    jQuery('#deleteModal').appendTo("body").modal('show');
    jQuery('#info').html(WPHRMCustomJS.Deletemsg);
    jQuery("#delete").click(function () {
        var ajax_url = ajaxurl;
        jQuery('.preloader-custom-gif').show();
        jQuery('.preloader ').show();
        wphrm_holiday_data = {
            'action': 'WPHRMCustomDelete',
            'WPHRMCustomDelete_id': id,
            'table_name': '' + tablename + '',
            'filed_name': '' + filed_name + ''
        }
        jQuery.post(ajax_url, wphrm_holiday_data, function (response) {
            var data = JSON.parse(response);
            if (data.success) {
                jQuery('.preloader-custom-gif').hide();
                jQuery('.preloader ').hide();
                jQuery('#WPHRMCustomDelete_success').removeClass('display-hide');
                window.location.reload();
            } else {
                jQuery('#WPHRMCustomDelete_error').html(data.errors);
                jQuery('#WPHRMCustomDelete_error').removeClass('display-hide');
                jQuery('.preloader-custom-gif').hide();
                jQuery('.preloader ').hide();
            }
        });
    })
}

/* for attendance Reports */
function showReport() {
    var month = jQuery("#monthSelect").val();
    var year = jQuery("#yearSelect").val();
    var employeeID = jQuery("#employee_id").val();
    var ajax_url = ajaxurl;
    wphrm_attendancereport_data = {
        'action': 'WPHRMEmployeeAttendanceReports',
        'employee_id': employeeID,
        'month': month,
        'year': year,
    }
    jQuery.post(ajax_url, wphrm_attendancereport_data, function (data) {
        var response = JSON.parse(data);
        if (response.success == "success") {
            jQuery('#attendanceReport').html(response.Working);
            jQuery('#attendancePerReport').html(response.PerReport);
        }
    });
}

/* for change month */
function changeMonthYear() {
    var month = jQuery("#monthSelect").val();
    var year = jQuery("#yearSelect").val();
    jQuery('#Attendance_Calendar').fullCalendar('gotoDate', year + '-' + month + '-01');
    showReport();
}


/* for remove salary earning and deduction details  */
function earning_remove(data_label, data_value, data_id, g_month, g_year) {
    var wphrm_emp_label = data_label;
    var wphrm_emp_value = data_value;
    var wphrm_employeeOther_id = data_id;
    var wphrm_generate_month = g_month;
    var wphrm_generate_year = g_year;
    function wphrm_earning_remove_validate() {
        var valid = true;
        if (wphrm_emp_label == "") {
            valid = false;
        }
        if (wphrm_employeeOther_id == "") {
            valid = false;
        }
        if (wphrm_emp_value == "") {
            valid = false;
        }
        return valid;
    }
    var ajax_url = ajaxurl;
    wphrm_data = {
        'action': 'WPHRMRemoveEarning',
        'wphrm_employeeOther_id': wphrm_employeeOther_id,
        'wphrm_emp_label': wphrm_emp_label,
        'wphrm_emp_value': wphrm_emp_value,
        'wphrm_generate_month': wphrm_generate_month,
        'wphrm_generate_year': wphrm_generate_year,
    }
    if (wphrm_earning_remove_validate()) {
        jQuery.post(ajax_url, wphrm_data, function (response) {
            var data = JSON.parse(response);
            if (data.success) {
                window.location.reload();
            }
        });
    }
}

/* for remove Satting earning and deduction details  */
function deleteEarningAndDedutions(datalabel) {
    jQuery('.removefiled' + datalabel).remove();
}

/* for redirect page*/
function redirect_to() {
    var employee = jQuery('#changeEmployee').val();
    var ajax_url = ajaxurl + '?page=wphrm-view-attendance&employee_id=' + employee;
    var url = ajax_url.replace('/admin-ajax', '/admin');
    window.location.href = url;
}


function showHide(id) {
    jQuery('#leaveTypeLabel').show(100);
    jQuery('#reasonLabel').show(100);
    if (jQuery('#checkbox' + id + ':checked').val() == 'on') {
        jQuery('#leaveType' + id).hide(1000);
        jQuery('#reason' + id).hide(1000);
    } else {
        jQuery('#leaveType' + id).show(100);
        jQuery('#reason' + id).show(500);
    }
}
function showHide_permissions(id) {
    if (jQuery('#checkbox' + id + ':checked').val() != 'on') {
        jQuery('.checkbox' + id).hide(1000);
    } else {
        jQuery('.checkbox' + id).show(800);
    }
}
function halfDayToggle(id, value) {
    if (value == 'half day') {
        jQuery('#halfDayLabel').show(100);
        jQuery('#halfLeaveType' + id).show(100);
    } else {
        jQuery('#halfLeaveType' + id).hide(100);
    }
}
jQuery(function () {
    var $dTable = jQuery("#wphrmDataTable").dataTable();
    $dateControls = jQuery("#baseDateControl").children("div").clone();
    jQuery("#feedbackTable_filter").prepend($dateControls);
    jQuery("#to-date").change(function () {
        jQuery.fn.dataTableExt.afnFiltering.push(
                function (oSettings, aData, iDataIndex) {
                    var dateStart = parseDateValue(jQuery("#from-date").val());
                    var dateEnd = parseDateValue(jQuery("#to-date").val());
                    var evalDate = parseDateValue(aData[3]);
                    if ((evalDate >= dateStart) && (evalDate <= dateEnd)) {
                        return true;
                    } else {
                        return false;
                    }
                }
        );
        function parseDateValue(rawDate) {
            var dateArray = rawDate.split("-");
            var parsedDate = dateArray[2] + dateArray[1] + dateArray[0];
            return parsedDate;
        }
        $dTable.fnDraw();
    });

    jQuery("#mainsearch").change(function () {
        jQuery.fn.dataTableExt.afnFiltering.push(
                function (oSettings, aData, iDataIndex) {
                    var mainsearch = jQuery('#mainsearch').val();
                    if (mainsearch == '') {
                        return true;
                    } else {
                        var evalDate = aData[3].toLowerCase().replace(/ /g, '-');
                        if (mainsearch == evalDate) {
                            return true;
                        } else {
                            return false;
                        }
                    }
                }
        );
        $dTable.fnDraw();
    });
});
function setDateActive() {
    var monthselect = jQuery('.month-data').val();
    var setYear = jQuery('.yeardata').val();
    jQuery.fn.datepicker.defaults.autoclose = true;
    jQuery('.date-picker-default').datepicker("setDate", new Date(setYear, monthselect));

}

function wphrmAddweekand() {
    var setYear = jQuery('.yeardata').val();
    jQuery('#wphrmyear').val(setYear);

}

function removedeductions(val) {
    jQuery('#deduction_scents' + val).remove();
}
function removeearning(val) {
    jQuery('#earning_scents' + val).remove();
}
function addHoliday(val) {
    jQuery('.addHoliday' + val).remove();
}
function  Bankfieldlabel(val) {
    jQuery('#Bankfieldlabel_scents' + val).remove();
}
function  Otherfieldlabel(val) {
    jQuery('#Otherfieldlabel_scents' + val).remove();
}
function  Salaryfieldlabel(val) {
    jQuery('#salaryfieldlabel_scents' + val).remove();
}
function  designationAdd(val) {
    jQuery('#designationID' + val).remove();
}
function  departmentAdd(val) {
    jQuery('#departmentID' + val).remove();
}
function  copyLocalAddresss() {
    var localAddresss = jQuery('#wphrm_employee_local_address').val();
    jQuery('#wphrm_employee_permanant_address').val(localAddresss);
}

function simple_tooltip(target_items, name){
jQuery(target_items).each(function(i){
		jQuery("body").append("<div class='"+name+"' id='"+name+i+"'><p>"+jQuery(this).attr('title')+"</p></div>");
		var my_tooltip = jQuery("#"+name+i);
		
		jQuery(this).removeAttr("title").mouseover(function(){
				my_tooltip.css({opacity:0.8, display:"none"}).fadeIn(400);
		}).mousemove(function(kmouse){
				my_tooltip.css({left:kmouse.pageX+15, top:kmouse.pageY+15});
		}).mouseout(function(){
				my_tooltip.fadeOut(400);				  
		});
	});
}


//  ** Tool tip fuction **//

(function(jQuery) {

    var defaults = {
        speed: 400,
        delay: 400
    };

    var config = {
        tooltip: 'customTooltip',
        arrowSize: 16
    };

   
    jQuery.fn.customTooltip = function() {
        // Initialize the plugin with the given arguments
        init.apply(this, arguments);

        return this;
    };

   
    function init(opts) {
        // Override default options with the custom ones
        var options = jQuery.extend({}, defaults, opts);

        // Save options for the current instance
        this.data(options);

        this.each(function(i, e) {
            animatecustomTooltip(jQuery(e), options);
        });
    }


    function animatecustomTooltip(selector, opts) {
        var tooltip = selector.find('.' + config.tooltip),
            delayHappened = false,
            timer;

        // Set the initial tooltip position
        setLeftOffset(selector, tooltip);
        setTopOffset(selector, tooltip);

        // Set again the position on resize
       jQuery(window).resize(function() {
            setLeftOffset(selector, tooltip);
            setTopOffset(selector, tooltip);
        });

        // Show and hide tooltip
        selector.on('mouseenter', function() {
            if (delayHappened === false) {
                timer = setTimeout(function() {
                    delayHappened = true;
                    showTooltip(tooltip, opts.speed);
                }, opts.delay);
            }
        }).on('mouseleave', function() {
            clearTimeout(timer);

            delayHappened = false;
            hideTooltip(tooltip, opts.speed);
        });
    }

   
    function setLeftOffset(selector, tooltip) {
        var leftOffset = 'auto';

        if (tooltip.hasClass('nt-right-top') || tooltip.hasClass('nt-right') || tooltip.hasClass('nt-right-bottom')) {
            leftOffset = selector.outerWidth() + config.arrowSize;
        }

        if (tooltip.hasClass('nt-left-top') || tooltip.hasClass('nt-left') || tooltip.hasClass('nt-left-bottom')) {
            leftOffset = '-' + (tooltip.outerWidth() + config.arrowSize);
        }

        if (tooltip.hasClass('nt-top') || tooltip.hasClass('nt-bottom')) {
            leftOffset = (selector.outerWidth() / 2) - (tooltip.outerWidth() / 2);
        }

        if (tooltip.hasClass('nt-top-right') || tooltip.hasClass('nt-bottom-right')) {
            leftOffset = selector.outerWidth() - tooltip.outerWidth();
        }

        if (tooltip.hasClass('nt-top-left') || tooltip.hasClass('nt-bottom-left')) {
            leftOffset = 0;
        }

        tooltip.css({
            left: leftOffset
        });
    }

    function setTopOffset(selector, tooltip) {
        var topOffset = 'auto';

        if (tooltip.hasClass('nt-top-left') || tooltip.hasClass('nt-top') || tooltip.hasClass('nt-top-right')) {
            topOffset = '-' + (selector.outerHeight() + tooltip.outerHeight());
        }
         if (tooltip.hasClass('nt-bottom-left') || tooltip.hasClass('nt-bottom') || tooltip.hasClass('nt-bottom-right')) {
            topOffset = selector.outerHeight() + config.arrowSize;
        }

        if (tooltip.hasClass('nt-right') || tooltip.hasClass('nt-left')) {
            topOffset = (selector.outerHeight() / 2) - (tooltip.outerHeight() / 2);
        }

        if (tooltip.hasClass('nt-left-bottom') || tooltip.hasClass('nt-right-bottom')) {
            topOffset = selector.outerHeight() - tooltip.outerHeight();
        }

        if (tooltip.hasClass('nt-left-top') || tooltip.hasClass('nt-right-top')) {
            topOffset = 0;
        }

       

        tooltip.css({
            top: topOffset
        });
    }

   
    function showTooltip(tooltip, speed) {
        tooltip.css({ visibility: 'visible' }).animate({
            opacity: 1
        }, speed);
    }

    
    function hideTooltip(tooltip, speed) {
        tooltip.animate({
            opacity: 0,
        }, speed, function() {
            tooltip.css({ visibility: 'hidden' });
        });
    }

})(jQuery);

 