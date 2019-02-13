function en_load_child_search() {

    $("#new-children .new-input").on('autocomplete:selected', function (event, suggestion, dataset) {

        tr_add(suggestion.en_id, 0, 0);

    }).autocomplete({hint: false, minLength: 3, keyboardShortcuts: ['a']}, [{

        source: function (q, cb) {
            algolia_en_index.search(q, {
                hitsPerPage: 7,
            }, function (error, content) {
                if (error) {
                    cb([]);
                    return;
                }
                cb(content.hits, content);
            });
        },
        templates: {
            suggestion: function (suggestion) {
                //If clicked, would trigger the autocomplete:selected above which will trigger the tr_add() function
                return echo_js_suggestion('en', suggestion, 0);
            },
            header: function (data) {
                if (!data.isEmpty) {
                    return '<a href="javascript:tr_add(0,' + en_focus_id + ',0)" class="suggestion"><span><i class="fal fa-plus-circle"></i> Create </span> <i class="fas fa-at"></i> ' + data.query + ' [as ' + en_focus_name + ']</a>';
                }
            },
            empty: function (data) {
                return '<a href="javascript:tr_add(0,' + en_focus_id + ',0)" class="suggestion"><span><i class="fal fa-plus-circle"></i> Create</span> <i class="fas fa-at"></i> ' + data.query + ' [as ' + en_focus_name + ']</a>';
            },
        }
    }]).keypress(function (e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if ((code == 13) || (e.ctrlKey && code == 13)) {
            tr_add(0, en_focus_id);
            return true;
        }
    });
}


//Define file upload variables:
var upload_control = $(".inputfile");
var $input = $('.drag-box').find('input[type="file"]'),
    $label = $('.drag-box').find('label'),
    showFiles = function (files) {
        $label.text(files.length > 1 ? ($input.attr('data-multiple-caption') || '').replace('{count}', files.length) : files[0].name);
    };

$(document).ready(function () {



    //Lookout for intent link related changes:
    $('#tr_status').change(function () {
        if (parseInt($('#tr_status').find(":selected").val()) < 0) {
            //About to delete? Notify them:
            $('.notify_en_unlink').removeClass('hidden');
        } else {
            $('.notify_en_unlink').addClass('hidden');
        }
    });

    $('#en_status').change(function () {
        if (parseInt($('#en_status').find(":selected").val()) < 0) {
            //About to delete? Notify them:
            $('.notify_en_remove').removeClass('hidden');
        } else {
            $('.notify_en_remove').addClass('hidden');
        }
    });

    if (is_compact) {

        //Adjust columns:
        $('.cols').removeClass('col-xs-6').addClass('col-sm-6');
        $('.fixed-box').addClass('release-fixture');
        $('.dash').css('margin-bottom', '0px'); //For iframe to show better

    } else {

        //Adjust height of the messaging windows:
        $('.grey-box').css('max-height', (parseInt($(window).height()) - 130) + 'px');

        //Make editing frames Sticky for scrolling longer lists
        $(".main-panel").scroll(function () {
            var top_position = $(this).scrollTop();
            clearTimeout($.data(this, 'scrollTimer'));
            $.data(this, 'scrollTimer', setTimeout(function () {
                $(".fixed-box").css('top', (top_position - 0)); //PX also set in style.css for initial load
            }, 34));
        });
    }


    //Loadup various search bars:
    en_load_child_search();


    $("#new-parent .new-input").on('autocomplete:selected', function (event, suggestion, dataset) {
        tr_add(suggestion.en_id, 0, 1);
    }).autocomplete({hint: false, minLength: 3, keyboardShortcuts: ['a']}, [{
        source: function (q, cb) {
            algolia_en_index.search(q, {
                hitsPerPage: 7,
            }, function (error, content) {
                if (error) {
                    cb([]);
                    return;
                }
                cb(content.hits, content);
            });
        },
        templates: {
            suggestion: function (suggestion) {
                //If clicked, would trigger the autocomplete:selected above which will trigger the tr_add() function
                return echo_js_suggestion('en', suggestion, 0);
            },
            header: function (data) {
                if (!data.isEmpty) {
                    return '<a href="javascript:tr_add(0,' + en_focus_id + ',1)" class="suggestion"><span><i class="fal fa-plus-circle"></i> Create </span> <i class="fas fa-at"></i> ' + data.query + ' [as ' + en_focus_name + ']</a>';
                }
            },
            empty: function (data) {
                return '<a href="javascript:tr_add(0,' + en_focus_id + ',1)" class="suggestion"><span><i class="fal fa-plus-circle"></i> Create </span> <i class="fas fa-at"></i> ' + data.query + ' [as ' + en_focus_name + ']</a>';
            },
        }
    }]);





    //Watchout for file uplods:
    $('.drag-box').find('input[type="file"]').change(function () {
        fn___en_new_url_from_attachment(droppedFiles, 'file');
    });

    //Should we auto start?
    if (isAdvancedUpload) {

        $('.drag-box').addClass('has-advanced-upload');
        var droppedFiles = false;

        $('.drag-box').on('drag dragstart dragend dragover dragenter dragleave drop', function (e) {
            e.preventDefault();
            e.stopPropagation();
        })
            .on('dragover dragenter', function () {
                $('.en-has-tr').addClass('is-working');
            })
            .on('dragleave dragend drop', function () {
                $('.en-has-tr').removeClass('is-working');
            })
            .on('drop', function (e) {
                droppedFiles = e.originalEvent.dataTransfer.files;
                e.preventDefault();
                fn___en_new_url_from_attachment(droppedFiles, 'drop');
            });
    }



    //Do we need to auto load anything?
    if (window.location.hash) {
        var hash = window.location.hash.substring(1); //Puts hash in variable, and removes the # character
        var hash_parts = hash.split("-");
        if (hash_parts.length >= 2) {
            //Fetch level if available:
            if (hash_parts[0] == 'entitymessages') {
                fn___load_en_messages( hash_parts[1]);
            } else if (hash_parts[0] == 'loadmodify') {
                fn___en_modify_load(hash_parts[1], hash_parts[2]);
            } else if (hash_parts[0] == 'status') {
                //Update status:
                u_load_filter_status(hash_parts[1]);
            }
        }
    }



});

