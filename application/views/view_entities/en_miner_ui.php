
<script>
    //Set global variables:
    var en_focus_filter = -1; //No filter, show all
    var en_focus_id = <?= $entity['en_id'] ?>;
    var en_focus_name = '<?= str_replace('\'', '’', $entity['en_name']) ?>';
</script>
<script src="/js/custom/entity-manage-js.js?v=v<?= $this->config->item('app_version') ?>"
        type="text/javascript"></script>


<div class="row">
    <div class="col-xs-6 cols">

        <?php
        //Parents
        echo '<h5><span class="badge badge-h"><i class="fas fa-sign-in-alt"></i> <span class="li-parent-count">' . count($entity['en__parents']) . '</span> Parent' . fn___echo__s(count($entity['en__parents'])) . '</span></h5>';
        echo '<div id="list-parent" class="list-group  grey-list">';
        foreach ($entity['en__parents'] as $en) {
            echo fn___echo_en($en, 2, true);
        }
        //Input to add new parents:
        echo '<div id="new-parent" class="list-group-item list_input grey-input">
                <div class="input-group">
                    <div class="form-group is-empty"><input type="text" class="form-control new-input algolia_search" data-lpignore="true" placeholder="Add Entity..."></div>
                    <span class="input-group-addon">
                        <a class="badge badge-secondary new-btn" href="javascript:void(0);" onclick="alert(\'Note: Either choose an option from the suggestion menu to continue\')">ADD</a>
                    </span>
                </div>
            </div>';

        echo '</div>';





        //Focused/current entity:
        echo '<h5 class="badge badge-h indent1"><i class="fas fa-at"></i> Entity</h5>';
        echo '<div id="entity-box" class="list-group indent1">';
        echo fn___echo_en($entity, 1);
        echo '</div>';




        //Children:
        echo '<div class="indent2"><table width="100%" style="margin-top:10px;"><tr>';
        echo '<td style="width: 100px;"><h5 class="badge badge-h"><i class="fas fa-sign-out-alt rotate90"></i> <span class="li-children-count">' . $entity['en__child_count'] . '</span> Children</h5></td>';
        
        //Count orphans IF we are in the top parent root:
        if ($this->config->item('en_primary_id') == $entity['en_id']) {
            $orphans_count = count($this->Database_model->fn___en_fetch(array(
                ' NOT EXISTS (SELECT 1 FROM table_ledger WHERE en_id=tr_en_child_id AND tr_status>=0) ' => null,
            ), array('skip_en__parents')));

            if ($orphans_count > 0) {
                echo '<td style="width:130px;">';
                echo '<span style="padding-left:8px; display: inline-block;"><a href="/entities/fn___en_orphans">' . $orphans_count . ' Orphans &raquo;</a></span>';
                echo '</td>';
            }
        }
        echo '<td style="text-align: right;"><div class="btn-group btn-group-sm" style="margin-top:-5px;" role="group">';

        //Fetch current count for each status from DB:
        $child_en_filters = $this->Database_model->fn___tr_fetch(array(
            'tr_en_parent_id' => $entity['en_id'],
            'tr_en_child_id >' => 0, //Any type of children is accepted
            'tr_status >=' => 0, //New+
            'en_status >=' => 0, //New+
        ), array('en_child'), 0, 0, array('en_status' => 'ASC'), 'COUNT(en_id) as totals, en_status', 'en_status');


        //Only show filtering UI if we find child entities with different statuses (Otherwise no need to filter):
        if (count($child_en_filters) > 0 && $child_en_filters[0]['totals'] < $entity['en__child_count']) {

            //Load status definitions:
            $status_index = $this->config->item('object_statuses');

            //Show fixed All button:
            echo '<a href="javascript:void(0)" onclick="u_load_filter_status(-1)" class="btn btn-default btn-secondary u-status-filter u-status--1" data-toggle="tooltip" data-placement="top" title="View all entities"><i class="fas fa-at"></i><span class="hide-small"> All</span> [<span class="li-children-count">' . $entity['en__child_count'] . '</span>]</a>';

            //Show each specific filter based on DB counts:
            foreach ($child_en_filters as $c_c) {
                $st = $status_index['en_status'][$c_c['en_status']];
                echo '<a href="#status-' . $c_c['en_status'] . '" onclick="u_load_filter_status(' . $c_c['en_status'] . ')" class="btn btn-default u-status-filter u-status-' . $c_c['en_status'] . '" data-toggle="tooltip" data-placement="top" title="' . $st['s_desc'] . '"><i class="' . $st['s_icon'] . '"></i><span class="hide-small"> ' . $st['s_name'] . '</span> [<span class="count-u-status-' . $c_c['en_status'] . '">' . $c_c['totals'] . '</span>]</a>';
            }

        }

        echo '</div></td>';
        echo '</tr></table></div>';


        echo '<div id="list-children" class="list-group grey-list indent2">';

        foreach ($entity['en__children'] as $en) {
            echo fn___echo_en($en, 2);
        }
        if ($entity['en__child_count'] > count($entity['en__children'])) {
            fn___echo_en_load_more(1, $this->config->item('en_per_page'), $entity['en__child_count']);
        }


        //Input to add new parents:
        echo '<div id="new-children" class="list-group-item list_input grey-input">
        <div class="input-group">
            <div class="form-group is-empty"><input type="text" class="form-control new-input algolia_search bottom-add" data-lpignore="true" placeholder="Add ' . stripslashes($entity['en_name']) . '"></div>
            <span class="input-group-addon">
                <a class="badge badge-secondary new-btn" href="javascript:tr_add(0,' . $entity['en_id'] . ', 0);">ADD</a>
            </span>
        </div>
    </div>';
        echo '</div>';

        ?>
    </div>

    <div class="col-xs-6 cols ">


        <div id="modifybox" class="fixed-box hidden" entity-id="0" entity-link-id="0">

            <h5 class="badge badge-h"><i class="fas fa-cog"></i> Modify</h5>
            <div style="text-align:right; font-size: 22px; margin:-32px 3px -20px 0;">
                <a href="javascript:void(0)" onclick="$('#modifybox').addClass('hidden')"><i
                            class="fas fa-times-circle"></i></a>
            </div>
            <div class="grey-box">


                <div class="row">
                    <div class="col-md-6">

                        <div class="title" style="margin-bottom:0; padding-bottom:0;">
                            <h4>
                                <i class="fal fa-fingerprint"></i> Entity Name
                                [<span style="margin:0 0 10px 0; font-size:0.8em;">
                            <span id="charNameNum">0</span>/<?= $this->config->item('en_name_max') ?>
                        </span>]
                            </h4>
                        </div>
                        <div class="inline-box">
                            <input type="text" id="en_name" value="" onkeyup="en_name_word_count()"
                                   maxlength="<?= $this->config->item('en_name_max') ?>" data-lpignore="true"
                                   placeholder="Name" class="form-control border">
                        </div>

                        <div class="title" style="margin-top:15px;"><h4><i class="fas fa-at"></i> Entity Settings
                            </h4></div>
                        <div class="inline-box">

                            <!-- Entity Icon -->
                            <div class="form-group label-floating is-empty"
                                 style="margin:1px 0 10px;">
                                <div class="input-group border" data-toggle="tooltip" title="Entity Icon" data-placement="top">
                                    <span class="input-group-addon addon-lean" style="color:#2f2739; font-weight: 300; padding-left:7px !important; padding-right:2px !important;"><i class="fas fa-at grey-at"></i></span>
                                    <input type="text" id="en_icon" value=""
                                           maxlength="<?= $this->config->item('en_name_max') ?>" data-lpignore="true" placeholder=""
                                           class="form-control">
                                </div>
                            </div>

                            <!-- Entity Status -->
                            <select class="form-control border" id="en_status" data-toggle="tooltip" title="Entity Status" data-placement="top">
                                <?php
                                foreach (fn___echo_status('en_status') as $status_id => $status) {
                                    echo '<option value="' . $status_id . '" title="' . $status['s_desc'] . '">' . $status['s_name'] . '</option>';
                                }
                                ?>
                            </select>

                        </div>

                    </div>
                    <div class="col-md-6">

                        <div class="li_component">

                            <div class="title"><h4><i class="fas fa-atlas"></i> Transaction Settings</h4></div>

                            <div class="inline-box">

                                <div class="li_component">
                                    <textarea class="form-control text-edit border msg" id="tr_content"
                                              onkeyup="tr_content_word_count()"
                                              maxlength="<?= $this->config->item('tr_content_max') ?>" data-lpignore="true"
                                              style="height:66px;">

                                    </textarea>
                                    <span style="margin:0 0 10px 0; font-size:0.8em;">
                                        <span id="chartr_contentNum">0</span>/<?= $this->config->item('tr_content_max') ?>
                                        & Link type detected as <b id="en_link_type_id"><i class="fas fa-atlas"></i> Naked</b>
                                    </span>
                                </div>

                                <div style="margin: 0 0 8px 0; padding: 0;">

                                </div>

                                <select class="form-control border" id="tr_status" data-toggle="tooltip" title="Transaction Status" data-placement="top">
                                    <?php
                                    foreach (fn___echo_status('tr_status') as $status_id => $status) {
                                        echo '<option value="' . $status_id . '" title="' . $status['s_desc'] . '">' . $status['s_name'] . '</option>';
                                    }
                                    ?>
                                </select>

                            </div>

                        </div>

                        <table width="100%" style="margin-top:10px;">
                            <tr>
                                <td style="text-align: right;"><span class="save_entity_changes"></span></td>
                                <td class="save-td"><a href="javascript:u_save_modify();" class="btn btn-secondary">Save</a></td>
                            </tr>
                        </table>

                    </div>

                </div>

            </div>

        </div>


        <div id="message-frame" class="fixed-box hidden" entity-id="">

            <h5 class="badge badge-h" data-toggle="tooltip"
                title="Message management can only be done using Intents. Entity messages are listed below for view-only"
                data-placement="bottom"><i class="fas fa-comment-dots"></i> Entity Messages <i class="fas fa-lock"></i>
            </h5>
            <div style="text-align:right; font-size: 22px; margin:-32px 3px -20px 0;">
                <a href="javascript:void(0)" onclick="$('#message-frame').addClass('hidden');$('#loaded-messages').html('');">
                    <i class="fas fa-times-circle"></i>
                </a>
            </div>
            <div class="grey-box">
                <div id="loaded-messages"></div>
            </div>

        </div>


        <?php $this->load->view('view_ledger/tr_actionplan_right_column'); ?>


    </div>
</div>