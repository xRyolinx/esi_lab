<?php
class Diaporama
{
    private $slides;
    public function __construct(array $slides)
    {
        $this->slides = $slides;
    }

    public function render()
    {
        ?>
        <!-- Diaporama  -->
        <section class="h-[80vh] w-full relative overflow-hidden my-4 bg-red-50"
        id="diaporama">
            <div class="h-full w-full relative">
                <?php foreach ($this->slides as $i => $slide) { ?>
                    <div class="absolute top-0 left-0 w-full h-full transition-opacity duration-700
                    <?php echo $i === 0 ? 'opacity-100 z-5' : 'opacity-0 z-0'; ?> slide-diapo"
                        data-index="<?php echo $i; ?>"
                        style="background: url('<?php echo $slide['img']; ?>') center center/cover no-repeat;">
                        <!-- Overlay layer -->
                        <div class="absolute inset-0 bg-black bg-opacity-50 z-6"></div>
                        <!-- Texte en bas -->
                        <div class="diapo-text absolute bottom-0 left-0 w-full h-full p-6 flex justify-center items-center z-[8]
                            <?php echo $i === 0 ? 'flex' : 'hidden'; ?>
                        ">
                            <div class="text-white text-xl md:text-4xl font-bold text-center drop-shadow-lg">
                                <?php echo $slide['text']; ?> <br>
                                <a href="<?php echo $slide['link']; ?>" class="inline-block text-3xl mt-4 underline ml-2 text-center text-blue-200">Voir plus</a>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <!-- Navigation boutons -->
                <button type="button" class="absolute left-4 top-1/2 -translate-y-1/2 bg-black bg-opacity-40 text-white rounded-full w-10 h-10 flex items-center justify-center z-[9] diapo-prev" aria-label="Précédent">
                    &#8592;
                </button>
                <button type="button" class="absolute right-4 top-1/2 -translate-y-1/2 bg-black bg-opacity-40 text-white rounded-full w-10 h-10 flex items-center justify-center z-[9] diapo-next" aria-label="Suivant">
                    &#8594;
                </button>
            </div>

            <!-- script js -->
            <script src="/js/diaporama.js"></script>
        </section>
        <?php
    }
}