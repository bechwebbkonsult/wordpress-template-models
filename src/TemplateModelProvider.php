<?php

namespace Bechwebb\TemplateModels;

class TemplateModelProvider
{
    protected $models = [];

    public function __construct()
    {
        add_filter('template_include', [$this, 'hook_template_include'], 99);
        add_action('template_model_include', [$this, 'hook_template_include'], 10, 2);
    }


    public function map($templates, $callback)
    {
        foreach ((array) $templates as $view) {
            $this->models[$view] = $callback;
        }
    }


    public function hook_template_include($template, $arguments = [])
    {
        $clean_template = str_replace(get_template_directory(), '', $template);

        if (!isset($this->models[$clean_template])) {
            return $template;
        }

        if (!class_exists($this->models[$clean_template])) {
            throw new \Exception($this->models[$clean_template].' class not found');
        }


        $composer = new $this->models[$clean_template](...$arguments);
        return $composer->getTemplate($clean_template);
        die();
    }
}
