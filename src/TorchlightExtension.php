<?php
/**
 * @author Aaron Francis <aarondfrancis@gmail.com|https://twitter.com/aarondfrancis>
 */

namespace Torchlight\Ibis;

use Illuminate\Cache\FileStore;
use Illuminate\Cache\Repository;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Client\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use League\CommonMark\ConfigurableEnvironmentInterface;
use Torchlight\Block;
use Torchlight\Commonmark\TorchlightExtension as TorchlightCommonmark;
use Torchlight\Manager;
use Torchlight\Torchlight;

class TorchlightExtension
{
    protected $baseDir;

    public static function make($baseDir = null)
    {
        return new static($baseDir);
    }

    public function __construct($baseDir = null)
    {
        $this->baseDir = $baseDir ?? getcwd();
    }

    public function register(ConfigurableEnvironmentInterface $environment)
    {
        $this->swapFacadeInstances();
        $this->registerConfig();
        $this->registerCache();

        // Throw exceptions when something goes wrong, since this
        // is a local build tool.
        Torchlight::overrideEnvironment('local');

        (new TorchlightCommonmark)
            // Add a custom renderer for Ibis, because mPDF doesn't allow you
            // to change a `code` tag to display:block, which we absolutely
            // need. https://mpdf.github.io/html-support/html-tags.html.
            ->useCustomBlockRenderer(function (Block $block) {
                return "<pre><div class='{$block->classes}' style='{$block->styles}'>{$block->highlighted}</div></pre>";
            })
            // Bind the whole thing into the Commonmark environment.
            ->register($environment);
    }

    protected function swapFacadeInstances()
    {
        // There is no container in an Ibis project,
        // so we just swap instances in.
        Torchlight::swap(new Manager);
        Http::swap(new Factory);
    }

    protected function registerConfig()
    {
        // Pull the configuration from a file named 'torchlight.php' in the base dir.
        $file = $this->baseDir . DIRECTORY_SEPARATOR . 'torchlight.php';

        $config = file_exists($file) ? require $file : [];

        Torchlight::getConfigUsing(function ($key, $default) use ($config) {
            return Arr::get($config, $key, $default);
        });
    }

    protected function registerCache()
    {
        // Build a Laravel-standard Cache store so we don't
        // have to send API requests every single time.
        Torchlight::setCacheInstance(new Repository(
            new FileStore(new Filesystem, $this->baseDir . DIRECTORY_SEPARATOR . 'cache')
        ));
    }
}
