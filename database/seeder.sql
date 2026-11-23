USE db_presensi_airlangga;

-- Akun Administrator default (username: admin | password: password)
INSERT INTO users (username, password) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Data Awal Kelas SMK TI Airlangga Samarinda
INSERT INTO kelas (nama_kelas) VALUES 
('X PPLG 1'),
('X PPLG 2'),
('XI PPLG 1'),
('XI TJKT 1'),
('XII DKV 1');


INSERT INTO siswa (nisn, nama, alamat, kelas_id) VALUES 
('0071234567', 'Ahmad Fauzi', 'Jl. Pahlawan, Samarinda', 3),
('0079876543', 'Siti Nurhaliza', 'Jl. Antasari, Samarinda', 3),
('0081122334', 'Rizky Pratama', NULL, 1);