//Adds OR links authors and content for entities
function tr_add(en_existing_id, extra_en_parent_id=0, is_parent) {

    //if en_existing_id>0 it means we're linking to an existing entity, in which case en_new_name should be null
    //If en_existing_id=0 it means we are creating a new entity and then linking it, in which case en_new_name is required

    if (is_parent) {
        var input = $('#new-parent .new-input');
        var list_id = 'list-parent';
        var counter_class = '.li-parent-count';
    } else {
        var input = $('#new-children .new-input');
        var list_id = 'list-children';
        var counter_class = '.li-children-count';
    }


    var en_new_name = null;
    if (en_existing_id == 0) {
        en_new_name = input.val();
        if (en_new_name.length < 1) {
            alert('ERROR: Missing entity name or URL, try again');
            input.focus();
            return false;
        }
    }


    //Add via Ajax:
    $.post("/entities/ens_link", {

        en_id: en_focus_id,
        en_existing_id: en_existing_id,
        en_new_name: en_new_name,
        is_parent: (is_parent ? 1 : 0),
        extra_en_parent_id: extra_en_parent_id,

    }, function (data) {

        //Release lock:
        input.prop('disabled', false);

        if (data.status) {

            //Empty input to make it ready for next URL:
            input.focus();

            //Add new object to list:
            fn___add_to_list(list_id, '.en-item', data.en_new_echo);

            //Adjust counters:
            $(counter_class).text((parseInt($(counter_class + ':first').text()) + 1));
            $('.count-u-status-' + data.en_new_status).text((parseInt($('.count-u-status-' + data.en_new_status).text()) + 1));

            //Tooltips:
            $('[data-toggle="tooltip"]').tooltip();

        } else {
            //We had an error:
            alert('Error: ' + data.message);
        }

    });
}


function u_load_filter_status(new_val) {
    if (new_val >= -1 || new_val <= 3) {
        //Remove active class:
        $('.u-status-filter').removeClass('btn-secondary');
        //We do have a filter:
        en_focus_filter = parseInt(new_val);
        $('.u-status-' + new_val).addClass('btn-secondary');
        fn___en_load_next_page(0, 1);
    } else {
        alert('Invalid new status');
        return false;
    }
}

function en_name_word_count() {
    var len = $('#en_name').val().length;
    if (len > en_name_max) {
        $('#charNameNum').addClass('overload').text(len);
    } else {
        $('#charNameNum').removeClass('overload').text(len);
    }
}

