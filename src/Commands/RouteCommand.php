<?php


namespace Cblink\MarkdownDoc\Commands;


use Cblink\MarkdownDoc\Markdown\Markdown;
use Cblink\MarkdownDoc\Markdown\Writer;
use Illuminate\Foundation\Console\RouteListCommand;
use Symfony\Component\Console\Input\InputOption;

class RouteCommand extends RouteListCommand
{
    protected $name = 'doc:md';

    public function handle()
    {
        if ($this->option('single')) {
            $this->single();
        } else {
            $this->module();
        }
    }

    public function single()
    {
        $writer = new Writer();

        $markdown = new Markdown();

        $routes = $this->getRoutes();


        if (!file_exists($this->option('dir'))) {
            mkdir($this->option('dir'), 0777, true);
        }

        $writer = $writer->open($this->option('dir').DIRECTORY_SEPARATOR.$this->option('filename'));

        foreach ($routes as $route) {
            $url = $route['method'].':'.($route['host'] ?: config('app.url').'/').$route['uri'];

            if ($writer->hasContent($url)) {
                continue;
            }

            $writer->write($markdown->h2('待补充'))
                ->write($markdown->text($url))
                ->write($markdown->text('中间件：'.$route['middleware']))
                ->write($markdown->tableHeader([
                    '参数', '类型', '默认值', '备注',
                ]))->write($markdown->tableRow([
                        'param', 'type', '', '-',
                    ]).PHP_EOL.PHP_EOL);
        }

        $writer->close();
    }

    public function module()
    {
        $markdown = new Markdown();

        if (!file_exists('docs')) {
            mkdir('docs', 0777, true);
        }

        $home = (new Writer())->open('docs/README.md');

        $routes = $this->getRoutes();

        foreach ($routes as $route) {
            $writer = new Writer();

            $path = 'docs'.DIRECTORY_SEPARATOR.$route['uri'];

            if (str_contains($path, '{')) {
                $path = str_before($path, '{');
            }

            if (str_contains($path, 'create')) {
                $path = str_before($path, 'create');
            }

            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            $file = $path.DIRECTORY_SEPARATOR.$this->option('filename');

            if (file_exists($file)) {
                $writer->open($file);
            } else {
                $writer->open($file);
                $writer->write($markdown->h2(ucfirst(substr($path, strrpos($path, '/') + 1))));
            }

            $url = $route['method'].':'.($route['host'] ?: config('app.url').'/').$route['uri'];

            if ($writer->hasContent($url)) {
                continue;
            }

            $link = substr(str_finish(str_replace('\\', '/', $path), '/'), 0, -1);

            $home->write($markdown->list($markdown->link($link, $link.'#')));

            $writer->write($markdown->h3('待补充'))
                ->write($markdown->text($url))
                ->write($markdown->text('中间件：'.$route['middleware']))
                ->write($markdown->tableHeader([
                    '参数', '类型', '默认值', '备注',
                ]))->write($markdown->tableRow([
                        'param', 'type', '', '-',
                    ]).PHP_EOL.PHP_EOL);

            $writer->close();
        }
    }

    protected function getOptions()
    {
        return array_merge([
            ['single', null, InputOption::VALUE_NONE, ''],
            ['dir', null, InputOption::VALUE_OPTIONAL, ''],
            ['filename', null, InputOption::VALUE_REQUIRED, '', 'README.md'],
        ], parent::getOptions());
    }
}