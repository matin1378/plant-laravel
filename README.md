# Laravel Plant Analyzer (Docker)

## اجرا با Docker
```bash
docker compose up --build
```
بعد از بالا آمدن، به این آدرس برو:
```
http://127.0.0.1:8000
```

## پیکربندی
مقادیر API در `.env` و `docker-compose.yml` از قبل روی این‌ها ست شده‌اند:
```
PLANT_API_KEY=کلید-API-خودت
PLANT_API_ENDPOINT=https://api.metisai.ir/api/v1/wrapper/openai_chat_completion/chat/completions
```
اگر کلید واقعی داری، همین‌ها را تغییر بده.
