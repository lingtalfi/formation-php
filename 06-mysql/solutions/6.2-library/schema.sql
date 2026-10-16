-- Load with:
--   docker compose exec -T db mysql -u training -ptraining training < schema.sql
-- or paste it into Adminer's "SQL command".

DROP TABLE IF EXISTS books, authors;

CREATE TABLE authors (
    id   INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE books (
    id        INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title     VARCHAR(255) NOT NULL,
    year      SMALLINT NULL,
    price     DECIMAL(6,2) NOT NULL DEFAULT 0,
    author_id INT UNSIGNED NOT NULL,
    FOREIGN KEY (author_id) REFERENCES authors(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO authors (id, name) VALUES
    (1, 'George Orwell'),
    (2, 'Isaac Asimov'),
    (3, 'Ursula K. Le Guin');

INSERT INTO books (title, year, price, author_id) VALUES
    ('Animal Farm', 1945, 8.50, 1),
    ('1984', 1949, 9.90, 1),
    ('I, Robot', 1950, 10.00, 2),
    ('Foundation', 1951, 11.50, 2),
    ('The End of Eternity', 1955, 9.00, 2),
    ('A Wizard of Earthsea', 1968, 12.00, 3),
    ('The Left Hand of Darkness', 1969, 13.50, 3);

-- Question 4: this fails with error 1452 "Cannot add or update a child row: a foreign key constraint fails".
-- The database itself refuses a book pointing to a non-existent author: data stays consistent
-- even if the PHP code has a bug.
-- INSERT INTO books (title, author_id) VALUES ('Ghost book', 99);
