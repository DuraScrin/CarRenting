# Deploy Package (Shared Hosting)

This folder builds a zip-ready package for FTP/shared-hosting deployments.

## Output

After running the script, you get:

- `deploy/dist/duraride-package/`
- `deploy/dist/duraride-shared-hosting.zip`

Both include:

- `public_html/`
- `src/`
- `database/`
- `.env.example` (if present)
- `composer.json` (if present)
- `README.md` (if present)

## Build command

```bash
cd /home/dura/Documents/Projects/CarRental/car-rental-app
bash deploy/build_deploy_zip.sh
```

## Public_html-only package

If your host only allows uploading directly into `public_html`, use:

```bash
cd /home/dura/Documents/Projects/CarRental/car-rental-app
bash deploy/build_public_html_only_zip.sh
```

This creates:

- `deploy/dist/duraride-public-html-only.zip`

The zip contains the **contents of `public_html` only** (no wrapper folder), so you can extract or upload directly into your hosting `public_html` directory.

## FTP upload

1. Extract `duraride-shared-hosting.zip` locally.
2. Upload the content of `duraride-package/` to your hosting account root.
3. Ensure `public_html/` is the domain document root.
4. Keep `src/` and `database/` outside the public web root.
