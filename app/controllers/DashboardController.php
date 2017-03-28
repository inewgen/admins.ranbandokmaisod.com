<?php

class DashboardController extends BaseController
{
    /**
     * The layout that should be used for responses.
     */
    //protected $layout = 'layouts.master';

    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return Response
     */
    public function index()
    {
        $theme = Theme::uses('default')->layout('adminlte2');
        $theme->setTitle('Admin ranbandokmaisod :: Home');
        $theme->setDescription('Home description');
        $theme->share('user', $this->user);
        
        $view = array(
            'theme' => $theme,
            'name' => 'xxx',
        );

        $script = $theme->scopeWithLayout('dashboard.jscript_index', $view)->content();
        $theme->asset()->container('inline_script')->usePath()->writeContent('custom-inline-script', $script);

        return $theme->scopeWithLayout('dashboard.index', $view)->render();
    }
}
