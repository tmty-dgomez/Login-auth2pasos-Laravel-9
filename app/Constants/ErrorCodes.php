<?php

namespace App\Constants;

class ErrorCodes
{
    // Errores de validación
    const E0R01 = 'E0R01'; // Nombre es obligatorio
    const E0R02 = 'E0R02'; // Nombre debe tener al menos 3 caracteres
    const E0R03 = 'E0R03'; // Correo electrónico es obligatorio
    const E0R04 = 'E0R04'; // Correo electrónico debe ser válido
    const E0R05 = 'E0R05'; // Correo electrónico ya está registrado
    const E0R06 = 'E0R06'; // Contraseña es obligatoria
    const E0R07 = 'E0R07'; // Contraseña debe tener al menos 5 caracteres

    // Errores de servidor
    const E500 = 'E500'; // Error interno del servidor
    const E501 = 'E501'; // Servicio no disponible

    // Otros errores
    const E404 = 'E404'; // Recurso no encontrado

    // Errores de inicio de sesión
    const E1001 = 'E1001'; // Credenciales incorrectas
}
