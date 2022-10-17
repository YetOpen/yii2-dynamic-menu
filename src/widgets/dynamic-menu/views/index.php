<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\web\JsExpression;
use yii\bootstrap\Modal;
use yii\bootstrap\ActiveForm;
use backend\modules\yii2rbac\RbacHelper;

//use \backend\modules\rbac\helpers\RbacHelper;

$this->title = 'Dynamic Menu Creator';
$this->params['breadcrumbs'][] = ['label' => 'Dynamic Menu', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Creator';

esempla\dynamicmenu\DynamicMenuAsset::register(Yii::$app->view);
yii2mod\alert\AlertAsset::register(Yii::$app->view);

$roles = Yii::$app->authManager->getRoles();

/**
 * Create an array of role names from role objects.
 */
$rolesOptions = array_map(function($role){
    return $role->name;
}, $roles);


?>
<div class="row">
    <div class="col-md-3">
        <?php
        Modal::begin([
            'options' => [
                'id' => 'role-modal',
                'tabindex' => false // important for Select2 to work properly
            ],
            'header' => '<h4 style="margin:0; padding:0">Select Role </h4>',
            'toggleButton' => ['label' => 'Create New Menu', 'class' => 'btn btn-success','style' => 'margin-bottom:5px;'],
        ]);
        echo Select2::widget([
            'name' => 'role_menu',
            'data' => $rolesOptions,
            'options' => ['placeholder' => 'Select a role ...'],
            'pluginOptions' => [
                'allowClear' => true
            ],
            'pluginEvents' => [
                "change" => "function() {
                    $('#roleName').empty();
                    $('#roleNameHead').empty();
                    $('#roleName').html($(this).find(':selected').val());
                    $('#roleNameHead').html('NEW MENU FOR ROLE : '+$(this).find(':selected').val()).show();
                    $('#myEditor').empty();
                    $('#labelLoadedID').empty();
                    $('#labelLoadedID').html('Loaded ID: NEW');
                    $('#panelMenus a[href=\"#menuView\"]').tab('show');
                    $('#role-modal').modal('hide');
                }",
            ]
        ]);
        Modal::end();

        ?>
    </div>
    <div class="col-md-9">
        <h1 id="roleNameHead" class="text-red"></h1>
        <span id="roleName" style="display: none;"></span>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">Edit item</div>
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
                    <div class="form-group">
                        <label for="visible_condition" class="col-sm-2 control-label">Visibility condition</label>
                        <div class="col-sm-10">
                            <input type="text" name="visible_condition" class="form-control item-menu" id="visible_condition" placeholder="Visibility condition">
                        </div>
                    </div>
                </form>
            </div>
            <div class="panel-footer">
                <button type="button" id="btnUpdate" class="btn btn-primary" disabled><i class="fa fa-refresh"></i> Update</button>
                <button type="button" id="btnAdd" class="btn btn-success"><i class="fa fa-plus"></i> Add</button>
            </div>
        </div>
        <div class="form-group">
            <button id="btnClear" type="button" class="btn btn-warning"><i class="glyphicon glyphicon-trash"></i> Clear</button>
            <button id="btnSave" type="button" class="btn btn-success" data-loading-text="Saving..."><i class="glyphicon glyphicon-ok"></i> Save</button>
        </div>
        <div class="form-group">
            <textarea id="out" class="form-control" cols="50" rows="10"></textarea>
        </div>
    </div>
    <div class="col-md-6">
        <div class="box">
            <div class="box-body">
                <div class="tab-container tab-danger" id="panelMenus">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#menusByRoles" data-toggle="tab"><i class="fa fa-list"></i>List Generated Menus</a></li>
                        <li class=""><a href="#menuView" data-toggle="tab"><i class="fa fa-eye"></i> View Menu</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="menusByRoles">
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
                                    //$models = false;
                                    if($menus){
                                        foreach ($menus as $item_m){
                                            ?>
                                            <tr class="tritem" data-id="<?php echo $item_m->id;?>">
                                                <td><?php echo $item_m->id;?></td>
                                                <td><?php echo $item_m->role;?></td>
                                                <td >
                                                    <?php if ($item_m->status == 1 ): ?>
                                                        <span class="label label-success">Active</span>
                                                    <?php else: ?>
                                                        <span class="label label-warning">Disabled</span>
                                                    <?php endif;?>
                                                </td>
                                                <td><span class="label label-success"><?php echo $item_m->row_version;?></span></td>
                                                <td>

                                                    <button id="" class="btn btn-sm btn-primary btnItemLoadData" data-menu="<?php echo $item_m->id;?>" data-role="<?php echo $item_m->role;?>" data-version="<?php echo $item_m->row_version;?>"> Load Data</button>
                                                    <button id="" class="btn btn-sm btn-danger btnItemDelete" data-menu="<?php echo $item_m->id;?>"><i class="fa fa-trash"></i> </button>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }

                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane" id="menuView">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading clearfix"><h5 class="pull-left">Menu</h5>
                                            <div class="pull-right">
                                                <span class="label label-success" id="labelLoadedID"></span>
                                                <span class="label label-success" id="labelLoadedROLE"></span>
                                                <span class="label label-success" id="labelLoadedVERSION"></span>
                                                <button id="btnOut" type="button" class="btn btn-primary">
                                                    <i class="glyphicon glyphicon-triangle-right"></i> View Output</button>
                                            </div>
                                        </div>
                                        <div class="panel-body" id="cont">
                                            <ul id="myEditor" class="sortableLists list-group">
                                            </ul>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
