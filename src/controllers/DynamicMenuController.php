<?php
/**
 * Created by PhpStorm.
 * User: nicolai
 * Date: 5/23/18
 * Time: 11:35 AM
 */

namespace esempla\dynamicmenu\controllers;

use yii\web\Controller;
use esempla\dynamicmenu\models\DynamicMenu;
use yii\filters\VerbFilter;

class DynamicMenuController extends Controller
{

    /**
     * Returns a list of behaviors that this component should behave as.
     *
     * @return array
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'index' => ['get'],
                    'create' => ['post'],
//                    'update' => ['get', 'post'],
//                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $model = new DynamicMenu();
        $menus = DynamicMenu::find()->orderBy("id DESC")->all();
        \Yii::$app->session->setFlash('info', 'Use button Create New Menu!');
        return $this->render('index', [
            'menus' => $menus,
            'model' => $model
        ]);
    }

    /**
     * Handle menu save request.
     * @todo: Check if current user is allowed to create menus.
     * @return array|mixed
     */
    public function actionCreate()
    {
        /**
         * Response format JSON
         */
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $model = new DynamicMenu();
        $data = \Yii::$app->request->post();

        /**
         * Check if provided menu_data is a valid JSON String.
         */
        if( !$model->validateJsonRow($data['menu_data']) ) {
            return [
                'status' => -1,
                'message' => 'Invalid JSON Menu Data provided.'
            ];
        }
        
        $model->setRole($data['role']);
        $model->setMenuData($data['menu_data']);

        if ($model->save()) {

            return [
                'status' => 1,
                'massage' => 'ok',
                'menu' => $model
            ];

        } else {

            return [
                'status' => 2,
                'message' => $model->getErrors()
            ];
        }
    }

    /**
     * Get menuData in JSON format
     * @return array
     */
    public function actionRead()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $request = \Yii::$app->request->post();
        $response = [];

        if( isset( $request['menuId'] ) && $menuId = $request['menuId'] ) {
            $menu = DynamicMenu::find()->where(['id' =>(int)$menuId])->one();

            if( isset( $menu ) ) {
                $response = [
                    'status' => 1,
                    'message' => 'ok',
                    'menu' => $menu->menu_data,
                ];
            }
        }

        return $response;
    }

    /**
     * Delete menu
     * @return array
     */
    public function actionDelete()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $request = \Yii::$app->request->post();
        $response = [];

        if( isset( $request['menuId'] ) && $menuId = $request['menuId'] ) {
            $menu = DynamicMenu::find()->where(['id' => $menuId])->one();
            $menu->remove();
            $response = [
                'status' => 'ok',
                'deleted' => $menuId,
            ];
        }

        return $response;
    }

}