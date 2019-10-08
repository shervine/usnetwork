
//In milli seconds:
var fadeout_frequency = 1000;
var fadeout_speed = 21;
var updating_basic_stats = false;
var js_timeframe_en_id;
var js_direction_en_id;

$(document).ready(function () {

    //Update stats on load:
    update_basic_stats();

    //Continue updating basic stats:
    setInterval(update_basic_stats, fadeout_frequency);

});


//Update page count stats & refresh them visually once they change:
var update_basic_stats = function() {
    //your jQuery ajax code

    if(updating_basic_stats){
        return false;
    }

    //Now we're updating:
    updating_basic_stats = true;

    //Fetch latest stats:
    $.post("/trainer_app/basic_stats_all", {}, function (data) {

        //Updated Intents?
        if(data.intents.current_count != $('#stats_intents_box .current_count').html()){
            $('#stats_intents_box .current_count').html(data.intents.current_count).fadeOut(fadeout_speed).fadeIn(fadeout_speed);
        }

        //Updated Entities?
        if(data.entities.current_count != $('#stats_entities_box .current_count').html()){
            $('#stats_entities_box .current_count').html(data.entities.current_count).fadeOut(fadeout_speed).fadeIn(fadeout_speed);
        }

        //Updated Links?
        if(data.links.current_count != $('#stats_links_box .current_count').html()){
            $('#stats_links_box .current_count').html(data.links.current_count).fadeOut(fadeout_speed).fadeIn(fadeout_speed);
        }

        //Reload Tooltip again:
        $('[data-toggle="tooltip"]').tooltip();

        //We're done updating:
        updating_basic_stats = false;

    });

};


function leaderboard_filter_direction(en_id){
    js_direction_en_id = en_id;
    load_leaderboard();
}

function leaderboard_filter_timeframe(en_id){
    js_timeframe_en_id = en_id;
    load_leaderboard();
}


//Function that loads extra stats into view:
function load_extra_stats(object_id){

    //See state:
    var is_openning = $('#stats_' + object_id + '_box .load_stats_box').hasClass('hidden');

    //Toggle view every time:
    $('#stats_' + object_id + '_box .extra_stat_content').toggleClass('hidden');

    //Open or close?
    if(is_openning){

        //Show spinner:
        $('#stats_' + object_id + '_box .load_stats_box').removeClass('hidden').html('<div style="text-align: center;"><i class="fas fa-yin-yang fa-spin"></i> ' + echo_ying_yang() + '</div>');

        //Save the rest of the content:
        $.post("/trainer_app/extra_stats_" + object_id, {}, function (data) {

            if(object_id=='links'){

                //Load initial leaderboard:;
                js_timeframe_en_id = 7801; //This Week
                js_direction_en_id = 10589; //Input

                //Load leaderboard:
                load_leaderboard();
            }

            //Load data:
            $('#stats_' + object_id + '_box .load_stats_box').html(data);

            //Reload Tooltip again:
            $('[data-toggle="tooltip"]').tooltip();


        });

    }
}




function load_leaderboard(){

    //Show loader:
    $('#body_inject').html('<tr><td colspan="10"><div style="text-align: center;"><i class="fas fa-yin-yang fa-spin"></i> ' + echo_ying_yang() + '</div></td></tr>');

    //Remove all classes:
    $('.user-type-filter').removeClass('btn-third');

    //Highlight current classes:
    $('.setting-en-'+js_timeframe_en_id).addClass('btn-third');
    $('.setting-en-'+js_direction_en_id).addClass('btn-third');

    //Fetch latest stats:
    $.post("/trainer_app/load_leaderboard/"+js_direction_en_id+"/"+js_timeframe_en_id, {}, function (data) {

        $('#body_inject').html(data);

        //Highlight current classes (AGAIN, to fix loading bug):
        $('.setting-en-'+js_timeframe_en_id).addClass('btn-third');
        $('.setting-en-'+js_direction_en_id).addClass('btn-third');

        //Reload Tooltip again:
        $('[data-toggle="tooltip"]').tooltip();
    });
}


