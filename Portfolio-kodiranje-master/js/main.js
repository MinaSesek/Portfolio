$('.nav-link').on('click', function(event){

    event.preventDefault();

    var sectionId = $(this).attr('href');

    var sectionPosition = $(sectionId).offset().top;
    console.log(sectionPosition);

    $('html, body').animate({
        scrollTop: sectionPosition
    }, 1000);

});



$('.gallery-trigger').magnificPopup({
    type: 'image',
    gallery:{
        enabled:true
    },
    zoom: {
        enabled: true,
        duration: 300 // don't forget to change the duration also in CSS
    }
});



// Kontakt forma
$('#contact-form').validate({
    submitHandler: function (form) {

        // Uzimanje podataka iz forme
        var data = $(form).serialize();

        // Uzimanje vrednosti iz action atributa
        var action = $(form).prop('action');

        // Onemogućavanje svih polja
        $('input, textarea, button').prop('disabled', true);
        // Promena natpisa na dugmetu
        $(form).find('button').text('Sending...');

        // Slanje podataka iz forme putem AJAX metode
        $.post(
            action,
            data,
            function (response) {
                console.log(response);
                if (response == 1) {
                    // Sakrij i ukloni formu
                    $(form).slideUp(function () {
                        $(this).remove();
                    });
                    // Prikaži da je poruka uspešno poslata
                    $('.alert-success').slideDown();
                } else if ( response != '') {
                    // Ako poruka nije prosleđena - pokazaće se greška
                    alert(response);
                } else {
                    alert('ReCaptcha failed - please try again');
                }
            }
        );
    }
});