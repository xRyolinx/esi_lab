<?php
require_once __DIR__ . '/../../../config/SessionManager.php';
require_once __DIR__ . '/../../templates/MainTemplate.php';
require_once __DIR__ . '/../../components/FormBuilder.php';

class LoginPage extends MainTemplate
{
    public function __construct($title = "Connexion - ESI LAB")
    {
        parent::__construct($title);
    }


    // contenu
    protected function content()
    {
        $fields = [
            ['type' => 'text', 'name' => 'username', 'label' => "Nom d'utilisateur", 'required' => true],
            ['type' => 'password', 'name' => 'password', 'label' => 'Mot de passe', 'required' => true],
        ];
        $form = new FormBuilder($fields, '/login', 'POST', 'Se connecter');

        ?>
        <main class="container mx-auto px-4 py-16 flex justify-center items-center min-h-[60vh]">
            <div class="w-full max-w-md bg-white shadow-lg rounded-lg p-8">
                <h1 class="text-2xl font-semibold text-center mb-6">Connexion</h1>
                <?= $form->render()?>
                <div class="flex items-center justify-end mt-2">
                    <div class="text-sm">
                        <a href="/forgot_password.php" class="text-secondary hover:underline">Mot de passe oublié ?</a>
                    </div>
                </div>
                <p class="mt-6 text-center text-sm text-gray-600">
                    Pas de compte ? <a href="/register" class="text-secondary hover:underline">S'inscrire</a>
                </p>
            </div>
        </main>
        <?php
    }


    // composants
    private function main()
    {
        ?>
        <main class="container mx-auto px-4 py-16 flex justify-center items-center min-h-[60vh]">
            <div class="w-full max-w-md bg-white shadow-lg rounded-lg p-8">
                <h1 class="text-2xl font-semibold text-center mb-6">Connexion</h1>

                <form method="POST" action="/login" class="space-y-4">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="email" required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-secondary focus:border-secondary">
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
                        <input type="password" name="password" id="password" required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-secondary focus:border-secondary">
                    </div>

                    <div class="flex items-center justify-end">
                        <div class="text-sm">
                            <a href="/forgot_password.php" class="text-secondary hover:underline">Mot de passe oublié ?</a>
                        </div>
                    </div>

                    <div>
                        <button type="submit"
                            class="w-full bg-secondary text-white py-2 px-4 rounded-md hover:bg-secondary-dark transition">
                            Se connecter
                        </button>
                    </div>
                </form>

                <p class="mt-6 text-center text-sm text-gray-600">
                    Pas de compte ? <a href="/register" class="text-secondary hover:underline">S'inscrire</a>
                </p>
            </div>
        </main>
        <?php
    }

}