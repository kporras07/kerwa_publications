Kerwa Publications
==================

Este módulo provee un plugin de Block para desplegar la información de publicaciones de Kerwá basado en un key y su correspondiente valor.

# ¿Cómo usarlo?

Vaya a /admin/config/services/kerwa-option y agregue un nuevo Kerwa Option con los siguientes valores:

- Label (Etiqueta)
- Key (Llave para consultar el servicio REST de Kerwá)
- Value (Valor correspondiente a dicha llave)
- Language (Idioma en el repositorio de Kerwá. Este valor es opcional)

Una vez agregada esta entidad, debe agregar un nuevo bloque. En la configuración del mismo debe seleccionar el Kerwa Option y los ítemes por página.

El bloque se desplegará con las siguientes columnas:

- Título
- Autor(es)
- Fecha
- Tipo de publicación
- Enlace

# Manejo de Caché

Cada Kerwa Option genera una entrada en caché sin fecha de expiración, pero dicha entrada se refrescará via cron cada 24 horas o al reconstruir caché.

Es importante notar que recién agregado el bloque, debe limpiarse caché o correr cron para poblar la caché pues esta no se puebla al generar el bloque para evitar timeouts en la página.