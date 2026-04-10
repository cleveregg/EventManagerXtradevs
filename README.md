# Mini Event Registration System

A full-stack web application where users can create events, register for them, and receive email notifications. Built as a demonstration project featuring event management with race-condition-safe registration, queued email notifications, and a public REST API.

## Tech Stack

- **Backend:** Laravel 11
- **Frontend:** Vue 3 (Options API) + Inertia.js
- **Styling:** Tailwind CSS
- **Database:** SQLite / MySQL
- **Authentication:** Laravel Breeze
- **Queue:** Database driver (for queued emails)

## Features

- User authentication (registration, login, logout)
- Full event CRUD (create, read, update, soft-delete)
- Authorization policies — only event creators can edit/delete their own events
- Event registration with race-condition-safe handling (pessimistic locking)
- Queued email notifications on registration (confirmation to registrant + notification to event creator)
- Public REST API (v1) with rate limiting
- Responsive UI with Tailwind CSS

## Installation

```bash
git clone https://github.com/cleveregg/EventManagerXtradevs
cd EventManagerXtradevs
composer install
npm install
cp .env.example .env
php artisan key:generate
# Configure database in .env
php artisan migrate --seed
php artisan storage:link
npm run build
php artisan serve
# In a separate terminal:
php artisan queue:work
```

## Running Tests

```bash
php artisan test
```

## Mail Configuration

For local development, set `MAIL_MAILER=log` in your `.env` file. Emails will appear in `storage/logs/laravel.log`.

For Mailtrap, set:

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=<your-mailtrap-username>
MAIL_PASSWORD=<your-mailtrap-password>
```

## API Documentation

The public REST API is available under the `/api/v1` prefix. No authentication is required. Rate limited to 60 requests per minute.

### List Upcoming Events

`GET /api/v1/events`

Returns a paginated list of upcoming events (future dates only), ordered by date ascending.

**Response:**

```json
{
  "data": [
    {
      "id": 1,
      "name": "Laravel Meetup",
      "description": "A meetup for Laravel enthusiasts.",
      "date": "2026-05-20T18:00:00.000000Z",
      "attendee_limit": 50,
      "registered_count": 12,
      "available_spots": 38,
      "image_url": "http://localhost/storage/events/example.jpg",
      "creator": "John Doe",
      "created_at": "2026-04-01T10:00:00.000000Z"
    }
  ],
  "links": { "...pagination links..." },
  "meta": { "...pagination meta..." }
}
```

### Get Single Event

`GET /api/v1/events/{id}`

Returns details for a single event. Same structure as above, single object in `data`. Returns `404` if not found.

## Queue Worker

The queue worker must be running for email delivery:

```bash
php artisan queue:work
```
