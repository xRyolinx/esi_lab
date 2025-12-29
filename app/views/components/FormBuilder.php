<?php
class FormBuilder
{
    private $fields = [];
    private $action = '';
    private $method = 'POST';
    private $submitLabel = 'Envoyer';
    private $multipart = false;
    private $disabled = false;

    public function __construct($fields = [], $action = '', $method = 'POST', $submitLabel = 'Envoyer', $multipart = false, $disabled = false)
    {
        $this->fields = $fields;
        $this->action = $action;
        $this->method = $method;
        $this->submitLabel = $submitLabel;
        $this->multipart = $multipart;
        $this->disabled = $disabled;
    }

    public function render()
    {
        ?>
        <form method="<?= htmlspecialchars($this->method) ?>" action="<?= htmlspecialchars($this->action) ?>" class="space-y-4"
            <?php if ($this->multipart): ?> enctype="multipart/form-data" <?php endif; ?>>
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
        $required = !empty($field['required']);
        $options = $field['options'] ?? [];
        $value = $field['value'] ?? '';
        $disabled = $field['disabled'] ?? false;

        // attributes
        $attrs = '';
        if ($required) {
            $attrs .= ' required';
        }
        if ($this->disabled || $disabled) {
            $attrs .= ' disabled';
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
        ?>
        <input type="<?= htmlspecialchars($type) ?>" name="<?= htmlspecialchars($name) ?>" id="<?= htmlspecialchars($name) ?>"
            value="<?= htmlspecialchars($value) ?>" <?= $attrs ?>
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-secondary focus:border-secondary">
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
