# AlphaTrade CRM Monorepo

This monorepo contains a minimal CRM server and React frontend built with the Devias Vite TypeScript template.

## Structure

- `apps/crm-server` – Node 20 Express API connecting directly to the AlphaTrade MySQL database.
- `apps/crm-frontend` – React (Vite) admin UI using Material UI.
- `packages/shared` – Shared TypeScript types.

## Environment

Create `apps/crm-server/.env`:
```
DB_HOST=localhost
DB_PORT=3306
DB_NAME=alphatrade
DB_USER=crm_user
DB_PASS=changeme
FILE_STORAGE_ROOT=/home/afaas/public_html/binary.afaas.online/core/storage/app
JWT_SECRET=supersecret
TZ=UTC
```

Grant minimal MySQL privileges:
```
GRANT SELECT, INSERT, UPDATE ON alphatrade.* TO 'crm_user'@'%';
```

## Build

```
npm install
npm run build -w apps/crm-server
npm run build -w apps/crm-frontend
```

## Deploy

The server listens on `PORT=4000`. Sample Apache reverse proxy:
```
ProxyPass /crm http://127.0.0.1:4000
ProxyPassReverse /crm http://127.0.0.1:4000
```

## Routes

- `POST /auth/login`
- `GET /auth/me`
- `POST /auth/logout`
- `POST /files/upload`
- `GET /internal/dashboard/summary`

- `GET /internal/users`
- `GET /internal/users/:id`
- `PUT /internal/users/:id`
- `POST /internal/users/:id/labels`
- `DELETE /internal/users/:id/labels/:labelId`
- `POST /internal/users/:id/reminders`
- `GET /internal/users/:id/logins`
- `POST /internal/users/:id/impersonate`


More modules can be added under `/internal/*` following the same pattern.
