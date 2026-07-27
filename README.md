# Open Hands website

A standalone Laravel 12 website built with:

- Blade
- Tailwind CSS 4
- Alpine.js
- Laravel validation, mail and rate limiting

There is no React, Livewire, Drizzle or database requirement.

## Local setup with Laravel Herd

Extract the project into your parked Herd directory:

```text
~/Herd/openhands
```

Then run:

```bash
cd ~/Herd/openhands
composer install
npm install
cp .env.example .env
php artisan key:generate
npm run dev
```

Open `http://openhands.test` in your browser. Herd serves the Laravel
application, while Vite compiles the Tailwind CSS and Alpine JavaScript.

To create production frontend files, run:

```bash
npm run build
```

## Enquiry email

The enquiry address is only read on the server and is never rendered in the
public page. It defaults to:

```dotenv
ENQUIRY_TO_ADDRESS="support@openhands.com.au"
```

You can replace that with an `enquiries@` address or alias at any time.

For local testing, the default `MAIL_MAILER=log` writes the complete email to:

```text
storage/logs/laravel.log
```

For production, add the SMTP settings supplied by your email provider to
`.env` and change:

```dotenv
MAIL_MAILER=smtp
```

The mail sent by the form uses your domain address as the sender and the
visitor's address as `Reply-To`.

## Spam protection

The form includes:

- Laravel validation
- a hidden honeypot field
- an encrypted form-start time check
- IP and email rate limiting
- a genuine-enquiry confirmation
- optional Cloudflare Turnstile support

To enable Turnstile, create a widget in Cloudflare and add its two keys:

```dotenv
TURNSTILE_SITE_KEY=
TURNSTILE_SECRET_KEY=
```

If the keys are blank, Turnstile is skipped while all other protections remain
active.

## Main files

```text
resources/views/home.blade.php              Page content and structure
resources/css/app.css                       Tailwind and visual styling
resources/js/app.js                         Alpine setup
app/Http/Controllers/EnquiryController.php  Form processing and bot checks
app/Http/Requests/EnquiryRequest.php         Validation rules
app/Mail/EnquiryReceived.php                 Email definition
resources/views/emails/enquiry.blade.php     Email design
routes/web.php                               Website routes
```

## Tests

After `composer install`, run:

```bash
php artisan test
```
