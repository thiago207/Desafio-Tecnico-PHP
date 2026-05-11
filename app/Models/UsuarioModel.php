<?php
// app/Models/UsuarioModel.php
namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table         = 'usuarios';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['login', 'senha'];
    protected $returnType    = 'array';

    // ── Cadastro ──────────────────────────────────────────
    public function cadastrar(string $login, string $senha): int|false
    {
        if ($this->where('login', $login)->countAllResults() > 0) {
            return false; // login já existe
        }

        $this->insert([
            'login' => $login,
            'senha' => password_hash($senha, PASSWORD_BCRYPT),
        ]);

        return (int) $this->getInsertID();
    }

    // ── Autenticação ──────────────────────────────────────
    public function autenticar(string $login, string $senha): array|false
    {
        $usuario = $this->where('login', $login)->first();

        if ($usuario && password_verify($senha, $usuario['senha'])) {
            return $usuario;
        }

        return false;
    }
}
