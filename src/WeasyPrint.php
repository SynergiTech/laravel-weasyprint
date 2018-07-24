<?php

namespace SynergiTech\WeasyPrint;

use Illuminate\Support\Facades\View;
use Illuminate\Contracts\Support\Renderable;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class WeasyPrint
{
    /**
     * [protected description]
     * @var [type]
     */
    protected $html;

    /**
     * [protected description]
     * @var [type]
     */
    protected $timeout;

    /**
     * [protected description]
     * @var [type]
     */
    protected $temp;

    /**
     * [render description]
     * @param  [type] $type [description]
     * @param  [type] $html [description]
     * @return [type]       [description]
     */
    protected function render($type, $html)
    {
        $input = tempnam(($this->temp !== null ? $this->temp : sys_get_temp_dir()), 'weasyprint_');
        $output = tempnam(($this->temp !== null ? $this->temp : sys_get_temp_dir()), 'weasyprint_');

        if (file_put_contents($input, $html) === false) {
            throw new WeasyPrintException;
        }

        $process = new Process(array(config('weasyprint.binary'), '-f' . $type, $input, $output));
        $process->setTimeout($this->timeout !== null ? $this->timeout : config('weasyprint.timeout'));
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        unlink($input);

        return file_get_contents($output);
    }

    /**
     * [html description]
     * @param  [type] $html [description]
     * @return [type]       [description]
     */
    public function html($html)
    {
        if ($html instanceof Renderable) {
            $html = $html->render();
        }

        $this->html = $html;

        return $this;
    }

    /**
     * [setTimeout description]
     * @param  [type] $timeout [description]
     * @return [type]       [description]
     */
    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;

        return $this;
    }

    /**
     * [setTemporaryFolder description]
     * @param  [type] $folder [description]
     * @return [type]       [description]
     */
    public function setTemporaryFolder($folder)
    {
        $this->temp = $folder;

        return $this;
    }

    /**
     * [view description]
     * @param  [type] $view [description]
     * @param  array  $data [description]
     * @return [type]       [description]
     */
    public function view($view, $data = [])
    {
        $html = View::make($view, $data);

        return $this->html($html);
    }

    /**
     * [pdf description]
     * @return [type] [description]
     */
    public function pdf()
    {
        return $this->render('pdf', $this->html);
    }

    /**
     * [png description]
     * @return [type] [description]
     */
    public function png()
    {
        return $this->render('png', $this->html);
    }
}
