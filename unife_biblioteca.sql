-- Database: unife_biblioteca
DROP DATABASE IF EXISTS unife_biblioteca;
CREATE DATABASE unife_biblioteca CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE unife_biblioteca;

-- Tables
CREATE TABLE branch(
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  address VARCHAR(200) NOT NULL,
  department_name VARCHAR(150) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE author(
  id INT AUTO_INCREMENT PRIMARY KEY,
  first_name VARCHAR(80) NOT NULL,
  last_name VARCHAR(80) NOT NULL,
  birth_date DATE NULL,
  birth_place VARCHAR(120) NULL
) ENGINE=InnoDB;

CREATE TABLE book(
  id INT AUTO_INCREMENT PRIMARY KEY,
  isbn VARCHAR(20) NOT NULL,
  title_en VARCHAR(200) NOT NULL,
  publication_year INT NOT NULL,
  original_language VARCHAR(40) NOT NULL,
  UNIQUE KEY uq_book_isbn (isbn)
) ENGINE=InnoDB;

CREATE TABLE book_author(
  book_id INT NOT NULL,
  author_id INT NOT NULL,
  PRIMARY KEY(book_id, author_id),
  CONSTRAINT fk_ba_book FOREIGN KEY (book_id) REFERENCES book(id) ON DELETE CASCADE,
  CONSTRAINT fk_ba_author FOREIGN KEY (author_id) REFERENCES author(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE inventory(
  branch_id INT NOT NULL,
  book_id INT NOT NULL,
  copies INT NOT NULL DEFAULT 0,
  PRIMARY KEY(branch_id, book_id),
  CONSTRAINT fk_inv_branch FOREIGN KEY (branch_id) REFERENCES branch(id) ON DELETE CASCADE,
  CONSTRAINT fk_inv_book FOREIGN KEY (book_id) REFERENCES book(id) ON DELETE CASCADE,
  CONSTRAINT chk_inventory_copies_nonneg CHECK (copies >= 0)
) ENGINE=InnoDB;

CREATE TABLE student(
  id INT AUTO_INCREMENT PRIMARY KEY,
  matricola VARCHAR(30) NOT NULL UNIQUE,
  first_name VARCHAR(80) NOT NULL,
  last_name VARCHAR(80) NOT NULL,
  address VARCHAR(200) NULL,
  phone VARCHAR(40) NULL,
  CONSTRAINT chk_student_matricola_format CHECK (matricola REGEXP '^[0-9]{6}$')
) ENGINE=InnoDB;

CREATE TABLE loan(
  id INT AUTO_INCREMENT PRIMARY KEY,
  student_id INT NOT NULL,
  book_id INT NOT NULL,
  branch_id INT NOT NULL,
  checkout_date DATE NOT NULL,
  due_date DATE NOT NULL,
  return_date DATE NULL,
  CONSTRAINT fk_loan_student FOREIGN KEY (student_id) REFERENCES student(id) ON DELETE CASCADE,
  CONSTRAINT fk_loan_book FOREIGN KEY (book_id) REFERENCES book(id) ON DELETE RESTRICT,
  CONSTRAINT fk_loan_branch FOREIGN KEY (branch_id) REFERENCES branch(id) ON DELETE RESTRICT
) ENGINE=InnoDB;



-- SUCCURSALI 
INSERT INTO branch(name, address, department_name) VALUES
('Biblioteca di Architettura', 'Via della Ghiara 36, 44121 Ferrara', 'Architettura'),
('Biblioteca di Economia', 'Via Voltapaletto 11, 44121 Ferrara', 'Economia e Management'),
('Biblioteca di Ingegneria', 'Via Giuseppe Saragat 1, 44122 Ferrara', 'Ingegneria'),
('Biblioteca di Studi Umanistici', 'Via Paradiso 12, 44121 Ferrara', 'Studi Umanistici'),
('Biblioteca di Fisica e Scienze della Terra', 'Via Giuseppe Saragat 1, 44122 Ferrara', 'Fisica e Scienze della Terra'),
('Biblioteca di Matematica e Informatica', 'Via Giuseppe Saragat 1, 44122 Ferrara', 'Matematica e Informatica'),
('Biblioteca di Giurisprudenza', 'Corso Ercole I d''Este 37, 44121 Ferrara', 'Giurisprudenza'),
('Biblioteca di Scienze Chimiche, Farmaceutiche ed Agrarie', 'Via Luigi Borsari 46, 44121 Ferrara', 'Scienze Chimiche, Farmaceutiche ed Agrarie'),
('Biblioteca di Neuroscienze e Riabilitazione', 'Via Luigi Borsari 46, 44121 Ferrara', 'Neuroscienze e Riabilitazione'),
('Biblioteca di Scienze dell''Ambiente e della Prevenzione', 'Via Luigi Borsari 46, 44121 Ferrara', 'Scienze dell''Ambiente e della Prevenzione'),
('Biblioteca di Scienze della Vita e Biotecnologie', 'Via Fossato di Mortara 17/19, 44121 Ferrara', 'Scienze della Vita e Biotecnologie'),
('Biblioteca di Scienze Mediche', 'Via Luigi Borsari 46, 44121 Ferrara', 'Scienze Mediche'),
('Biblioteca di Medicina Traslazionale e per la Romagna', 'Via Luigi Borsari 46, 44121 Ferrara', 'Medicina Traslazionale e per la Romagna');

-- AUTORI
INSERT INTO author(first_name, last_name, birth_date, birth_place) VALUES
('J.K.', 'Rowling', '1965-07-31', 'Yate, UK'),
('Isaac', 'Asimov', '1920-01-02', 'Petroviči, Russia'),
('Italo', 'Calvino', '1923-10-15', 'Santiago de las Vegas, Cuba'),
('George', 'Orwell', '1903-06-25', 'Motihari, India'),
('Mary', 'Shelley', '1797-08-30', 'London, UK'),
('Arthur C.', 'Clarke', '1917-12-16', 'Minehead, UK'),
('Ursula K.', 'Le Guin', '1929-10-21', 'Berkeley, USA'),
('Frank', 'Herbert', '1920-10-08', 'Tacoma, USA');

-- LIBRI 
INSERT INTO book(isbn, title_en, publication_year, original_language) VALUES
('9780747532743', 'Harry Potter and the Philosopher''s Stone', 1997, 'English'),
('9780553294385', 'Foundation', 1951, 'English'),
('9788804727276', 'If on a winter''s night a traveler', 1979, 'Italian'),
('9780451524935', '1984', 1949, 'English'),
('9780141439471', 'Frankenstein; or, The Modern Prometheus', 1818, 'English'),
('9780345358790', 'Childhood''s End', 1953, 'English'),
('9780143111597', 'The Left Hand of Darkness', 1969, 'English'),
('9780441172719', 'Dune', 1965, 'English');

-- RELAZIONE AUTORE–LIBRO
INSERT INTO book_author (book_id, author_id)
SELECT b.id, a.id FROM book b JOIN author a
  ON b.isbn='9780747532743' AND a.first_name='J.K.' AND a.last_name='Rowling'
UNION ALL
SELECT b.id, a.id FROM book b JOIN author a
  ON b.isbn='9780553294385' AND a.first_name='Isaac' AND a.last_name='Asimov'
UNION ALL
SELECT b.id, a.id FROM book b JOIN author a
  ON b.isbn='9788804727276' AND a.first_name='Italo' AND a.last_name='Calvino'
UNION ALL
SELECT b.id, a.id FROM book b JOIN author a
  ON b.isbn='9780451524935' AND a.first_name='George' AND a.last_name='Orwell'
UNION ALL
SELECT b.id, a.id FROM book b JOIN author a
  ON b.isbn='9780141439471' AND a.first_name='Mary' AND a.last_name='Shelley'
UNION ALL
SELECT b.id, a.id FROM book b JOIN author a
  ON b.isbn='9780345358790' AND a.first_name='Arthur C.' AND a.last_name='Clarke'
UNION ALL
SELECT b.id, a.id FROM book b JOIN author a
  ON b.isbn='9780143111597' AND a.first_name='Ursula K.' AND a.last_name='Le Guin'
UNION ALL
SELECT b.id, a.id FROM book b JOIN author a
  ON b.isbn='9780441172719' AND a.first_name='Frank' AND a.last_name='Herbert';

-- INVENTARIO 
INSERT INTO inventory(branch_id, book_id, copies)
SELECT br.id, b.id, 3 FROM branch br JOIN book b
  ON br.name='Biblioteca di Architettura' AND b.isbn='9780747532743'
UNION ALL
SELECT br.id, b.id, 2 FROM branch br JOIN book b
  ON br.name='Biblioteca di Fisica e Scienze della Terra' AND b.isbn='9780747532743'
UNION ALL
SELECT br.id, b.id, 2 FROM branch br JOIN book b
  ON br.name='Biblioteca di Giurisprudenza' AND b.isbn='9780747532743'
UNION ALL
SELECT br.id, b.id, 5 FROM branch br JOIN book b
  ON br.name='Biblioteca di Fisica e Scienze della Terra' AND b.isbn='9780553294385'
UNION ALL
SELECT br.id, b.id, 3 FROM branch br JOIN book b
  ON br.name='Biblioteca di Matematica e Informatica' AND b.isbn='9780553294385'
UNION ALL
SELECT br.id, b.id, 2 FROM branch br JOIN book b
  ON br.name='Biblioteca di Ingegneria' AND b.isbn='9780553294385'
UNION ALL
SELECT br.id, b.id, 4 FROM branch br JOIN book b
  ON br.name='Biblioteca di Studi Umanistici' AND b.isbn='9788804727276'
UNION ALL
SELECT br.id, b.id, 1 FROM branch br JOIN book b
  ON br.name='Biblioteca di Giurisprudenza' AND b.isbn='9788804727276'
UNION ALL
SELECT br.id, b.id, 5 FROM branch br JOIN book b
  ON br.name='Biblioteca di Studi Umanistici' AND b.isbn='9780451524935'
UNION ALL
SELECT br.id, b.id, 2 FROM branch br JOIN book b
  ON br.name='Biblioteca di Economia' AND b.isbn='9780451524935'
UNION ALL
SELECT br.id, b.id, 2 FROM branch br JOIN book b
  ON br.name='Biblioteca di Giurisprudenza' AND b.isbn='9780451524935'
UNION ALL
SELECT br.id, b.id, 3 FROM branch br JOIN book b
  ON br.name='Biblioteca di Studi Umanistici' AND b.isbn='9780141439471'
UNION ALL
SELECT br.id, b.id, 2 FROM branch br JOIN book b
  ON br.name='Biblioteca di Scienze Chimiche, Farmaceutiche ed Agrarie' AND b.isbn='9780141439471'
UNION ALL
SELECT br.id, b.id, 3 FROM branch br JOIN book b
  ON br.name='Biblioteca di Matematica e Informatica' AND b.isbn='9780345358790'
UNION ALL
SELECT br.id, b.id, 2 FROM branch br JOIN book b
  ON br.name='Biblioteca di Fisica e Scienze della Terra' AND b.isbn='9780345358790'
UNION ALL
SELECT br.id, b.id, 2 FROM branch br JOIN book b
  ON br.name='Biblioteca di Studi Umanistici' AND b.isbn='9780143111597'
UNION ALL
SELECT br.id, b.id, 2 FROM branch br JOIN book b
  ON br.name='Biblioteca di Economia' AND b.isbn='9780143111597'
UNION ALL
SELECT br.id, b.id, 4 FROM branch br JOIN book b
  ON br.name='Biblioteca di Fisica e Scienze della Terra' AND b.isbn='9780441172719'
UNION ALL
SELECT br.id, b.id, 3 FROM branch br JOIN book b
  ON br.name='Biblioteca di Ingegneria' AND b.isbn='9780441172719'
UNION ALL
SELECT br.id, b.id, 2 FROM branch br JOIN book b
  ON br.name='Biblioteca di Neuroscienze e Riabilitazione' AND b.isbn='9780441172719';

-- STUDENTI 
INSERT INTO student(matricola, first_name, last_name, address, phone) VALUES
('183845', 'Giulia', 'Bianchi', 'Via Roma 10, 44121 Ferrara', '+39 333 1111111'),
('274913', 'Luca',   'Verdi',   'Via Bologna 20, 44121 Ferrara', '+39 333 2222222'),
('362507', 'Sara',   'Rossi',   'Via Modena 30, 44122 Ferrara',  '+39 333 3333333'),
('490186', 'Marco',  'Neri',    'Via Po 5, 44122 Ferrara',       '+39 333 4444444'),
('505772', 'Chiara', 'Galli',   'Via Garibaldi 25, 44121 Ferrara','+39 333 5555555');

-- PRESTITI 
INSERT INTO loan(student_id, book_id, branch_id, checkout_date, due_date, return_date)
SELECT s.id, b.id, br.id, '2025-08-15', '2025-09-14', NULL
FROM student s, book b, branch br
WHERE s.matricola='183845' AND b.isbn='9780747532743' AND br.name='Biblioteca di Architettura'
UNION ALL
SELECT s.id, b.id, br.id, '2025-08-28', '2025-09-27', NULL
FROM student s, book b, branch br
WHERE s.matricola='274913' AND b.isbn='9780553294385' AND br.name='Biblioteca di Ingegneria'
UNION ALL
SELECT s.id, b.id, br.id, '2025-08-01', '2025-08-31', '2025-08-20'
FROM student s, book b, branch br
WHERE s.matricola='362507' AND b.isbn='9788804727276' AND br.name='Biblioteca di Studi Umanistici'
UNION ALL
SELECT s.id, b.id, br.id, '2025-09-01', '2025-10-01', NULL
FROM student s, book b, branch br
WHERE s.matricola='490186' AND b.isbn='9780441172719' AND br.name='Biblioteca di Fisica e Scienze della Terra'
UNION ALL
SELECT s.id, b.id, br.id, '2025-08-20', '2025-09-19', '2025-09-05'
FROM student s, book b, branch br
WHERE s.matricola='505772' AND b.isbn='9780451524935' AND br.name='Biblioteca di Economia'