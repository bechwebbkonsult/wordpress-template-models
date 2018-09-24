<?php

if (!function_exists('get_model_template')) {
    function get_model_template($template, ...$arguments)
    {
        do_action('template_model_include', $template, $arguments);
    }
}
