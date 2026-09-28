<?php

namespace ianez\multiselect;

use Yii;
use yii\web\AssetBundle;

/**
 * Asset bundle for the Bootstrap Multiselect plugin.
 *
 * Automatically resolves Bootstrap 4 vs Bootstrap 5 environments and serves 
 * matching plugin versions via unpkg CDN by default, with local asset fallback support.
 */
class MultiSelectAsset extends AssetBundle
{
    /**
     * @var bool Set to true to load assets from unpkg CDN (default), 
     * or false to load local assets from src/assets/.
     */
    public static $useCdn = true;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // Core dependency
        $this->depends = [
            'yii\web\JqueryAsset',
        ];

        // 1. Resolve Bootstrap environment ('2' for BS5, '1' for BS4)
        $version = $this->resolveVersion();

        // 2. Check CDN preference (static property or app params override)
        $useCdn = static::$useCdn;
        if (isset(Yii::$app->params['multiselectUseCdn'])) {
            $useCdn = (bool) Yii::$app->params['multiselectUseCdn'];
        }

        if ($useCdn) {
            // --- REMOTE CDN MODE (unpkg) ---
            if ($version === '2') {
                $this->depends[] = 'yii\bootstrap5\BootstrapPluginAsset';
            } else {
                $this->depends[] = 'yii\bootstrap4\BootstrapPluginAsset';
            }

            $this->css = [
                "https://unpkg.com/bootstrap-multiselect@{$version}/dist/css/bootstrap-multiselect.min.css",
            ];
            $this->js = [
                "https://unpkg.com/bootstrap-multiselect@{$version}/dist/js/bootstrap-multiselect.min.js",
            ];
        } else {
            // --- LOCAL BUNDLED MODE ---
            $this->sourcePath = __DIR__ . '/assets';

            if ($version === '2') {
                $this->depends[] = 'yii\bootstrap5\BootstrapPluginAsset';
                $this->css = ['bs5/css/bootstrap-multiselect.min.css'];
                $this->js = ['bs5/js/bootstrap-multiselect.min.js'];
            } else {
                $this->depends[] = 'yii\bootstrap4\BootstrapPluginAsset';
                $this->css = ['bs4/css/bootstrap-multiselect.min.css'];
                $this->js = ['bs4/js/bootstrap-multiselect.min.js'];
            }
        }
    }

    /**
     * Resolves the active Bootstrap version in the application environment.
     *
     * @return string '2' for Bootstrap 5 (plugin v2.x), '1' for Bootstrap 4 (plugin v1.x).
     */
    protected function resolveVersion(): string
    {
        $hasBs5 = class_exists('yii\bootstrap5\BootstrapAsset') || class_exists('yii\bootstrap5\BootstrapPluginAsset');
        $hasBs4 = class_exists('yii\bootstrap4\BootstrapAsset') || class_exists('yii\bootstrap4\BootstrapPluginAsset');

        // 1. Single-version vendor optimization
        if ($hasBs5 && !$hasBs4) {
            return '2';
        }

        if ($hasBs4 && !$hasBs5) {
            return '1';
        }

        // 2. Dual-version hybrid environment inspection
        if ($hasBs5 && $hasBs4) {
            $view = Yii::$app->getView();

            // A. Inspect registered AssetBundles in the active view
            if ($view && !empty($view->assetBundles)) {
                foreach (array_keys($view->assetBundles) as $bundleName) {
                    if (strpos($bundleName, 'yii\bootstrap5\\') === 0) {
                        return '2';
                    }
                    if (strpos($bundleName, 'yii\bootstrap4\\') === 0) {
                        return '1';
                    }
                }
            }

            // B. Inspect active Theme pathMap configuration
            if ($view && $view->theme && !empty($view->theme->pathMap)) {
                $pathString = json_encode($view->theme->pathMap);
                if (stripos($pathString, 'bs5') !== false || stripos($pathString, 'bootstrap5') !== false) {
                    return '2';
                }
                if (stripos($pathString, 'bs4') !== false || stripos($pathString, 'bootstrap4') !== false) {
                    return '1';
                }
            }
        }

        // Default fallback to Bootstrap 5
        return '2';
    }
}