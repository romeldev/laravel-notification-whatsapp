# Laravel Notification WhatsApp

Canal de [Laravel Notifications](https://laravel.com/docs/notifications) para WhatsApp, agnóstico
al dominio y con proveedores intercambiables (*drivers*). Su diseño espeja el canal de correo
nativo: una notificación declara `whatsapp` en `via()` y devuelve un `WhatsAppMessage` desde
`toWhatsApp()`.

Proveedores soportados:

- **Evolution API** (driver `evolution`, incluido en esta versión)
- WAHA (driver `waha`, planificado)
- WhatsApp Cloud API oficial de Meta (driver `cloud`, planificado)

## Requisitos

- PHP 8.3+
- Laravel 12 o 13

## Instalación

```bash
composer require romeldev/laravel-notification-whatsapp
```

El service provider se descubre automáticamente. Publica la configuración si necesitas ajustarla:

```bash
php artisan vendor:publish --tag=whatsapp-config

# o por provider
php artisan vendor:publish --provider="Romeldev\\WhatsApp\\WhatsAppServiceProvider"
```

## Configuración

Variables de entorno para Evolution API:

```dotenv
WHATSAPP_DRIVER=evolution
WHATSAPP_DEFAULT_COUNTRY=PE
WHATSAPP_VALIDATE_RECIPIENT=true

WHATSAPP_EVOLUTION_URL=https://evolution.example.com
WHATSAPP_EVOLUTION_KEY=tu-api-key
WHATSAPP_EVOLUTION_INSTANCE=mi-instancia
```

- `WHATSAPP_DEFAULT_COUNTRY`: código ISO-3166 alpha-2 usado para interpretar números locales
  (por ejemplo `987654321`).
- `WHATSAPP_VALIDATE_RECIPIENT`: cuando está activo, cada destinatario se valida con
  [libphonenumber](https://github.com/giggsey/libphonenumber-for-php) antes de enviarse.

## Uso

Marca tu notificación con el canal `whatsapp`:

```php
use Illuminate\Notifications\Notification;
use Romeldev\WhatsApp\Messages\WhatsAppMessage;

class AppointmentReminder extends Notification
{
    public function via(object $notifiable): array
    {
        return ['whatsapp'];
    }

    public function toWhatsApp(object $notifiable): WhatsAppMessage
    {
        return WhatsAppMessage::create()
            ->to($notifiable->phone)
            ->body('Tu cita es mañana a las 9:00.');
    }
}
```

El destinatario se toma de `to()` en el mensaje. Si no lo defines, se usa
`routeNotificationForWhatsApp()` del notifiable (o
`Notification::route('whatsapp', $number)`).

### Envío directo

```php
use Romeldev\WhatsApp\Facades\WhatsApp;
use Romeldev\WhatsApp\Messages\WhatsAppMessage;

WhatsApp::driver('evolution')->send(
    WhatsAppMessage::create()->to('+51 987 654 321')->body('Hola')
);
```

También puedes forzar el driver por mensaje con `->driver('evolution')`, o registrar tus
propios drivers:

```php
WhatsApp::extend('mi-proveedor', function ($app) {
    return new MiTransporte(/* ... */);
});
```

## Testing

```php
use Romeldev\WhatsApp\Facades\WhatsApp;
use Romeldev\WhatsApp\Messages\WhatsAppMessage;

$fake = WhatsApp::fake();

// ... ejecuta el código que envía

$fake->assertSent(fn (WhatsAppMessage $message) => $message->bodyText() === 'Hola');
$fake->assertNotSent(fn (WhatsAppMessage $message) => $message->bodyText() === 'Adiós');
$fake->assertNothingSent();
```

## Licencia

MIT.
