#ePayco plugin para WooCommerce y Dokan

**Si usted tiene alguna pregunta o problema, no dude en ponerse en contacto con nuestro soporte técnico: desarrollo@payco.co.**

## Tabla de contenido

* [Requisitos](#requisitos)
* [Instalación](#instalación)
* [Pasos](#pasos)
* [Versiones](#versiones)

## Requisitos

* Tener una cuenta activa en [ePayco](https://dashboard.epayco.co/login?utm_campaign=epayco&utm_medium=button-header&utm_source=web#registro).
* Tener instalado WordPress, WooCommerce y Dokan.
* Acceso a las carpetas donde se encuetra instalado WordPress y WooCommerce.
* Acceso al admin de WordPress.

## Instalación

1. [Descarga el plugin.](https://github.com/pruebasepayco/epayco-dokan#versiones)
2. Ingresa al administrador de tu wordPress.
3. Ingresa a Plugins / Añadir-Nuevo / Subir-Plugin. 
4. Busca el plugin descargado en tu equipo y súbelo como cualquier otro archivo.
5. Después de instalar el .zip lo puedes ver en la lista de plugins instalados , puedes activarlo o desactivarlo.
6. Para configurar el plugin debes ir a: WooCommerce / Ajustes / Finalizar Compra y Ubica la pestaña ePayco.
7. Configura el plugin ingresando el **P_CUST_ID_CLIENTE** quien sera el **p_split_merchant_receiver** y **p_split_primary_receiver** (recibidor primario) a quien se registrara la orden de la transaccion y resibira el valor restante de la venta de acuerdo al valor y tipo de comision que resibiran los vendedores configurado en Dokan, la **P_KEY** y la **PUBLIC_KEY** respectiva, los puedes ver en su [panel de clientes](https://dashboard.epayco.co/configuration).
8. Configura los vendedores con el correo que configuran en Dokan y el **P_CUST_ID_CLIENTE** de estos, tener en cuenta las reglas de negocio de [Split payment](https://docs.epayco.co/tools/split-payment), para la correcta dispercion de montos.
9. Selecciona o crea una página de respuesta donde el usuario será devuelto después de finalizar la compra.
9. Realiza una o varias compras para comprobar que todo esté bien.
10. Si todo está bien recuerda cambiar la variable Modo Prueba a NO y empieza a recibir pagos de forma instantánea y segura con ePayco.


## Pasos

<img src="https://github.com/pruebasepayco/epayco-dokan/blob/main/ImgTutorialWooCommerce/tuto-1.png" width="400px"/>
<img src="ImgTutorialWooCommerce/tuto-2.jpg" width="400px"/>
<img src="ImgTutorialWooCommerce/tuto-3.jpg" width="400px"/>
<img src="ImgTutorialWooCommerce/tuto-4.jpg" width="400px"/>
<img src="ImgTutorialWooCommerce/tuto-5.jpg" width="400px"/>
<img src="ImgTutorialWooCommerce/tuto-6.jpg" width="400px"/>
<img src="ImgTutorialWooCommerce/tuto-6A.jpg" width="400px"/>
<img src="ImgTutorialWooCommerce/tuto-7.jpg" width="400px"/>
<img src="ImgTutorialWooCommerce/tuto-8.jpg" width="400px"/>


## Versiones
* [ePayco plugin WooCommerce v5.x.x](https://github.com/epayco/Plugin_ePayco_WooCommerce/releases/tag/3.5.3).

