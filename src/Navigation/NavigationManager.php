<?php

namespace Zeus\Admin\Navigation;

use Zeus\Admin\Plugins\PluginManager;

class NavigationManager
{
    protected $navigation = null;
    protected $allPluginsNavigation = [];

    public function __construct(\Illuminate\Contracts\Foundation\Application  $app=null)
    {
        $this->navigation = $this->getDefaultNavigation();
        $this->allPluginsNavigation;
        $this->app = $app;
    }

    public function getDefaultNavigation()
    {
        if (class_exists('App\Admin\Navigation\NavigationList')) {
            $navigation = \App\Admin\Navigation\NavigationList::getNavigationList();
        }
        else{
            $navigation = NavigationDefault::getNavigationList();
        }

        return $navigation;
    }

    public function getNavigation()
    {
        return array_merge($this->navigation,$this->app['PluginsData']['PluginsNavigation']);
    }

    public static function returnNavigation(\Illuminate\Contracts\Foundation\Application  $app)
    {
        $navigation = (new self($app))->getNavigation();

        return $navigation;
    }

}