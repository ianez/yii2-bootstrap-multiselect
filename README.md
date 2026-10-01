Markdown
# Yii2 Bootstrap Multiselect Widget

A flexible and lightweight Yii2 widget wrapper for [David Stutz's bootstrap-multiselect](https://github.com/davidstutz/bootstrap-multiselect) plugin.

---

## 🚀 Features

- **Automatic Bootstrap Version Detection (BS4 vs BS5)**
- Works seamlessly **with or without `ActiveForm`**
- Sensible default options pre-configured
- Support for `optgroup`, custom styling, filtering, and select-all actions

---

## 📦 Installation

The preferred way to install this extension is through [Composer](https://getcomposer.org/).

Run:

```bash
composer require ianez/yii2-bootstrap-multiselect:"*"
🔍 How Bootstrap 4 / 5 Auto-Detection Works
The widget automatically detects whether your Yii2 application is running on Bootstrap 4 or Bootstrap 5:

bsVersion Global Parameter: The widget checks Yii::$app->params['bsVersion'] (e.g., '5.x', '4.x', 5, or 4).

Asset Bundle Fallback: If bsVersion is not explicitly set in params.php, the widget inspects loaded AssetBundles or defaults to Bootstrap 5 when yiisoft/yii2-bootstrap5 is installed.

Plugin Version Mapping:
Default / Bootstrap 5: Loads bootstrap-multiselect v2.x assets (compatible with Bootstrap 5 JS/CSS and data-bs-* attributes).

Bootstrap 4: Loads bootstrap-multiselect v1.x assets (compatible with Bootstrap 4 JS/CSS and data-* attributes).

Override: You can manually force the version by setting bsVersion directly on the widget:

PHP
echo \ianez\multiselect\Multiselect::widget([
    'bsVersion' => '5.x', // '4.x' or '5.x'
    // ...
]);
⚙️ Default Behavior
Out of the box, the widget:

Default Plugin Version: Downloads and registers bootstrap-multiselect v2.x by default (optimized for Bootstrap 5), unless Bootstrap 4 is explicitly detected or configured.

Multiple Attribute: Automatically appends 'multiple' => true to the HTML <select> element (unless explicitly set to false).

Asset Bundle Registration: Automatically registers the required CSS and JS assets in the Yii2 View.

jQuery Initialization: Executes $('#element-id').multiselect(...) on document ready.

Bootstrap Styling: Applies standard Bootstrap form-control button styling for the multiselect dropdown menu.

💻 Usage Examples
💡 Plugin Options & Documentation: All properties passed into clientOptions correspond directly to the plugin's native options. You can find the full list of available options, methods, and events on the Official Bootstrap Multiselect Documentation Website.

1. With ActiveForm (Model Binding)
PHP
use ianez\multiselect\Multiselect;

<?= $form->field($model, 'categories')->widget(Multiselect::class, [
    'data' => [
        1 => 'PHP',
        2 => 'JavaScript',
        3 => 'HTML/CSS',
        4 => 'SQL',
    ],
    'clientOptions' => [
        'nonSelectedText' => 'Seleziona opzioni...',
        'includeSelectAllOption' => true,
        'selectAllText' => 'Seleziona tutti',
        'enableFiltering' => true,
        'enableCaseInsensitiveFiltering' => true,
        'buttonWidth' => '100%',
    ],
]) ?>
2. Without ActiveForm (Standalone Input)
PHP
use ianez\multiselect\Multiselect;

<?= Multiselect::widget([
    'name' => 'user_roles',
    'value' => [1, 3], // Pre-selected values
    'data' => [
        1 => 'Administrator',
        2 => 'Editor',
        3 => 'Author',
        4 => 'Subscriber',
    ],
    'options' => [
        'id' => 'user-roles-multiselect',
        'class' => 'form-control',
    ],
    'clientOptions' => [
        'nonSelectedText' => 'Seleziona ruoli...',
        'buttonWidth' => '100%',
    ],
]) ?>
3. Usage with optgroup (Grouped Options)
PHP
<?= Multiselect::widget([
    'name' => 'technologies',
    'data' => [
        'Backend' => [
            'php' => 'PHP',
            'python' => 'Python',
            'node' => 'Node.js',
        ],
        'Frontend' => [
            'js' => 'JavaScript',
            'vue' => 'Vue.js',
            'react' => 'React',
        ],
    ],
    'clientOptions' => [
        'enableClickableOptGroups' => true,
        'enableCollapsibleOptGroups' => true,
        'nonSelectedText' => 'Seleziona tecnologia...',
    ],
]) ?>
📄 License
This project is licensed under the MIT License - see the LICENSE file for details.