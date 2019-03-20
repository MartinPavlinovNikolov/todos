<?php

namespace Marto\Todos;

use Illuminate\Support\ServiceProvider;

class TodosServiceProvider extends ServiceProvider
{
    public function boot()
    {
       $this->loadRoutesFrom(__DIR__.'/routes/web.php'); 
       $this->loadViewsFrom(__DIR__.'/views', 'todos');
       $this->mergeConfigFrom(__DIR__.'/config/todos.php', 'todos');
    }

    public function register()
    {
        
    }
}