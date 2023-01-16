// jQuery
$(document).ready(function() {
    // utilisation de l'id form
    let form = $('#form');
    // utilisation de la class delete dans la balise a 
    let del = $('a.delete');

    // Pour chaque lien a avec la class delete  
    $(del).each(function(){
        $(this).on('click', function(e){
            // empeche de suivre le comportement du navigateur par defaut c'est a dire le lien
            e.preventDefault();

            // Juste une question de porté de variable, on le réutilisera plus tard dans le script 
            let link = $(this);
            let target = $(this).attr('href');

            Swal.fire({
                icon: 'warning',
                title: 'Êtes-vous sûr de vouloir supprimé cette tâche ?',
                text:'Cette action est irréversible',
                showCancelButton: true,
                confirmButtonText: 'Oui',
                cancelButtonText: 'Non',
            }).then((result) => {
                if (result.value) {
                    // Ici autre façon de faire ces verif que dans la partie Ajax ci dessous 
                    fetch(target, {method: 'get'}).then(response => response.json()).then(message => {
                        console.log(message);
                        Swal.fire({
                            icon: 'success',
                            title: 'Supprimé !',
                            html: '<p>'+message.success+'</p>',
                            type: 'success',
                        });
                    });
                    // Supprimez du code HTML avec jQuery
                    $(link).closest('tr').fadeOut();

                }
                // Attraper une erreur 
            }).catch(err => {
                console.log(err);
                Swal.fire({
                    icon: 'error',
                    title: 'Oups!',
                    text: 'Une erreur est survenue.',
                    type: 'error',
                });
            });
        });
    });

    // Empêche de poster le formulaire selon le comportement du navigateur . Nous le posteront nous même en Ajax
    $(form).on('submit', function(e){
        e.preventDefault();
        // Ajax 
        $.ajax({
            url: $(this).attr('action'),
            type: $(this).attr('method'),
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                console.log(response);
                if (response.errors) {
                    let errorString = '';
                    $.each(response.errors, function(key, value){
                        errorString += '<p>'+ value +'</p>';
                    });
                    // Intégration sweet Alert 2 erreurs
                    Swal.fire({
                        icon: 'error',
                        title : 'Erreur!',
                        html: errorString,
                        type:'error',
                        confirmButtonText: "OK",
                    });
                }
                // Intégration sweet Alert 2 success
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Validé !',
                        text: response.success,
                        type:'success',
                        confirmButtonText:"OK",
                    }).then((result)=> {
                        if (result.value) {
                            document.location.reload(true);
                        }
                    });
                }
            },
            error: function(err) {
                console.log(err);
            }
        });
    });
});