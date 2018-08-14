<?php
namespace Wdxr\Modules\Admin\Tags;

use Phalcon\Acl;
use Phalcon\Tag;
use Wdxr\Auth\Auth;

class MenuTags extends Tag
{
    /**
     *
     * @param $parameters
     * @return string
     */
    static public function acl_button($parameters)
    {
        if(!is_array($parameters)) {
            $parameters = [$parameters, ''];
        }

        $url = array_shift($parameters);
        $text = array_shift($parameters);

        $role = MenuTags::getCurrentRoles();
        $allow = true;
        $active = '';
        list($controller, $action) = MenuTags::getControllerAction($url);
        foreach ($role as $item) {
            $allow = Tag::getDI()->get('acl')->isAllowed($item, $controller, $action);
            if($allow == Acl::ALLOW) {
                break;
            }
        }
        if($allow) {
            if(isset($parameters['href'])) {
                $attr = '';
                foreach ($parameters as $key => $item) {
                    $attr .= $key."='".$item."' ";
                }
                return "<a ".$attr.">".$text."</a>";
            } else {
                $parameters['action'] = $url;
                $parameters['text'] = $text;
                return Tag::linkTo($parameters);
            }
        } else {
            return '';
        }
    }

    static public function acl_menu($url, $text)
    {
        $active = strpos($_SERVER['REQUEST_URI'], $url) === false ? '' : 'class="active"';
        if(strcmp($url, '#') === 0) {
            return '';
        }

        list($controller, $action) = MenuTags::getControllerAction($url);
        foreach (MenuTags::getCurrentRoles() as $item) {
            $allow = Tag::getDI()->get('acl')->isAllowed($item, $controller, $action);
            if($allow == Acl::ALLOW) {
                return "<li $active>".Tag::linkTo([$url, $text])."</li>";
            }
        }
        return '';
    }

    static public function acl_group($title, $parameters)
    {
        $menu_html = '';
        $active = '';
        foreach ($parameters as $parameter) {
            $menu_html .= MenuTags::acl_menu($parameter[0], $parameter[1]);
            if(strpos($_SERVER['REQUEST_URI'], $parameter[0]) !== false) {
                $active = ' class="active"';
            }
        }
        if(empty($menu_html)) {
            return '';
        }
        return "<li{$active}><a href='#'>{$title}</a><ul class='nav nav-second-level collapse'>{$menu_html}</ul></li>";
    }

    static protected function getCurrentRoles()
    {
        $role = Auth::getUserRoles();
        return $role;
    }

    static protected function getControllerAction($url)
    {
        list($module, $controller, $action) = explode('/', $url);
        if(!$action) {
            $action = $controller;
            $controller = $module;
        }
        return [$controller, $action];
    }



}