<?php

namespace esempla\dynamicmenu\widgets;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use esempla\dynamicmenu\models\DynamicMenu;
use yii\helpers\ArrayHelper;

class DynamicMenuWidget extends \yii\widgets\Menu
{
    /**
     * @inheritdoc
     */
    public $linkTemplate = '<a href="{href}" data-toggle="tooltip" data-placement="right" title="{title}" target="{target}">{icon} {text}</a>';
    /**
     * @inheritdoc
     * Styles all texts of items on sidebar by AdminLTE
     */
    public $textTemplate = '<span>{text}</span>';
    public $submenuTemplate = "\n<ul class='treeview-menu' {show}>\n{items}\n</ul>\n";
    public $activateParents = true;
    public $defaultIconHtml = '<i class="fa fa-circle-o"></i> ';
    public $options = ['class' => 'sidebar-menu', 'data-widget' => 'tree'];

    /** @var array|string Load menu items for this roles, instead of checking for the current user's ones */
    public $roles = null;

    /**
     * @var string is prefix that will be added to $item['icon'] if it exist.
     * By default uses for Font Awesome (http://fontawesome.io/)
     */
    public static $iconClassPrefix = 'fa ';

    private $noDefaultAction;
    private $noDefaultRoute;

    /**
     * Renders the menu.
     */
    public function run()
    {
        if (is_string($this->roles)) {
            $this->roles = [$this->roles];
        }
        if (is_array($this->roles)) {
            $this->roles = array_combine($this->roles, $this->roles);
        }
        $this->items = DynamicMenu::loadMenu($this->roles);

        if ($this->route === null && Yii::$app->controller !== null) {
            $this->route = Yii::$app->controller->getRoute();
        }
        if ($this->params === null) {
            $this->params = Yii::$app->request->getQueryParams();
        }
        $posDefaultAction = strpos($this->route, Yii::$app->controller->defaultAction);
        if ($posDefaultAction) {
            $this->noDefaultAction = rtrim(substr($this->route, 0, $posDefaultAction), '/');
        } else {
            $this->noDefaultAction = false;
        }
        $posDefaultRoute = strpos($this->route, Yii::$app->controller->module->defaultRoute);
        if ($posDefaultRoute) {
            $this->noDefaultRoute = rtrim(substr($this->route, 0, $posDefaultRoute), '/');
        } else {
            $this->noDefaultRoute = false;
        }
        $items = $this->normalizeItems($this->items, $hasActiveChild);
        if (!empty($items)) {
            $options = $this->options;
            $tag = ArrayHelper::remove($options, 'tag', 'ul');

            echo Html::tag($tag, $this->renderItems($items), $options);
        }
    }

    /**
     * @inheritdoc
     */
    protected function renderItem($item)
    {
        if (isset($item['children'])) {
            $textTemplate = '<a href="{href}" >{icon} {text} <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>';
            $linkTemplate = '<a href="{href}" >{icon} {text} <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>';
        } else {
            $textTemplate = $this->textTemplate;
            $linkTemplate = $this->linkTemplate;
        }
        //todo de adaugat un preg replace la icon  sa ramina numai fa fa-icon
        $replacements = [
            '{text}' => strtr($this->textTemplate, ['{text}' => $item['text'],]),
            '{icon}' => empty($item['icon']) ? $this->defaultIconHtml
                : Html::tag("i", "", ['class' => $item['icon']]),
            '{href}' => ArrayHelper::getValue($item, 'href', 'javascript:void(0);'),
            '{title}' => ArrayHelper::getValue($item, 'title', ''),
            '{target}' => ArrayHelper::getValue($item, 'target', '_self'),
        ];
        $template = ArrayHelper::getValue($item, 'template', isset($item['href']) ? $linkTemplate : $textTemplate);

        return strtr($template, $replacements);
    }

    /**
     * Recursively renders the menu items (without the container tag).
     * @param array $items the menu items to be rendered recursively
     * @return string the rendering result
     */
    protected function renderItems($items)
    {
        $n = count($items);
        $lines = [];
        foreach ($items as $i => $item) {
            $options = array_merge($this->itemOptions, ArrayHelper::getValue($item, 'options', []));
            $tag = ArrayHelper::remove($options, 'tag', 'li');
            $class = [];
            if ($item['active']) {
                $class[] = $this->activeCssClass;
            }
            if ($i === 0 && $this->firstItemCssClass !== null) {
                $class[] = $this->firstItemCssClass;
            }
            if ($i === $n - 1 && $this->lastItemCssClass !== null) {
                $class[] = $this->lastItemCssClass;
            }
            if (!empty($class)) {
                if (empty($options['class'])) {
                    $options['class'] = implode(' ', $class);
                } else {
                    $options['class'] .= ' ' . implode(' ', $class);
                }
            }
            $menu = $this->renderItem($item);
            if (!empty($item['children'])) {
                $menu .= strtr($this->submenuTemplate, [
                    '{show}' => $item['active'] ? "style='display: block'" : '',
                    '{items}' => $this->renderItems($item['children']),
                ]);
                if (isset($options['class'])) {
                    $options['class'] .= ' treeview';
                } else {
                    $options['class'] = 'treeview';
                }
            }
            $lines[] = Html::tag($tag, $menu, $options);
        }
        return implode("\n", $lines);
    }

