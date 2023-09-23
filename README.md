Run with docker 
`docker build pthread .`
`docker run -v "$(pwd):/app" -w /app -it pthread php password_generator.php`