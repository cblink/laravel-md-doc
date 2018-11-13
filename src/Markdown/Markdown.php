<?php


namespace Cblink\MarkdownDoc\Markdown;


class Markdown
{

    public function h2(string $title)
    {
        return sprintf('## %s', $title).PHP_EOL.PHP_EOL;
    }

    public function h3(string $title)
    {
        return sprintf('### %s', $title).PHP_EOL.PHP_EOL;
    }

    public function text(string $text)
    {
        return $text.PHP_EOL.PHP_EOL;
    }

    public function link(string $label, string $uri)
    {
        return sprintf('[%s](%s)', $label, $uri).PHP_EOL;
    }

    public function list(string $text)
    {
        return sprintf('* %s', $text);
    }

    public function table(array $header, array $data)
    {
        $str = $this->tableHeader($header);
        foreach ($data as $rows) {
            $str .= '|';
            foreach ($rows as $row) {
                $str .= $row . '|';
            }
            $str .= PHP_EOL;
        }

        return $str;
    }

    public function tableHeader(array $header)
    {
        $str = '|'.implode('|', $header).'|'.PHP_EOL;

        $str .= '|'.implode('|', array_fill(0, count($header), '---')).'|'.PHP_EOL;

        return $str;
    }

    public function tableRow(array $data)
    {
        $str = '|';

        foreach ($data as $row) {
            $str .= $row . '|';
        }

        $str .= PHP_EOL;

        return $str;
    }

    public function newline()
    {
        return PHP_EOL;
    }
}