    /**
     * Check if the item should be visible or not, according to user permissions.
     * The `$item` should have a `visible_condition` string, which is a mix of permissions to be
     * checked with `can()`.
     * Multiple permissions can be concatenated either with `|` or `&`. They cannot be mixed, nor
     * parenthesis can be used to mix conditions.
     *
     * @param array $item 
     * @return boolean
     */
    private function _checkItemVisiblity($item)
    {
        $visible_condition = ArrayHelper::getValue($item, 'visible_condition');
        if (empty($visible_condition)) {
            return true;
        }

        $merge_condition = "&";
        $result = true;
        if (strpos($visible_condition, "&") !== false) {
            $merge_condition = "&";
            $result = true;
        } else if (strpos($visible_condition, "|") !== false) {
            $merge_condition = "|";
            $result = false;
        }

        $conditions = $merge_condition != "" ? explode($merge_condition, $visible_condition) : [$visible_condition];
        foreach ($conditions as $c) {
            $c = trim($c);
            $is_negated = false;
            if (strpos($c, "!") === 0) {
                $is_negated = true;
                $c = substr($c, 1);
            }
            switch ($c) {
                    // Special cases first
                case "ISGUEST":
                    $result = $this->_mergeCondition($merge_condition, Yii::$app->user->isGuest, $is_negated, $result);
                    break;
                default:
                    // Check permission by default
                    $result = $this->_mergeCondition($merge_condition, Yii::$app->user->can($c), $is_negated, $result);
                    break;
            }
        }

        return $result;
    }

    /**
     * Merges a visibility condition with the `$current` status
     * 
     * @param string $merge_type either `|` or `&`, logical condition for merging the permissions
     * @param boolean $check The check result
     * @param boolean $is_negated Determines if the check should be negated or not
     * @param boolean $current Current status to be merged with `$check`
     * @return boolean `$check` logically joined to `$current` using `$merge_type`   
     */
    private function _mergeCondition($merge_type, $check, $is_negated, $current)
    {
        if ($merge_type == "|") {
            $current |= $is_negated == false ? (bool)$check : !(bool)$check;
        } else if ($merge_type == "&") {
            $current &= $is_negated == false ? (bool)$check : !(bool)$check;
        }
        return $current;
    }

    /**
     * Parses the href string, if it "looks like" a route array will attempt to reconstruct it for `Url::toRoute()`
     *
     * @param string $url If the string starts with a `[`, will attempt a basic array decode from the string
     * @return string URL route
     * @see https://stackoverflow.com/a/74044428/738852
     */
    private function _parseRoute($url)
    {
        $re = '/(?:\G(?!^)|^\[["\'](?P<route>[\w\/\-]+)["\'])(?:,\s*["\'](?P<paramname>\w+)["\']\s*=>\s*["\']?(?P<paramval>[\\\\\w]+)?["\']?|)(?=.*?])/';
        if (preg_match_all($re, $url, $matches, PREG_SET_ORDER, 0) == false) {
            return Url::toRoute($url);
        }
        $base = array_merge([$matches[0]['route']], array_combine(ArrayHelper::getColumn($matches, "paramname"), ArrayHelper::getColumn($matches, "paramval")));
        return Url::toRoute($base);
    }

    /**
     * @inheritdoc
     */
    protected function normalizeItems($items, &$active)
    {
        foreach ($items as $i => $item) {
            if ($this->_checkItemVisiblity($item) == false) {
                unset($items[$i]);
                continue;
            }
            if (!isset($item['text'])) {
                $item['text'] = '';
            }
            $encodeLabel = isset($item['encode']) ? $item['encode'] : $this->encodeLabels;
            $items[$i]['text'] = $encodeLabel ? Html::encode($item['text']) : $item['text'];
            $items[$i]['icon'] = isset($item['icon']) ? $item['icon'] : '';
            $items[$i]['href'] = $this->_parseRoute($item['href']);
            $hasActiveChild = false;
            if (isset($item['children'])) {
                $items[$i]['children'] = $this->normalizeItems($item['children'], $hasActiveChild);
                if (empty($items[$i]['children']) && $this->hideEmptyItems) {
                    unset($items[$i]['children']);
                    if (!isset($item['href'])) {
                        unset($items[$i]);
                        continue;
                    }
                }
            }
            if (!isset($item['active'])) {
                if ($this->activateParents && $hasActiveChild || $this->activateItems && $this->isItemActive($item)) {
                    $active = $items[$i]['active'] = true;
                } else {
                    $items[$i]['active'] = false;
                }
            } elseif ($item['active']) {
                $active = true;
            }
        }
        return array_values($items);
    }

    /**
     * Checks whether a menu item is active.
     * This is done by checking if [[route]] and [[params]] match that specified in the `href` option of the menu item.
     * When the `href` option of a menu item is specified in terms of an array, its first element is treated
     * as the route for the item and the rest of the elements are the associated parameters.
     * Only when its route and parameters match [[route]] and [[params]], respectively, will a menu item
     * be considered active.
     * @param array $item the menu item to be checked
     * @return boolean whether the menu item is active
     */
    protected function isItemActive($item)
    {
        if (isset($item['href']) && is_array($item['href']) && isset($item['href'][0])) {
            $route = $item['href'][0];
            if ($route[0] !== '/' && Yii::$app->controller) {
                $route = ltrim(Yii::$app->controller->module->getUniqueId() . '/' . $route, '/');
            }
            $route = ltrim($route, '/');
            if ($route != $this->route && $route !== $this->noDefaultRoute && $route !== $this->noDefaultAction) {
                return false;
            }
            unset($item['href']['#']);
            if (count($item['href']) > 1) {
                foreach (array_splice($item['href'], 1) as $name => $value) {
                    if ($value !== null && (!isset($this->params[$name]) || $this->params[$name] != $value)) {
                        return false;
                    }
                }
            }
            return true;
        }
        return false;
    }
}