function tr_content_word_count() {

    //Also update type:
    fn___update_link_type();

    var len = $('#tr_content').val().length;
    if (len > tr_content_max) {
        $('#chartr_contentNum').addClass('overload').text(len);
    } else {
        $('#chartr_contentNum').removeClass('overload').text(len);
    }
}


function fn___en_load_next_page(page, load_new_filter = 0) {

    if (load_new_filter) {
        //Replace load more with spinner:
        var append_div = $('#new-children').html();
        //The padding-bottom would remove the scrolling effect on the left side!
        $('#list-children').html('<span class="load-more" style="padding-bottom:500px;"><i class="fas fa-spinner fa-spin"></i></span>').hide().fadeIn();
    } else {
        //Replace load more with spinner:
        $('.load-more').html('<span class="load-more"><i class="fas fa-spinner fa-spin"></i></span>').hide().fadeIn();
    }

    $.post("/entities/fn___en_load_next_page", {
        page: page,
        parent_en_id: en_focus_id,
        en_focus_filter: en_focus_filter,
    }, function (data) {

        //Appending to existing content:
        $('.load-more').remove();

        if (load_new_filter) {
            $('#list-children').html(data + '<div id="new-children" class="list-group-item list_input grey-input">' + append_div + '</div>').hide().fadeIn();
            //Reset search engine:
            en_load_child_search();
        } else {
            //Update UI to confirm with user:
            $(data).insertBefore('#new-children');
        }

        //Tooltips:
        $('[data-toggle="tooltip"]').tooltip();
    });

}


function fn___update_link_type() {
    /*
     * Updates the type of link based on the link content
     *
     * */

    $('#en_link_type_id').html('<i class="fas fa-spinner fa-spin"></i> Loading...');


    //Fetch Intent Data to load modify widget:
    $.post("/entities/fn___update_link_type", {
        tr_content: $('#tr_content').val(),
        en_id: en_focus_id,
    }, function (data) {
        //All good, let's load the data into the Modify Widget...
        $('#en_link_type_id').html((data.status ? data.html_ui : 'Error: ' + data.message));

        if(data.status && data.en_link_preview.length > 0){
            $('#en_link_preview').html(data.en_link_preview);
        } else {
            $('#en_link_preview').html('');
        }

        //Reload Tooltip again:
        $('[data-toggle="tooltip"]').tooltip();
    });
}



function fn___en_modify_load(en_id, tr_id) {

    //Make sure inputs are valid:
    if (!$('.en___' + en_id).length) {
        alert('Error: Invalid Entity ID');
        return false;
    }

    //Update variables:
    $('#modifybox').attr('entity-link-id', tr_id);
    $('#modifybox').attr('entity-id', en_id);

    //Cannot be archived OR unlinked as this would not load, so remove them:
    $('.notify_en_remove, .notify_en_unlink').addClass('hidden');


    var en_full_name = $(".en_name_" + en_id + ":first").text();
    $('#en_name').val(en_full_name);
    $('.edit-header').html('<i class="fas fa-cog"></i> ' + en_full_name);
    $('#en_status').val($(".en___" + en_id + ":first").attr('entity-status'));
    $('#tr_status').val($(".en___" + en_id + ":first").attr('tr-status'));
    $('.save_entity_changes').html('');


    if (parseInt($('.en_icon_' + en_id).attr('en-is-set')) > 0) {
        $('.icon-demo').html($('.en_icon_' + en_id).html());
        $('#en_icon').val($('.en_icon_' + en_id).html());
    } else {
        //Clear out input:
        $('.icon-demo').html('<i class="fas fa-at grey-at"></i>');
        $('#en_icon').val('');
    }

    en_name_word_count();

    //Only show unlink button if not level 1
    if (parseInt(tr_id) > 0) {

        //Make the UI link and the notes in the edit box:
        $('.unlink-entity, .en-has-tr').removeClass('hidden');
        $('.en-no-tr').addClass('hidden');

        //Assign value:
        $('#tr_content').val($(".tr_content_val_" + tr_id + ":first").text());

        //Update count:
        tr_content_word_count();

    } else {

        //Hide the section and clear it:
        $('.unlink-entity, .en-has-tr').addClass('hidden');
        $('.en-no-tr').removeClass('hidden');

    }

    //Make the frame visible:
    $('.fixed-box').addClass('hidden');
    $("#modifybox").removeClass('hidden').hide().fadeIn();

    //We might need to scroll:
    if (is_compact) {
        $('.main-panel').animate({
            scrollTop: 9999
        }, 150);
    }
}

