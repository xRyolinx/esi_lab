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
        <!-- Diaporama refait -->
        <section class="h-[70vh] w-full relative overflow-hidden my-4 bg-red-50" id="diaporama">
            <div class="h-full w-full relative">
                <?php foreach ($this->slides as $i => $slide) { ?>
                    <div class="absolute top-0 left-0 w-full h-full transition-opacity duration-700 <?php echo $i === 0 ? 'opacity-100 z-5' : 'opacity-0 z-0'; ?> slide-diapo" data-index="<?php echo $i; ?>"
                        style="background: url('<?php echo $slide['img']; ?>') center center/cover no-repeat;">
                        <!-- Overlay layer -->
                        <div class="absolute inset-0 bg-black bg-opacity-50 z-5"></div>
                        <!-- Texte en bas -->
                        <div class="absolute bottom-0 left-0 w-full p-6 flex flex-col items-start z-5">
                            <div class="text-white text-xl md:text-2xl font-bold drop-shadow-lg">
                                <?php echo $slide['text']; ?>
                                <a href="<?php echo $slide['link']; ?>" class="underline ml-2 text-blue-200">Voir plus</a>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <!-- Navigation boutons -->
                <button type="button" class="absolute left-4 top-1/2 -translate-y-1/2 bg-black bg-opacity-40 text-white rounded-full w-10 h-10 flex items-center justify-center z-5 diapo-prev" aria-label="Précédent">
                    &#8592;
                </button>
                <button type="button" class="absolute right-4 top-1/2 -translate-y-1/2 bg-black bg-opacity-40 text-white rounded-full w-10 h-10 flex items-center justify-center z-5 diapo-next" aria-label="Suivant">
                    &#8594;
                </button>
            </div>

            <!-- script js -->
            <script>
            (function() {
                let current = 0;
                const slides = document.querySelectorAll('#diaporama .slide-diapo');
                const prevBtn = document.querySelector('#diaporama .diapo-prev');
                const nextBtn = document.querySelector('#diaporama .diapo-next');
                function showSlide(idx) {
                    slides.forEach((slide, i) => {
                        if (i === idx) {
                            slide.classList.add('opacity-100', 'z-10');
                            slide.classList.remove('opacity-0', 'z-0');
                        } else {
                            slide.classList.remove('opacity-100', 'z-10');
                            slide.classList.add('opacity-0', 'z-0');
                        }
                    });
                }
                prevBtn.addEventListener('click', function() {
                    current = (current - 1 + slides.length) % slides.length;
                    showSlide(current);
                });
                nextBtn.addEventListener('click', function() {
                    current = (current + 1) % slides.length;
                    showSlide(current);
                });
            })();
            </script>
        </section>
        <?php
    }
}