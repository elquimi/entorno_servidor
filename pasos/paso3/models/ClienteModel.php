<?php
// paso2/models/ClienteModel.php
class ClienteModel
{
 private $pdo;
 public function __construct(PDO $pdo)
 {
 $this->pdo = $pdo;
 }
 /** Obtener todos los clientes */
 public function getAll(): array
 {
 $stmt = $this->pdo->query("SELECT * FROM clientes ORDER BY id DESC");
 return $stmt->fetchAll();
 }
 /** Obtener un cliente por id */
 public function getById(int $id): ?array
 {
 $stmt = $this->pdo->prepare("SELECT * FROM clientes WHERE id = ?");
 $stmt->execute([$id]);
 $r = $stmt->fetch();
 return $r ?: null;
 }
 /** Crear cliente. $data debe contener 'nombre','email','telefono','direccion' */
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
 /** Actualizar cliente por id */
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
 /** Borrar cliente por id */
 public function delete(int $id): bool
 {
 $stmt = $this->pdo->prepare("DELETE FROM clientes WHERE id = ?");
 return $stmt->execute([$id]);
 }
}