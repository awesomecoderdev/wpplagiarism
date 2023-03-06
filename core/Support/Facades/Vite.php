<?php

namespace AwesomeCoder\Support\Facades;

/**
 * @method static array preloadedAssets()
 * @method static string|null cspNonce()
 * @method static string useCspNonce(string|null $nonce = null)
 * @method static \AwesomeCoder\Foundation\Vite useIntegrityKey(string|false $key)
 * @method static \AwesomeCoder\Foundation\Vite withEntryPoints(array $entryPoints)
 * @method static \AwesomeCoder\Foundation\Vite useManifestFilename(string $filename)
 * @method static string hotFile()
 * @method static \AwesomeCoder\Foundation\Vite useHotFile(string $path)
 * @method static \AwesomeCoder\Foundation\Vite useBuildDirectory(string $path)
 * @method static \AwesomeCoder\Foundation\Vite useScriptTagAttributes(callable|array $attributes)
 * @method static \AwesomeCoder\Foundation\Vite useStyleTagAttributes(callable|array $attributes)
 * @method static \AwesomeCoder\Foundation\Vite usePreloadTagAttributes(callable|array|false $attributes)
 * @method static \AwesomeCoder\Support\HtmlString|void reactRefresh()
 * @method static string asset(string $asset, string|null $buildDirectory = null)
 * @method static string|null manifestHash(string|null $buildDirectory = null)
 * @method static bool isRunningHot()
 * @method static string toHtml()
 * @method static void macro(string $name, object|callable $macro)
 * @method static void mixin(object $mixin, bool $replace = true)
 * @method static bool hasMacro(string $name)
 * @method static void flushMacros()
 *
 * @see \AwesomeCoder\Foundation\Vite
 */
class Vite extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return \AwesomeCoder\Foundation\Vite::class;
    }
}
