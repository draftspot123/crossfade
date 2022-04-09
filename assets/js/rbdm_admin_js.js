jQuery(document).ready(function($) {

    let rbdm_ajax_url = plugin_ajax_object.ajax_url;

    let selected_users='';

    

    let discount_obj={

        id:'',

        users:selected_users,

        limited:0,

        type:'percent',

        type_value:0,

        free_shipping:0,

        products:'all',

        products_origin:'',

        cond_type:'quantity',

        cond_type_value:0,

        time_start:Date.now(),

        time_end:0,

        individual:0



    };



    function get_discount_object(){

        discount_obj.id=$('.rbdm_update_discount_modal_title').text();

        discount_obj.users=$('.rbdm_update_discount_users').val();

        discount_obj.limited=$('.rbdm_update_discount_limit').val();

        discount_obj.type=$('.rbdm_update_discount_type:checked').val();

        discount_obj.type_value=$('.rbdm_update_discount_type_value').val();

        discount_obj.free_shipping=$('.rbdm_update_discount_shipping:checked').val();

        discount_obj.products=$('.rbdm_update_discount_products_search_input').val(); 

        discount_obj.products_origin=$('.rbdm_update_discount_products_s_search_input').val(); 

        discount_obj.cond_type=$('.rbdm_update_discount_cond:checked').val(); 

        discount_obj.cond_type_value=$('.rbdm_update_discount_cond_value').val();  

        discount_obj.time_start=$('.rbdm_update_discount_date_from').val(); 

        discount_obj.time_end=$('.rbdm_update_discount_date_to').val(); 

        discount_obj.individual=$('.rbdm_update_discount_indiv_only:checked').val();

        return discount_obj;               

    }

    $('body').on('click','.rbdm_add_discount_submit',function(){

        get_discount_object();

        let discount_JSON=JSON.stringify(discount_obj);

        $.ajax({

            url:rbdm_ajax_url,

            type : 'POST',

            data: {

                action: "rbdm_add_discount_save",

                discount_JSON:discount_JSON

            },

            success: function(response) {

                if (response) {

                        $('.rbdm_update_discount_modal_wrapper').hide(3000);

                        rbdm_refresh_list();

                } else {

                    

                }

            }

        })

    })

    $('body').on('click','.rbdm_update_discount_submit',function(){

        get_discount_object()

        let discount_JSON=JSON.stringify(discount_obj);

        $.ajax({

            url:rbdm_ajax_url,

            type : 'POST',

            data: {

                action: "rbdm_update_discount_save",

                discount_JSON:discount_JSON

            },

            success: function(response) {

                if (response) {

                        $('.rbdm_update_discount_modal_wrapper').hide(3000);

                        rbdm_refresh_list();

                } else {

                    

                }

            }

        })

    })

    



    /*$('body').on('click','.rbdm_add_discount_btn',function(){

        $('.rbdm_add_discount_row_type').show(500);

    })

    $('body').on('change','.rbdm_add_discount_limit',function(){

        discount_obj.limited=$(this).val();

    })

    $('body').on('click','.rbdm_add_discount_type',function(){

        $('.rbdm_add_discount_type').closest('label').hide();

        $(this).closest('label').show();

        $('.rbdm_add_discount_type_value_label').show(500);

        $('.rbdm_add_discount_row_products').show(500);

        discount_obj.type=$(this).val();

        discount_obj.users=$('.rbdm-add-discount-modal-wrapper').attr('data-users');

        console.log(discount_obj);

    })*/

    $('body').on('keydown paste','.rbdm_add_discount_products_search',function(){

        $('input[name=rbdm_update_discount_products_all]').removeAttr('checked');

        $('.rbdm_update_discount_products_search_input').val('');

        let input_content=$(this).val();

        

        if(input_content.length>2){

            $.ajax({

                url:rbdm_ajax_url,

                type : 'POST',

                data: {

                    action: "rbdm_add_discount_product_search",

                    input_content:input_content

                },

                success: function(response) {

                    if (response) {

                        $('.rbdm_add_discount_products_search_result').show();

                        $('.rbdm_add_discount_products_search_result').html(response);

                        $('.rbdm_update_discount_products_search_result').html(response);

                    } else {}

                }

            }) 

        }

    })

    $('body').on('keydown paste','.rbdm_add_discount_products_s_search',function(){

        let input_content=$(this).val();

        if(input_content.length>2){

            $.ajax({

                url:rbdm_ajax_url,

                type : 'POST',

                data: {

                    action: "rbdm_add_discount_product_search",

                    input_content:input_content

                },

                success: function(response) {

                    if (response) {

                        

                        $('.rbdm_update_discount_products_s_search_result').html(response);

                    } else {}

                }

            }) 

        }

    })

    /*$('body').on('click','input[name=rbdm_add_discount_products_all]',function(){

        console.log('nnn');

        $('.rbdm_add_discount_products_search').val($(this).val());

        $('.rbdm_add_discount_products_search_input').val(0);

        $('.rbdm_add_discount_products_search_result').hide();

        $('.rbdm_add_discount_row_cond_modal').show(500);

        discount_obj.type_value=$('.rbdm_add_discount_type_value').val();

        discount_obj.products='all';

        console.log(discount_obj);

    })

    $('body').on('change','.rbdm_add_discount_products_search_result_list',function(){

        let product_ids=$(this).val().join(',');

        $('.rbdm_add_discount_products_search').val(product_ids);

        $('.rbdm_add_discount_products_search_input').val(product_ids);

        $('.rbdm_add_discount_products_search_result').hide();

        $('.rbdm_add_discount_row_cond_modal').show(500);

        discount_obj.type_value=$('.rbdm_add_discount_type_value').val();

        discount_obj.products=product_ids;

        console.log(discount_obj);

    })

    $('body').on('click','.rbdm_add_discount_cond',function(){

        $('.rbdm_add_discount_cond_value').show(500);

        discount_obj.cond_type=$(this).val();

        console.log(discount_obj);

    })

    $('body').on('change keyup paste','.rbdm_add_discount_cond_value',function(){

        $('.rbdm_add_discount_row_date_modal').show(500);

        discount_obj.cond_type_value=$(this).val();

        console.log(discount_obj);

    })

    $('body').on('change','.rbdm_add_discount_date_to',function(){

        $('.rbdm_add_discount_row_submit_modal').show(500);

        discount_obj.time_start=$('.rbdm_add_discount_date_from').val();

        discount_obj.time_end=$(this).val();

        discount_obj.cond_type_value=$('.rbdm_add_discount_cond_value').val();

        console.log(discount_obj);

    })

    $('body').on('change','.rbdm_add_discount_date_nolimit',function(){

        $('.rbdm_add_discount_row_submit_modal').show(500);

        discount_obj.time_start=$('.rbdm_add_discount_date_from').val();

        discount_obj.time_end=0;

    })

    $('body').on('change','.rbdm_add_discount_indiv_only',function(){

        discount_obj.individual=$(this).val();

    })*/

    



    $('body').on('click','.rb_list_discounts_content_table_action_delete',function(){

        let rbdm_coupon_id=$(this).attr('data-id');

        $.ajax({

            url:rbdm_ajax_url,

            type : 'POST',

            data: {

                action: "rbdm_discount_delete",

                rbdm_coupon_id:rbdm_coupon_id

            },

            success: function(response) {

                if (response) {

                    rbdm_refresh_list();

                } else {

                    

                }

            }

        })

    })

    $('body').on('click','.rb_list_discounts_content_table_action_update',function(){

        let rbdm_coupon_id=$(this).attr('data-id');

        $.ajax({

            url:rbdm_ajax_url,

            type : 'POST',

            data: {

                action: "rbdm_discount_update_form",

                rbdm_coupon_id:rbdm_coupon_id,rbdm_modal_type:'update'

            },

            success: function(response) {

                if (response) {

                    //let discount_obj=JSON.parse(response);

                    $('.rbdm_update_discount_modal_wrapper').html(response);

                    $('.rbdm_update_discount_modal_wrapper').removeClass('rbdm_hide');

                } else {

                    

                }

            }

        })

    })



    function rbdm_refresh_list(){

        $.ajax({

            url:rbdm_ajax_url,

            type : 'POST',

            data: {

                action: "rbdm_get_content_options_page"

            },

            success: function(response) {

                if (response) {

                    $('.rb_list_discounts_content').html(response);

                } else {

                    

                }

            }

        })

    }

/*init*/

    function GetURLParameter(sParam){

        var sPageURL = window.location.search.substring(1);

        var sURLVariables = sPageURL.split('&');

        for (var i = 0; i < sURLVariables.length; i++) 

        {

            var sParameterName = sURLVariables[i].split('=');

            if (sParameterName[0] == sParam) 

            {

                return sParameterName[1];

            }

        }

    }



    if(GetURLParameter('add-discount')){

        let selected_ids=GetURLParameter('add-discount').split('-');

        let modal_html='<div class="rbdm-add-discount-modal-wrapper" data-users='+selected_ids+'>';

            modal_html+='<span class="rbdm_coppyright">Discount Manager</span>';

            modal_html+='<h3 class="rbdm-add-discount-modal-title">Add Discount</h3>';

            modal_html+='<div class="rbdm-add-discount-modal-content">';

            modal_html+='</div>'; 

        modal_html+='</div>';

        $('body.users-php .wrap').prepend(modal_html);

        /*$.ajax({

            url:rbdm_ajax_url,

            type : 'POST',

            data: {

                action: "rbdm_add_discount_modal",

                selected_ids:selected_ids

            },

            success: function(response) {

                if (response) {

                        $('.rbdm-add-discount-modal-wrapper').show(500);

                        $('.rbdm-add-discount-modal-content').html(response);

                } else {

                    

                }

            }

        })*/

        $.ajax({

            url:rbdm_ajax_url,

            type : 'POST',

            data: {

                action: "rbdm_discount_update_form",

                rbdm_modal_type:'bulk',rbdm_selected_users:selected_ids

            },

            success: function(response) {

                if (response) {

                    $('.rbdm-add-discount-modal-wrapper').show(500);

                    $('.rbdm-add-discount-modal-content').html(response);

                } else {

                    

                }

            }

        })

    }

    /*$('body').on('click','.rbdm_add_discount_btn',function(){

        $('.rbdm_add_discount_row_users').toggleClass('rbdm_hide');

        console.log($('.rbdm_add_discount_row_users'));



    })*/

    $('body').on('click','.rbdm_add_discount_btn',function(){

        $.ajax({

            url:rbdm_ajax_url,

            type : 'POST',

            data: {

                action: "rbdm_discount_update_form",

                rbdm_modal_type:'add'

            },

            success: function(response) {

                if (response) {

                    $('.rbdm_update_discount_modal_wrapper').show(500);

                    $('.rbdm_update_discount_modal_wrapper').html(response);

                    $('.rbdm_update_discount_modal_wrapper').removeClass('rbdm_hide');

                } else {

                    

                }

            }

        })



    })

    $('body').on('click','.rbdm_update_discount_modal_delete',function(){

        $('.rbdm_update_discount_modal_wrapper').hide(500);

        $('.rbdm_update_discount_modal_wrapper').addClass('rbdm_hide');

    })

    $('body').on('keydown paste','.rbdm_add_discount_users_search',function(){//add

        $('input[name=rbdm_add_discount_users_all]').removeAttr('checked');

        let input_content=$(this).val();

        if(input_content.length>2){

            $.ajax({

                url:rbdm_ajax_url,

                type : 'POST',

                data: {

                    action: "rbdm_add_discount_user_search",

                    input_content:input_content

                },

                success: function(response) {

                    if (response) {

                        $('.rbdm_add_discount_users_search_result').show();

                        $('.rbdm_add_discount_users_search_result').html(response);

                    } else {}

                }

            }) 

        }

    })

    $('body').on('keydown paste','.rbdm_update_discount_add_user',function(){//update

        $('input[name=rbdm_add_discount_users_all]').removeAttr('checked');

        let input_content=$(this).val();

        if(input_content.length>2){

            $.ajax({

                url:rbdm_ajax_url,

                type : 'POST',

                data: {

                    action: "rbdm_add_discount_user_search",

                    input_content:input_content

                },

                success: function(response) {

                    if (response) {

                        $('.rbdm_user_search_result').show();

                        $('.rbdm_user_search_result').html(response);

                    } else {}

                }

            }) 

        }

    })

    $('body').on('click','input[name=rbdm_update_discount_products_all]',function(){//update

        

        $('.rbdm_update_discount_products_search_input').val('');

        $('.rbdm_update_discount_modal_products_left').text('All Products');

        

    })

    $('body').on('change','.rbdm_add_discount_users_search_result_list',function(){//add

        let user_ids=$(this).val().join(',');

        $('.rbdm_add_discount_users_search').val(user_ids);

        $('.rbdm_add_discount_users_search_input').val(user_ids);

        

        $('.rbdm_add_discount_users_search_result').hide();

       

        

       /* $('.rbdm_add_discount_row_cond_modal').show(500);*/

       discount_obj.users=user_ids;

       rbdm_get_discount_form(user_ids);

    })

    $('body').on('change','.rbdm_update_discount_modal_wrapper .rbdm_add_discount_users_search_result_list',function(){//update

        let user_ids=$(this).val().join(',');

        $('.rbdm_update_discount_add_user').val(''); //update

        $('.rbdm_user_search_result').empty();

        let old_users=$('.rbdm_update_discount_users').val().split(',');

        let curr_users=user_ids.split(',');

        let all_users=old_users.concat(curr_users);

        all_users = all_users.filter(item => item);

        all_users=all_users.filter(onlyUnique);

        $('.rbdm_update_discount_users').val(all_users.join(','));

        $.ajax({

            url:rbdm_ajax_url,

            type : 'POST',

            data: {

                action: "rbdm_get_selected_users",

                selected_ids:all_users.join(',')

            },

            success: function(response) {

                if (response) {

                        $('.rbdm_update_discount_modal_user_left').html(response);

                } else {

                    

                }

            }

        })





    }) 

    $('body').on('change','.rbdm_update_discount_modal_products .rbdm_add_discount_products_search_result_list',function(){//update

        let products=$(this).val()

        let product_ids=products.join(',');

        

        $('.rbdm_add_discount_products_search').val(''); //update

        $('.rbdm_update_discount_products_search_result').empty();

        let old_products=$('.rbdm_update_discount_products_search_input').val().split(',');

        let curr_products=product_ids.split(',');

        let all_products=old_products.concat(curr_products);

        all_products = all_products.filter(item => item);

        all_products=all_products.filter(onlyUnique);

        console.log(all_products);

        $('.rbdm_update_discount_products_search_input').val(all_products.join(','));

        $.ajax({

            url:rbdm_ajax_url,

            type : 'POST',

            data: {

                action: "rbdm_get_selected_products",

                selected_ids:all_products.join(',')

            },

            success: function(response) {

                if (response) {

                        $('.rbdm_update_discount_modal_products_left').html(response);

                } else {

                    

                }

            }

        })

    })

    $('body').on('change','.rbdm_update_discount_modal_condition .rbdm_add_discount_products_search_result_list',function(){//update

        let product_ids=$(this).val().join(',');

       

        $('.rbdm_add_discount_products_search').val(''); //update

        $('.rbdm_update_discount_products_s_search_result').empty();

        let old_products=$('.rbdm_update_discount_products_s_search_input').val().split(',');

        let curr_products=product_ids.split(',');

        let all_products=old_products.concat(curr_products);

        all_products = all_products.filter(item => item);

        all_products=all_products.filter(onlyUnique);

        

        $('.rbdm_update_discount_products_s_search_input').val(all_products.join(','));

        $.ajax({

            url:rbdm_ajax_url,

            type : 'POST',

            data: {

                action: "rbdm_get_selected_products",

                selected_ids:all_products.join(',')

            },

            success: function(response) {

                if (response) {

                        $('.rbdm_update_discount_products_s_search_list').html(response);

                } else {

                    

                }

            }

        })

    })

    function onlyUnique(value, index, self) {

        return self.indexOf(value) === index;

      }



    $('body').on('click','.rbdm_update_discount_user .rbdm_update_discount_remove',function(){

        let user_id=$(this).attr('data-id');

        $(this).closest('.rbdm_update_discount_user').remove();

        let users_str=$('.rbdm_update_discount_users').val();

        let users_array=users_str.split(',')

        const index = users_array.indexOf(user_id);

        if (index > -1) {

            users_array.splice(index, 1);

        }

        $('.rbdm_update_discount_users').val(users_array.join(','));

        if(users_array.length==0){

           $('.rbdm_update_discount_modal_user_left').text('All Users'); 

        }



    })

    $('body').on('click','.rbdm_update_discount_modal_products_left .rbdm_update_discount_remove',function(){

        let product_id=$(this).attr('data-id');

        $(this).closest('.rbdm_update_discount_product').remove();

        let product_str=$('.rbdm_update_discount_products_search_input').val();

        let products_array=product_str.split(',')

        const index =products_array.indexOf(product_id);

        if (index > -1) {

            products_array.splice(index, 1);

        }

        $('.rbdm_update_discount_products_search_input').val(products_array.join(','));

    })

    $('body').on('click','.rbdm_update_discount_modal_condition .rbdm_update_discount_remove',function(){

        let product_id=$(this).attr('data-id');

        $(this).closest('.rbdm_update_discount_product').remove();

        let product_str=$('.rbdm_update_discount_products_s_search_input').val();

        let products_array=product_str.split(',')

        const index =products_array.indexOf(product_id);

        if (index > -1) {

            products_array.splice(index, 1);

        }

        $('.rbdm_update_discount_products_s_search_input').val(products_array.join(','));

    })



    function rbdm_get_discount_form(user_ids){

        let modal_html='<div class="rbdm-add-discount-modal-wrapper" data-users='+user_ids+'>';

            modal_html+='<div class="rbdm-add-discount-modal-content">';

            modal_html+='</div>'; 

            modal_html+='</div>';

        $('body.toplevel_page_rbdm-options .rbdm_add_discount_form_wrapper').html(modal_html);

        $.ajax({

            url:rbdm_ajax_url,

            type : 'POST',

            data: {

                action: "rbdm_add_discount_modal",

                selected_ids:user_ids

            },

            success: function(response) {

                if (response) {

                        $('.rbdm-add-discount-modal-wrapper').show(500);

                        $('.rbdm-add-discount-modal-content').html(response);

                } else {

                    

                }

            }

        })

    }





    /*crossfade*/

    $('body').on('click','.rbdm_add_rule_submit',function(){

        let product_s=$('#rbdm_cross_source_product').val();

        let quantity_s=$('.rbdm_cross_source_qty').val();

        let product_t=$('#rbdm_cross_target_product').val();

        let quantity_t=$('.rbdm_cross_target_qty').val();

        let discount_type=$('.rbdm_cross_discount_type:checked').val();

        let discount_value=$('.rbdm_cross_discount_value').val();

        $.ajax({

            url:rbdm_ajax_url,

            type : 'POST',

            data: {

                action: "rbdm_add_rule",

                product_s:product_s,quantity_s:quantity_s,product_t:product_t,quantity_t:quantity_t,discount_type:discount_type,discount_value:discount_value,

            },

            success: function(response) {

                if (response) {

                        $('.rb_list_rule_content_table_body ').html(response);

                } else {

                    

                }

            }

        })

    })

    $('body').on('click','.rb_list_rule_content_table_row_delete',function(){

        let res=confirm('Really dele rule?');

        if(res){

            let rule_id=$(this).attr('data-id');

            $.ajax({

                url:rbdm_ajax_url,

                type : 'POST',

                data: {

                    action: "rbdm_delete_rule",

                    rule_id:rule_id,

                },

                success: function(response) {

                    if (response) {

                            $('.rb_list_rule_content_table_body ').html(response);

                    } else {

                        

                    }

                }

            })  

        }

    })



    $('body').on('click','.rbdm_add_rule_btn',function(){

        $('.rbdm_form_rule_wrapper').toggleClass('rbdm_hide');

    })

    let rbdm_orders_date;

    $('body').on('change','.rbdm_cross_orders_date',function(){

        rbdm_orders_date=$(this).val();

        $('input[name=rbdm_cross_orders_date_radio]').removeAttr('checked');

    })

    $('body').on('click','.rbdm_cross_orders_date_all',function(){

        rbdm_orders_date='2000-01-01';

        $('.rbdm_cross_orders_date').val(rbdm_orders_date);

    })

    $('body').on('click','.rbdm_cross_orders_date_null',function(){

        rbdm_orders_date=$('.rbdm_cross_orders_date_wrapper').attr('data-now');

        $('.rbdm_cross_orders_date').val(rbdm_orders_date);

    })

    $('body').on('click','.rbdm_orders_submit_btn',function(){

        $.ajax({

            url:rbdm_ajax_url,

            type : 'POST',

            data: {

                action: "rbdm_upload_orders",

                rbdm_orders_date:rbdm_orders_date,

            },

            beforeSend: function() {

                $(".rbdm_preload_wrapper").toggleClass('rbdm_hide');

             },

            success: function(response) {

                if (response) {

                        $(".rbdm_preload_wrapper").addClass('rbdm_hide');

                        $(".rbdm_orders_rule_notices").text('Options saved successfully!')

                } else {

                    

                }

            }

        })

    })

    

})
