<?php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\UserRepository;
use App\Core\Auth;

final class UserService
{
    private UserRepository $repo;

    public function __construct()
    {
        $this->repo = new UserRepository();
    }

    public function getProfile(int $id): array
    {
        return $this->repo->findById($id);
    }

    public function updateProfile(int $id, array $data): void
    {
        $this->repo->update($id, $data);
        $updated = $this->repo->findById($id);
        Auth::login($updated);
    }

    public function updatePassword(int $id, string $current, string $new): bool
    {
        $user = $this->repo->findById($id);
        if (!password_verify($current, $user['password_hash'])) {
            return false;
        }
        $this->repo->updatePassword($id, password_hash($new, PASSWORD_DEFAULT));
        return true;
    }

    public function uploadAvatar(int $id, array $file): string
    {
        $ext     = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (!in_array($ext, $allowed)) throw new \RuntimeException('Formato no permitido');
        if ($file['size'] > 2 * 1024 * 1024) throw new \RuntimeException('Máximo 2MB');

        $dir = BASE_PATH . '/public/assets/img/avatars/';
        if (!is_dir($dir)) mkdir($dir, 0775, true);

        $name = 'avatar_' . $id . '_' . time() . '.' . $ext;
        move_uploaded_file($file['tmp_name'], $dir . $name);

        $path = '/assets/img/avatars/' . $name;
        $this->repo->updateAvatar($id, $path);
        $updated = $this->repo->findById($id);
        Auth::login($updated);
        return $path;
    }

    public function uploadLogo(array $file): string
    {
        $ext     = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
        if (!in_array($ext, $allowed)) throw new \RuntimeException('Formato no permitido');
        if ($file['size'] > 2 * 1024 * 1024) throw new \RuntimeException('Máximo 2MB');

        $dir = BASE_PATH . '/public/assets/img/';
        if (!is_dir($dir)) mkdir($dir, 0775, true);

        // Borrar logos anteriores para no acumular archivos
        foreach (glob($dir . 'logo-custom.*') as $old) {
            @unlink($old);
        }

        $name = 'logo-custom.' . $ext;
        $dest = $dir . $name;

        if (!move_uploaded_file($file['tmp_name'], $dest)) {
            throw new \RuntimeException('No se pudo guardar el archivo. Verifica permisos del directorio.');
        }

        $path = '/assets/img/' . $name;
        $this->repo->setSetting('logo_path', $path);
        return $path;
    }

    public function getLogo(): ?string
    {
        return $this->repo->getSetting('logo_path');
    }

    public function isAdmin(int $id): bool
    {
        return $this->repo->isAdmin($id);
    }
}