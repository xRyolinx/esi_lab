<?php
require_once __DIR__ . '/../../templates/AuthTemplate.php';

class CreateRolePage extends AuthTemplate
{
    public function __construct($title = 'Créer un rôle', array $data = [])
    {
        parent::__construct($title);
    }

    protected function content()
    {
        ?>
        <div class="max-w-lg mx-auto">
            <h1 class="text-2xl font-bold mb-6">Créer un nouveau rôle</h1>
            <form method="POST" action="/admin/roles">
                <div class="mb-4">
                    <label for="nom_role" class="block text-sm font-medium text-gray-700">Nom du rôle</label>
                    <input type="text" name="nom_role" id="nom_role" required
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-secondary focus:border-secondary">
                </div>
                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" id="description" rows="3"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-secondary focus:border-secondary"></textarea>
                </div>
                <button type="submit" class="w-full bg-secondary text-white py-2 px-4 rounded-md hover:bg-secondary-dark transition">Créer</button>
            </form>
        </div>
        <?php
    }
}
