<?php

namespace AwesomeCoder\Plugin\Wp;

/**
 * Represents an asset, a WordPress mechanism used for enqueueing scripts and stylesheets,
 *
 * @since 0.1
 */
class Asset
{
    /**
     * The type for script assets.
     *
     * @since 0.1
     */
    const SCRIPT = 0;
    /**
     * The type for style assets.
     *
     * @since 0.1
     */
    const STYLE = 1;

    /**
     * @since 0.1
     *
     * @var int
     */
    public $type;

    /**
     * @since 0.1
     *
     * @var string
     */
    public $url;

    /**
     * @since 0.1
     *
     * @var string[]
     */
    public $deps;

    /**
     * @since 0.1
     *
     * @var string|null
     */
    public $ver;

    /**
     * Constructor.
     *
     * @since 0.1
     *
     * @param int         $type Either {@link Asset::SCRIPT} or {@link Asset::style}.
     * @param string      $url  The URL of the asset.
     * @param string|null $ver  The version of the asset, used for caching.
     * @param string[]    $deps Keys of dependency assets of the same type.
     */
    public function __construct(int $type, string $url, string $ver = null, array $deps = [])
    {
        $this->type = $type;
        $this->url = $url;
        $this->ver = $ver;
        $this->deps = $deps;
    }

    /**
     * Retrieves the asset type.
     *
     * @since 0.1
     *
     * @see   Asset::SCRIPT
     * @see   Asset::style
     *
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * Retrieves the asset URL.
     *
     * @since 0.1
     *
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * Retrieves the asset's dependencies.
     *
     * @since 0.1
     *
     * @return string[]
     */
    public function getDeps(): array
    {
        return $this->deps;
    }

    /**
     * Retrieves the version of the asset.
     *
     * @since 0.1
     *
     * @return string
     */
    public function getVer(): ?string
    {
        return $this->ver;
    }

    /**
     * Creates a script asset.
     *
     * @since 0.1
     *
     * @param string      $file  The URL of the asset.
     * @param string|null $ver  The version of the asset, used for caching.
     * @param string[]    $deps Keys of dependency script assets.
     *
     * @return static
     */
    public static function script(string $file, string $ver = null, array $deps = [], bool $in_footer = true)
    {
        // return new static(static::SCRIPT, $url, $ver, $deps);
        $url = PLAGIARISM_URL . "assets/js/$file";
        $path = PLAGIARISM_PATH . "assets/js/$file";
        $handler = "wp-plagiarism-" . basename($path, ".js");
        if (file_exists($path)) {
            $version = is_null($ver) ? filemtime($path) : $ver;
            wp_enqueue_script($handler, $url, $deps, $version, $in_footer);
        }
    }

    /**
     * Creates a style asset.
     *
     * @since 0.1
     *
     * @param string      $file  The URL of the asset.
     * @param string|null $ver  The version of the asset, used for caching.
     * @param string[]    $deps Keys of dependency style assets.
     *
     * @return static
     */
    public static function style(string $file, string $ver = null, array $deps = [], $media = null)
    {
        // return new static(static::STYLE, $url, $ver, $deps);
        $url = PLAGIARISM_URL . "assets/css/$file";
        $path = PLAGIARISM_PATH . "assets/css/$file";
        $handler = "wp-plagiarism-" . basename($path, ".css");
        if (file_exists($path)) {
            $version = is_null($ver) ? filemtime($path) : $ver;
            wp_enqueue_style($handler, $url, $deps, $version, $media);
        }
    }

    /**
     * Registers an asset.
     *
     * @since 0.1
     *
     * @param string $key   The handle with which to register the asset.
     * @param Asset  $asset The asset instance.
     *
     * @return bool True on success, false on failure.
     */
    public static function register(string $key, Asset $asset)
    {
        return ($asset->type === static::SCRIPT)
            ? wp_register_script($key, $asset->url, $asset->deps, $asset->ver, true)
            : wp_register_style($key, $asset->url, $asset->deps, $asset->ver);
    }
}
