<?php

/**
 * JsonDB - Gestionnaire de stockage JSON (Singleton)
 * Couche d'accès aux données uniquement - Pas de logique métier
 */
class JsonDB {
    private static $instance = null;
    private $dataDir;

    /**
     * Constructeur privé pour empêcher l'instanciation directe
     */
    private function __construct() {
        $this->dataDir = __DIR__ . '/../data';

        // Créer le dossier data s'il n'existe pas
        if (!file_exists($this->dataDir)) {
            mkdir($this->dataDir, 0755, true);
        }

        // Créer le dossier uploads s'il n'existe pas
        $uploadsDir = __DIR__ . '/../uploads/biens';
        if (!file_exists($uploadsDir)) {
            mkdir($uploadsDir, 0755, true);
        }

        // Initialiser les fichiers JSON
        $this->initFiles();
    }

    /**
     * Empêche le clonage de l'instance
     */
    private function __clone() {}

    /**
     * Empêche la désérialisation de l'instance
     */
    public function __wakeup() {
        throw new Exception("Cannot unserialize singleton");
    }

    /**
     * Récupère l'instance unique de JsonDB
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function initFiles() {
        // Initialiser biens.json
        $biensFile = $this->dataDir . '/biens.json';
        if (!file_exists($biensFile)) {
            file_put_contents($biensFile, json_encode([], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }

        // Initialiser users.json avec un admin par défaut
        $usersFile = $this->dataDir . '/users.json';
        if (!file_exists($usersFile)) {
            $defaultUser = [
                [
                    'id' => 1,
                    'username' => 'admin',
                    'password' => password_hash('admin123', PASSWORD_DEFAULT),
                    'email' => 'admin@capcir.fr',
                    'created_at' => date('Y-m-d H:i:s')
                ]
            ];
            file_put_contents($usersFile, json_encode($defaultUser, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }
    }

    /**
     * Lit un fichier JSON
     */
    public function read($filename) {
        $filepath = $this->dataDir . '/' . $filename;
        if (!file_exists($filepath)) {
            return [];
        }
        $content = file_get_contents($filepath);
        return json_decode($content, true) ?? [];
    }

    /**
     * Écrit dans un fichier JSON
     */
    public function write($filename, $data) {
        $filepath = $this->dataDir . '/' . $filename;
        return file_put_contents($filepath, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    /**
     * Récupère le chemin du dossier data
     */
    public function getDataDir() {
        return $this->dataDir;
    }
}