1. route
Route::get('/{any}', function () {
    return view('index');
})->where('any', '.*');

2. copy toàn bộ src/ -> resources/js/

3. copy package.json
{
    "name": "gundam-impact-galaxy",
    "private": true,
    "version": "0.0.0",
    "type": "module",
    "scripts": {
        "dev": "vite",
        "build": "tsc -b && vite build",
        "lint": "eslint .",
        "preview": "vite preview"
    },
    "devDependencies": {
        "axios": "^1.6.4",
        "laravel-vite-plugin": "^1.0.0",
        "@eslint/js": "^9.21.0",
        "@types/react": "^19.0.10",
        "@types/react-dom": "^19.0.4",
        "@vitejs/plugin-react": "^4.3.4",
        "eslint": "^9.21.0",
        "eslint-plugin-react-hooks": "^5.0.0",
        "eslint-plugin-react-refresh": "^0.4.19",
        "globals": "^15.15.0",
        "typescript": "~5.7.2",
        "typescript-eslint": "^8.24.1",
        "vite": "^6.2.0"
    },
    "dependencies": {
        "@vitejs/plugin-react": "^4.3.4",
        "react": "^19.0.0",
        "react-dom": "^19.0.0",
        "@fortawesome/fontawesome-free": "^6.7.2",
        "@fortawesome/fontawesome-svg-core": "^6.7.2",
        "@fortawesome/free-brands-svg-icons": "^6.7.2",
        "@fortawesome/free-solid-svg-icons": "^6.7.2",
        "@fortawesome/react-fontawesome": "^0.2.2",
        "bootstrap": "^5.3.3",
        "bootstrap-icons": "^1.11.3",
        "gundam-impact-galaxy": "file:",
        "react-bootstrap": "^2.10.9",
        "react-router-dom": "^7.2.0",
        "sass": "^1.85.1",
        "swiper": "^11.2.4"
    }
}

4. npm install

5. View 
<!-- resources\views\index.blade.php
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel React</title>
    @viteReactRefresh
    @vite(['resources/js/main.tsx'])
</head>
<body>
    <div id="root"></div>
</body>
</html> 
-->

6. npm run dev / php artisan ser