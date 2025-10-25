<?php
/**
 * Contact Module
 * Module de formulaire de contact
 */

namespace Modules\Contact;

use Core\Module;
use Core\Database;

class ContactModule extends Module {
    protected $type = 'contact';
    protected $name = 'Contact';

    /**
     * Rend le module
     */
    public function render() {
        $title = $this->content['title'] ?? 'Contactez-nous';
        $successMessage = $this->settings['success_message'] ?? 'Merci pour votre message. Nous vous répondrons dans les plus brefs délais.';
        $showPhone = $this->settings['show_phone'] ?? true;
        $formId = 'contact-form-' . $this->id;

        $html = "<div class='module module-contact' data-module-id='{$this->id}'>";
        $html .= "<h2>{$title}</h2>";
        $html .= "<form id='{$formId}' class='contact-form' method='post' action='/api/contact/submit'>";
        $html .= "<input type='hidden' name='module_id' value='{$this->id}'>";

        $html .= "<div class='form-group'>";
        $html .= "<label for='name'>Nom *</label>";
        $html .= "<input type='text' name='name' id='name' class='form-control' required>";
        $html .= "</div>";

        $html .= "<div class='form-group'>";
        $html .= "<label for='email'>Email *</label>";
        $html .= "<input type='email' name='email' id='email' class='form-control' required>";
        $html .= "</div>";

        if ($showPhone) {
            $html .= "<div class='form-group'>";
            $html .= "<label for='phone'>Téléphone</label>";
            $html .= "<input type='tel' name='phone' id='phone' class='form-control'>";
            $html .= "</div>";
        }

        $html .= "<div class='form-group'>";
        $html .= "<label for='subject'>Sujet</label>";
        $html .= "<input type='text' name='subject' id='subject' class='form-control'>";
        $html .= "</div>";

        $html .= "<div class='form-group'>";
        $html .= "<label for='message'>Message *</label>";
        $html .= "<textarea name='message' id='message' class='form-control' rows='5' required></textarea>";
        $html .= "</div>";

        $html .= "<button type='submit' class='btn btn-primary'>Envoyer</button>";
        $html .= "</form>";

        $html .= "<div id='contact-message' class='alert' style='display:none; margin-top: 15px;'></div>";

        $html .= "<script>
        document.getElementById('{$formId}').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const messageDiv = document.getElementById('contact-message');

            fetch('/api/contact/submit', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                messageDiv.style.display = 'block';
                if (data.success) {
                    messageDiv.className = 'alert alert-success';
                    messageDiv.textContent = '{$successMessage}';
                    this.reset();
                } else {
                    messageDiv.className = 'alert alert-danger';
                    messageDiv.textContent = data.message || 'Une erreur est survenue.';
                }
            })
            .catch(error => {
                messageDiv.style.display = 'block';
                messageDiv.className = 'alert alert-danger';
                messageDiv.textContent = 'Une erreur est survenue.';
            });
        });
        </script>";

        $html .= "</div>";

        return $html;
    }

    /**
     * Rend le formulaire d'édition
     */
    public function renderEditForm() {
        $title = $this->content['title'] ?? 'Contactez-nous';
        $email = $this->settings['recipient_email'] ?? '';
        $successMessage = $this->settings['success_message'] ?? 'Merci pour votre message. Nous vous répondrons dans les plus brefs délais.';
        $showPhone = $this->settings['show_phone'] ?? true;

        return '
        <div class="form-group">
            <label>Titre du formulaire</label>
            <input type="text" name="content[title]" class="form-control" value="' . htmlspecialchars($title) . '">
        </div>
        <div class="form-group">
            <label>Email de réception</label>
            <input type="email" name="settings[recipient_email]" class="form-control" value="' . htmlspecialchars($email) . '" placeholder="contact@example.com">
            <small class="form-text text-muted">Les messages seront envoyés à cette adresse</small>
        </div>
        <div class="form-group">
            <label>Message de confirmation</label>
            <textarea name="settings[success_message]" class="form-control" rows="2">' . htmlspecialchars($successMessage) . '</textarea>
        </div>
        <div class="form-group">
            <div class="form-check">
                <input type="checkbox" name="settings[show_phone]" class="form-check-input" value="1"' . ($showPhone ? ' checked' : '') . '>
                <label class="form-check-label">Afficher le champ téléphone</label>
            </div>
        </div>';
    }

    /**
     * Traite la soumission du formulaire
     */
    public static function handleSubmission($data) {
        $db = Database::getInstance();

        // Validation
        if (empty($data['name']) || empty($data['email']) || empty($data['message'])) {
            return ['success' => false, 'message' => 'Veuillez remplir tous les champs obligatoires'];
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'Email invalide'];
        }

        // Enregistre la soumission
        $insertData = [
            'page_id' => $data['page_id'] ?? null,
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'subject' => $data['subject'] ?? null,
            'message' => $data['message'],
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null,
            'status' => 'new'
        ];

        $db->execute(
            "INSERT INTO contact_submissions (page_id, name, email, phone, subject, message, ip_address, user_agent, status, created_at)
             VALUES (:page_id, :name, :email, :phone, :subject, :message, :ip_address, :user_agent, :status, NOW())",
            $insertData
        );

        // TODO: Envoyer un email à l'administrateur

        return ['success' => true, 'message' => 'Message envoyé avec succès'];
    }
}
