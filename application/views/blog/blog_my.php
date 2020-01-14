<div class="container">

    <?php

    $en_all_11035 = $this->config->item('en_all_11035'); //MENCH PLAYER NAVIGATION
    $en_all_6201 = $this->config->item('en_all_6201'); //BLOG TABLE

    if(!$session_en){

        echo '<div style="padding:10px 0;"><a href="/signin?url=/blog" class="btn btn-blog montserrat">'.$en_all_11035[4269]['m_name'].'<span class="icon-block">'.$en_all_11035[4269]['m_icon'].'</span></a> to start blogging.</div>';

    } else {

        //LEFT
        //echo '<div class="pull-left">'.echo_menu(12343, 'btn-blog').'</div>';

        echo '<h1 class="pull-left inline-block blog"><span class="icon-block-xlg">' . $en_all_11035[4535]['m_icon'] . '</span>'.$en_all_11035[4535]['m_name'].'</h1>';


        //RIGHT
        echo '<div class="pull-right inline-block side-margin">';

            //Blog History
            echo '<a href="/ledger?ln_type_play_id='.join(',', $this->config->item('en_ids_12273')).'&ln_status_play_id='.join(',', $this->config->item('en_ids_7359')).'&ln_creator_play_id='.$session_en['en_id'].'" class="btn btn-blog btn-five icon-block-lg '.superpower_active(10964).'" style="padding-top:10px;" data-toggle="tooltip" data-placement="bottom" title="'.$en_all_11035[12215]['m_name'].'">'.$en_all_11035[12215]['m_icon'].'</a>';

            //Create Blog:
            echo '<a href="javascript:void(0);" onclick="$(\'#newBlogTitle\').focus()" data-toggle="modal" data-target="#addBlogModal" class="btn btn-blog btn-five icon-block-lg '.superpower_active(10939).'" style="padding-top:10px;" data-toggle="tooltip" data-placement="bottom" title="'.$en_all_11035[12214]['m_name'].'">'.$en_all_11035[12214]['m_icon'].'</a>';


        echo '</div>';
        echo '<div class="doclear">&nbsp;</div>';



        //List current blogs:
        $player_blogs = $this->READ_model->ln_fetch(array(
            'in_status_play_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Blog Statuses Active
            'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'ln_type_play_id' => 10573, //Blog Note Bookmarks
            'ln_parent_play_id' => $session_en['en_id'], //For this trainer
        ), array('in_child'), 0, 0, array('in_title' => 'ASC'));
        if(count($player_blogs)){

            echo '<div class="list-group">';
            foreach($player_blogs as $bookmark_in){
                echo echo_in_blog($bookmark_in);
            }
            echo '</div>';

        } else {

            //No bookmarks yet:
            echo '<div class="alert alert-warning">No blogs created yet</div>';

        }

        if(!superpower_assigned(10939)) {

            echo '<div style="padding:10px 0;"><a href="/'.config_var(10939).'" class="btn btn-blog montserrat">START BLOGGING <i class="fad fa-step-forward"></i></a></div>';

        }

    }
    ?>
</div>

<div class="modal fade" id="addBlogModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><?= $en_all_6201[4736]['m_icon'].' '.$en_all_6201[4736]['m_name'] ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="text" id="newBlogTitle" class="form-control" placeholder="<?= config_var(12352) ?>" />
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary"><?= $en_all_11035[12214]['m_icon'].' '.$en_all_11035[12214]['m_name'] ?></button>
            </div>
        </div>
    </div>
</div>