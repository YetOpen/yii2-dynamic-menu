<?php

namespace esempla\dynamicmenu;

/**
 * dynamic-menu module definition class
 */
class DynamicMenu extends \yii\base\Module
{
    /** @var boolean Skip duplicate href found when merging menu items for the user */
    public $skipDuplicateHref = true;

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'esempla\dynamicmenu\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }
}
