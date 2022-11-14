
/**
 * Dynamic Menu Editor Component
 * @type {{editor: null, init: DynamicMenuComponent.init, initializeEventListeners: DynamicMenuComponent.initializeEventListeners}}
 */
var DynamicMenuComponent = {

    /**
     * jQuery-Menu-Editor instance.
     */
    editor: null,

    /**
     * Initialize plugins
     */
    init: function(){

        var self = this;

        var strjson = [{"href":"http://home.com","icon":"fa fa-home","text":"Home", "target": "_top", "title": "My Home"},{"icon":"fa fa-bar-chart-o","text":"Opcion2"},{"icon":"fa fa-cloud-upload","text":"Opcion3"},{"icon":"fa fa-crop","text":"Opcion4"},{"icon":"fa fa-flask","text":"Opcion5"},{"icon":"fa fa-map-marker","text":"Opcion6"},{"icon":"fa fa-search","text":"Opcion7","children":[{"icon":"fa fa-plug","text":"Opcion7-1","children":[{"icon":"fa fa-filter","text":"Opcion7-1-1"}]}]}];
        //icon picker options
        var iconPickerOptions = {searchText: 'Search Icon', labelHeader: '{0} de {1} Pags.', iconClass: 'fa'};
        //sortable list options
        var sortableListOptions = {
            placeholderCss: {'background-color': 'cyan'}
        };

        /**
         * Initialize jQuery Menu Editor
         * @type {MenuEditor}
         */
        var editor = new MenuEditor('myEditor', {listOptions: sortableListOptions, iconPicker: iconPickerOptions, labelEdit: 'Edit' });

        editor.setForm($('#frmEdit'));
        editor.setUpdateButton($('#btnUpdate'));

        /**
         * Store editor in component scope
         * @type {MenuEditor}
         */
        self.editor = editor;

        $('#btnReload').on('click', function () {
            self.editor.setData(strjson);
        });

        /**
         * Initialize event listeners
         */
        self.initializeEventListeners();
    },

    /**
     * Handle add menu button click.
     * Perform basic validation.
     */
    handleAddMenuItem: function(){

        var self = this;

        $('#btnAdd').click(function(){
            var $menuItemText = $('#frmEdit').find('#text'),
                menuItemTextValue = $menuItemText.val(),
                $fg = $menuItemText.closest('.form-group');

            if( menuItemTextValue != '' && menuItemTextValue !== undefined ) {
                self.editor.add();
            } else {
                self.addMenuTextErrorBlock($fg);
            }
        });
    },

    /**
     * Display error block below "Text" field.
     * @param $fg jQueryNode Form Group containing Text input
     */
    addMenuTextErrorBlock: function($fg){
        
        /**
         * Only one error message per form-group.
         */
        if( !$fg.find('.has-error').length ) {
            $fg.addClass('has-error');
            $fg.append('<div class="col-sm-12 help-block has-error" style="margin-left:16.5%;">Please set menu item text.</div>');
        }
    },

    /**
     * Remove error block from "Text" field.
     * @param $fg jQuerynode Form group containing Text input,
     */
    clearMenuTextErrorBlock: function($fg){
        $fg.removeClass('has-error');
        $fg.find('.help-block').remove()
    },

    /**
     * Handle role select value change.
     */
    handleRoleChangeSelect: function(){

        var self = this;

        $(document).on('change', '#frmEdit #text', function(){
            var $menuItemText = $(this),
                menuItemTextValue = $menuItemText.val(),
                $fg = $menuItemText.closest('.form-group');

            if( menuItemTextValue == '' || menuItemTextValue == undefined ) {
                self.addMenuTextErrorBlock($fg);
            } else {
                self.clearMenuTextErrorBlock($fg);
            }
        });
    },

    /**
     * Handle load data click
     */
    handleMenuLoadData: function(){
        var self = this;

        $(document).on("click",".btnItemLoadData",function (e) {

            var id = $(this).attr('data-menu'),
                role = $(this).attr('data-role'),
                version = $(this).attr('data-version');

            $("#roleName").empty();
            $("#roleName").text(role);

            $.ajax({
                data: {menuId:id},
                url: $(this).attr('data-url'),
                dataType: 'json',
                type: 'POST',
                success:function(data) {
                    self.editor.setData(data.menu);
                    $('#labelLoadedID').empty();
                    $('#labelLoadedROLE').empty();
                    $('#labelLoadedVERSION').empty();
                    $('#labelLoadedID').html('<b>Loaded ID:'+id+'</b>');
                    $('#labelLoadedROLE').html('<b>ROLE:'+role+'</b>');
                    $('#labelLoadedVERSION').html('<b>V.'+version+'</b>');
                    $('#panelMenus a[href="#menuView"]').tab('show');
                    console.log('Data response:'+data);
                    //var response =  JSON.parse(data);
                    //console.log(response);
                    //callback(data);
                },
                error:function(data) {
                    console.log(data);
                    //var response =  JSON.parse(data);
                    //console.log(response);
                    //callback_error(data);
                }
            });
        });
    },

    /**
     * Handle item delete
     */
    handleItemDelete: function(){

         var self = this;

        /**
         * Delete menu item click handle
         */
        $(document).on("click",".btnItemDelete",function (e) {

            /**
             * Read menu id
             * @type {*|jQuery}
             */
            var id = $(this).attr('data-menu');

            /**
             * Ask confirmation.
             */
            swal({
                title: "Are you sure you want to delete this menu ?",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it",
                cancelButtonText: "No, keep it",
            }, function(){

                /**
                 * If confirmed, send ajax request to delete item.
                 */
                $.ajax({
                    data: {
                        menuId:id
                    },
                    url: $(this).attr('data-url'),
                    dataType: 'json',
                    type: 'POST',
                    success:function(data) {

                        swal({
                            title:" Deleted successfully",
                            type: "success",
                            confirmButtonText: "OK & Reload",
                        }, function(){
                            window.location.reload();
                        });

                    },
                    error:function(data) {
                        console.log(data);
                        display_error(data);
                    }
                });
            });

        });
    },

    /**
     * Handle save menu events
     */
    handleSaveMenu: function(){

        $('#btnSave').on("click",function (e) {

            e.preventDefault();

            /**
             * @var string dataMenu Menu items as JSON string
             */
            var dataMenu = $("#out").val();

            /**
             * @var string roleName Selected role name.
             */
            var roleName = $("#roleName").text();

            console.log(dataMenu);
            console.log(roleName);

            if(dataMenu !=='' && roleName !==''){
                $.ajax({
                    data: {role:roleName,menu_data:dataMenu},
                    url: $(this).attr('data-url'),
                    dataType: 'json',
                    type: 'POST',
                    success:function(response) {

                        console.log(response);

                        if( response.status !== undefined && response.status == 1 ) {
                            swal({
                                title:"Saved Successfully!",
                                type: "success",
                                confirmButtonText: "OK & Reload",
                            }, function(){
                                console.log('Confirm clicked.');
                                window.location.reload();
                            });

                        } else {
                            swal({
                                title: "Error.",
                                text: response.message,
                                type: "warning",
                            });
                        }
                    },
                    error:function(data){
                        console.log(data);
                        // alert(data.message);
                        //callback_error(data);
                        display_error(data);
                    }
                });

            } else {
                swal({
                    title:"Menu data or role not set. Please select an role and click View Output to load menu data.",
                    type: "warning"
                });
            }
        });
    },


    /**
     * Handle Clear action,
     */
    handleClearMenu: function(){

        var self = this;

        /**
         * Clear menu
         */
        $('#btnClear').on('click', function(e){
            e.preventDefault();

            var $this = $(this);

            swal({
                title: "Are you sure you want to clear editor?",
                text: "This will remove all currently unsaved items added to the editor.",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, clear editor",
                cancelButtonText: "No, keep it",
            }, function(){

                /**
                 * Clear output value, set menu data to empty array,
                 * Clear selected role.
                 */
                $('#out').val('');
                // $('#roleNameHead').hide();
                // $('#roleName').text('');
                self.editor.setData([]);

            });
        });
    },

    /**
     * Attach DOM Listener methods
     */
    initializeEventListeners: function(){

        var self = this;

        var editor = self.editor;

        $('#btnOut').on('click', function () {
            var str = editor.getString();
            $("#out").text(str);
        });

        $("#btnUpdate").click(function(){
            editor.update();
        });

        /**
         * Real-time JSON Validation.
         */
        $('#out').on('change', function(e){
            var $this = $(this),
                _val = $this.val();
        });

        /**
         * Handler names to be autoloaded.
         * @type {string[]}
         */
        var handlers = [
            'handleAddMenuItem',
            'handleRoleChangeSelect',
            'handleMenuLoadData',
            'handleItemDelete',
            'handleSaveMenu',
            'handleClearMenu'
        ];

        /**
         * Autoload event handlers.
         */
        $.each(handlers, function(i, handler){
            if( self[handler] !== undefined && typeof self[handler] == 'function' ) {
                self[handler]();
            }
        });
    },
}


/**
 * Document loaded
 */
jQuery(document).ready(function () {

    /**
     * Initialize DynamicMenuComponent
     */
    var dynamicMenuComponent = DynamicMenuComponent.init();

    /**
     * Store component globally.
     */
    window.DynamicMenuComponent = dynamicMenuComponent;
});

/**
 * Render AJAX Error helper.
 * @param response
 */
function display_error(response)
{
    $('#yii-debug-toolbar').hide();
    $('#ajax_debug_error').remove();
    $('body').append('<div id="ajax_error_debug">' + response.responseText + '</div>');
}