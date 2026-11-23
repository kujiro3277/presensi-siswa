<?php
require_once __DIR__ . '/../core/BaseModel.php';

class Kelas extends BaseModel {
    private ?int $id = null;
    private string $namaKelas = '';

    public function __construct(PDO $db) {
        parent::__construct($db);
    }

    // Getter & Setter
    public function getId(): ?int { return $this->id; }
    public function setId(?int $id): void { $this->id = $id; }

    public function getNamaKelas(): string { return $this->namaKelas; }
    public function setNamaKelas(string $namaKelas): void { $this->namaKelas = trim($namaKelas); }

    public function getAll(): array {
        $stmt = $this->db->query("SELECT * FROM kelas ORDER BY nama_kelas ASC");
        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM kelas WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch();
        return $data ?: null;
    }

    public function isDuplicate(string $namaKelas, ?int $excludeId = null): bool {
        if ($excludeId) {
            $stmt = $this->db->prepare("SELECT id FROM kelas WHERE nama_kelas = :nama AND id != :id");
            $stmt->execute(['nama' => $namaKelas, 'id' => $excludeId]);
        } else {
            $stmt = $this->db->prepare("SELECT id FROM kelas WHERE nama_kelas = :nama");
            $stmt->execute(['nama' => $namaKelas]);
        }
        return $stmt->rowCount() > 0;
    }

    public function create(): bool {
        $stmt = $this->db->prepare("INSERT INTO kelas (nama_kelas) VALUES (:nama)");
        return $stmt->execute(['nama' => $this->namaKelas]);
    }

    public function update(): bool {
        $stmt = $this->db->prepare("UPDATE kelas SET nama_kelas = :nama WHERE id = :id");
        return $stmt->execute(['nama' => $this->namaKelas, 'id' => $this->id]);
    }

    public function hasSiswa(int $id): bool {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM siswa WHERE kelas_id = :id");
        $stmt->execute(['id' => $id]);
        return (int)$stmt->fetchColumn() > 0;
    }

    public function delete(int $id): bool {
        if ($this->hasSiswa($id)) {
            throw new Exception("Kelas tidak dapat dihapus karena masih memiliki siswa terdaftar.");
        }
        $stmt = $this->db->prepare("DELETE FROM kelas WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
