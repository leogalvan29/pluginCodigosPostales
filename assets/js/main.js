
jQuery(document).ready(function($) {
    const postalCodeInput = $('#custom_postal_code');
    const addToCartButton = $('.single_add_to_cart_button');
    const postalCodeMessage = $('#postal-code-message');
    const cantidad = $('.quantity')
    addToCartButton.hide();
    cantidad.hide()
    postalCodeInput.on('input', function() {
        const postalCode = $(this).val();
        
        // Si el campo está vacío, restablece el estado.
        if (!postalCode) {
            
            postalCodeMessage.text('');
            
            // Muestra el botón de agregar al carrito.
            addToCartButton.hide();
            cantidad.hide()
            
            $('#whatsapp-button').remove();
            
             // Elimina el botón de WhatsApp si existe.
             
            return;
        }

        // Validar el código postal vía AJAX.
        $.ajax({
            url: postalCodeAjax.ajax_url,
            method: 'POST',
            data: {
                action: 'validate_postal_code',
                postal_code: postalCode,
            },
            success: function(response) {
                if (response.success) {
                    addToCartButton.disabled = false
                    postalCodeMessage.text('¡Si contamos con envío!').css('color', 'green');
                    addToCartButton.show(); // Muestra el botón de agregar al carrito.
                    $('#whatsapp-button').remove(); // Elimina el botón de WhatsApp.
                    cantidad.show()
                   
                } else {
                    postalCodeMessage.text(response.data.message).css('color', 'red');
                    addToCartButton.hide(); // Oculta el botón de agregar al carrito.
                     
                    // Agregar botón de WhatsApp si no existe.
                    if (!$('#whatsapp-button').length) {
                        $('<a>', {
                            id: 'whatsapp-button',
                            href: postalCodeAjax.whatsapp_url,
                            text: 'Contáctanos por WhatsApp',
                            class: 'button alt',
                            style: 'margin-top: 15px; ',
                        }).insertAfter(addToCartButton);
                    }
                }
            },
        });
    });
});
