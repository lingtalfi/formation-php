# Guestbook — MySQL version

Same app as module 05, running on MySQL. Compared to the SQLite version, only these files changed or were added:

- `src/Database.php` — reads `config.php`, connects to MySQL, no more `CREATE TABLE`
- `config.example.php` — template for the credentials
- `schema.sql` — the table definition
- `.gitignore` — keeps `config.php` out of Git

`MessageRepository.php`, `index.php`, `admin.php` and `logout.php` are untouched.

## Run it

1. Start MySQL (`docker compose up -d` in the `06-mysql` folder).
2. Load the schema, from this folder:

   ```bash
   docker compose -f ../../docker-compose.yml exec -T db mysql -u training -ptraining training < schema.sql
   ```

3. Copy `config.example.php` to `config.php` and set the password (`training` with the Docker setup).
4. `php -S localhost:8000` and open <http://localhost:8000>.
