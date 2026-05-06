<?php

/**
 * This file has been auto-generated
 * by the Symfony Routing Component.
 */

return [
    false, // $matchHost
    [ // $staticRoutes
        '/_wdt/styles' => [[['_route' => '_wdt_stylesheet', '_controller' => 'web_profiler.controller.profiler::toolbarStylesheetAction'], null, null, null, false, false, null]],
        '/_profiler' => [[['_route' => '_profiler_home', '_controller' => 'web_profiler.controller.profiler::homeAction'], null, null, null, true, false, null]],
        '/_profiler/search' => [[['_route' => '_profiler_search', '_controller' => 'web_profiler.controller.profiler::searchAction'], null, null, null, false, false, null]],
        '/_profiler/search_bar' => [[['_route' => '_profiler_search_bar', '_controller' => 'web_profiler.controller.profiler::searchBarAction'], null, null, null, false, false, null]],
        '/_profiler/phpinfo' => [[['_route' => '_profiler_phpinfo', '_controller' => 'web_profiler.controller.profiler::phpinfoAction'], null, null, null, false, false, null]],
        '/_profiler/xdebug' => [[['_route' => '_profiler_xdebug', '_controller' => 'web_profiler.controller.profiler::xdebugAction'], null, null, null, false, false, null]],
        '/_profiler/open' => [[['_route' => '_profiler_open_file', '_controller' => 'web_profiler.controller.profiler::openAction'], null, null, null, false, false, null]],
        '/api/verify-email' => [[['_route' => 'account_verify_email', '_controller' => 'App\\Controller\\AccountController::verifyEmail'], null, ['POST' => 0], null, false, false, null]],
        '/api/password/forgot' => [[['_route' => 'account_password_forgot', '_controller' => 'App\\Controller\\AccountController::forgotPassword'], null, ['POST' => 0], null, false, false, null]],
        '/api/password/reset' => [[['_route' => 'account_password_reset', '_controller' => 'App\\Controller\\AccountController::resetPassword'], null, ['POST' => 0], null, false, false, null]],
        '/api/me' => [[['_route' => 'account_me', '_controller' => 'App\\Controller\\AccountController::me'], null, ['GET' => 0], null, false, false, null]],
        '/api/admin/dashboard/kpi' => [[['_route' => 'admin_dashboard_kpi', '_controller' => 'App\\Controller\\Admin\\DashboardController::kpi'], null, ['GET' => 0], null, false, false, null]],
        '/api/admin/dashboard/sales-by-day' => [[['_route' => 'admin_dashboard_sales_by_day', '_controller' => 'App\\Controller\\Admin\\DashboardController::salesByDay'], null, ['GET' => 0], null, false, false, null]],
        '/api/admin/dashboard/sales-by-category' => [[['_route' => 'admin_dashboard_sales_by_category', '_controller' => 'App\\Controller\\Admin\\DashboardController::salesByCategory'], null, ['GET' => 0], null, false, false, null]],
        '/api/catalog/search' => [[['_route' => 'catalog_search', '_controller' => 'App\\Controller\\CatalogController::search'], null, ['GET' => 0], null, false, false, null]],
        '/' => [[['_route' => 'app_home', '_controller' => 'App\\Controller\\HomeController::index'], null, null, null, false, false, null]],
        '/api/home' => [[['_route' => 'api_home', '_controller' => 'App\\Controller\\HomeController::apiHome'], null, ['GET' => 0], null, false, false, null]],
    ],
    [ // $regexpList
        0 => '{^(?'
                .'|/api(?'
                    .'|/(?'
                        .'|docs(?:\\.([^/]++))?(*:37)'
                        .'|\\.well\\-known/genid/([^/]++)(*:72)'
                        .'|validation_errors/([^/]++)(*:105)'
                    .')'
                    .'|(?:/(index)(?:\\.([^/]++))?)?(*:142)'
                    .'|/(?'
                        .'|c(?'
                            .'|ont(?'
                                .'|exts/([^.]+)(?:\\.(jsonld))?(*:191)'
                                .'|act_messages(?'
                                    .'|(?:\\.([^/]++))?(*:229)'
                                    .'|/([^/\\.]++)(?:\\.([^/]++))?(*:263)'
                                    .'|(?:\\.([^/]++))?(*:286)'
                                    .'|/([^/\\.]++)(?:\\.([^/]++))?(?'
                                        .'|(*:323)'
                                    .')'
                                .')'
                            .')'
                            .'|a(?'
                                .'|r(?'
                                    .'|ousel_slides(?'
                                        .'|(?:\\.([^/]++))?(*:372)'
                                        .'|/([^/\\.]++)(?:\\.([^/]++))?(*:406)'
                                        .'|(?:\\.([^/]++))?(*:429)'
                                        .'|/([^/\\.]++)(?:\\.([^/]++))?(?'
                                            .'|(*:466)'
                                        .')'
                                    .')'
                                    .'|t(?'
                                        .'|s(?'
                                            .'|(?:\\.([^/]++))?(*:499)'
                                            .'|/([^/\\.]++)(?:\\.([^/]++))?(?'
                                                .'|(*:536)'
                                            .')'
                                        .')'
                                        .'|_items(?'
                                            .'|(?:\\.([^/]++))?(*:570)'
                                            .'|/([^/\\.]++)(?:\\.([^/]++))?(?'
                                                .'|(*:607)'
                                            .')'
                                        .')'
                                    .')'
                                .')'
                                .'|tegories(?'
                                    .'|(?:\\.([^/]++))?(*:645)'
                                    .'|/([^/\\.]++)(?:\\.([^/]++))?(*:679)'
                                    .'|(?:\\.([^/]++))?(*:702)'
                                    .'|/([^/\\.]++)(?:\\.([^/]++))?(?'
                                        .'|(*:739)'
                                    .')'
                                .')'
                            .')'
                        .')'
                        .'|errors/(\\d+)(?:\\.([^/]++))?(*:778)'
                        .'|validation_errors/([^/]++)(?'
                            .'|(*:815)'
                        .')'
                        .'|addresses(?'
                            .'|(?:\\.([^/]++))?(*:851)'
                            .'|/([^/\\.]++)(?:\\.([^/]++))?(*:885)'
                            .'|(?:\\.([^/]++))?(*:908)'
                            .'|/([^/\\.]++)(?:\\.([^/]++))?(?'
                                .'|(*:945)'
                            .')'
                        .')'
                        .'|homepage_texts(?'
                            .'|(?:\\.([^/]++))?(*:987)'
                            .'|/([^/\\.]++)(?:\\.([^/]++))?(*:1021)'
                            .'|(?:\\.([^/]++))?(*:1045)'
                            .'|/([^/\\.]++)(?:\\.([^/]++))?(?'
                                .'|(*:1083)'
                            .')'
                        .')'
                        .'|invoices(?'
                            .'|(?:\\.([^/]++))?(*:1120)'
                            .'|/([^/\\.]++)(?:\\.([^/]++))?(*:1155)'
                        .')'
                        .'|order(?'
                            .'|s(?'
                                .'|(?:\\.([^/]++))?(*:1192)'
                                .'|/([^/\\.]++)(?:\\.([^/]++))?(*:1227)'
                                .'|(?:\\.([^/]++))?(*:1251)'
                                .'|/([^/\\.]++)(?:\\.([^/]++))?(?'
                                    .'|(*:1289)'
                                .')'
                            .')'
                            .'|_items/([^/\\.]++)(?:\\.([^/]++))?(*:1332)'
                        .')'
                        .'|p(?'
                            .'|ayment_methods(?'
                                .'|(?:\\.([^/]++))?(*:1378)'
                                .'|/([^/\\.]++)(?:\\.([^/]++))?(*:1413)'
                                .'|(?:\\.([^/]++))?(*:1437)'
                                .'|/([^/\\.]++)(?:\\.([^/]++))?(?'
                                    .'|(*:1475)'
                                .')'
                            .')'
                            .'|romo_codes(?'
                                .'|(?:\\.([^/]++))?(*:1514)'
                                .'|/([^/\\.]++)(?:\\.([^/]++))?(*:1549)'
                                .'|(?:\\.([^/]++))?(*:1573)'
                                .'|/([^/\\.]++)(?:\\.([^/]++))?(?'
                                    .'|(*:1611)'
                                .')'
                            .')'
                        .')'
                        .'|saas_services(?'
                            .'|(?:\\.([^/]++))?(*:1654)'
                            .'|/([^/\\.]++)(?:\\.([^/]++))?(*:1689)'
                            .'|(?:\\.([^/]++))?(*:1713)'
                            .'|/([^/\\.]++)(?:\\.([^/]++))?(?'
                                .'|(*:1751)'
                            .')'
                        .')'
                        .'|users(?'
                            .'|(?:\\.([^/]++))?(*:1785)'
                            .'|/([^/\\.]++)(?:\\.([^/]++))?(*:1820)'
                            .'|(?:\\.([^/]++))?(*:1844)'
                            .'|/([^/\\.]++)(?:\\.([^/]++))?(?'
                                .'|(*:1882)'
                            .')'
                        .')'
                    .')'
                .')'
                .'|/_(?'
                    .'|error/(\\d+)(?:\\.([^/]++))?(*:1926)'
                    .'|wdt/([^/]++)(*:1947)'
                    .'|profiler/(?'
                        .'|font/([^/\\.]++)\\.woff2(*:1990)'
                        .'|([^/]++)(?'
                            .'|/(?'
                                .'|search/results(*:2028)'
                                .'|router(*:2043)'
                                .'|exception(?'
                                    .'|(*:2064)'
                                    .'|\\.css(*:2078)'
                                .')'
                            .')'
                            .'|(*:2089)'
                        .')'
                    .')'
                .')'
            .')/?$}sDu',
    ],
    [ // $dynamicRoutes
        37 => [[['_route' => 'api_doc', '_controller' => 'api_platform.action.documentation', '_format' => null, '_api_respond' => true], ['_format'], ['GET' => 0, 'HEAD' => 1], null, false, true, null]],
        72 => [[['_route' => 'api_genid', '_controller' => 'api_platform.action.not_exposed', '_api_respond' => true], ['id'], ['GET' => 0, 'HEAD' => 1], null, false, true, null]],
        105 => [[['_route' => 'api_validation_errors', '_controller' => 'api_platform.action.not_exposed'], ['id'], ['GET' => 0, 'HEAD' => 1], null, false, true, null]],
        142 => [[['_route' => 'api_entrypoint', '_controller' => 'api_platform.action.entrypoint', '_format' => null, '_api_respond' => true, 'index' => 'index'], ['index', '_format'], ['GET' => 0, 'HEAD' => 1], null, false, true, null]],
        191 => [[['_route' => 'api_jsonld_context', '_controller' => 'api_platform.jsonld.action.context', '_format' => 'jsonld', '_api_respond' => true], ['shortName', '_format'], ['GET' => 0, 'HEAD' => 1], null, false, true, null]],
        229 => [[['_route' => '_api_/contact_messages{._format}_get_collection', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\ContactMessage', '_api_operation_name' => '_api_/contact_messages{._format}_get_collection', '_format' => null], ['_format'], ['GET' => 0], null, false, true, null]],
        263 => [[['_route' => '_api_/contact_messages/{id}{._format}_get', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\ContactMessage', '_api_operation_name' => '_api_/contact_messages/{id}{._format}_get', '_format' => null], ['id', '_format'], ['GET' => 0], null, false, true, null]],
        286 => [[['_route' => '_api_/contact_messages{._format}_post', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\ContactMessage', '_api_operation_name' => '_api_/contact_messages{._format}_post', '_format' => null], ['_format'], ['POST' => 0], null, false, true, null]],
        323 => [
            [['_route' => '_api_/contact_messages/{id}{._format}_patch', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\ContactMessage', '_api_operation_name' => '_api_/contact_messages/{id}{._format}_patch', '_format' => null], ['id', '_format'], ['PATCH' => 0], null, false, true, null],
            [['_route' => '_api_/contact_messages/{id}{._format}_delete', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\ContactMessage', '_api_operation_name' => '_api_/contact_messages/{id}{._format}_delete', '_format' => null], ['id', '_format'], ['DELETE' => 0], null, false, true, null],
        ],
        372 => [[['_route' => '_api_/carousel_slides{._format}_get_collection', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\CarouselSlide', '_api_operation_name' => '_api_/carousel_slides{._format}_get_collection', '_format' => null], ['_format'], ['GET' => 0], null, false, true, null]],
        406 => [[['_route' => '_api_/carousel_slides/{id}{._format}_get', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\CarouselSlide', '_api_operation_name' => '_api_/carousel_slides/{id}{._format}_get', '_format' => null], ['id', '_format'], ['GET' => 0], null, false, true, null]],
        429 => [[['_route' => '_api_/carousel_slides{._format}_post', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\CarouselSlide', '_api_operation_name' => '_api_/carousel_slides{._format}_post', '_format' => null], ['_format'], ['POST' => 0], null, false, true, null]],
        466 => [
            [['_route' => '_api_/carousel_slides/{id}{._format}_patch', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\CarouselSlide', '_api_operation_name' => '_api_/carousel_slides/{id}{._format}_patch', '_format' => null], ['id', '_format'], ['PATCH' => 0], null, false, true, null],
            [['_route' => '_api_/carousel_slides/{id}{._format}_delete', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\CarouselSlide', '_api_operation_name' => '_api_/carousel_slides/{id}{._format}_delete', '_format' => null], ['id', '_format'], ['DELETE' => 0], null, false, true, null],
        ],
        499 => [[['_route' => '_api_/carts{._format}_post', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\Cart', '_api_operation_name' => '_api_/carts{._format}_post', '_format' => null], ['_format'], ['POST' => 0], null, false, true, null]],
        536 => [
            [['_route' => '_api_/carts/{id}{._format}_get', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\Cart', '_api_operation_name' => '_api_/carts/{id}{._format}_get', '_format' => null], ['id', '_format'], ['GET' => 0], null, false, true, null],
            [['_route' => '_api_/carts/{id}{._format}_patch', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\Cart', '_api_operation_name' => '_api_/carts/{id}{._format}_patch', '_format' => null], ['id', '_format'], ['PATCH' => 0], null, false, true, null],
            [['_route' => '_api_/carts/{id}{._format}_delete', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\Cart', '_api_operation_name' => '_api_/carts/{id}{._format}_delete', '_format' => null], ['id', '_format'], ['DELETE' => 0], null, false, true, null],
        ],
        570 => [[['_route' => '_api_/cart_items{._format}_post', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\CartItem', '_api_operation_name' => '_api_/cart_items{._format}_post', '_format' => null], ['_format'], ['POST' => 0], null, false, true, null]],
        607 => [
            [['_route' => '_api_/cart_items/{id}{._format}_get', '_controller' => 'api_platform.action.not_exposed', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\CartItem', '_api_operation_name' => '_api_/cart_items/{id}{._format}_get', '_format' => null], ['id', '_format'], ['GET' => 0], null, false, true, null],
            [['_route' => '_api_/cart_items/{id}{._format}_patch', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\CartItem', '_api_operation_name' => '_api_/cart_items/{id}{._format}_patch', '_format' => null], ['id', '_format'], ['PATCH' => 0], null, false, true, null],
            [['_route' => '_api_/cart_items/{id}{._format}_delete', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\CartItem', '_api_operation_name' => '_api_/cart_items/{id}{._format}_delete', '_format' => null], ['id', '_format'], ['DELETE' => 0], null, false, true, null],
        ],
        645 => [[['_route' => '_api_/categories{._format}_get_collection', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\Category', '_api_operation_name' => '_api_/categories{._format}_get_collection', '_format' => null], ['_format'], ['GET' => 0], null, false, true, null]],
        679 => [[['_route' => '_api_/categories/{id}{._format}_get', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\Category', '_api_operation_name' => '_api_/categories/{id}{._format}_get', '_format' => null], ['id', '_format'], ['GET' => 0], null, false, true, null]],
        702 => [[['_route' => '_api_/categories{._format}_post', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\Category', '_api_operation_name' => '_api_/categories{._format}_post', '_format' => null], ['_format'], ['POST' => 0], null, false, true, null]],
        739 => [
            [['_route' => '_api_/categories/{id}{._format}_patch', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\Category', '_api_operation_name' => '_api_/categories/{id}{._format}_patch', '_format' => null], ['id', '_format'], ['PATCH' => 0], null, false, true, null],
            [['_route' => '_api_/categories/{id}{._format}_delete', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\Category', '_api_operation_name' => '_api_/categories/{id}{._format}_delete', '_format' => null], ['id', '_format'], ['DELETE' => 0], null, false, true, null],
        ],
        778 => [[['_route' => '_api_errors', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => null, '_api_resource_class' => 'ApiPlatform\\State\\ApiResource\\Error', '_api_operation_name' => '_api_errors', '_format' => null], ['status', '_format'], ['GET' => 0], null, false, true, null]],
        815 => [
            [['_route' => '_api_validation_errors_problem', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => null, '_api_resource_class' => 'ApiPlatform\\Validator\\Exception\\ValidationException', '_api_operation_name' => '_api_validation_errors_problem', '_format' => null], ['id'], ['GET' => 0], null, false, true, null],
            [['_route' => '_api_validation_errors_hydra', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => null, '_api_resource_class' => 'ApiPlatform\\Validator\\Exception\\ValidationException', '_api_operation_name' => '_api_validation_errors_hydra', '_format' => null], ['id'], ['GET' => 0], null, false, true, null],
            [['_route' => '_api_validation_errors_jsonapi', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => null, '_api_resource_class' => 'ApiPlatform\\Validator\\Exception\\ValidationException', '_api_operation_name' => '_api_validation_errors_jsonapi', '_format' => null], ['id'], ['GET' => 0], null, false, true, null],
            [['_route' => '_api_validation_errors_xml', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => null, '_api_resource_class' => 'ApiPlatform\\Validator\\Exception\\ValidationException', '_api_operation_name' => '_api_validation_errors_xml', '_format' => null], ['id'], ['GET' => 0], null, false, true, null],
        ],
        851 => [[['_route' => '_api_/addresses{._format}_get_collection', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\Address', '_api_operation_name' => '_api_/addresses{._format}_get_collection', '_format' => null], ['_format'], ['GET' => 0], null, false, true, null]],
        885 => [[['_route' => '_api_/addresses/{id}{._format}_get', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\Address', '_api_operation_name' => '_api_/addresses/{id}{._format}_get', '_format' => null], ['id', '_format'], ['GET' => 0], null, false, true, null]],
        908 => [[['_route' => '_api_/addresses{._format}_post', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\Address', '_api_operation_name' => '_api_/addresses{._format}_post', '_format' => null], ['_format'], ['POST' => 0], null, false, true, null]],
        945 => [
            [['_route' => '_api_/addresses/{id}{._format}_patch', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\Address', '_api_operation_name' => '_api_/addresses/{id}{._format}_patch', '_format' => null], ['id', '_format'], ['PATCH' => 0], null, false, true, null],
            [['_route' => '_api_/addresses/{id}{._format}_delete', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\Address', '_api_operation_name' => '_api_/addresses/{id}{._format}_delete', '_format' => null], ['id', '_format'], ['DELETE' => 0], null, false, true, null],
        ],
        987 => [[['_route' => '_api_/homepage_texts{._format}_get_collection', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\HomepageText', '_api_operation_name' => '_api_/homepage_texts{._format}_get_collection', '_format' => null], ['_format'], ['GET' => 0], null, false, true, null]],
        1021 => [[['_route' => '_api_/homepage_texts/{id}{._format}_get', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\HomepageText', '_api_operation_name' => '_api_/homepage_texts/{id}{._format}_get', '_format' => null], ['id', '_format'], ['GET' => 0], null, false, true, null]],
        1045 => [[['_route' => '_api_/homepage_texts{._format}_post', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\HomepageText', '_api_operation_name' => '_api_/homepage_texts{._format}_post', '_format' => null], ['_format'], ['POST' => 0], null, false, true, null]],
        1083 => [
            [['_route' => '_api_/homepage_texts/{id}{._format}_patch', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\HomepageText', '_api_operation_name' => '_api_/homepage_texts/{id}{._format}_patch', '_format' => null], ['id', '_format'], ['PATCH' => 0], null, false, true, null],
            [['_route' => '_api_/homepage_texts/{id}{._format}_delete', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\HomepageText', '_api_operation_name' => '_api_/homepage_texts/{id}{._format}_delete', '_format' => null], ['id', '_format'], ['DELETE' => 0], null, false, true, null],
        ],
        1120 => [[['_route' => '_api_/invoices{._format}_get_collection', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\Invoice', '_api_operation_name' => '_api_/invoices{._format}_get_collection', '_format' => null], ['_format'], ['GET' => 0], null, false, true, null]],
        1155 => [[['_route' => '_api_/invoices/{id}{._format}_get', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\Invoice', '_api_operation_name' => '_api_/invoices/{id}{._format}_get', '_format' => null], ['id', '_format'], ['GET' => 0], null, false, true, null]],
        1192 => [[['_route' => '_api_/orders{._format}_get_collection', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\Order', '_api_operation_name' => '_api_/orders{._format}_get_collection', '_format' => null], ['_format'], ['GET' => 0], null, false, true, null]],
        1227 => [[['_route' => '_api_/orders/{id}{._format}_get', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\Order', '_api_operation_name' => '_api_/orders/{id}{._format}_get', '_format' => null], ['id', '_format'], ['GET' => 0], null, false, true, null]],
        1251 => [[['_route' => '_api_/orders{._format}_post', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\Order', '_api_operation_name' => '_api_/orders{._format}_post', '_format' => null], ['_format'], ['POST' => 0], null, false, true, null]],
        1289 => [
            [['_route' => '_api_/orders/{id}{._format}_patch', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\Order', '_api_operation_name' => '_api_/orders/{id}{._format}_patch', '_format' => null], ['id', '_format'], ['PATCH' => 0], null, false, true, null],
            [['_route' => '_api_/orders/{id}{._format}_delete', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\Order', '_api_operation_name' => '_api_/orders/{id}{._format}_delete', '_format' => null], ['id', '_format'], ['DELETE' => 0], null, false, true, null],
        ],
        1332 => [[['_route' => '_api_/order_items/{id}{._format}_get', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\OrderItem', '_api_operation_name' => '_api_/order_items/{id}{._format}_get', '_format' => null], ['id', '_format'], ['GET' => 0], null, false, true, null]],
        1378 => [[['_route' => '_api_/payment_methods{._format}_get_collection', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\PaymentMethod', '_api_operation_name' => '_api_/payment_methods{._format}_get_collection', '_format' => null], ['_format'], ['GET' => 0], null, false, true, null]],
        1413 => [[['_route' => '_api_/payment_methods/{id}{._format}_get', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\PaymentMethod', '_api_operation_name' => '_api_/payment_methods/{id}{._format}_get', '_format' => null], ['id', '_format'], ['GET' => 0], null, false, true, null]],
        1437 => [[['_route' => '_api_/payment_methods{._format}_post', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\PaymentMethod', '_api_operation_name' => '_api_/payment_methods{._format}_post', '_format' => null], ['_format'], ['POST' => 0], null, false, true, null]],
        1475 => [
            [['_route' => '_api_/payment_methods/{id}{._format}_patch', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\PaymentMethod', '_api_operation_name' => '_api_/payment_methods/{id}{._format}_patch', '_format' => null], ['id', '_format'], ['PATCH' => 0], null, false, true, null],
            [['_route' => '_api_/payment_methods/{id}{._format}_delete', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\PaymentMethod', '_api_operation_name' => '_api_/payment_methods/{id}{._format}_delete', '_format' => null], ['id', '_format'], ['DELETE' => 0], null, false, true, null],
        ],
        1514 => [[['_route' => '_api_/promo_codes{._format}_get_collection', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\PromoCode', '_api_operation_name' => '_api_/promo_codes{._format}_get_collection', '_format' => null], ['_format'], ['GET' => 0], null, false, true, null]],
        1549 => [[['_route' => '_api_/promo_codes/{id}{._format}_get', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\PromoCode', '_api_operation_name' => '_api_/promo_codes/{id}{._format}_get', '_format' => null], ['id', '_format'], ['GET' => 0], null, false, true, null]],
        1573 => [[['_route' => '_api_/promo_codes{._format}_post', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\PromoCode', '_api_operation_name' => '_api_/promo_codes{._format}_post', '_format' => null], ['_format'], ['POST' => 0], null, false, true, null]],
        1611 => [
            [['_route' => '_api_/promo_codes/{id}{._format}_patch', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\PromoCode', '_api_operation_name' => '_api_/promo_codes/{id}{._format}_patch', '_format' => null], ['id', '_format'], ['PATCH' => 0], null, false, true, null],
            [['_route' => '_api_/promo_codes/{id}{._format}_delete', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\PromoCode', '_api_operation_name' => '_api_/promo_codes/{id}{._format}_delete', '_format' => null], ['id', '_format'], ['DELETE' => 0], null, false, true, null],
        ],
        1654 => [[['_route' => '_api_/saas_services{._format}_get_collection', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\SaasService', '_api_operation_name' => '_api_/saas_services{._format}_get_collection', '_format' => null], ['_format'], ['GET' => 0], null, false, true, null]],
        1689 => [[['_route' => '_api_/saas_services/{id}{._format}_get', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\SaasService', '_api_operation_name' => '_api_/saas_services/{id}{._format}_get', '_format' => null], ['id', '_format'], ['GET' => 0], null, false, true, null]],
        1713 => [[['_route' => '_api_/saas_services{._format}_post', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\SaasService', '_api_operation_name' => '_api_/saas_services{._format}_post', '_format' => null], ['_format'], ['POST' => 0], null, false, true, null]],
        1751 => [
            [['_route' => '_api_/saas_services/{id}{._format}_patch', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\SaasService', '_api_operation_name' => '_api_/saas_services/{id}{._format}_patch', '_format' => null], ['id', '_format'], ['PATCH' => 0], null, false, true, null],
            [['_route' => '_api_/saas_services/{id}{._format}_delete', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\SaasService', '_api_operation_name' => '_api_/saas_services/{id}{._format}_delete', '_format' => null], ['id', '_format'], ['DELETE' => 0], null, false, true, null],
        ],
        1785 => [[['_route' => '_api_/users{._format}_get_collection', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\User', '_api_operation_name' => '_api_/users{._format}_get_collection', '_format' => null], ['_format'], ['GET' => 0], null, false, true, null]],
        1820 => [[['_route' => '_api_/users/{id}{._format}_get', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\User', '_api_operation_name' => '_api_/users/{id}{._format}_get', '_format' => null], ['id', '_format'], ['GET' => 0], null, false, true, null]],
        1844 => [[['_route' => '_api_/users{._format}_post', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\User', '_api_operation_name' => '_api_/users{._format}_post', '_format' => null], ['_format'], ['POST' => 0], null, false, true, null]],
        1882 => [
            [['_route' => '_api_/users/{id}{._format}_patch', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\User', '_api_operation_name' => '_api_/users/{id}{._format}_patch', '_format' => null], ['id', '_format'], ['PATCH' => 0], null, false, true, null],
            [['_route' => '_api_/users/{id}{._format}_delete', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\User', '_api_operation_name' => '_api_/users/{id}{._format}_delete', '_format' => null], ['id', '_format'], ['DELETE' => 0], null, false, true, null],
        ],
        1926 => [[['_route' => '_preview_error', '_controller' => 'error_controller::preview', '_format' => 'html'], ['code', '_format'], null, null, false, true, null]],
        1947 => [[['_route' => '_wdt', '_controller' => 'web_profiler.controller.profiler::toolbarAction'], ['token'], null, null, false, true, null]],
        1990 => [[['_route' => '_profiler_font', '_controller' => 'web_profiler.controller.profiler::fontAction'], ['fontName'], null, null, false, false, null]],
        2028 => [[['_route' => '_profiler_search_results', '_controller' => 'web_profiler.controller.profiler::searchResultsAction'], ['token'], null, null, false, false, null]],
        2043 => [[['_route' => '_profiler_router', '_controller' => 'web_profiler.controller.router::panelAction'], ['token'], null, null, false, false, null]],
        2064 => [[['_route' => '_profiler_exception', '_controller' => 'web_profiler.controller.exception_panel::body'], ['token'], null, null, false, false, null]],
        2078 => [[['_route' => '_profiler_exception_css', '_controller' => 'web_profiler.controller.exception_panel::stylesheet'], ['token'], null, null, false, false, null]],
        2089 => [
            [['_route' => '_profiler', '_controller' => 'web_profiler.controller.profiler::panelAction'], ['token'], null, null, false, true, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];
