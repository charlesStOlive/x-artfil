<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Mail;
use App\Settings\AdminSettings;

class ContactForm extends Component
{
    #[Validate('nullable|min:2|max:50')]
    public string $prenom = '';
    
    #[Validate('nullable|min:2|max:50')]
    public string $nom = '';
    
    #[Validate('required|email|max:100')]
    public string $email = '';
    
    #[Validate('nullable|regex:/^([0-9\s\-\+\(\)]*)$/|min:10')]
    public string $telephone = '';
    
    #[Validate('required|min:5|max:100')]
    public string $objet = '';
    
    #[Validate('required|min:10|max:1000')]
    public string $message = '';
    
    public bool $success = false;
    public string $successMessage = '';

    protected $messages = [
        'prenom.min' => 'Le prénom doit contenir au moins 2 caractères.',
        'nom.min' => 'Le nom doit contenir au moins 2 caractères.',
        'email.required' => 'L\'email est obligatoire.',
        'email.email' => 'L\'email doit être valide.',
        'telephone.regex' => 'Le numéro de téléphone n\'est pas valide.',
        'objet.required' => 'L\'objet est obligatoire.',
        'objet.min' => 'L\'objet doit contenir au moins 5 caractères.',
        'message.required' => 'Le message est obligatoire.',
        'message.min' => 'Le message doit contenir au moins 10 caractères.',
        'message.max' => 'Le message ne peut pas dépasser 1000 caractères.',
    ];

    public function submit()
    {
        // Throttling : maximum 3 tentatives par minute
        $key = 'contact-form:' . request()->ip();
        
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            $this->addError('throttle', "Trop de tentatives. Réessayez dans {$seconds} secondes.");
            return;
        }

        RateLimiter::hit($key, 60); // 60 secondes

        // Validation des données
        $this->validate();

        try {
            // Ici vous pouvez traiter l'envoi du mail
            // Pour l'exemple, on simule l'envoi
            $this->sendContactEmail();
            
            // Message de succès
            $this->success = true;
            $this->successMessage = 'Votre message a été envoyé avec succès ! Nous vous répondrons dans les plus brefs délais.';
            
            // Réinitialisation du formulaire
            $this->reset(['prenom', 'nom', 'email', 'telephone', 'objet', 'message']);
            
            // Effacer le throttling en cas de succès
            RateLimiter::clear($key);
            
        } catch (\Exception $e) {
            $this->addError('send', 'Une erreur est survenue lors de l\'envoi. Veuillez réessayer.');
            \Log::error('Erreur envoi formulaire contact: ' . $e->getMessage());
        }
    }

    private function sendContactEmail()
    {
        // Données du formulaire
        $data = [
            'prenom' => $this->prenom,
            'nom' => $this->nom,
            'email' => $this->email,
            'telephone' => $this->telephone,
            'objet' => $this->objet,
            'message' => $this->message,
            'date' => now()->format('d/m/Y H:i'),
        ];

        // Récupération de l'email récepteur depuis les paramètres administrateur
        $adminSettings = app(AdminSettings::class);
        $mailRecepteur = $adminSettings->mailRecepteur ?? config('mail.contact_email', 'contact@votre-site.com');

        // Envoi du mail
        Mail::send('emails.contact', $data, function($message) use ($mailRecepteur) {
            $message->to($mailRecepteur)
                    ->subject('Nouveau message de contact : ' . $this->objet)
                    ->replyTo($this->email, $this->prenom . ' ' . $this->nom);
        });
    }

    public function hideSuccess()
    {
        $this->success = false;
        $this->successMessage = '';
    }

    public function render()
    {
        return view('livewire.contact-form');
    }
}