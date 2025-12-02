<?php
// paso4/app/models/ClienteModel.php
class ClienteModel
{
 private $pdo;
 public function __construct(PDO $pdo)
 {
 $this->pdo = $pdo;
 }
 public function getAll(): array
 {
 $stmt = $this->pdo->query("SELECT * FROM clientes ORDER BY id DESC");
 return $stmt->fetchAll();
 }
 public function getById(int $id): ?array
 {
 $stmt = $this->pdo->prepare("SELECT * FROM clientes WHERE id = ?");
 $stmt->execute([$id]);
 $r = $stmt->fetch();
 return $r ?: null;
 }
 public function create(array $data): bool
 {
 $stmt = $this->pdo->prepare("INSERT INTO clientes (nombre,email,telefono,direccion) VALUES 
(?,?,?,?)");
 return $stmt->execute([
 $data['nombre'] ?? '',
 $data['email'] ?? '',
 $data['telefono'] ?? null,
 $data['direccion'] ?? null
 ]);
 }
 public function update(int $id, array $data): bool
 {
 $stmt = $this->pdo->prepare("UPDATE clientes SET nombre=?, email=?, telefono=?, direccion=? WHERE 
id=?");
 return $stmt->execute([
 $data['nombre'] ?? '',
 $data['email'] ?? '',
 $data['telefono'] ?? null,
 $data['direccion'] ?? null,
 $id
 ]);
 }
 public function delete(int $id): bool
 {
 $stmt = $this->pdo->prepare("DELETE FROM clientes WHERE id = ?");
 return $stmt->execute([$id]);
 }
}
