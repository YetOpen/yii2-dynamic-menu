<?php

namespace esempla\dynamicmenu\models;

/**
 * Class DynamicMenu
 * @package backend\models
 * @todo: Refactor, improve code quality.
 */
class DynamicMenu extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_DISABLED = 2;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%sidebar_menu}}';
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {

            //check if exist prev row_version set new version
            $this->row_version = (integer)self::getLatestVersion() + 1;
            $this->status = self::STATUS_ACTIVE;
            
            //update prev version status = Disabled
            self::disableOlderVersions();
            
            $this->create_user = \Yii::$app->user->id;
            $this->create_datetime = new \yii\db\Expression('NOW()');

            return true;

        } else {
            return false;
        }
    }


    /**
     * Set menu role
     * @param $role
     */
    public function setRole($role)
    {
        $this->role = $role;
    }

    /**
     * Get menu role
     * @return mixed
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Menu data setter.
     * @param $value
     */
    public function setMenuData($value)
    {
        if(is_array($value)) {
            $value = json_encode($value);
        }

        if( $this->validateJsonRow($value) ) {
            $this->menu_data = $value;
        } else {
            $this->menu_data = json_encode([]);
        }
    }

    /**
     * Delete menu item.
     * Find previous version of menu for this role.
     * If such menu exists, Set it to active.
     * Delete this menu item.
     */
    public function remove()
    {
        $previousMenu = $this->find()->where(['row_version' => $this->row_version - 1])->one();

        if( $previousMenu ) {
            $previousMenu->status = self::STATUS_ACTIVE;
            $previousMenu->save();
        }

        $this->delete();
    }

    /**
     * Check latest version of menu items with same role as this.
     * @return int|mixed
     */
    public function getLatestVersion()
    {
        $latestVersion = self::find()->where(['role' => $this->role])->max('row_version');
        return !empty($latestVersion) ? $latestVersion : 0;
    }

    /**
     * Disable menus with same role as this.
     */
    private function disableOlderVersions()
    {
        self::updateAll(['status' => self::STATUS_DISABLED], ['=', 'role', $this->role]);
    }

    /**
     * Validate JSON
     * @param mixed $menuData
     * @return bool
     */
    public function validateJsonRow($menuData)
    {
        if (!empty($menuData)) {
            $buffer = json_decode($menuData, true);
            return (json_last_error() === JSON_ERROR_NONE);
        }
        return false;
    }

    /**
     * Load menu data
     * @param null $roleName
     * @return array|mixed
     */
    public static function loadMenu($roleName = null)
    {
        $menuData = [];
        if($roleName === null) {
            $roles = \Yii::$app->authManager->getRolesByUser(\Yii::$app->user->getId());
            $roleName = is_array($roles)?key($roles):'undefined';
        }

        $menu = self::find()
            ->where(['role' => $roleName, 'status' => self::STATUS_ACTIVE])
            ->orderBy("row_version DESC")
            ->one();

        if ($menu) {
            $menuData = json_decode($menu->menu_data, true);
        }

        return $menuData;
    }

}
