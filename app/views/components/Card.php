<?php
// app/views/components/Card.php
// Composant générique pour une carte (card)
class Card
{
    private string $title;
    private string $content;
    private ?string $footer;
    private array $options;

    public function __construct(string $title, string $content, ?string $footer = null, array $options = [])
    {
        $this->title = $title;
        $this->content = $content;
        $this->footer = $footer;
        $this->options = $options;
    }

    public function render()
    {
        $class = $this->options['class'] ?? 'bg-white p-6 rounded-lg shadow hover:shadow-lg transition';
        $attrs = '';
        foreach ($this->options as $k => $v) {
            if ($k === 'class') continue;
            $attrs .= ' ' . htmlspecialchars($k) . '="' . htmlspecialchars($v) . '"';
        }
        ?>
        <div class="<?= htmlspecialchars($class) ?>"<?= $attrs ?>>
            <div>
                <h3 class="text-xl font-semibold mb-2">
                    <?= htmlspecialchars($this->title) ?>
                </h3>
                <?= $this->content ?>
            </div>
            <?php if ($this->footer): ?>
                <div class="mt-auto">
                    <?= $this->footer ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }
}
