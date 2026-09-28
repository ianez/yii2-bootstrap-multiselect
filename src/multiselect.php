<?php

namespace ianez\multiselect;

use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\JsExpression;
use yii\widgets\InputWidget;

/**
 * MultiSelect widget wrapper for the Bootstrap Multiselect plugin.
 *
 * Supports both ActiveForm models and standalone usage, automatically integrating 
 * with Bootstrap 4 or Bootstrap 5 environments.
 */
class MultiSelect extends InputWidget
{
    /**
     * @var array Key-value pairs for the dropdown option list.
     */
    public $data = [];

    /**
     * @var array Configuration options passed directly to the underlying JS plugin.
     */
    public $clientOptions = [];

    /**
     * @var array Event callbacks for the plugin (e.g., 'onChange' => 'function(option, checked) { ... }').
     */
    public $clientEvents = [];

    /**
     * @inheritdoc
     */
    public function run()
    {
        $this->registerClientScript();
        return $this->renderWidget();
    }

    /**
     * Renders the select dropdown element for ActiveForm or standalone use.
     *
     * @return string Generated HTML select element.
     */
    protected function renderWidget(): string
    {
        // Enforce multiple selection attribute if not specified
        if (!isset($this->options['multiple'])) {
            $this->options['multiple'] = true;
        }

        if ($this->hasModel()) {
            return Html::activeDropDownList($this->model, $this->attribute, $this->data, $this->options);
        }

        return Html::dropDownList($this->name, $this->value, $this->data, $this->options);
    }

    /**
     * Registers the required AssetBundle and client script to initialize the plugin.
     */
    protected function registerClientScript()
    {
        $view = $this->getView();

        // Register asset bundle
        MultiSelectAsset::register($view);

        $id = $this->options['id'];
        $clientOptions = $this->clientOptions;

        // Merge clientEvents into clientOptions as JsExpressions
        if (!empty($this->clientEvents)) {
            foreach ($this->clientEvents as $event => $handler) {
                if (!($handler instanceof JsExpression)) {
                    $handler = new JsExpression($handler);
                }
                $clientOptions[$event] = $handler;
            }
        }

        $options = empty($clientOptions) ? '{}' : Json::encode($clientOptions);

        $js = "jQuery('#{$id}').multiselect({$options});";
        $view->registerJs($js);
    }
}