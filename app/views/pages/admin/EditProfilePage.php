<?php
require_once __DIR__ . '/../../templates/AuthTemplate.php';
require_once __DIR__ . '/../../components/FormBuilder.php';


class EditProfilePage extends AuthTemplate
{
    private $data;

    public function __construct($title = 'Modifier mon profil', $data = [])
    {
        parent::__construct($title);
        $this->data = $data;
    }

    protected function content()
    {
        $user = $this->data['user'] ?? null;
        if (!$user) {
            echo '<h2>Profil introuvable.</h2>';
            return;
        }

        // render
        $this->photoForm($user);
        $this->modifProfileForm($user);
    }

    private function photoForm($user)
    {
        ?>
        <div class="max-w-2xl mx-auto bg-white rounded-xl shadow-lg p-8 mt-8 mb-8">
            <h2 class="text-xl font-bold mb-4">Photo de profil</h2>
            <form action="/admin/profile/photo" method="post" enctype="multipart/form-data" class="flex flex-col items-center gap-4">
                <img src="<?= htmlspecialchars($user['photo'] ?? '/public/img/default-user.png') ?>" alt="Photo de profil"
                    class="rounded-full border-4 border-secondary shadow w-32 h-32 object-cover">
                <input type="file" name="photo" accept="image/*" class="block mt-1">
                <button type="submit" class="px-4 py-2 bg-primary hover:bg-secondary text-white rounded-lg shadow">
                    Mettre à jour la photo
                </button>
            </form>
        </div>
        <?php
    }
    private function modifProfileForm($user)
    {
        ?>
        <div class="max-w-2xl mx-auto bg-white rounded-xl shadow-lg p-8 mt-8">
            <h1 class="text-2xl font-bold mb-6">Modifier mon profil</h1>
            <?php
            $fields = [
                [
                    'type' => 'text',
                    'name' => 'prenom',
                    'label' => 'Prénom',
                    'value' => $user['prenom'] ?? '',
                    'required' => true,
                ],
                [
                    'type' => 'text',
                    'name' => 'nom',
                    'label' => 'Nom',
                    'value' => $user['nom'] ?? '',
                    'required' => true,
                ],
                [
                    'type' => 'text',
                    'name' => 'username',
                    'label' => "Nom d'utilisateur",
                    'value' => $user['username'] ?? '',
                    'required' => true,
                ],
                [
                    'type' => 'email',
                    'name' => 'email',
                    'label' => 'Email',
                    'value' => $user['email'] ?? '',
                    'required' => true,
                ],
                [
                    'type' => 'text',
                    'name' => 'poste',
                    'label' => 'Poste',
                    'value' => $user['poste'] ?? '',
                ],
                [
                    'type' => 'text',
                    'name' => 'grade',
                    'label' => 'Grade',
                    'value' => $user['grade'] ?? '',
                ],
                [
                    'type' => 'text',
                    'name' => 'domaine_recherche',
                    'label' => 'Domaine de recherche',
                    'value' => $user['domaine_recherche'] ?? '',
                ],
                [
                    'type' => 'textarea',
                    'name' => 'biographie',
                    'label' => 'Biographie',
                    'value' => $user['biographie'] ?? '',
                ],
            ];
            $form = new FormBuilder(
                $fields,
                '/admin/profile/edit',
                'POST',
                'Enregistrer',
                false,
                false
            );
            $form->render();
            ?>
            <div class="flex justify-end mt-4">
                <a href="/admin/profile" class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300 text-gray-700">Annuler</a>
            </div>
        </div>
        <?php
    }
}
