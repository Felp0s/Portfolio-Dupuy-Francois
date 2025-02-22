    //pour le diaporama
    let slideIndex = 1;
    showSlides(slideIndex);

    //  changer de diapositive
    function changeSlide(n) {
        showSlides(slideIndex += n);
    }

    // pour afficher une diapo spécifique
    function currentSlide(n) {
        showSlides(slideIndex = n);
    }

    function showSlides(n) {
        let i;
        let slides = document.getElementsByClassName("slide");
        let dots = document.getElementsByClassName("dot");

        // Boucle pour remettre au dernier diapo
        if (n > slides.length) {slideIndex = 1}
        if (n < 1) {slideIndex = slides.length}

        // Masquer toutes les diapositives
        for (i = 0; i < slides.length; i++) {
            slides[i].style.display = "none";
        }

        for (i = 0; i < dots.length; i++) {
            dots[i].className = dots[i].className.replace(" active", "");
        }

        // Afficher la diapositive actuelle et activer le point de navigation correspondant
        slides[slideIndex-1].style.display = "block";
        dots[slideIndex-1].className += " active";
    }
    