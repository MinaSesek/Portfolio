<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Primer kontakt stranice koja koristi AJAX</title>
    <style>
        .form-group {
            margin-bottom: 30px;
        }
    </style>
</head>
<body>



<!-- Poruka -->
<div class="alert alert-success" style="display: none;">
	<strong>Poruka je uspešno prosleđena!</strong> Hvala na interesovanju
</div>
<!-- Forma -->
<form action="_send-email.php" method="post" id="contact-form">
    <div class="form-group">
        <label for="SenderName">Ime</label>
        <input type="text" name="SenderName" class="form-control" id="SenderName" required>
    </div>
    <div class="form-group">
        <label for="SenderEmail">E-mail</label>
        <input type="email" name="SenderEmail" class="form-control" id="SenderEmail" required>
    </div>

    <!--
     Dodatna polja imaju drugačiju vrednost za name atribut.
     Mora da sadrži Sender[] gde se unutar uglastih zagrada upisuje jedinstveni key.
     Taj key će biti iskorišćen u telu poruke.
     Ukoliko key sadrži dve ili više reči - koristiti donju crtu između.
     -->
    <div class="form-group">
        <label for="SenderPhone">Broj telefona</label>
        <input type="text" name="Sender[Broj_telefona]" class="form-control" id="SenderPhone" required>
    </div>

    <div class="form-group">
        <label for="SenderMessage">Poruka</label>
        <textarea name="SenderMessage" id="SenderMessage" class="form-control" required></textarea>
    </div>
    <button class="btn btn-primary">Pošalji</button>
</form>




<!-- jQuery - ne kopirati ukoliko je na sajtu već uključen -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>


<!-- Validation plugin -->
<script src="js/jquery.validate.min.js"></script>
<!-- Validacione Poruke na srpskom -->
<script src="js/messages_sr_lat.js"></script>
<script>
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
            $(form).find('button').text('Slanje u toku...');

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
                        alert('Serverska validacija nije prošla');
                    }
                }
            );
        }
    });
</script>
</body>
</html>