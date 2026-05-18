<?php

/**
 * This file has been auto-generated
 * by the Symfony Routing Component.
 */

return [
    false, // $matchHost
    [ // $staticRoutes
        '/2fa' => [[['_route' => '2fa_login', '_controller' => 'scheb_two_factor.form_controller::form'], null, null, null, false, false, null]],
        '/2fa_check' => [[['_route' => '2fa_login_check'], null, null, null, false, false, null]],
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
        '/api/chatbot/message' => [[['_route' => 'chatbot_message', '_controller' => 'App\\Controller\\ChatbotController::message'], null, ['POST' => 0], null, false, false, null]],
        '/' => [[['_route' => 'app_home', '_controller' => 'App\\Controller\\HomeController::index'], null, null, null, false, false, null]],
        '/api/home' => [[['_route' => 'api_home', '_controller' => 'App\\Controller\\HomeController::apiHome'], null, ['GET' => 0], null, false, false, null]],
        '/api/security/2fa/setup' => [[['_route' => 'api_2fa_setup', '_controller' => 'App\\Controller\\Security\\TwoFactorController::setup'], null, ['POST' => 0], null, false, false, null]],
        '/api/security/2fa/enable' => [[['_route' => 'api_2fa_enable', '_controller' => 'App\\Controller\\Security\\TwoFactorController::enable'], null, ['POST' => 0], null, false, false, null]],
        '/api/security/2fa/disable' => [[['_route' => 'api_2fa_disable', '_controller' => 'App\\Controller\\Security\\TwoFactorController::disable'], null, ['POST' => 0], null, false, false, null]],
        '/api/security/2fa/test' => [[['_route' => 'api_2fa_test', '_controller' => 'App\\Controller\\Security\\TwoFactorController::test'], null, ['POST' => 0], null, false, false, null]],
        '/api/security/2fa/toggle-login' => [[['_route' => 'api_2fa_toggle_login', '_controller' => 'App\\Controller\\Security\\TwoFactorController::toggleLogin'], null, ['POST' => 0], null, false, false, null]],
        '/api/login/2fa-verify' => [[['_route' => 'api_login_2fa_verify', '_controller' => 'App\\Controller\\Security\\TwoFactorLoginController::verify'], null, ['POST' => 0], null, false, false, null]],
        '/api/login_check' => [[['_route' => 'api_login_check'], null, null, null, false, false, null]],
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
                            .'|hatbot_conversations(?'
                                .'|(?:\\.([^/]++))?(*:788)'
                                .'|/([^/\\.]++)(?:\\.([^/]++))?(?'
                                    .'|(*:825)'
                                .')'
                            .')'
                        .')'
                        .'|errors/(\\d+)(?:\\.([^/]++))?(*:863)'
                        .'|validation_errors/([^/]++)(?'
                            .'|(*:900)'
                        .')'
                        .'|addresses(?'
                            .'|(?:\\.([^/]++))?(*:936)'
                            .'|/([^/\\.]++)(?:\\.([^/]++))?(*:970)'
                            .'|(?:\\.([^/]++))?(*:993)'
                            .'|/([^/\\.]++)(?:\\.([^/]++))?(?'
                                .'|(*:1030)'
                            .')'
                        .')'
                        .'|homepage_texts(?'
                            .'|(?:\\.([^/]++))?(*:1073)'
                            .'|/([^/\\.]++)(?:\\.([^/]++))?(*:1108)'
                            .'|(?:\\.([^/]++))?(*:1132)'
                            .'|/([^/\\.]++)(?:\\.([^/]++))?(?'
                                .'|(*:1170)'
                            .')'
                        .')'
                        .'|invoices(?'
                            .'|(?:\\.([^/]++))?(*:1207)'
                            .'|/([^/\\.]++)(?:\\.([^/]++))?(*:1242)'
                        .')'
                        .'|order(?'
                            .'|s(?'
                                .'|(?:\\.([^/]++))?(*:1279)'
                                .'|/([^/\\.]++)(?:\\.([^/]++))?(*:1314)'
                                .'|(?:\\.([^/]++))?(*:1338)'
                                .'|/([^/\\.]++)(?:\\.([^/]++))?(?'
                                    .'|(*:1376)'
                                .')'
                            .')'
                            .'|_items/([^/\\.]++)(?:\\.([^/]++))?(*:1419)'
                        .')'
                        .'|p(?'
                            .'|ayment_methods(?'
                                .'|(?:\\.([^/]++))?(*:1465)'
                                .'|/([^/\\.]++)(?:\\.([^/]++))?(*:1500)'
                                .'|(?:\\.([^/]++))?(*:1524)'
                                .'|/([^/\\.]++)(?:\\.([^/]++))?(?'
                                    .'|(*:1562)'
                                .')'
                            .')'
                            .'|romo_codes(?'
                                .'|(?:\\.([^/]++))?(*:1601)'
                                .'|/([^/\\.]++)(?:\\.([^/]++))?(*:1636)'
                                .'|(?:\\.([^/]++))?(*:1660)'
                                .'|/([^/\\.]++)(?:\\.([^/]++))?(?'
                                    .'|(*:1698)'
                                .')'
                            .')'
                        .')'
                        .'|saas_services(?'
                            .'|(?:\\.([^/]++))?(*:1741)'
                            .'|/([^/\\.]++)(?:\\.([^/]++))?(*:1776)'
                            .'|(?:\\.([^/]++))?(*:1800)'
                            .'|/([^/\\.]++)(?:\\.([^/]++))?(?'
                                .'|(*:1838)'
                            .')'
                        .')'
                        .'|users(?'
                            .'|(?:\\.([^/]++))?(*:1872)'
                            .'|/([^/\\.]++)(?:\\.([^/]++))?(*:1907)'
                            .'|(?:\\.([^/]++))?(*:1931)'
                            .'|/([^/\\.]++)(?:\\.([^/]++))?(?'
                                .'|(*:1969)'
                            .')'
                        .')'
                    .')'
                .')'
                .'|/_(?'
                    .'|error/(\\d+)(?:\\.([^/]++))?(*:2013)'
                    .'|wdt/([^/]++)(*:2034)'
                    .'|profiler/(?'
                        .'|font/([^/\\.]++)\\.woff2(*:2077)'
                        .'|([^/]++)(?'
                            .'|/(?'
                                .'|search/results(*:2115)'
                                .'|router(*:2130)'
                                .'|exception(?'
                                    .'|(*:2151)'
                                    .'|\\.css(*:2165)'
                                .')'
                            .')'
                            .'|(*:2176)'
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
        788 => [[['_route' => '_api_/chatbot_conversations{._format}_get_collection', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\ChatbotConversation', '_api_operation_name' => '_api_/chatbot_conversations{._format}_get_collection', '_format' => null], ['_format'], ['GET' => 0], null, false, true, null]],
        825 => [
            [['_route' => '_api_/chatbot_conversations/{id}{._format}_get', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\ChatbotConversation', '_api_operation_name' => '_api_/chatbot_conversations/{id}{._format}_get', '_format' => null], ['id', '_format'], ['GET' => 0], null, false, true, null],
            [['_route' => '_api_/chatbot_conversations/{id}{._format}_patch', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\ChatbotConversation', '_api_operation_name' => '_api_/chatbot_conversations/{id}{._format}_patch', '_format' => null], ['id', '_format'], ['PATCH' => 0], null, false, true, null],
            [['_route' => '_api_/chatbot_conversations/{id}{._format}_delete', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\ChatbotConversation', '_api_operation_name' => '_api_/chatbot_conversations/{id}{._format}_delete', '_format' => null], ['id', '_format'], ['DELETE' => 0], null, false, true, null],
        ],
        863 => [[['_route' => '_api_errors', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => null, '_api_resource_class' => 'ApiPlatform\\State\\ApiResource\\Error', '_api_operation_name' => '_api_errors', '_format' => null], ['status', '_format'], ['GET' => 0], null, false, true, null]],
        900 => [
            [['_route' => '_api_validation_errors_problem', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => null, '_api_resource_class' => 'ApiPlatform\\Validator\\Exception\\ValidationException', '_api_operation_name' => '_api_validation_errors_problem', '_format' => null], ['id'], ['GET' => 0], null, false, true, null],
            [['_route' => '_api_validation_errors_hydra', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => null, '_api_resource_class' => 'ApiPlatform\\Validator\\Exception\\ValidationException', '_api_operation_name' => '_api_validation_errors_hydra', '_format' => null], ['id'], ['GET' => 0], null, false, true, null],
            [['_route' => '_api_validation_errors_jsonapi', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => null, '_api_resource_class' => 'ApiPlatform\\Validator\\Exception\\ValidationException', '_api_operation_name' => '_api_validation_errors_jsonapi', '_format' => null], ['id'], ['GET' => 0], null, false, true, null],
            [['_route' => '_api_validation_errors_xml', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => null, '_api_resource_class' => 'ApiPlatform\\Validator\\Exception\\ValidationException', '_api_operation_name' => '_api_validation_errors_xml', '_format' => null], ['id'], ['GET' => 0], null, false, true, null],
        ],
        936 => [[['_route' => '_api_/addresses{._format}_get_collection', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\Address', '_api_operation_name' => '_api_/addresses{._format}_get_collection', '_format' => null], ['_format'], ['GET' => 0], null, false, true, null]],
        970 => [[['_route' => '_api_/addresses/{id}{._format}_get', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\Address', '_api_operation_name' => '_api_/addresses/{id}{._format}_get', '_format' => null], ['id', '_format'], ['GET' => 0], null, false, true, null]],
        993 => [[['_route' => '_api_/addresses{._format}_post', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\Address', '_api_operation_name' => '_api_/addresses{._format}_post', '_format' => null], ['_format'], ['POST' => 0], null, false, true, null]],
        1030 => [
            [['_route' => '_api_/addresses/{id}{._format}_patch', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\Address', '_api_operation_name' => '_api_/addresses/{id}{._format}_patch', '_format' => null], ['id', '_format'], ['PATCH' => 0], null, false, true, null],
            [['_route' => '_api_/addresses/{id}{._format}_delete', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\Address', '_api_operation_name' => '_api_/addresses/{id}{._format}_delete', '_format' => null], ['id', '_format'], ['DELETE' => 0], null, false, true, null],
        ],
        1073 => [[['_route' => '_api_/homepage_texts{._format}_get_collection', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\HomepageText', '_api_operation_name' => '_api_/homepage_texts{._format}_get_collection', '_format' => null], ['_format'], ['GET' => 0], null, false, true, null]],
        1108 => [[['_route' => '_api_/homepage_texts/{id}{._format}_get', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\HomepageText', '_api_operation_name' => '_api_/homepage_texts/{id}{._format}_get', '_format' => null], ['id', '_format'], ['GET' => 0], null, false, true, null]],
        1132 => [[['_route' => '_api_/homepage_texts{._format}_post', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\HomepageText', '_api_operation_name' => '_api_/homepage_texts{._format}_post', '_format' => null], ['_format'], ['POST' => 0], null, false, true, null]],
        1170 => [
            [['_route' => '_api_/homepage_texts/{id}{._format}_patch', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\HomepageText', '_api_operation_name' => '_api_/homepage_texts/{id}{._format}_patch', '_format' => null], ['id', '_format'], ['PATCH' => 0], null, false, true, null],
            [['_route' => '_api_/homepage_texts/{id}{._format}_delete', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\HomepageText', '_api_operation_name' => '_api_/homepage_texts/{id}{._format}_delete', '_format' => null], ['id', '_format'], ['DELETE' => 0], null, false, true, null],
        ],
        1207 => [[['_route' => '_api_/invoices{._format}_get_collection', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\Invoice', '_api_operation_name' => '_api_/invoices{._format}_get_collection', '_format' => null], ['_format'], ['GET' => 0], null, false, true, null]],
        1242 => [[['_route' => '_api_/invoices/{id}{._format}_get', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\Invoice', '_api_operation_name' => '_api_/invoices/{id}{._format}_get', '_format' => null], ['id', '_format'], ['GET' => 0], null, false, true, null]],
        1279 => [[['_route' => '_api_/orders{._format}_get_collection', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\Order', '_api_operation_name' => '_api_/orders{._format}_get_collection', '_format' => null], ['_format'], ['GET' => 0], null, false, true, null]],
        1314 => [[['_route' => '_api_/orders/{id}{._format}_get', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\Order', '_api_operation_name' => '_api_/orders/{id}{._format}_get', '_format' => null], ['id', '_format'], ['GET' => 0], null, false, true, null]],
        1338 => [[['_route' => '_api_/orders{._format}_post', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\Order', '_api_operation_name' => '_api_/orders{._format}_post', '_format' => null], ['_format'], ['POST' => 0], null, false, true, null]],
        1376 => [
            [['_route' => '_api_/orders/{id}{._format}_patch', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\Order', '_api_operation_name' => '_api_/orders/{id}{._format}_patch', '_format' => null], ['id', '_format'], ['PATCH' => 0], null, false, true, null],
            [['_route' => '_api_/orders/{id}{._format}_delete', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\Order', '_api_operation_name' => '_api_/orders/{id}{._format}_delete', '_format' => null], ['id', '_format'], ['DELETE' => 0], null, false, true, null],
        ],
        1419 => [[['_route' => '_api_/order_items/{id}{._format}_get', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\OrderItem', '_api_operation_name' => '_api_/order_items/{id}{._format}_get', '_format' => null], ['id', '_format'], ['GET' => 0], null, false, true, null]],
        1465 => [[['_route' => '_api_/payment_methods{._format}_get_collection', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\PaymentMethod', '_api_operation_name' => '_api_/payment_methods{._format}_get_collection', '_format' => null], ['_format'], ['GET' => 0], null, false, true, null]],
        1500 => [[['_route' => '_api_/payment_methods/{id}{._format}_get', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\PaymentMethod', '_api_operation_name' => '_api_/payment_methods/{id}{._format}_get', '_format' => null], ['id', '_format'], ['GET' => 0], null, false, true, null]],
        1524 => [[['_route' => '_api_/payment_methods{._format}_post', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\PaymentMethod', '_api_operation_name' => '_api_/payment_methods{._format}_post', '_format' => null], ['_format'], ['POST' => 0], null, false, true, null]],
        1562 => [
            [['_route' => '_api_/payment_methods/{id}{._format}_patch', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\PaymentMethod', '_api_operation_name' => '_api_/payment_methods/{id}{._format}_patch', '_format' => null], ['id', '_format'], ['PATCH' => 0], null, false, true, null],
            [['_route' => '_api_/payment_methods/{id}{._format}_delete', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\PaymentMethod', '_api_operation_name' => '_api_/payment_methods/{id}{._format}_delete', '_format' => null], ['id', '_format'], ['DELETE' => 0], null, false, true, null],
        ],
        1601 => [[['_route' => '_api_/promo_codes{._format}_get_collection', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\PromoCode', '_api_operation_name' => '_api_/promo_codes{._format}_get_collection', '_format' => null], ['_format'], ['GET' => 0], null, false, true, null]],
        1636 => [[['_route' => '_api_/promo_codes/{id}{._format}_get', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\PromoCode', '_api_operation_name' => '_api_/promo_codes/{id}{._format}_get', '_format' => null], ['id', '_format'], ['GET' => 0], null, false, true, null]],
        1660 => [[['_route' => '_api_/promo_codes{._format}_post', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\PromoCode', '_api_operation_name' => '_api_/promo_codes{._format}_post', '_format' => null], ['_format'], ['POST' => 0], null, false, true, null]],
        1698 => [
            [['_route' => '_api_/promo_codes/{id}{._format}_patch', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\PromoCode', '_api_operation_name' => '_api_/promo_codes/{id}{._format}_patch', '_format' => null], ['id', '_format'], ['PATCH' => 0], null, false, true, null],
            [['_route' => '_api_/promo_codes/{id}{._format}_delete', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\PromoCode', '_api_operation_name' => '_api_/promo_codes/{id}{._format}_delete', '_format' => null], ['id', '_format'], ['DELETE' => 0], null, false, true, null],
        ],
        1741 => [[['_route' => '_api_/saas_services{._format}_get_collection', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\SaasService', '_api_operation_name' => '_api_/saas_services{._format}_get_collection', '_format' => null], ['_format'], ['GET' => 0], null, false, true, null]],
        1776 => [[['_route' => '_api_/saas_services/{id}{._format}_get', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\SaasService', '_api_operation_name' => '_api_/saas_services/{id}{._format}_get', '_format' => null], ['id', '_format'], ['GET' => 0], null, false, true, null]],
        1800 => [[['_route' => '_api_/saas_services{._format}_post', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\SaasService', '_api_operation_name' => '_api_/saas_services{._format}_post', '_format' => null], ['_format'], ['POST' => 0], null, false, true, null]],
        1838 => [
            [['_route' => '_api_/saas_services/{id}{._format}_patch', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\SaasService', '_api_operation_name' => '_api_/saas_services/{id}{._format}_patch', '_format' => null], ['id', '_format'], ['PATCH' => 0], null, false, true, null],
            [['_route' => '_api_/saas_services/{id}{._format}_delete', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\SaasService', '_api_operation_name' => '_api_/saas_services/{id}{._format}_delete', '_format' => null], ['id', '_format'], ['DELETE' => 0], null, false, true, null],
        ],
        1872 => [[['_route' => '_api_/users{._format}_get_collection', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\User', '_api_operation_name' => '_api_/users{._format}_get_collection', '_format' => null], ['_format'], ['GET' => 0], null, false, true, null]],
        1907 => [[['_route' => '_api_/users/{id}{._format}_get', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\User', '_api_operation_name' => '_api_/users/{id}{._format}_get', '_format' => null], ['id', '_format'], ['GET' => 0], null, false, true, null]],
        1931 => [[['_route' => '_api_/users{._format}_post', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\User', '_api_operation_name' => '_api_/users{._format}_post', '_format' => null], ['_format'], ['POST' => 0], null, false, true, null]],
        1969 => [
            [['_route' => '_api_/users/{id}{._format}_patch', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\User', '_api_operation_name' => '_api_/users/{id}{._format}_patch', '_format' => null], ['id', '_format'], ['PATCH' => 0], null, false, true, null],
            [['_route' => '_api_/users/{id}{._format}_delete', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\User', '_api_operation_name' => '_api_/users/{id}{._format}_delete', '_format' => null], ['id', '_format'], ['DELETE' => 0], null, false, true, null],
        ],
        2013 => [[['_route' => '_preview_error', '_controller' => 'error_controller::preview', '_format' => 'html'], ['code', '_format'], null, null, false, true, null]],
        2034 => [[['_route' => '_wdt', '_controller' => 'web_profiler.controller.profiler::toolbarAction'], ['token'], null, null, false, true, null]],
        2077 => [[['_route' => '_profiler_font', '_controller' => 'web_profiler.controller.profiler::fontAction'], ['fontName'], null, null, false, false, null]],
        2115 => [[['_route' => '_profiler_search_results', '_controller' => 'web_profiler.controller.profiler::searchResultsAction'], ['token'], null, null, false, false, null]],
        2130 => [[['_route' => '_profiler_router', '_controller' => 'web_profiler.controller.router::panelAction'], ['token'], null, null, false, false, null]],
        2151 => [[['_route' => '_profiler_exception', '_controller' => 'web_profiler.controller.exception_panel::body'], ['token'], null, null, false, false, null]],
        2165 => [[['_route' => '_profiler_exception_css', '_controller' => 'web_profiler.controller.exception_panel::stylesheet'], ['token'], null, null, false, false, null]],
        2176 => [
            [['_route' => '_profiler', '_controller' => 'web_profiler.controller.profiler::panelAction'], ['token'], null, null, false, true, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];