function fn___entity_link_form_lock(){
    $('#tr_content').prop("disabled", true).css('background-color','#CCC');

    $('.btn-save').addClass('grey').attr('href', '#').html('<i class="fas fa-spinner fa-spin"></i> Uploading');

}

function fn___entity_link_form_unlock(result){

    //What was the result?
    if (!result.status) {
        alert('ERROR: ' + result.message);
    }

    //Unlock either way:
    $('#tr_content').prop("disabled", false).css('background-color','#FFF');

    $('.btn-save').removeClass('grey').attr('href', 'javascript:fn___en_modify_save();').html('Save');

    //Tooltips:
    $('[data-toggle="tooltip"]').tooltip();

    //Replace the upload form to reset:
    upload_control.replaceWith( upload_control = upload_control.clone( true ) );
}


function fn___en_new_url_from_attachment(droppedFiles, uploadType) {

    //Prevent multiple concurrent uploads:
    if ($('.drag-box').hasClass('is-uploading')) {
        return false;
    }

    var current_value = $('#tr_content').val();
    if(current_value.length > 0){
        //There is something in the input field, notify the user:
        var r = confirm("Current transaction content [" + current_value + "] will be removed. Continue?");
        if (r == false) {
            return false;
        }
    }


    if (isAdvancedUpload) {

        //Lock message:
        fn___entity_link_form_lock();

        var ajaxData = new FormData($('.drag-box').get(0));
        if (droppedFiles) {
            $.each(droppedFiles, function (i, file) {
                var thename = $input.attr('name');
                if (typeof thename == typeof undefined || thename == false) {
                    var thename = 'drop';
                }
                ajaxData.append(uploadType, file);
            });
        }

        ajaxData.append('upload_type', uploadType);

        $.ajax({
            url: '/entities/fn___en_new_url_from_attachment',
            type: 'post',
            data: ajaxData,
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            complete: function () {
                $('.drag-box').removeClass('is-uploading');
            },
            success: function (data) {

                if(data.status){

                    //Add URL to input:
                    $('#tr_content').val( data.new__url );

                    //Update count:
                    tr_content_word_count();
                }

                //Unlock form:
                fn___entity_link_form_unlock(data);

            },
            error: function (data) {
                var result = [];
                result.status = 0;
                result.message = data.responseText;
                fn___entity_link_form_unlock(result);
            }
        });
    } else {
        // ajax for legacy browsers
    }
}


