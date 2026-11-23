<?php
require_once __DIR__ . '/../core/BaseModel.php';

class Siswa extends BaseModel {
    private ?int $id = null;
    private string $nisn = '';
    private string $nama = '';
    private ?string $alamat = null;
    private int $kelasId = 0;

    public function __construct(PDO $db) {
        parent::__construct($db);
    }

    // Getter & Setter
    public function getId(): ?int { return $this->id; }
    public function setId(?int $id): void { $this->id = $id; }

    public function getNisn(): string { return $this->nisn; }
    public function setNisn(string $nisn): void { $this->nisn = trim($nisn); }

    public function getNama(): string { return $this->nama; }
    public function setNama(string $nama): void { $this->nama = trim($nama); }

    public function getAlamat(): ?string { return $this->alamat; }
    public function setAlamat(?string $alamat): void {
        $trimmed = trim((string)$alamat);
        $this->alamat = ($trimmed === '') ? null : $trimmed;
    }

    public function getKelasId(): int { return $this->kelasId; }
    public function setKelasId(int $kelasId): void { $this->kelasId = $kelasId; }

    public function getAll(string $keyword = ''): array {
        $sql = "SELECT siswa.*, kelas.nama_kelas 
                FROM siswa 
                INNER JOIN kelas ON siswa.kelas_id = kelas.id";
        $params = [];

        if ($keyword !== '') {
            $sql .= " WHERE siswa.nama LIKE :kw1 OR siswa.nisn LIKE :kw2";
            $params['kw1'] = "%$keyword%";
            $params['kw2'] = "%$keyword%";
        }

        $sql .= " ORDER BY kelas.nama_kelas ASC, siswa.nama ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM siswa WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch();
        return $data ?: null;
    }

    public function isNisnDuplicate(string $nisn, ?int $excludeId = null): bool {
        if ($excludeId) {
            $stmt = $this->db->prepare("SELECT id FROM siswa WHERE nisn = :nisn AND id != :id");
            $stmt->execute(['nisn' => $nisn, 'id' => $excludeId]);
        } else {
            $stmt = $this->db->prepare("SELECT id FROM siswa WHERE nisn = :nisn");
            $stmt->execute(['nisn' => $nisn]);
        }
        return $stmt->rowCount() > 0;
    }

    public function validate(?int $excludeId = null): array {
        $errors = [];

        // Validasi NISN: wajib 10 digit angka
        if ($this->nisn === '') {
            $errors['nisn'] = 'NISN wajib diisi.';
        } elseif (!preg_match('/^\d{10}$/', $this->nisn)) {
            $errors['nisn'] = 'NISN wajib terdiri dari tepat 10 digit angka.';
        } elseif ($this->isNisnDuplicate($this->nisn, $excludeId)) {
            $errors['nisn'] = 'NISN sudah terdaftar pada siswa lain.';
        }

        // Validasi Nama: wajib diisi
        if ($this->nama === '') {
            $errors['nama'] = 'Nama siswa wajib diisi.';
        }

        // Validasi Kelas: wajib dipilih dari kelas yang ada
        if ($this->kelasId <= 0) {
            $errors['kelas_id'] = 'Kelas wajib dipilih.';
        } else {
            $stmt = $this->db->prepare("SELECT id FROM kelas WHERE id = :id");
            $stmt->execute(['id' => $this->kelasId]);
            if ($stmt->rowCount() === 0) {
                $errors['kelas_id'] = 'Kelas yang dipilih tidak valid.';
            }
        }

        return $errors;
    }

    public function create(): bool {
        $stmt = $this->db->prepare("INSERT INTO siswa (nisn, nama, alamat, kelas_id) VALUES (:nisn, :nama, :alamat, :kelas_id)");
        return $stmt->execute([
            'nisn'     => $this->nisn,
            'nama'     => $this->nama,
            'alamat'   => $this->alamat,
            'kelas_id' => $this->kelasId
        ]);
    }

    public function update(): bool {
        $stmt = $this->db->prepare("UPDATE siswa SET nisn = :nisn, nama = :nama, alamat = :alamat, kelas_id = :kelas_id WHERE id = :id");
        return $stmt->execute([
            'nisn'     => $this->nisn,
            'nama'     => $this->nama,
            'alamat'   => $this->alamat,
            'kelas_id' => $this->kelasId,
            'id'       => $this->id
        ]);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM siswa WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
