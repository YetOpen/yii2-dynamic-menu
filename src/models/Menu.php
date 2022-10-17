<?php

namespace esempla\dynamicmenu\models;

use Yii;

/**
 * This is the model class for table "menu".
 *
 * @property int $id
 * @property string $label
 * @property string $icon
 * @property string $class
 * @property string $url
 * @property string $visible_condition
 *
 * @property MenuRole[] $menuRoles
 */
class Menu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['label', 'icon', 'class', 'url'], 'required'],
            [['label', 'icon', 'class', 'url'], 'string', 'max' => 255],
            [['visible_condition'], 'string', 'max' => 500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'label' => 'Label',
            'icon' => 'Icon',
            'class' => 'Class',
            'url' => 'Url',
            'visible_condition' => 'Visibility condition',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuRoles()
    {
        return $this->hasMany(MenuRole::className(), ['menu_id' => 'id']);
    }
}
