<?php

/** Created by griga at 15.07.2014 | 20:40.
 * 
 */



class SeoDispatcher extends CApplicationComponent {

    public $default_title_key = "site_name";
    public $default_description_key = "site_description";
    public $default_keywords_key = "site_keywords";


    public function registerSeo($model = null){
        if(!$model){
            $this->registerPageTitle($this->render(SeoConfig::get($this->default_title_key)));
            $this->registerDescription($this->render(SeoConfig::get($this->default_description_key)));
            $this->registerKeywords($this->render(SeoConfig::get($this->default_keywords_key)));
        }  else {
            $prefix = underscore_case(get_class($model));
            $this->registerPageTitle($this->render(SeoConfig::get($prefix.'_page_title'),$model));
            $this->registerDescription($this->render(SeoConfig::get($prefix.'_description'),$model));
            $this->registerKeywords($this->render(SeoConfig::get($prefix.'_keywords'),$model));
        }
    }

    public function registerPageTitle($title){
        app()->controller->pageTitle = $title;
    }

    public function registerDescription($content){
        $this->registerMetaTag($content, 'description');
    }

    public function registerKeywords($content){
        $this->registerMetaTag($content, 'keywords');
    }

    public function registerMetaTag($content, $name){
        cs()->registerMetaTag($content, $name);
    }

    private function render($template, $model = null){
        if(preg_match('/{{.+}}/', $template)){
            $variables = SeoConfig::getCache();
            if($model)
                $variables[underscore_case(get_class($model))] = $model;

            return app()->mustache->compile($template, $variables);
        } else {
            return $template;
        }

    }
} 