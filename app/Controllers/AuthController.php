<?php
// app/Controllers/AuthController.php
namespace App\Controllers;

use App\Models\UsuarioModel;
use CodeIgniter\Controller;

class AuthController extends Controller
{
    private UsuarioModel $model;

    public function __construct()
    {
        $this->model = new UsuarioModel();
        helper(['url', 'form']);
    }

    // ── Redireciona para agenda se já logado ──────────────
    private function jaLogado(): bool
    {
        $session = session();
        if ($session->get('usuario_id')) {
            return true;
        }
        return false;
    }

    // ── GET /login ────────────────────────────────────────
    public function login()
    {
        if ($this->jaLogado()) {
            return redirect()->to('/agenda');
        }
        return view('auth/login');
    }

    // ── POST /login ───────────────────────────────────────
    public function doLogin()
    {
        $login = $this->request->getPost('login');
        $senha = $this->request->getPost('senha');

        $usuario = $this->model->autenticar($login, $senha);

        if ($usuario) {
            session()->set([
                'usuario_id'    => $usuario['id'],
                'usuario_login' => $usuario['login'],
                'logado'        => true,
            ]);
            return redirect()->to('/agenda');
        }

        return redirect()->back()
            ->with('erro', 'Login ou senha incorretos.')
            ->withInput();
    }

    // ── GET /cadastro ─────────────────────────────────────
    public function cadastro()
    {
        if ($this->jaLogado()) {
            return redirect()->to('/agenda');
        }
        return view('auth/cadastro');
    }

    // ── POST /cadastro ────────────────────────────────────
    public function doCadastro()
    {
        $login  = trim($this->request->getPost('login'));
        $senha  = $this->request->getPost('senha');
        $conf   = $this->request->getPost('confirmar');

        // Validações
        if (strlen($login) < 3) {
            return redirect()->back()
                ->with('erro', 'Login deve ter pelo menos 3 caracteres.')
                ->withInput();
        }
        if (strlen($senha) < 6) {
            return redirect()->back()
                ->with('erro', 'A senha deve ter pelo menos 6 caracteres.')
                ->withInput();
        }
        if ($senha !== $conf) {
            return redirect()->back()
                ->with('erro', 'As senhas não coincidem.')
                ->withInput();
        }

        $id = $this->model->cadastrar($login, $senha);

        if (!$id) {
            return redirect()->back()
                ->with('erro', 'Este login já está em uso. Escolha outro.')
                ->withInput();
        }

        return redirect()->to('/login')
            ->with('sucesso', 'Conta criada com sucesso! Faça login.');
    }

    // ── GET /logout ───────────────────────────────────────
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
