<?php
require_once __DIR__ . '/../../config/SessionManager.php';
require_once __DIR__ . '/../components/Notifications.php';
require_once __DIR__ . '/../../models/Parametres.php';
abstract class BaseTemplate
{
    protected $title;
    protected $primary;
    protected $secondary;
    protected $cssFiles = [];
    protected $jsFiles = [];

    public function __construct($title = "ESI LAB")
    {
        $this->title = $title;
        $params = Parametres::getAllIndexedBy('cle');
        $this->primary = $params['primary'][0]['valeur'];
        $this->secondary = $params['secondary'][0]['valeur'];
    }

    // ------------- render à implémenter --------------
    abstract public function render();


    // ------------- dans controller --------------
    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function addCSS($file)
    {
        $this->cssFiles[] = $file;
    }

    public function addJS($file)
    {
        $this->jsFiles[] = $file;
    }

    
    // ------------- template content --------------
    protected function head()
    {
        ?>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <title><?php echo htmlspecialchars($this->title); ?></title>
            <script src="https://cdn.tailwindcss.com"></script>
            <script>
                tailwind.config = {
                    theme: {
                        extend: {
                            colors: {
                                primary: { DEFAULT: '<?php echo htmlspecialchars($this->primary); ?>', dark: '#1a252f', light: '#34495e' },
                                secondary: { DEFAULT: '<?php echo htmlspecialchars($this->secondary); ?>', dark: '#2980B9', light: '#5DADE2' },
                                accent: { DEFAULT: '#E74C3C', dark: '#C0392B', light: '#EC7063' }
                            }
                        }
                    }
                }
            </script>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
            <?php foreach ($this->cssFiles as $css): ?>
                <link rel="stylesheet" href="<?php echo htmlspecialchars($css); ?>">
            <?php endforeach; ?>
            <style>
                body { font-family: 'Inter', sans-serif; }
                .sidebar-link.active { background: #3498DB; color: #fff; }
            </style>
        </head>
        <?php
    }

    protected function flashMessages()
    {
        $messages = [
            'success' => SessionManager::getFlashMessage('success'),
            'error' => SessionManager::getFlashMessage('error'),
            'warning' => SessionManager::getFlashMessage('warning'),
            'info' => SessionManager::getFlashMessage('info'),
        ];
        $notifs = new Notifications($messages);
        $notifs->render();
    }
}
