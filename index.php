<?php
/**
 * Plugin Name: Custom Postal Codes API
 * Description: Provides an API endpoint to get custom postal codes and integrates with WooCommerce.
 * Version: 1.0
 * Author: Tu Nombre
 */

if (!defined('ABSPATH')) {
    exit; // Bloquear acceso directo.
}

// Registrar el endpoint personalizado.
add_action('rest_api_init', function () {
    register_rest_route('custom/v1', '/postal-codes', [
        'methods'  => 'GET',
        'callback' => 'get_custom_postal_codes',
        'permission_callback' => '__return_true', // Permitir acceso público.
    ]);
});

// Función que devuelve los códigos postales.
function get_custom_postal_codes() {
    $postal_codes = [
        80270, 80296, 80016, 80430, 80308, 80280, 80199, 80054,
        // (Agregar el resto de los códigos aquí)
    ];

    return [
        'success' => true,
        'data'    => $postal_codes,
    ];
}

// Enlazar CSS y JS.
add_action('wp_enqueue_scripts', 'custom_postal_codes_enqueue_assets');
function custom_postal_codes_enqueue_assets() {
    // Registrar CSS
    wp_enqueue_style(
        'custom-postal-codes-style',
        plugin_dir_url(__FILE__) . 'assets/css/styles.css',
        [],
        '1.0.0',
        'all'
    );

    // Registrar JS
    wp_enqueue_script(
        'custom-postal-codes-script',
        plugin_dir_url(__FILE__) . 'assets/js/main.js',
        ['jquery'], // Dependencias, si las hay
        '1.0.0',
        true // Cargar en el footer
    );

    // Pasar datos a JavaScript
    wp_localize_script('custom-postal-codes-script', 'postalCodeAjax', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'whatsapp_url' => 'https://wa.me/1234567890?text=Hola,%20tengo%20una%20pregunta%20sobre%20su%20producto',
    ]);
}

// Agregar un campo antes del botón "Agregar al carrito".
add_action('woocommerce_before_add_to_cart_button', 'add_postal_code_field');
function add_postal_code_field() {
    echo '<div id="custom-postal-code-wrapper">';
    echo '<label for="custom_postal_code"><small>Revisa si contamos con envío en tu zona:</small></label>';
    echo '<input type="text" id="custom_postal_code" name="custom_postal_code" class="input-text" placeholder="Ingresar CP">';
    echo '<small id="postal-code-message" style="display: block; color: red; margin-top: 5px;"></small>';
    echo '</div>';
}

// Validar el código postal con AJAX.
add_action('wp_ajax_validate_postal_code', 'validate_postal_code_ajax');
add_action('wp_ajax_nopriv_validate_postal_code', 'validate_postal_code_ajax');
function validate_postal_code_ajax() {
    $postal_code = sanitize_text_field($_POST['postal_code']);
    $allowed_postal_codes = [
        80270, 80296, 80016, 80430, 80308, 80280, 80199, 80054,
        80247, 80464, 80230, 80450, 80010, 80058, 80496, 80393,
        80304, 80303, 80190, 80492, 80130, 80014, 80395, 80318,
        80319, 80028, 80019, 80391, 80025, 80027, 80177, 80402,
        80295, 80063, 80249, 80434, 80400, 80194, 80435, 80386,
        80380, 80060, 80050, 80409, 80384, 80189, 80140, 80301,
        80311, 80390, 80176, 80184, 80246, 80065, 80059, 80408,
        80210, 80106, 80020, 80029, 80018, 80145, 80030, 80015,
        80490, 80398, 80439, 80437, 80433, 80415, 80309, 80178,
        80385, 80000, 80040, 80013, 80297, 80228, 80197, 80419,
        80034, 80100, 80294, 80498, 80107, 80139, 80300, 80436,
        80405, 80394, 80080, 80486, 80460, 80410, 80467, 80383,
        80305, 80313, 80453, 80017, 80315, 80411, 80442, 80444,
        80491, 80457, 80440, 80463, 80110, 80443, 80441, 80260,
        80485, 80480, 80470, 80104, 80155, 80220, 80455, 80240,
        80180, 80064, 80489, 80298, 80120, 80160, 80179, 80159,
        80225, 80170, 80026, 80310, 80200, 80135, 80465, 80449,
        80302, 80473, 80248, 80454, 80493, 80314, 80416, 80316,
        80397, 80481, 80290, 80417, 80090, 80387, 80250, 80447,
        80128, 80299, 80452, 80227, 80093, 80483, 80150, 80143,
        80468, 80484, 80466, 80105, 80279, 80475, 80396, 80317,
        80144, 80103, 80109, 80403, 80055, 80070, 80088
        // (Agregar el resto de los códigos aquí)
    ];

    if (in_array($postal_code, $allowed_postal_codes)) {
        wp_send_json_success(['message' => 'Si contamos con envío.']);
    } else {
        wp_send_json_error(['message' => 'Este producto no está disponible para envio en tu área.']);
    }
}

// $numero = 5;
// $numeros = [
//     1,2,3,4,5,6,7,8,9
// ];

// if(in_array($numero, $numeros)){
//   echo "si esta";
// }




