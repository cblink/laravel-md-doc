<?php


namespace Cblink\MarkdownDoc\Markdown;


class Writer
{

    protected $file;

    public function open(string $path)
    {
        $this->file = fopen($path, 'a+');

        return $this;
    }

    public function hasContent($needle)
    {
        $valid = false;

        while (($buffer = fgets($this->file)) !== false) {
            if (strpos($buffer, $needle) !== false) {
                $valid = true;
                break; // Once you find the string, you should break out the loop.
            }
        }

        return $valid;
    }

    public function write(string $line)
    {
        fwrite($this->file, $line);

        return $this;
    }

    public function close()
    {
        fclose($this->file);
    }

    public function mkdir(string $path)
    {
        $path = pathinfo($path, PATHINFO_DIRNAME);

        dd(($path));
        if (is_dir($path)) {
            return true;
        } else {
            if ($this->mkdir($path)) {
                dd($path);
                if (mkdir($path)) {
                    chmod($path, 0777);
                    return true;
                }
            }
        }

        return false;
    }

}