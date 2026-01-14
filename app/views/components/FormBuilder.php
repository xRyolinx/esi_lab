<?php
class FormBuilder
{
    private $fields = [];
    private $action = '';
    private $method = 'POST';
    private $submitLabel = 'Envoyer';
    private $multipart = false;
    private $disabled = false;
    private $formId = '';

    public function __construct($fields = [], $action = '', $method = 'POST', $submitLabel = 'Envoyer', $multipart = false, $disabled = false, $formId = '')
    {
        $this->fields = $fields;
        $this->action = $action;
        $this->method = $method;
        $this->submitLabel = $submitLabel;
        $this->multipart = $multipart;
        $this->disabled = $disabled;
        $this->formId;
    }

    public function render()
    {
        ?>
        <form id="<?= htmlspecialchars($this->formId) ?>" method="<?= htmlspecialchars($this->method) ?>"
            action="<?= htmlspecialchars($this->action) ?>" class="space-y-4" <?php if ($this->multipart): ?>
                enctype="multipart/form-data" <?php endif; ?>>
            <?php foreach ($this->fields as $field): ?>
                <?= $this->renderField($field) ?>
            <?php endforeach; ?>
            <?php if (!$this->disabled): ?>
                <div>
                    <button type="submit"
                        class="w-full bg-secondary text-white py-2 px-4 rounded-md hover:bg-secondary-dark transition">
                        <?= htmlspecialchars($this->submitLabel) ?>
                    </button>
                </div>
            <?php endif; ?>
        </form>
        <?php
    }

    private function renderField($field)
    {
        $type = $field['type'] ?? 'text';
        $name = $field['name'] ?? '';
        $label = $field['label'] ?? '';
        $required = !empty($field['required']) && $field['required'] == true;
        $options = $field['options'] ?? [];
        $value = $field['value'] ?? '';
        $disabled = $field['disabled'] ?? false;
        $checked = !empty($field['checked']) && $field['checked'] == true;

        // attributes
        $attrs = '';
        if ($required) {
            $attrs .= ' required';
        }
        if ($this->disabled || $disabled) {
            $attrs .= ' disabled';
        }
        if ($type === 'checkbox' && $checked) {
            $attrs .= ' checked';
        }
        ob_start();
        ?>
        <div>
            <?php if ($label): ?>
                <label for="<?= htmlspecialchars($name) ?>"
                    class="block text-sm font-medium text-gray-700"><?= htmlspecialchars($label) ?></label>
            <?php endif; ?>
            <?php
            if ($type === 'select') {
                $this->renderSelect($name, $options, $value, $attrs);
            } elseif ($type === 'textarea') {
                $this->renderTextarea($name, $value, $attrs);
            } else {
                $this->renderInput($type, $name, $value, $attrs);
            }
            ?>
        </div>
        <?php
        return ob_get_clean();
    }

    private function renderInput($type, $name, $value, $attrs)
    {
        if ($type === 'checkbox') {
            echo json_encode($attrs);
        }
        ?>
        <input type="<?= htmlspecialchars($type) ?>" name="<?= htmlspecialchars($name) ?>" id="<?= htmlspecialchars($name) ?>"
            <?php if (!empty($value))
                echo 'value="' . htmlspecialchars($value) . '"'; ?>         <?= $attrs ?> class="mt-1 block px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-secondary focus:border-secondary
            <?= ($type === 'checkbox') ? 'w-4 h-4' : 'w-full' ?>">
        <?php
    }

    private function renderSelect($name, $options, $value, $attrs)
    {
        ?>
        <select name="<?= htmlspecialchars($name) ?>" id="<?= htmlspecialchars($name) ?>" <?= $attrs ?>
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-secondary focus:border-secondary">
            <option disabled selected value="">Sélectionner</option>
            <?php foreach ($options as $opt): ?>
                <option value="<?= htmlspecialchars($opt['value']) ?>" <?= ($value == $opt['value']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($opt['label']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <?php
    }

    private function renderTextarea($name, $value, $attrs)
    {
        ?>
        <textarea name="<?= htmlspecialchars($name) ?>" id="<?= htmlspecialchars($name) ?>" rows="3" <?= $attrs ?>
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-secondary focus:border-secondary"><?= htmlspecialchars($value) ?></textarea>
        <?php
    }
}
