<?php

use CodeIgniter\Router\RouteCollection;
use Config\Services;


$routes->setAutoRoute(false);
$security = \Config\Services::security();

/**
 * @var RouteCollection $routes
 */

$uri = current_url(true);
$router = service('router');
$module = $router->controllerName();

if (session()->get('logged_in') !== null) {
    if (!session()->get('logged_in')) {
        $routes->get('/', 'Main::index');
    } else {
        $routes->get('/', 'Login::index');
    }
} else {
    $routes->get('/', 'Login::index');
}

$routes->post('/test', 'Test::index', ['filter' => 'authfilter']);

#non modules
$routes->get('/main', 'Main::index', ['filter' => 'authfilter']);
$routes->get('/Main', 'Main::index', ['filter' => 'authfilter']);
$routes->get('/Main/setMenu', 'Main::setMenu', ['filter' => 'authfilter']);
$routes->get('/login', 'Login::index');
$routes->post('login/post_login', 'Login::post_login');
$routes->get('login/logout', 'Login::logout');

$routes->get('/', '\App\Modules\\' . $module . '\Controllers\\' . $module . '::index');

#ECENTRIX
$routes->get('/Ecentrix8/getCallCenterConfigurationSupervisor', 'Ecentrix8::getCallCenterConfigurationSupervisor');
$routes->get('/Ecentrix8/getCallCenterConfiguration', 'Ecentrix8::getCallCenterConfiguration');
$routes->post('/Ecentrix8/updateAccountCodeSessionLog', 'Ecentrix8::updateAccountCodeSessionLog');


$routes->add('settings/get_system_configuration/', '\App\Modules\Settings\Controllers\Settings::get_system_configuration', ['filter' => 'authfilter']);
$routes->add('scoring/scheduler', '\App\Modules\Scoring\Controllers\Scoring::scheduler', ['filter' => 'authfilter']);
$routes->add('scoring/tieringPreview', '\App\Modules\TieringPreview\Controllers\TieringPreview::TieringPreview', ['filter' => 'authfilter']);
$routes->add('scoring/scoringDataDetail', '\App\Modules\ScoringDataDetail\Controllers\ScoringDataDetail::ScoringDataDetail', ['filter' => 'authfilter']);
$routes->add('scoring/parameters', '\App\Modules\Parameters\Controllers\Parameters::get_parameters_list', ['filter' => 'authfilter']);
$routes->add('scoring/preview', '\App\Modules\Preview\Controllers\Preview::preview', ['filter' => 'authfilter']);
$routes->add('scoring/setting', '\App\Modules\Setting\Controllers\Setting::setting', ['filter' => 'authfilter']);
$routes->add('scoring/setting/upload_file_form', '\App\Modules\Setting\Controllers\Setting::upload_file_form', ['filter' => 'authfilter']);
$routes->add('scoring/setting/save_file', '\App\Modules\Setting\Controllers\Setting::save_file', ['filter' => 'authfilter']);
$routes->post('scoring/setting/save_setting', '\App\Modules\Setting\Controllers\Setting::save_setting', ['filter' => 'authfilter']);
$routes->add('scoring/setting/(:segment)', '\App\Modules\Setting\Controllers\Setting::setting/$1', ['filter' => 'authfilter']);
$routes->add('setting/general/', '\App\Modules\GeneralSetting\Controllers\GeneralSetting::GeneralSetting', ['filter' => 'authfilter']);

if ($uri->getTotalSegments() > 2) {
    $function = $uri->getSegment(3);

    $routes->add('scoring/scheduler/' . $function, '\App\Modules\Scoring\Controllers\Scoring::' . $function, ['filter' => 'authfilter']);
    $routes->add('scoring/scoringDataDetail/' . $function, '\App\Modules\ScoringDataDetail\Controllers\ScoringDataDetail::' . $function, ['filter' => 'authfilter']);

    $routes->add('scoring/parameters/' . $function, '\App\Modules\Parameters\Controllers\Parameters::' . $function, ['filter' => 'authfilter']);
    $routes->add('scoring/preview/' . $function, '\App\Modules\Preview\Controllers\Preview::' . $function, ['filter' => 'authfilter']);
    $routes->add('scoring/setting/' . $function, '\App\Modules\Setting\Controllers\Setting::' . $function, ['filter' => 'authfilter']);
    $routes->add('setting/general/'. $function, '\App\Modules\GeneralSetting\Controllers\GeneralSetting::' . $function, ['filter' => 'authfilter']);

}
