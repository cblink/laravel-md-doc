<?php


namespace Cblink\MarkdownDoc;


use Cblink\MarkdownDoc\Commands\RouteCommand;
use Illuminate\Support\ServiceProvider;

class MarkdownDocServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->commands(RouteCommand::class);
    }
}