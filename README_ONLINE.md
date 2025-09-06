# Run Laravel Online (Gitpod & Replit)

This package adds ready-to-use configs to run your Laravel app online without local setup.

## Gitpod (easiest for full IDE)
1. Push this project to a GitHub repo (private or public).
2. Open: https://gitpod.io/#<your-repo-url>
3. Gitpod runs `.gitpod.yml`: installs PHP & Composer, creates SQLite DB, and serves on port 8000.
4. If you need API keys, add them to Gitpod **Variables/Secrets** and they will be available as env vars.

## Replit (quick sandbox)
1. Create a new Repl → "Import from GitHub" and select your repo.
2. Replit will use `replit.nix` to install PHP and extensions, then `.replit_run.sh` to start the app.
3. The app runs on port 8000. Add secrets like `PLANT_API_KEY` using Replit's Secrets UI.

## SQLite by default
Both configs default to SQLite (`database/database.sqlite`). If your app expects MySQL, change your `.env` accordingly.

## Docker (optional)
Build and run:
```bash
docker build -t my-laravel .
docker run -p 8080:80 my-laravel
```
Then open http://localhost:8080

## Notes
- If your app needs storage symlink:
  ```bash
  php artisan storage:link
  ```
- If you have migrations/seeds and are using SQLite:
  ```bash
  php artisan migrate --force
  php artisan db:seed --force
  ```
