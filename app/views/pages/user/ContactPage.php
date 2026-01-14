<?php
require_once __DIR__ . '/../../templates/MainTemplate.php';

class ContactPage extends MainTemplate
{
    private ?string $success;
    private ?string $error;

    public function __construct($title = 'Contact', array $data = [])
    {
        parent::__construct($title);
        $this->success = $data['success'] ?? null;
        $this->error = $data['error'] ?? null;
    }

    protected function content()
    {
        ?>
        <section class="container mx-auto px-4 py-12 max-w-lg">
            <h1 class="text-3xl font-bold text-primary mb-6">Contact</h1>
            <?php if ($this->success): ?>
                <div class="bg-green-100 text-green-700 p-3 rounded mb-4">Votre message a bien été envoyé.</div>
            <?php elseif ($this->error): ?>
                <div class="bg-red-100 text-red-700 p-3 rounded mb-4"><?= htmlspecialchars($this->error) ?></div>
            <?php endif; ?>
            <form method="POST" action="/contact">
                <div class="mb-4">
                    <label for="nom" class="block text-sm font-medium text-gray-700">Nom</label>
                    <input type="text" id="nom" name="nom" required class="border rounded px-3 py-2 w-full" value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>">
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="email" name="email" required class="border rounded px-3 py-2 w-full" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                </div>
                <div class="mb-4">
                    <label for="sujet" class="block text-sm font-medium text-gray-700">Sujet</label>
                    <input type="text" id="sujet" name="sujet" required class="border rounded px-3 py-2 w-full" value="<?= htmlspecialchars($_POST['sujet'] ?? '') ?>">
                </div>
                <div class="mb-4">
                    <label for="message" class="block text-sm font-medium text-gray-700">Message</label>
                    <textarea id="message" name="message" required class="border rounded px-3 py-2 w-full" rows="5"><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
                </div>
                <button type="submit" class="bg-primary text-white px-4 py-2 rounded">Envoyer</button>
            </form>
        </section>
        <?php
    }
}
