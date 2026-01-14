<?php

class Organigramme
{
    private array $equipe;

    public function __construct(array $equipe)
    {
        $this->equipe = $equipe;
    }

    public function render(): void
    {
        $equipe = $this->equipe;

        // Séparer chef et membres
        $chef = null;
        $membres = [];

        foreach ($equipe['membres'] as $user) {
            if ($user['id_user'] == $equipe['id_chef']) {
                $chef = $user;
            } else {
                $membres[] = $user;
            }
        }
        // fallback si chef non trouvé
        if (!$chef && !empty($equipe['membres'])) {
            $chef = $equipe['membres'][0];
            $membres = array_slice($equipe['membres'], 1);
        }

        ?>
        <section class="py-12 bg-gray-50">
            <div class="max-w-6xl mx-auto px-4">
                <h2 class="text-2xl font-bold text-primary mb-8 text-center">
                    <?= htmlspecialchars($equipe['nom_equipe']) ?>
                </h2>
                <div class="flex flex-col items-center">
                    <!-- Chef -->
                    <div class="flex flex-col items-center relative">
                        <img src="<?= htmlspecialchars($chef['photo']) ?>"
                            alt="<?= htmlspecialchars($chef['nom'] . ' ' . $chef['prenom']) ?>"
                            class="w-16 h-16 rounded-full border-2 border-primary mb-2">
                        <span class="font-semibold"><?= htmlspecialchars($chef['prenom'] . ' ' . $chef['nom']) ?> (Chef)</span>
                        <!-- ligne verticale vers les membres -->
                        <?php if (!empty($membres)): ?>
                            <div class="absolute top-full left-1/2 w-0 h-6 border-l-2 border-gray-300"></div>
                        <?php endif; ?>
                    </div>

                    <!-- Membres -->
                    <?php if (!empty($membres)): ?>
                        <div class="flex justify-center mt-6 space-x-8">
                            <?php foreach ($membres as $user): ?>
                                <div class="flex flex-col items-center relative">
                                    <!-- lignes horizontales vers le chef -->
                                    <div class="absolute top-0 left-0 w-1/2 h-0.5 bg-gray-300"></div>
                                    <div class="absolute top-0 right-0 w-1/2 h-0.5 bg-gray-300"></div>

                                    <div class="h-2"></div>
                                    <img src="<?= htmlspecialchars($user['photo']) ?>"
                                        alt="<?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?>"
                                        class="w-12 h-12 rounded-full border border-gray-300 mb-1">
                                    <span class="text-sm text-center"><?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>
        <?php
    }
}
