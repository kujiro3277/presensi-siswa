<?php
require_once __DIR__ . '/../core/BaseModel.php';

class Presensi extends BaseModel {
    private ?int $id = null;
    private int $siswaId = 0;
    private string $tanggalWaktu = '';

    public function __construct(PDO $db) {
        parent::__construct($db);
    }

    // Getter & Setter
    public function getId(): ?int { return $this->id; }
    public function setId(?int $id): void { $this->id = $id; }

    public function getSiswaId(): int { return $this->siswaId; }
    public function setSiswaId(int $siswaId): void { $this->siswaId = $siswaId; }

    public function getTanggalWaktu(): string { return $this->tanggalWaktu; }

    public function getAll(): array {
        return $this->getFiltered(date('Y-m-d'), null, '');
    }

    public function getFiltered(?string $tanggal = '', ?int $kelasId = null, string $keyword = ''): array {
        $sql = "SELECT presensi.id, presensi.tanggal_waktu, siswa.nisn, siswa.nama, kelas.nama_kelas
                FROM presensi
                INNER JOIN siswa ON presensi.siswa_id = siswa.id
                INNER JOIN kelas ON siswa.kelas_id = kelas.id
                WHERE 1=1";
        $params = [];

        if (!empty($tanggal)) {
            $sql .= " AND DATE(presensi.tanggal_waktu) = :tanggal";
            $params['tanggal'] = $tanggal;
        }

        if (!empty($kelasId)) {
            $sql .= " AND kelas.id = :kelas_id";
            $params['kelas_id'] = $kelasId;
        }

        if ($keyword !== '') {
            $sql .= " AND (siswa.nama LIKE :kw1 OR siswa.nisn LIKE :kw2)";
            $params['kw1'] = "%$keyword%";
            $params['kw2'] = "%$keyword%";
        }

        $sql .= " ORDER BY presensi.tanggal_waktu DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function tapKartu(string $nisn): array {
        $nisn = trim($nisn);
        if (!preg_match('/^\d{10}$/', $nisn)) {
            throw new Exception("Format NISN tidak valid. Kartu RFID harus berisi 10 digit angka.");
        }

        $stmt = $this->db->prepare("SELECT siswa.id, siswa.nama, siswa.nisn, kelas.nama_kelas 
                                    FROM siswa 
                                    INNER JOIN kelas ON siswa.kelas_id = kelas.id 
                                    WHERE siswa.nisn = :nisn LIMIT 1");
        $stmt->execute(['nisn' => $nisn]);
        $siswa = $stmt->fetch();

        if (!$siswa) {
            throw new Exception("Kartu ditolak! NISN ($nisn) tidak terdaftar di sistem.");
        }

        // Hanya kirim siswa_id; id dan tanggal_waktu diisi otomatis oleh database
        $insert = $this->db->prepare("INSERT INTO presensi (siswa_id) VALUES (:siswa_id)");
        $insert->execute(['siswa_id' => $siswa['id']]);

        return $siswa;
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM presensi WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
