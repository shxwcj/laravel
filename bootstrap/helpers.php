<?php
/**
 * @desc 此方法会将当前请求的路由名称转换为 CSS 类名称，作用是允许我们针对某个页面做页面样式定制
 *
 * @return mixed
 */
function route_class()
{
    return str_replace('.', '-', Route::currentRouteName());
}

/**
 * @desc 配置暂时的公关外网环境
 * @param $routeName
 * @param array $parameters
 * @return string
 */
function ngrok_url($routeName, $parameters = [])
{
    // 开发环境，并且配置了 NGROK_URL
    if(app()->environment('local') && $url = config('app.ngrok_url')) {
        // route() 函数第三个参数代表是否绝对路径
        return $url.route($routeName, $parameters, false);
    }

    return route($routeName, $parameters);
}

function big_number($number,$scale = 2)
{
    return new \Moontoast\Math\BigNumber($number,$scale);
}