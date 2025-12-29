<?php
class Notifications
{
    private array $notifs; //doit [] de: ('success' or 'error' or 'warning' or 'info') => [...]

    public function __construct(array $notifs)
    {
        $this->notifs = $notifs;
    }

    private function css()
    {
        ?>
        <style>
            @keyframes slideInRight {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }

                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }

            .animate-slide-in-right {
                animation: slideInRight 0.5s ease-out forwards;
            }
        </style>
        <?php
    }


    public function render()
    {
        $types = [
            'success' => [
                'bg' => 'bg-green-100',
                'border' => 'border-green-500',
                'text' => 'text-green-700',
                'icon' => 'fa-check-circle'
            ],
            'error' => [
                'bg' => 'bg-red-100',
                'border' => 'border-red-500',
                'text' => 'text-red-700',
                'icon' => 'fa-exclamation-circle'
            ],
            'warning' => [
                'bg' => 'bg-yellow-100',
                'border' => 'border-yellow-500',
                'text' => 'text-yellow-700',
                'icon' => 'fa-exclamation-triangle'
            ],
            'info' => [
                'bg' => 'bg-blue-100',
                'border' => 'border-blue-500',
                'text' => 'text-blue-700',
                'icon' => 'fa-info-circle'
            ],
        ];

        ?>
        <?php $this->css(); ?>

        <div class="fixed z-50 top-4 right-4
            transform translate-x-0
            animate-slide-in-right
            mx-2 my-3 min-w-[30%] max-w-1/2">
            <?php
            foreach (array_keys($types) as $type) {
                if (!empty($this->notifs[$type]) && count($this->notifs[$type]) > 0) {
                    ?>
                    <div class="w-full
                border-l-4 py-3 px-4 my-2
                flex items-center rounded-lg
                <?= $types[$type]['bg'] ?>
                <?= $types[$type]['border'] ?>
                <?= $types[$type]['text'] ?>
                " role="alert">
                        <i class="fas <?= $types[$type]['icon'] ?> text-2xl mr-3"></i>
                        <span class="flex-1">
                            <?php foreach ($this->notifs[$type] as $msg) {
                                echo $msg;
                                if ($msg !== end($this->notifs[$type])) {
                                    ?><br><?php
                                }
                            } ?>
                        </span>
                        <button onclick="this.parentElement.remove()" class="<?= $types[$type]['text'] ?> hover:opacity-80">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
        <?php
    }
}