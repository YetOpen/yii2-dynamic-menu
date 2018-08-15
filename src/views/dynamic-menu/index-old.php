<?php
/**
 * Created by PhpStorm.
 * User: nicolai
 * Date: 5/23/18
 * Time: 11:37 AM
 */
$this->title = 'Dynamic Menu Creator';
?>


<?php
/**
 * Created by PhpStorm.
 * User: tudor
 */

?>
<div id="page-heading">
    <h1>System Sidebar Builder</h1>
    <div class="options">
        <div class="btn-toolbar">
            <button id="btnAddMenu" class="btn btn-default" ><i class="fa fa-plus-circle"></i><span class="hidden-sm"> Create New  </span></button>
        </div>
    </div>
</div>
<div class="row" id="panelRoles" style="display: none;">
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-body" id="rolep">
                <div class="tab-container tab-danger">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#forroles" data-toggle="tab">Select For Role</a></li>
                        <li class=""><a href="#forusers" data-toggle="tab">Select For User</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="forroles">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label class="col-md-3 control-label"
                                                   style="color: #ff5c27;"><?php echo Yii::t('mess', 'Role in System '); ?></label>
                                            <div class="col-md-6">
                                                <?php //echo CHtml::dropDownList('DocRole[dp_role_name]', 'dp_role_name', $roles, array('empty' => Yii::t('mess', '--- Selectati Role ---'), 'class' => 'col-md-12', 'style' => 'height:35px;')); //,'options' => array(18=>array('selected'=>true)) ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="tab-pane" id="forusers">
                            Acest compartiment este in dezvoltare ..
                        </div>
                    </div>
                </div>

            </div>
        </div>


    </div>
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-body" >
                <h3>Create New Sidebar Menu Visible For Role</h3>
                <h3 style="text-align: center; color: red;font-weight: bolder;" id="roleName"></h3>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="tab-container tab-danger" id="panelMenus">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#menusByRoles" data-toggle="tab"><i class="fa fa-list"></i> Generated Menus</a></li>
                <li class=""><a href="#menuView" data-toggle="tab"><i class="fa fa-eye"></i> View Menu</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="menusByRoles">
                    <!--                    <div class="row">-->
                    <!--                        -->
                    <!--                    </div>-->
                    <div class="table-responsive" style="overflow-y: scroll;overflow-x:hidden;  height:315px;">
                        <table class="table" style="margin-bottom: 0px;">
                            <thead>
                            <tr>
                                <th class="">ID</th>
                                <th class="">Role</th>
                                <th class="">Status</th>
                                <th class="">Version</th>
                                <th class="">Action</th>
                            </tr>
                            </thead>
                            <tbody class="selects" >
                            <?php
                            $models = false;
                            if($models){
                                foreach ($models as $item_m){
                                    ?>
                                    <tr class="tritem" data-id="<?php echo $item_m->id;?>">
                                        <td><?php echo $item_m->id;?></td>
                                        <td><?php echo $item_m->role;?></td>
                                        <td ><?php echo ($item_m->status==1)?'Active':'Not Active';?></td>
                                        <td><span class="label label-success"><?php echo $item_m->row_version;?></span></td>
                                        <td>

                                            <button id="" class="btn btn-sm btn-primary btnItemLoadData" data-menu="<?php echo $item_m->id;?>" data-role="<?php echo $item_m->role;?>" data-version="<?php echo $item_m->row_version;?>"> Load Data</button>
                                            <button id="" class="btn btn-sm btn-danger btnItemDetete" data-menu="<?php echo $item_m->id;?>"><i class="fa fa-trash"></i> </button>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            }

                            ?>
                            </tbody>
                            <tfoot>
                            <!--<tr class="active">
                                <td colspan="4" class="text-left">
                                    <label for="action" style="margin-bottom:0">Action </label>
                                    <select name="action">
                                        <option value="Edit">Edit</option>
                                        <option value="Aprove">Aprove</option>
                                        <option value="Move">Move</option>
                                        <option value="Delete">Delete</option>
                                    </select>
                                </td>
                            </tr>-->
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="tab-pane" id="menuView">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading clearfix"><h5 class="pull-left">Menu</h5>
                                    <div class="pull-right">
                                        <span class="label label-success" id="labelLoadedID">

                                        </span>
                                        <span class="label label-success" id="labelLoadedROLE">

                                        </span>
                                        <span class="label label-success" id="labelLoadedVERSION">

                                        </span>
                                        <!--<button id="btnReload" type="button" class="btn btn-default">
                                            <i class="glyphicon glyphicon-triangle-right"></i> Load Data</button>-->
                                    </div>
                                </div>
                                <div class="panel-body" id="cont">
                                    <ul id="myEditor" class="sortableLists list-group">
                                    </ul>
                                </div>
                                <div class="panel-footer">
                                    <button id="btnOut" type="button" class="btn btn-primary pull-right"><i class="glyphicon glyphicon-eye-open"></i>View Output</button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">Edit item
                    <span class="pull-right">
<!--                        <input type="checkbox" id="toggle-event" checked data-toggle="toggle" data-on="Internal" data-off="External" data-onstyle="success" data-width="100" >-->
                    </span>
            </div>
            <script>
                $(function() {
                    $('#toggle-event').change(function() {
                        $('#href').vall('Toggle: ' + $(this).prop('checked'))
                    })
                })
            </script>
            <div class="panel-body">
                <form id="frmEdit" class="form-horizontal">
                    <div class="form-group">
                        <label for="text" class="col-sm-2 control-label">Text</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <input type="text" class="form-control item-menu" name="text" id="text" placeholder="Text">
                                <div class="input-group-btn">
                                    <button type="button" id="myEditor_icon" class="btn btn-default" data-iconset="fontawesome"></button>
                                </div>
                                <input type="hidden" name="icon" class="item-menu">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="href" class="col-sm-2 control-label">URL</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control item-menu" id="href" name="href" placeholder="URL">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="target" class="col-sm-2 control-label">Target</label>
                        <div class="col-sm-10">
                            <select name="target" id="target" class="form-control item-menu">
                                <option value="_self">Self</option>
                                <option value="_blank">Blank</option>
                                <option value="_top">Top</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="title" class="col-sm-2 control-label">Tooltip</label>
                        <div class="col-sm-10">
                            <input type="text" name="title" class="form-control item-menu" id="title" placeholder="Tooltip">
                        </div>
                    </div>
                </form>
            </div>
            <div class="panel-footer">
                <button type="button" id="btnUpdate" class="btn btn-primary" disabled><i class="fa fa-refresh"></i> Update</button>
                <button type="button" id="btnAdd" class="btn btn-success"><i class="fa fa-plus"></i> Add</button>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <button id="btnClear" type="button" class="btn btn-warning"><i class="glyphicon glyphicon-trash"></i> Clear</button>
            <button id="btnSave" type="button" class="btn btn-success"><i class="glyphicon glyphicon-ok"></i> Save Data</button>
        </div>
        <div class="form-group">
            <textarea id="out" class="form-control" cols="50" rows="10"></textarea>
        </div>
    </div>
</div>


<hr>
<script src='/js/jquery-menu-editor.js'></script>
<script src='/js/iconset/iconset-fontawesome-4.2.0.min.js'></script>
<script src='/js/bootstrap-iconpicker.js'></script>
<script>
    jQuery(document).ready(function () {
        var strjson = '[{"href":"http://home.com","icon":"fa fa-home","text":"Home", "target": "_top", "title": "My Home"},{"icon":"fa fa-bar-chart-o","text":"Opcion2"},{"icon":"fa fa-cloud-upload","text":"Opcion3"},{"icon":"fa fa-crop","text":"Opcion4"},{"icon":"fa fa-flask","text":"Opcion5"},{"icon":"fa fa-map-marker","text":"Opcion6"},{"icon":"fa fa-search","text":"Opcion7","children":[{"icon":"fa fa-plug","text":"Opcion7-1","children":[{"icon":"fa fa-filter","text":"Opcion7-1-1"}]}]}]';
        var iconPickerOpt = {cols: 5, searchText: "Cauta icon...", labelHeader: '{0} de {1} Pags.', footer: false};
        var options = {
            hintCss: {'border': '1px dashed #13981D'},
            placeholderCss: {'background-color': 'gray'},
            opener: {
                as: 'html',
                close: '<i class="fa fa-minus"></i>',
                open: '<i class="fa fa-plus"></i>',
                openerCss: {'margin-right': '10px'},
                openerClass: 'btn btn-success btn-xs'
            }
        };
        var editor = new MenuEditor('myEditor', {listOptions: options, iconPicker: iconPickerOpt, labelEdit: 'Edit'});
        editor.setForm($('#frmEdit'));
        editor.setUpdateButton($('#btnUpdate'));

        $('#btnReload').on('click', function () {
            editor.setData(strjson);
        });
        $('#btnOut').on('click', function () {
            var str = editor.getString();
            $("#out").text(str);
        });
        $('#btnClear').on('click', function () {
            $("#out").empty();
        });
        $("#btnUpdate").click(function(){
            editor.update();
        });
        $('#btnAdd').click(function(){
            editor.add();
        });
        $("#DocRole_dp_role_name").on("change", function(e) {
            e.preventDefault();
            $('#roleName').empty();
            $('#roleName').html($(this).find(':selected').val());

            $('#myEditor').empty();
            $('#labelLoadedID').empty();
            $('#labelLoadedID').html('<b>Loaded ID: NEW</b>');
            $('#panelMenus a[href="#menuView"]').tab('show');
        });
        $('#btnAddMenu').click(function(e){
            e.preventDefault();
            $('#panelRoles').show();
            $(this).addClass('btn-success');
        });

        $('#btnSave').on("click",function (e) {
            e.preventDefault();
            var dataMenu = $("#out").val();
            var roleName = $("#roleName").text();
            //var id = $("#roleName").text();
            console.log(dataMenu);
            console.log(roleName);
            if(dataMenu !=='' && roleName !==''){
                $.ajax({
                    data: {role:roleName,data:dataMenu},
                    url: '',
                    dataType: 'json',
                    type: 'POST',
                    success:function(data) {
                        console.log(data);
                        //callback(data);
                    },
                    error:function(data) {
                        console.log(data);
                        //callback_error(data);
                    }
                });
            }else
                alert('Click View Output Button');
        });
        $(document).on("click",".btnItemLoadData",function (e) {
            var id = $(this).attr('data-menu');
            var role = $(this).attr('data-role');
            var version = $(this).attr('data-version');
            $("#roleName").empty();
            $("#roleName").text(role);
            $.ajax({
                data: {menuid:id},
                url: '',
                dataType: 'json',
                type: 'POST',
                success:function(data) {
                    editor.setData(data.menu);
                    $('#labelLoadedID').empty();
                    $('#labelLoadedROLE').empty();
                    $('#labelLoadedVERSION').empty();
                    $('#labelLoadedID').html('<b>Loaded ID:'+id+'</b>');
                    $('#labelLoadedROLE').html('<b>ROLE:'+role+'</b>');
                    $('#labelLoadedVERSION').html('<b>V.'+version+'</b>');
                    $('#panelMenus a[href="#menuView"]').tab('show');
                    console.log(data);
                    //callback(data);
                },
                error:function(data) {
                    console.log(data);
                    //callback_error(data);
                }
            });
        });
        $(document).on("click",".btnItemDetete",function (e) {
            var id = $(this).attr('data-menu');
            $.ajax({
                data: {menuid:id},
                url: '',
                dataType: 'json',
                type: 'POST',
                success:function(data) {

                    console.log(data);
                    //callback(data);
                },
                error:function(data) {
                    console.log(data);
                    //callback_error(data);
                }
            });
        });



    });
</script>