function fn___en_modify_save() {

    //Validate that we have all we need:
    if ($('#modifybox').hasClass('hidden') || !parseInt($('#modifybox').attr('entity-id'))) {
        //Oops, this should not happen!
        return false;
    }

    //Prepare data to be modified for this intent:
    var modify_data = {
        tr_id: parseInt($('#modifybox').attr('entity-link-id')),
        tr_content: $('#tr_content').val(),
        tr_status: $('#tr_status').val(),
        en_id: parseInt($('#modifybox').attr('entity-id')),
        en_name: $('#en_name').val(),
        en_status: $('#en_status').val(), //The new status (might not have changed too)
        en_icon: $('#en_icon').val(),
    };

    //Show spinner:
    $('.save_entity_changes').html('<span><i class="fas fa-spinner fa-spin"></i></span> Saving...').hide().fadeIn();


    $.post("/entities/fn___en_modify_save", modify_data, function (data) {

        if (data.status) {

            if(data.remove_from_ui){

                //need to remove this entity:
                //Intent has been either removed OR unlinked:
                if (modify_data['en_id'] == en_focus_id) {

                    //move up 1 level as this was the focus intent:
                    if($('.redirect_go_' + modify_data['en_id']).length){
                        window.location = "/entities/" + $('.redirect_go_' + modify_data['en_id'] + ':first').attr('entity-id');
                    } else {
                        window.location = "/entities/";
                    }

                } else {

                    //Remove Hash:
                    window.location.hash = '#';

                    //Remove from UI:
                    $('.tr_' + modify_data['tr_id']).html('<span style="color:#2f2739;"><i class="fas fa-trash-alt"></i> Removed</span>').fadeOut();

                    //Disappear in a while:
                    setTimeout(function () {

                        //Hide the editor & saving results:
                        $('.tr_' + modify_data['tr_id']).remove();

                        //Hide editing box:
                        $('#modifybox').addClass('hidden');

                    }, 377);

                }

            } else {

                //Reflect changed:
                //Update variables:
                $(".en_name_" + modify_data['en_id']).text(modify_data['en_name']);


                //Always update 2x Entity icons:
                $('.en_icon_ui_' + modify_data['en_id']).html(modify_data['en_icon']);
                $('.en_status_' + modify_data['en_id']).html('<span data-toggle="tooltip" data-placement="right" title="' + object_js_statuses['en_status'][modify_data['en_status']]["s_name"] + ': ' + object_js_statuses['en_status'][modify_data['en_status']]["s_desc"] + '">' + object_js_statuses['en_status'][modify_data['en_status']]["s_icon"] + '</span>');


                //Update other instances of the icon:
                var icon_is_set = ( modify_data['en_icon'].length > 0 ? 1 : 0 );
                $('.en_icon_' + modify_data['en_id']).attr('en-is-set' , icon_is_set );

                if(!icon_is_set){
                    //Set entity default icon:
                    modify_data['en_icon'] = '<i class="fas fa-at grey-at"></i>';
                    $('.en_icon_child_' + modify_data['en_id']).addClass('hidden');
                } else {
                    $('.en_icon_child_' + modify_data['en_id']).removeClass('hidden').html(modify_data['en_icon']);
                }
                $('.icon-demo').html(modify_data['en_icon']);



                //Did we have notes to update?
                if (modify_data['tr_id'] > 0) {

                    //Yes, update the notes:
                    $(".tr_content_" + modify_data['tr_id']).html(data.tr_content);
                    $(".tr_content_val_" + modify_data['tr_id']).text(data.tr_content_final);

                    //Did the content get modified? (Likely for a domain URL):
                    if(!(data.tr_content_final==modify_data['tr_content'])){
                        $("#tr_content").val(data.tr_content_final).hide().fadeIn('slow');
                    }


                    //Update 2x icons:
                    $('.tr_type_' + modify_data['tr_id']).html('<span class="tr_type_val" data-toggle="tooltip" data-placement="right" title="' + entity_links[data.js_tr_type_en_id]["m_name"] + ': ' + entity_links[data.js_tr_type_en_id]["m_desc"] + '">' + entity_links[data.js_tr_type_en_id]["m_icon"] + '</span>');

                    //Update status icon:
                    $('.tr_status_' + modify_data['tr_id']).html('<span class="tr_status_val" data-toggle="tooltip" data-placement="right" title="' + object_js_statuses['tr_status'][modify_data['tr_status']]["s_name"] + ': ' + object_js_statuses['tr_status'][modify_data['tr_status']]["s_desc"] + '">' + object_js_statuses['tr_status'][modify_data['tr_status']]["s_icon"] + '</span>');

                }

                if (modify_data['en_icon'].length > 0) {
                    $('.en_icon_ui_' + modify_data['en_id']).html(modify_data['en_icon']);
                    $('.en_icon_child_' + modify_data['en_id']).html(modify_data['en_icon']);
                } else {
                    //hide that section
                    $('.en_icon_ui_' + modify_data['en_id']).html('<i class="fas fa-at grey-at"></i>');
                    $('.en_icon_child_' + modify_data['en_id']).html('');
                }


                //Update entity timestamp:
                $('.save_entity_changes').html(data.message);

                //Reload Tooltip again:
                $('[data-toggle="tooltip"]').tooltip();
            }

        } else {
            //Ooops there was an error!
            $('.save_entity_changes').html('<span style="color:#FF0000;"><i class="fas fa-exclamation-triangle"></i> ' + data.message + '</span>').hide().fadeIn();
        }

    });

}



function fn___load_en_messages(en_id) {

    //Make the frame visible:
    $('.fixed-box').addClass('hidden');
    $("#message-frame").removeClass('hidden').hide().fadeIn().attr('entity-id', en_id);
    $("#message-frame h4").text($(".en_name_" + en_id + ":first").text());

    var handler = $("#loaded-messages");

    //Show tem loader:
    handler.html('<div style="text-align:center; padding:10px 0 50px;"><i class="fas fa-spinner fa-spin"></i></div>');

    //We might need to scroll:
    if (is_compact) {
        $('.main-panel').animate({
            scrollTop: 9999
        }, 150);
    }

    //Load the frame:
    $.post("/entities/fn___load_en_messages/"+en_id, {}, function (data) {
        //Empty Inputs Fields if success:
        handler.html(data);

        //Show inner tooltips:
        $('[data-toggle="tooltip"]').tooltip();

    });

}