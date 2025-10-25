<?php
/**
 * Module Class
 * Classe de base pour tous les modules/blocs
 */

namespace Core;

abstract class Module {
    protected $id;
    protected $name;
    protected $type;
    protected $settings = [];
    protected $content = [];
    protected $order = 0;

    /**
     * Constructeur
     */
    public function __construct($data = []) {
        $this->id = $data['id'] ?? null;
        $this->settings = $data['settings'] ?? [];
        $this->content = $data['content'] ?? [];
        $this->order = $data['order'] ?? 0;
    }

    /**
     * Rend le module (doit être implémenté par chaque module)
     */
    abstract public function render();

    /**
     * Rend le formulaire d'édition du module
     */
    abstract public function renderEditForm();

    /**
     * Valide les données du module
     */
    public function validate($data) {
        return true;
    }

    /**
     * Sauvegarde le module
     */
    public function save($pageId) {
        $db = Database::getInstance();

        $data = [
            'page_id' => $pageId,
            'type' => $this->type,
            'content' => json_encode($this->content),
            'settings' => json_encode($this->settings),
            'order_position' => $this->order,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($this->id) {
            // Mise à jour
            return $db->execute(
                "UPDATE modules SET content = :content, settings = :settings,
                 order_position = :order_position, updated_at = :updated_at
                 WHERE id = :id",
                array_merge($data, ['id' => $this->id])
            );
        } else {
            // Création
            $data['created_at'] = date('Y-m-d H:i:s');
            $columns = implode(', ', array_keys($data));
            $placeholders = ':' . implode(', :', array_keys($data));

            if ($db->execute(
                "INSERT INTO modules ({$columns}) VALUES ({$placeholders})",
                $data
            )) {
                $this->id = $db->lastInsertId();
                return true;
            }
        }

        return false;
    }

    /**
     * Supprime le module
     */
    public function delete() {
        if ($this->id) {
            $db = Database::getInstance();
            return $db->execute("DELETE FROM modules WHERE id = :id", ['id' => $this->id]);
        }
        return false;
    }

    /**
     * Récupère les paramètres du module
     */
    public function getSettings() {
        return $this->settings;
    }

    /**
     * Définit les paramètres du module
     */
    public function setSettings($settings) {
        $this->settings = $settings;
    }

    /**
     * Récupère le contenu du module
     */
    public function getContent() {
        return $this->content;
    }

    /**
     * Définit le contenu du module
     */
    public function setContent($content) {
        $this->content = $content;
    }

    /**
     * Récupère l'ID du module
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Récupère le type du module
     */
    public function getType() {
        return $this->type;
    }

    /**
     * Charge un module par son ID
     */
    public static function load($moduleId) {
        $db = Database::getInstance();
        $data = $db->selectOne("SELECT * FROM modules WHERE id = :id", ['id' => $moduleId]);

        if ($data) {
            $moduleClass = self::getModuleClass($data['type']);
            if (class_exists($moduleClass)) {
                return new $moduleClass([
                    'id' => $data['id'],
                    'content' => json_decode($data['content'], true),
                    'settings' => json_decode($data['settings'], true),
                    'order' => $data['order_position']
                ]);
            }
        }

        return null;
    }

    /**
     * Récupère la classe du module selon son type
     */
    private static function getModuleClass($type) {
        $className = ucfirst($type) . 'Module';
        return "Modules\\{$className}\\{$className}";
    }
}
